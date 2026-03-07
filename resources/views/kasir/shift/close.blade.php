<x-app-layout>

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 py-12">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-2xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-800">Tutup Shift Kasir</h2>
            <p class="text-gray-500">Ringkasan penjualan City Cups untuk sesi ini</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <span class="text-sm text-blue-600 font-semibold uppercase">Mulai Shift</span>
                <p class="text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($shift->start_time)->format('d M Y, H:i') }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                <span class="text-sm text-green-600 font-semibold uppercase">Modal Awal</span>
                <p class="text-lg font-bold text-gray-800">Rp {{ number_format($shift->starting_cash, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="space-y-4 mb-8">
            <div class="flex justify-between items-center border-b pb-2">
                <span class="text-gray-600">Total Penjualan Tunai (Sistem)</span>
                <span class="font-bold text-gray-800">Rp {{ number_format($totalCashSales, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center border-b pb-4">
                <span class="text-gray-700 font-semibold text-lg">Total Uang di Laci (Seharusnya)</span>
                <span class="font-extrabold text-xl text-blue-600">Rp {{ number_format($expectedCash, 0, ',', '.') }}</span>
            </div>
        </div>

        

        <form action="{{ route('kasir.shift.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Total Uang Fisik di Laci Sekarang</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">Rp</span>
                    <input type="number" 
                           name="total_cash_actual" 
                           class="pl-12 w-full border-2 border-blue-100 rounded-xl py-3 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all text-xl font-bold" 
                           placeholder="0" 
                           required>
                </div>
                <p class="text-xs text-gray-400 mt-2 italic">*Hitung semua uang tunai (kertas & koin) yang ada di laci sebelum mengakhiri shift.</p>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('kasir.transaksi') }}" class="w-1/3 text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-4 rounded-xl transition duration-200">
                    Batal
                </a>
                <button type="submit" 
                        onclick="return confirm('Apakah Anda yakin ingin menutup shift? Anda akan otomatis logout setelah ini.')"
                        class="w-2/3 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-red-200 transition duration-200">
                    Selesaikan & Tutup Shift
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>