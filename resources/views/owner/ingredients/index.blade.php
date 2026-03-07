<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Stok Bahan Baku
            </h2>
            <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-bold text-sm shadow-sm">
                + Tambah Bahan Baru
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-tighter">
                                <th class="p-4 border-b font-bold">Nama Bahan</th>
                                <th class="p-4 border-b font-bold">Stok Saat Ini</th>
                                <th class="p-4 border-b font-bold">Min. Stok</th>
                                <th class="p-4 border-b font-bold">Status</th>
                                <th class="p-4 border-b font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($ingredients as $ing)
                            <tr class="hover:bg-gray-50 transition border-b last:border-0 {{ $ing->stock <= $ing->min_stock ? 'bg-red-50' : '' }}">
                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ $ing->name }}</div>
                                    <div class="text-xs text-gray-400 font-medium lowercase">Satuan: {{ $ing->unit }}</div>
                                </td>
                                <td class="p-4 font-black text-lg {{ $ing->stock <= $ing->min_stock ? 'text-red-600' : 'text-gray-700' }}">
                                    {{ number_format($ing->stock) }} <span class="text-xs font-normal text-gray-400">{{ $ing->unit }}</span>
                                </td>
                                <td class="p-4 text-gray-500 font-medium">
                                    {{ number_format($ing->min_stock) }} {{ $ing->unit }}
                                </td>
                                <td class="p-4">
                                    @if($ing->stock <= $ing->min_stock)
                                        <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-[10px] font-black uppercase tracking-wider animate-pulse">Kritis</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-wider">Aman</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('ingredients.addStock', $ing->id) }}" method="POST" class="flex gap-1">
                                            @csrf
                                            <input type="number" name="amount" placeholder="Qty" class="w-16 text-xs border-gray-300 rounded focus:ring-blue-500 py-1" required>
                                            <button type="submit" class="bg-blue-100 text-blue-600 p-1 rounded hover:bg-blue-200" title="Tambah Stok">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </form>

                                        <form action="{{ route('ingredients.destroy', $ing->id) }}" method="POST" onsubmit="return confirm('Hapus bahan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-600 p-1 rounded hover:bg-red-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-400 italic">Belum ada bahan baku. Tambahkan sekarang!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modalTambah" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Tambah Bahan Baku</h3>
            <form action="{{ route('ingredients.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium">Nama Bahan</label>
                    <input type="text" name="name" class="w-full border-gray-300 rounded-lg text-sm" placeholder="Contoh: Biji Kopi" required>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-sm font-medium">Stok Awal</label>
                        <input type="number" name="stock" class="w-full border-gray-300 rounded-lg text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Satuan</label>
                        <input type="text" name="unit" class="w-full border-gray-300 rounded-lg text-sm" placeholder="gram / ml" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-red-600">Batas Stok Kritis (Min)</label>
                    <input type="number" name="min_stock" class="w-full border-red-200 bg-red-50 rounded-lg text-sm" required>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Harga Beli Kemasan (Rp)</label>
        <input type="number" name="purchase_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Isi Per Kemasan</label>
        <input type="number" name="unit_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        <p class="text-xs text-gray-500 mt-1">*Contoh: 1000 jika beli 1 Liter/1 Kg</p>
    </div>
</div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 bg-gray-200 rounded-lg text-sm font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>