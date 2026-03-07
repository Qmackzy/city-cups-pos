<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $products = Product::with('category')
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($request->category, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            ->latest()
            ->get();

        return view('owner.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('owner.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0', // TAMBAHKAN VALIDASI INI
            'stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambah!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('owner.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->is_active ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $existsInTransaction = TransactionDetail::where('product_id', $id)->exists();

        if ($existsInTransaction) {
            $product->update(['is_active' => false]);
            return redirect()->route('products.index')
                ->with('success', 'Produk dinonaktifkan karena memiliki riwayat transaksi.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    /* -------------------------------------------------------------------------- */
    /* BAGIAN LAPORAN (PERBAIKAN UTAMA)                   */
    /* -------------------------------------------------------------------------- */
   public function report(Request $request)
    {
        // 1. Tentukan rentang tanggal (WAJIB PALING ATAS)
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        // 2. Total Omzet
        $totalRevenue = Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                        ->sum('total_price');

        // 3. Total HPP (Cost of Goods Sold)
        $totalCOGS = TransactionDetail::whereHas('transaction', function($q) use ($startDate, $endDate) {
                            $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                        })
                        ->selectRaw('SUM(qty * cost_price) as total_hpp')
                        ->first()->total_hpp ?? 0;

        // 4. Total Pengeluaran Operasional (Expenses)
        $totalExpenses = 0;
        if (Schema::hasTable('expenses')) {
            $totalExpenses = DB::table('expenses')
                            ->whereBetween('date', [$startDate, $endDate])
                            ->sum('amount') ?? 0;
        }

        // 5. TOTAL WASTE LOSS (Pencatatan Barang Rusak) - FASE 2
        $totalWasteLoss = 0;
        if (Schema::hasTable('wastes')) {
            $totalWasteLoss = \App\Models\Waste::whereBetween('waste_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                                ->sum('loss_amount');
        }

        // 6. Laba Bersih (Revenue - HPP - Pengeluaran - Waste)
        $netProfit = $totalRevenue - $totalCOGS - $totalExpenses - $totalWasteLoss;

        // 7. Ringkasan Bulanan & Daftar Transaksi
        $monthlyReport = Transaction::whereMonth('created_at', date('m'))
                        ->whereYear('created_at', date('Y'))
                        ->sum('total_price');

        $transactions = Transaction::with('user')
                        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                        ->latest()
                        ->get();

        return view('owner.report', compact(
            'totalRevenue', 
            'totalCOGS', 
            'totalExpenses', 
            'totalWasteLoss', // Sekarang variabel ini sudah aman dikirim ke view
            'netProfit', 
            'monthlyReport', 
            'transactions', 
            'startDate', 
            'endDate'
        ));
    }

    public function manageRecipe($id)
    {
        $product = Product::with('ingredients')->findOrFail($id);
        $ingredients = Ingredient::all();
        return view('owner.products.recipe', compact('product', 'ingredients'));
    }

    public function storeRecipe(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $inputIngredients = $request->input('ingredients', []);
        $syncData = [];

        foreach ($inputIngredients as $idIng => $details) {
            if (isset($details['ingredient_id']) && !empty($details['amount'])) {
                $syncData[$idIng] = ['amount' => $details['amount']];
            }
        }

        $product->ingredients()->sync($syncData);
        return redirect()->route('products.index')->with('success', 'Resep diperbarui!');
    }

    public function dashboard()
    {
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<', 10)->count();
        $todayEarnings = Transaction::whereDate('created_at', date('Y-m-d'))->sum('total_price');
        $todayTransactions = Transaction::whereDate('created_at', date('Y-m-d'))->count();

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chartLabels[] = date('D', strtotime($date));
            $chartData[] = (int) Transaction::whereDate('created_at', $date)->sum('total_price');
        }

        $bestSellers = TransactionDetail::select('product_id', DB::raw('SUM(qty) as total_sold'))
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts', 
            'lowStockProducts', 
            'todayEarnings', 
            'todayTransactions',
            'chartLabels',
            'chartData',
            'bestSellers'
        ));
    }
}