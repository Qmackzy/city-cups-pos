<?php

namespace App\Http\Controllers;

use App\Models\Waste;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WasteController extends Controller
{
    /**
     * Menampilkan daftar barang rusak (Waste)
     */
    public function index()
    {
        $wastes = Waste::with(['ingredient', 'user'])->latest()->paginate(10);
        $ingredients = Ingredient::all(); 

        return view('owner.waste.index', compact('wastes', 'ingredients'));
    }

    /**
     * Menyimpan data pembuangan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:255',
        ]);

        $ingredient = Ingredient::findOrFail($request->ingredient_id);

        // --- PERBAIKAN: CEK DIVISION BY ZERO ---
        if ($ingredient->unit_amount <= 0) {
            return redirect()->back()
                ->with('error', "Gagal! Bahan baku '{$ingredient->name}' memiliki 'Kapasitas Satuan' bernilai 0. Silakan perbaiki data bahan baku terlebih dahulu.");
        }

        // LOGIKA PERHITUNGAN KERUGIAN
        // Rumus: (Jumlah Dibuang / Kapasitas Satuan Beli) * Harga Beli
        $lossAmount = ($request->amount / $ingredient->unit_amount) * $ingredient->purchase_price;

        Waste::create([
            'ingredient_id' => $request->ingredient_id,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'waste_date' => now(),
            'loss_amount' => $lossAmount,
            'user_id' => Auth::id(),
        ]);

        // POTONG STOK OTOMATIS
        $ingredient->decrement('stock', $request->amount);

        return redirect()->route('waste.index')->with('success', 'Berhasil mencatat kerugian barang dan memotong stok.');
    }
}