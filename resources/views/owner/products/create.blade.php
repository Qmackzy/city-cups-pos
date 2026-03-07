<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Menu Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700">Nama Produk</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Kategori</label>
                        <select name="category_id" class="w-full border-gray-300 rounded shadow-sm" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Harga (Rp)</label>
                        <input type="number" name="price" class="w-full border-gray-300 rounded shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Stok Awal</label>
                        <input type="number" name="stock" class="w-full border-gray-300 rounded shadow-sm" required>
                    </div>

                    <div class="mb-4">
        <label class="block text-gray-700">Gambar Produk (Opsional)</label>
        <input type="file" name="image" class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, JPEG. Maks: 2MB</p>
    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Simpan Menu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>