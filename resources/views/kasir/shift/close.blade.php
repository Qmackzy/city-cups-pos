<x-app-layout>
    <div class="min-h-screen bg-stone-50 py-8 px-4">
        <div class="max-w-xl mx-auto">

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-orange-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-stone-800 uppercase tracking-tight">Tutup Shift Kasir</h2>
                <p class="text-stone-500 text-sm italic">City Cups: Selesaikan laporan sesi Anda</p>
            </div>

            <div class="bg-white rounded-[2rem] shadow-xl shadow-stone-200/50 border border-stone-100 overflow-hidden">
                <div class="grid grid-cols-2 border-b border-stone-100">
                    <div class="p-5 border-r border-stone-100">
                        <span class="block text-[10px] font-black text-stone-400 uppercase tracking-widest mb-1">Mulai
                            Shift</span>
                        <p class="text-sm font-bold text-stone-800">
                            {{ \Carbon\Carbon::parse($shift->start_time)->format('d M, H:i') }}
                        </p>
                    </div>
                    <div class="p-5 bg-stone-50/50">
                        <span class="block text-[10px] font-black text-stone-400 uppercase tracking-widest mb-1">Modal
                            Awal</span>
                        <p class="text-sm font-bold text-green-700">
                            Rp {{ number_format($shift->starting_cash, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="p-8">
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center py-2 border-b border-dashed border-stone-200">
                            <span class="text-stone-500 text-sm">Penjualan Tunai</span>
                            <span class="font-bold text-stone-800">Rp
                                {{ number_format($totalCashSales, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-2xl flex justify-between items-center mt-4">
                            <span class="text-orange-900 font-bold text-sm uppercase">Total di Laci (Sistem)</span>
                            <span class="font-black text-xl text-orange-900">
                                Rp {{ number_format($expectedCash, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('kasir.shift.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-8">
                            <label
                                class="block text-stone-800 text-xs font-black uppercase tracking-widest mb-3 text-center">
                                Masukkan Total Uang Fisik Sekarang
                            </label>
                            <div class="relative group">
                                <span
                                    class="absolute inset-y-0 left-0 pl-5 flex items-center text-stone-400 font-black text-xl">Rp</span>
                                <input type="number" name="total_cash_actual"
                                    class="pl-16 w-full bg-stone-50 border-2 border-stone-100 rounded-2xl py-5 focus:border-orange-800 focus:ring-0 transition-all text-3xl font-black text-stone-800 text-center shadow-inner"
                                    placeholder="0" required autofocus>
                            </div>
                            <div class="mt-4 p-4 bg-blue-50 rounded-xl flex gap-3">
                                <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-[11px] text-blue-700 leading-relaxed font-medium italic">
                                    Pastikan menghitung semua uang (lembaran & koin). Selisih akan dicatat sebagai
                                    performa shift.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('kasir.transaksi') }}"
                                class="order-2 sm:order-1 sm:w-1/3 text-center bg-stone-100 hover:bg-stone-200 text-stone-600 font-bold py-4 px-4 rounded-2xl transition duration-200 text-sm uppercase tracking-widest">
                                Batal
                            </a>
                            <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin menutup shift? Anda akan otomatis logout setelah ini.')"
                                class="order-1 sm:order-2 sm:w-2/3 bg-stone-900 hover:bg-black text-white font-black py-4 px-4 rounded-2xl shadow-xl shadow-stone-900/20 transition duration-200 text-sm uppercase tracking-widest flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Selesaikan Shift
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
