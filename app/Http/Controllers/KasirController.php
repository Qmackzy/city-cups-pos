<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shift;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KasirController extends Controller
{
    public function index()
    {
        // Ambil produk yang stoknya > 0 dan aktif
        $products = Product::where('stock', '>', 0)
                           ->where('is_active', true)
                           ->get();
        
        // Ambil semua kategori untuk tab filter
        $categories = Category::all(); 
        
        return view('kasir.transaksi', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_price' => 'required|numeric',
            'pay_amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'items' => 'required|array',
        ]);

        // Validasi saldo: Hanya dicek jika metode pembayaran adalah 'cash'
        if ($request->payment_method === 'cash' && $request->pay_amount < $request->total_price) {
            return back()->with('error', 'Uang bayar tidak cukup!');
        }

        DB::beginTransaction();

        try {
            // Logika: Jika QRIS/Debit/Transfer, bayar = total (tidak ada kembalian manual)
            $payAmount = $request->payment_method === 'cash' ? $request->pay_amount : $request->total_price;
            $changeAmount = $payAmount - $request->total_price;

            // 1. Simpan Header Transaksi
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . date('YmdHis'),
                'payment_method' => $request->payment_method,
                'total_price' => $request->total_price,
                'pay_amount' => $payAmount,
                'change_amount' => $changeAmount,
            ]);

            // 2. Simpan Detail Transaksi & Kurangi Stok
            foreach ($request->items as $item) {
                // Eager load ingredients untuk efisiensi pengurangan bahan baku
                $product = Product::with('ingredients')->find($item['id']);
                
                if (!$product || $product->stock < $item['qty']) {
                    throw new \Exception("Stok untuk {$product->name} tidak cukup!");
                }

                // Pengurangan Bahan Baku (Inventory System)
                foreach ($product->ingredients as $ingredient) {
                    $totalUsed = $ingredient->pivot->amount * $item['qty'];
                    if ($ingredient->stock < $totalUsed) {
                        throw new \Exception("Bahan baku {$ingredient->name} tidak cukup untuk membuat {$product->name}!");
                    }
                    $ingredient->decrement('stock', $totalUsed);
                }

                // SIMPAN DETAIL DENGAN SNAPSHOT HPP (cost_price)
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'qty'            => $item['qty'],
                    'price'          => $product->price,       // Harga jual saat kejadian
                    'cost_price'     => $product->cost_price,  // SNAPSHOT: Harga modal saat kejadian
                    'subtotal'       => $product->price * $item['qty'],
                ]);

                // Kurangi stok produk jadi
                $product->decrement('stock', $item['qty']);
            }

            DB::commit();

            return redirect()->route('kasir.transaksi')
                ->with('success', 'Transaksi berhasil disimpan!')
                ->with('transaction_id', $transaction->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }

    public function print($id)
    {
        // Eager load detail dan produk untuk cetak struk
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);
        return view('kasir.print', compact('transaction'));
    }

    public function checkShift() {
    $activeShift = Shift::where('user_id', auth()->id())
                        ->where('status', 'open')
                        ->first();
    return $activeShift;
}
}