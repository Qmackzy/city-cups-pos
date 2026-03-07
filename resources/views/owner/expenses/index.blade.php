<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Pengeluaran Operasional</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-100">
                <h3 class="font-bold text-gray-700 mb-4">Tambah Pengeluaran Baru</h3>
                <form action="{{ route('expenses.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="Nama (e.g. Listrik)" class="rounded-lg border-gray-300 text-sm" required>
                    <input type="number" name="amount" placeholder="Jumlah (Rp)" class="rounded-lg border-gray-300 text-sm" required>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="rounded-lg border-gray-300 text-sm" required>
                    <button type="submit" class="bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700">Simpan</button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs uppercase text-gray-500 border-b">
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Keterangan</th>
                            <th class="p-4">Jumlah</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($expenses as $e)
                        <tr class="border-b">
                            <td class="p-4">{{ date('d M Y', strtotime($e->date)) }}</td>
                            <td class="p-4 font-bold">{{ $e->name }}</td>
                            <td class="p-4 text-red-600 font-bold">Rp {{ number_format($e->amount) }}</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('expenses.destroy', $e->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $expenses->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>