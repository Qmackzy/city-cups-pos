<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Coffee Dashboard') }} ☕
            </h2>
            <p class="text-sm text-gray-500">{{ date('d F Y') }}</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(Auth::user()->role == 'owner')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-2xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-80 font-medium uppercase">Omzet Hari Ini</p>
                            <p class="text-2xl font-bold mt-1">Rp {{ number_format($todayEarnings) }}</p>
                        </div>
                        <span class="text-3xl">💰</span>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-80 font-medium uppercase">Transaksi</p>
                            <p class="text-2xl font-bold mt-1">{{ $todayTransactions }} Pesanan</p>
                        </div>
                        <span class="text-3xl">🧾</span>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-2xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-80 font-medium uppercase">Total Menu</p>
                            <p class="text-2xl font-bold mt-1">{{ $totalProducts }} Item</p>
                        </div>
                        <span class="text-3xl">☕</span>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-orange-600 p-6 rounded-2xl shadow-lg text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-80 font-medium uppercase">Stok Menipis</p>
                            <p class="text-2xl font-bold mt-1 text-yellow-200">{{ $lowStockProducts }} Menu</p>
                        </div>
                        <span class="text-3xl">⚠️</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="p-2 bg-red-50 text-red-500 rounded-lg text-xs">PENTING</span>
                            Stok Hampir Habis
                        </h3>
                        <a href="{{ route('products.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-xs uppercase">
                                <tr>
                                    <th class="px-6 py-4 font-medium">Menu</th>
                                    <th class="px-6 py-4 font-medium">Kategori</th>
                                    <th class="px-6 py-4 font-medium">Sisa Stok</th>
                                    <th class="px-6 py-4 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse(\App\Models\Product::where('stock', '<', 10)->with('category')->limit(5)->get() as $p)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $p->name }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $p->category->name }}</td>
                                    <td class="px-6 py-4 text-red-600 font-bold">{{ $p->stock }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs">Restock Segera</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Semua stok masih aman. Aman Bos! ✅</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-6">Akses Cepat</h3>
                    <div class="space-y-4">
                        <a href="{{ route('products.create') }}" class="flex items-center gap-4 p-4 rounded-xl border border-gray-50 hover:bg-blue-50 hover:border-blue-100 transition group text-left w-full">
                            <span class="p-3 bg-blue-100 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition">➕</span>
                            <div>
                                <p class="font-bold text-gray-800">Tambah Menu</p>
                                <p class="text-xs text-gray-500 text-left">Input produk kopi baru</p>
                            </div>
                        </a>

                        <a href="{{ route('owner.laporan') }}" class="flex items-center gap-4 p-4 rounded-xl border border-gray-50 hover:bg-green-50 hover:border-green-100 transition group text-left w-full">
                            <span class="p-3 bg-green-100 text-green-600 rounded-xl group-hover:bg-green-600 group-hover:text-white transition">📊</span>
                            <div>
                                <p class="font-bold text-gray-800">Cek Laporan</p>
                                <p class="text-xs text-gray-500 text-left">Analisa penjualan hari ini</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="p-2 bg-blue-50 text-blue-500 rounded-lg text-xs font-bold uppercase">Trend</span>
            Penjualan 7 Hari Terakhir
        </h3>
        <canvas id="salesChart" height="150"></canvas>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="p-2 bg-orange-50 text-orange-500 rounded-lg text-xs font-bold uppercase">Top</span>
            Produk Terlaris
        </h3>
        
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] text-gray-400 border-b uppercase tracking-wider">
                    <th class="pb-2">Menu</th>
                    <th class="pb-2 text-center">Terjual</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($bestSellers as $item)
                <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                    <td class="py-3 flex items-center">
                        <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-[10px] font-bold mr-3 shrink-0">
                            {{ $loop->iteration }}
                        </span>
                        <span class="font-semibold text-gray-700 truncate max-w-[120px]" title="{{ $item->product->name ?? 'Produk Dihapus' }}">
                            {{ $item->product->name ?? 'Produk Dihapus' }}
                        </span>
                    </td>
                    <td class="py-3 text-center font-bold text-gray-900 whitespace-nowrap">
                        {{ $item->total_sold }} <span class="text-[10px] text-gray-400 font-normal">cup</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="py-10 text-center text-gray-400 italic text-xs">Belum ada data penjualan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

            @else
            <div class="bg-white p-12 rounded-3xl shadow-sm border border-gray-100 text-center">
                <div class="w-24 h-24 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">☕</div>
                <h3 class="text-3xl font-extrabold text-gray-800 mb-2">Selamat Bekerja, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Sudah siap menyeduh kebahagiaan untuk pelanggan hari ini?</p>
                <a href="{{ route('kasir.transaksi') }}" class="inline-block bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition transform hover:-translate-y-1">
                    BUKA KASIR SEKARANG
                </a>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($chartData) !!},
                borderColor: '#3b82f6', // Warna biru tailwind
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4, // Membuat garis melengkung (smooth)
                pointRadius: 5,
                pointBackgroundColor: '#3b82f6'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>