<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        // withCount untuk menghitung jumlah produk di tiap kategori
        $categories = Category::withCount('products')->latest()->get();
        return view('owner.categories.index', compact('categories'));
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Otomatis jadi "kopi-susu" jika inputnya "Kopi Susu"
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Menghapus kategori
    public function destroy(Category $category)
    {
        // Proteksi: Jangan hapus jika masih ada produk di dalamnya
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal! Kategori ini masih memiliki produk.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
