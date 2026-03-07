<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    // Menampilkan daftar bahan baku (Gula, Susu, Kopi, dll)
    public function index()
    {
        $ingredients = Ingredient::orderBy('name', 'asc')->get();
        return view('owner.ingredients.index', compact('ingredients'));
    }

    // Menyimpan bahan baku baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20', // Contoh: gram, ml, pcs
            'min_stock' => 'required|numeric|min:0',
        ]);

        Ingredient::create([
            'name' => $request->name,
            'stock' => $request->stock,
            'unit' => $request->unit,
            'min_stock' => $request->min_stock,
        ]);

        return redirect()->back()->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    // Memperbarui data bahan baku (misal ganti nama atau batas stok)
    public function update(Request $request, $id)
    {
        $ingredient = Ingredient::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'min_stock' => 'required|numeric|min:0',
        ]);

        $ingredient->update($request->all());

        return redirect()->back()->with('success', 'Data bahan baku diperbarui!');
    }

    // Fungsi khusus untuk menambah stok (Restock)
    public function addStock(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);
        
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->increment('stock', $request->amount);

        return redirect()->back()->with('success', "Stok {$ingredient->name} berhasil ditambah sebanyak {$request->amount}!");
    }

    // Menghapus bahan baku
    public function destroy($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        
        // Proteksi: Jangan hapus jika bahan masih dipakai di resep produk mana pun
        if ($ingredient->products()->exists()) {
            return redirect()->back()->with('error', 'Bahan tidak bisa dihapus karena masih digunakan dalam resep!');
        }

        $ingredient->delete();
        return redirect()->back()->with('success', 'Bahan baku berhasil dihapus!');
    }
}
