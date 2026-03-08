<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shift;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /**
     * Menampilkan halaman transaksi kasir.
     */
    public function index()
    {
        // Ambil produk yang aktif dan stoknya tersedia
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->with('category')
            ->get();

        $categories = Category::all();

        return view('kasir.transaksi', compact('products', 'categories'));
    }

    /**
     * Menyimpan transaksi baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input yang ketat
        $request->validate([
            'total_price' => 'required|numeric|min:0',
            'pay_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,qris,debit,transfer',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        // 2. Cek kecukupan saldo jika pembayaran tunai
        if ($request->payment_method === 'cash' && $request->pay_amount < $request->total_price) {
            return back()->with('error', 'Uang bayar tidak mencukupi total belanja!')->withInput();
        }

        DB::beginTransaction();

        try {
            // Hitung kembalian (jika non-cash, kembalian dianggap 0)
            $payAmount = $request->payment_method === 'cash' ? $request->pay_amount : $request->total_price;
            $changeAmount = max(0, $payAmount - $request->total_price);

            // 3. Simpan Header Transaksi
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . auth()->id(),
                'payment_method' => $request->payment_method,
                'total_price' => $request->total_price,
                'pay_amount' => $payAmount,
                'change_amount' => $changeAmount,
            ]);

            // 4. Proses setiap item (Detail & Stok)
            foreach ($request->items as $item) {
                // Gunakan lockForUpdate untuk mencegah tabrakan stok (race condition)
                $product = Product::with('ingredients')->lockForUpdate()->find($item['id']);

                // Validasi stok produk jadi
                if (!$product || $product->stock < $item['qty']) {
                    throw new \Exception("Stok untuk produk '{$product->name}' telah berubah atau tidak mencukupi.");
                }

                // 5. Pengurangan Bahan Baku (Inventory System)
                if ($product->ingredients->isNotEmpty()) {
                    foreach ($product->ingredients as $ingredient) {
                        $totalUsed = $ingredient->pivot->amount * $item['qty'];

                        if ($ingredient->stock < $totalUsed) {
                            throw new \Exception("Bahan baku '{$ingredient->name}' tidak cukup untuk membuat {$product->name}.");
                        }

                        // Kurangi stok bahan baku
                        $ingredient->decrement('stock', $totalUsed);
                    }
                }

                // 6. Simpan Detail Transaksi dengan Snapshot Harga
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'qty'            => $item['qty'],
                    'price'          => $product->price,       // Harga jual saat ini
                    'cost_price'     => $product->cost_price,  // Modal/HPP saat ini
                    'subtotal'       => $product->price * $item['qty'],
                ]);

                // 7. Kurangi stok produk jadi
                $product->decrement('stock', $item['qty']);
            }

            DB::commit();

            return redirect()->route('kasir.transaksi')
                ->with('success', 'Transaksi #' . $transaction->invoice_number . ' Berhasil!')
                ->with('transaction_id', $transaction->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan view cetak struk.
     */
    public function print($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);
        return view('kasir.print', compact('transaction'));
    }

    /**
     * Cek apakah kasir memiliki shift yang masih terbuka.
     */
    public function checkShift()
    {
        return Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();
    }
}
