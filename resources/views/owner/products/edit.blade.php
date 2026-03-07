<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Menu: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700">Nama Produk</label>
                        <input type="text" name="name" value="{{ $product->name }}" class="w-full border-gray-300 rounded shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Kategori</label>
                        <select name="category_id" class="w-full border-gray-300 rounded shadow-sm" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ $product->price }}" class="w-full border-gray-300 rounded shadow-sm" required>
                    </div>

      <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">Harga Modal (HPP)</label>
    <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" 
           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" 
           placeholder="Contoh: 8000" required>
    <p class="text-xs text-gray-400 mt-1">*Modal dasar per porsi/item (Gunakan angka saja tanpa Rp)</p>
</div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Stok</label>
                        <input type="number" name="stock" value="{{ $product->stock }}" class="w-full border-gray-300 rounded shadow-sm" required>
                    </div>
                    
                    <div class="mb-4">
    <label class="flex items-center">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
        <span class="ml-2 text-sm text-gray-600 font-bold">Menu ini Aktif (Muncul di Kasir)</span>
    </label>
</div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Gambar Saat Ini</label>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded mb-2 border">
                        @else
                            <p class="text-sm text-gray-500 italic">Belum ada gambar.</p>
                        @endif
                        <label class="block text-gray-700 mt-3">Ganti Gambar (Opsional)</label>
                        <input type="file" name="image" class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500">
                    </div>

                    <div class="flex items-center justify-end mt-4 gap-2">
                        <a href="{{ route('products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Update Menu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>