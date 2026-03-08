<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShiftController; // 1. Pastikan ShiftController di-import
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// --- 1. Halaman Publik ---
Route::get('/', function () {
    return view('welcome');
});

// --- 2. Group Rute Semua User Login (Auth & Verified) ---
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/fix-storage', function () {
        Artisan::call('storage:link');
        return 'Storage link created!';
    });

    // Dashboard & Profile
    Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Struk Cetak (Bisa diakses Owner & Kasir)
    Route::get('/kasir/transaksi/cetak/{id}', [KasirController::class, 'print'])->name('kasir.print');

    // ROUTE SHIFT (Buka & Tutup Kasir)
    // Ditaruh di sini agar Kasir bisa akses, tapi tidak terkena middleware 'check.shift' 
    // agar tidak terjadi redirect berulang (loop).
    Route::get('/kasir/shift/create', [ShiftController::class, 'create'])->name('kasir.shift.create');
    Route::post('/kasir/shift/store', [ShiftController::class, 'store'])->name('kasir.shift.store');
    Route::get('/kasir/shift/close', [ShiftController::class, 'close'])->name('kasir.shift.close');
    Route::put('/kasir/shift/update', [ShiftController::class, 'update'])->name('kasir.shift.update');
});

// --- 3. KHUSUS OWNER ---
Route::middleware(['auth', 'role:owner'])->group(function () {
    // Laporan & Export
    Route::get('/owner/laporan', [ProductController::class, 'report'])->name('owner.laporan');
    Route::get('/owner/reports/export', [ReportController::class, 'exportToPdf'])->name('reports.download');
    Route::get('/owner/shifts', [ShiftController::class, 'index'])->name('owner.shifts.index');

    // Manajemen User
    Route::resource('owner/users', UserController::class)->names('users')->except(['show', 'create', 'edit']);

    // CRUD Produk
    Route::resource('owner/products', ProductController::class)->names('products');

    // Resep & Ingredients
    Route::get('/owner/products/{id}/recipe', [ProductController::class, 'manageRecipe'])->name('products.recipe');
    Route::post('/owner/products/{id}/recipe', [ProductController::class, 'storeRecipe'])->name('products.recipe.store');
    Route::resource('ingredients', IngredientController::class);
    Route::post('ingredients/{id}/add-stock', [IngredientController::class, 'addStock'])->name('ingredients.addStock');

    // Pengeluaran
    Route::resource('expenses', ExpenseController::class)->only(['index', 'store', 'destroy']);

    // Rute Waste Management (Fase 2)
    Route::get('/owner/waste', [App\Http\Controllers\WasteController::class, 'index'])->name('waste.index');
    Route::post('/owner/waste', [App\Http\Controllers\WasteController::class, 'store'])->name('waste.store');
    Route::delete('/owner/waste/{id}', [App\Http\Controllers\WasteController::class, 'destroy'])->name('waste.destroy');

    Route::resource('categories', CategoryController::class);
});

// --- 4. KHUSUS KASIR (Dengan Pengamanan Shift) ---
Route::middleware(['auth', 'role:kasir', 'check.shift'])->group(function () {
    // Transaksi Kasir (Hanya bisa dibuka jika sudah Buka Shift)
    Route::get('/kasir/transaksi', [KasirController::class, 'index'])->name('kasir.transaksi');
    Route::post('/kasir/transaksi', [KasirController::class, 'store'])->name('kasir.store');
});

require __DIR__ . '/auth.php';
