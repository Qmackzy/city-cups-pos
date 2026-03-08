<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Pendapatan Coffee Shop
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-100">
                <div class="flex flex-wrap items-end gap-4">

                    <form action="{{ route('owner.laporan') }}" method="GET" class="flex flex-wrap items-end gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Sampai</label>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 text-sm">
                        </div>
                        <button type="submit"
                            class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition font-bold text-sm shadow-sm">
                            Filter Laporan
                        </button>
                    </form>

                    <form action="{{ route('reports.download') }}" method="GET" target="_blank">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">

                        <button type="submit"
                            class="flex items-center gap-2 bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 transition shadow-sm font-bold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Cetak PDF
                        </button>
                    </form>

                    <a href="{{ route('owner.laporan') }}"
                        class="bg-gray-100 text-gray-600 px-5 py-2 rounded-lg hover:bg-gray-200 transition font-bold text-sm border border-gray-200">
                        Reset
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-500 p-5 rounded-xl text-white shadow-sm">
                    <p class="text-xs uppercase font-bold opacity-80">Total Omzet</p>
                    <h2 class="text-2xl font-extrabold leading-tight">Rp {{ number_format($totalRevenue) }}</h2>
                </div>

                <div class="bg-orange-500 p-5 rounded-xl text-white shadow-sm">
                    <p class="text-xs uppercase font-bold opacity-80">Total Modal (HPP)</p>
                    <h2 class="text-2xl font-extrabold leading-tight">Rp {{ number_format($totalCOGS) }}</h2>
                </div>

                <div class="bg-red-500 p-5 rounded-xl text-white shadow-sm">
                    <p class="text-xs uppercase font-bold opacity-80">Pengeluaran Operasional</p>
                    <h2 class="text-2xl font-extrabold leading-tight">Rp {{ number_format($totalExpenses) }}</h2>
                </div>

                <div
                    class="{{ $netProfit >= 0 ? 'bg-green-600' : 'bg-red-700' }} p-5 rounded-xl text-white shadow-lg border-2 border-white border-opacity-20">
                    <p class="text-xs uppercase font-bold opacity-80">Laba Bersih (Profit)</p>
                    <h2 class="text-2xl font-extrabold leading-tight">Rp {{ number_format($netProfit) }}</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 border border-gray-100">
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Pendapatan (Filter Terpilih)
                    </h3>
                    <p class="text-3xl font-black text-gray-800 mt-1">Rp {{ number_format($totalRevenue) }}</p>
                    <p class="text-xs text-gray-400 mt-2 italic font-medium">Periode:
                        {{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500 border border-gray-100">
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Pendapatan Bulan Ini</h3>
                    <p class="text-3xl font-black text-gray-800 mt-1">Rp {{ number_format($monthlyReport) }}</p>
                    <p class="text-xs text-gray-400 mt-2 font-medium">Data s/d {{ date('d M Y') }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-sm text-gray-500 uppercase font-bold">Kerugian Waste</div>
                    <div class="text-2xl font-bold text-red-600">
                        Rp {{ number_format($totalWasteLoss, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    Daftar Transaksi Terakhir
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-tighter">
                                <th class="p-4 border-b font-bold">No. Invoice</th>
                                <th class="p-4 border-b font-bold">Kasir</th>
                                <th class="p-4 border-b font-bold">Total</th>
                                <th class="p-4 border-b font-bold">Waktu</th>
                                <th class="p-4 border-b font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($transactions as $trans)
                                <tr class="hover:bg-gray-50 transition border-b last:border-0 text-gray-700">
                                    <td class="p-4 font-mono font-bold text-blue-600">{{ $trans->invoice_number }}</td>
                                    <td class="p-4 font-medium">{{ $trans->user->name ?? 'N/A' }}</td>
                                    <td class="p-4 font-bold">Rp {{ number_format($trans->total_price) }}</td>
                                    <td class="p-4 text-gray-500">{{ $trans->created_at->format('d M Y H:i') }}</td>
                                    <td class="p-4 text-center">
                                        <a href="{{ route('kasir.print', $trans->id) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-md transition-all font-bold text-xs uppercase tracking-wider border border-blue-100 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            Lihat Struk
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-10 text-center text-gray-400 italic">Tidak ada transaksi
                                        di periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
