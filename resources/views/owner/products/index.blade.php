<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Menu City Cups') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    <h3 class="text-lg font-bold text-gray-700 whitespace-nowrap">
                        Total: {{ $products->count() }}
                    </h3>
                    
                    <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full">
                        <select name="category" onchange="this.form.submit()" class="bg-white border border-gray-300 text-gray-700 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 shadow-sm font-bold min-w-[140px]">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="relative w-full sm:w-64">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama menu..." 
                                class="bg-white border border-gray-300 text-gray-700 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 pl-8 shadow-sm font-medium">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>

                        <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm font-bold text-xs uppercase">
                            Cari
                        </button>

                        @if(request('category') || request('search'))
                            <a href="{{ route('products.index') }}" class="text-[10px] text-red-500 font-bold uppercase hover:text-red-700 transition px-2">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <a href="{{ route('products.create') }}" class="w-full md:w-auto bg-green-600 text-center text-white px-5 py-2.5 rounded-xl hover:bg-green-700 transition shadow-lg font-bold text-sm">
                    + Tambah Produk Baru
                </a>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100">
                <table class="w-full mt-2 text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 uppercase text-xs leading-normal">
                            <th class="p-3 border-b text-center font-bold">Gambar</th>
                            <th class="p-3 border-b font-bold">Nama</th>
                            <th class="p-3 border-b font-bold">Kategori</th>
                            <th class="p-3 border-b font-bold">Harga</th>
                            <th class="p-3 border-b font-bold">Stok</th>
                            <th class="p-3 border-b text-center font-bold">Status</th> 
                            <th class="p-3 border-b text-center font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @forelse($products as $product)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="p-3 text-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-lg mx-auto border shadow-sm">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg mx-auto flex items-center justify-center text-[10px] text-gray-400 border border-dashed text-center">No Img</div>
                                @endif
                            </td>
                            <td class="p-3 font-bold text-gray-800">{{ $product->name }}</td>
                            <td class="p-3">
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-[10px] font-bold">{{ $product->category->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td class="p-3 font-semibold text-blue-600">Rp {{ number_format($product->price) }}</td>
                            <td class="p-3">
                                <span class="{{ $product->stock < 10 ? 'text-red-500 font-bold' : 'text-gray-700' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                @if($product->is_active)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase">Aktif</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('products.recipe', $product->id) }}" class="bg-orange-50 text-orange-600 px-3 py-2 rounded-lg hover:bg-orange-600 hover:text-white transition font-bold text-[10px] uppercase shadow-sm">
                                        Resep
                                    </a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="bg-blue-50 text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-600 hover:text-white transition font-bold text-[10px] uppercase shadow-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-50 text-red-600 px-3 py-2 rounded-lg hover:bg-red-600 hover:text-white transition font-bold text-[10px] uppercase shadow-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-10 text-center text-gray-400 italic font-medium">Menu tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>