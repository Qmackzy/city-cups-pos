<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Barang Rusak (Waste)') }}
        </h2>
    </x-slot>
@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-bold mb-4">Catat Barang Rusak/Basi</h3>
                <form action="{{ route('waste.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bahan Baku</label>
                        <select name="ingredient_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach($ingredients as $ing)
                                <option value="{{ $ing->id }}">{{ $ing->name }} (Stok: {{ $ing->stock }} {{ $ing->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah Dibuang</label>
                        <input type="number" step="0.01" name="amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alasan</label>
                        <input type="text" name="reason" placeholder="Contoh: Tumpah / Basi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">Simpan Kerugian</button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Bahan Baku</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Estimasi Rugi</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($wastes as $w)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $w->waste_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold">{{ $w->ingredient->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $w->amount }} {{ $w->ingredient->unit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-red-600">Rp {{ number_format($w->loss_amount) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $w->reason }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $wastes->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>