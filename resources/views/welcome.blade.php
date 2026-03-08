<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Cafe POS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">

    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-stone-50 text-stone-900 font-sans antialiased">

    <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden">



        <nav class="absolute top-0 w-full p-6 flex justify-between items-center max-w-7xl">

            <div class="text-2xl font-bold tracking-tighter text-orange-900">

                {{ config('app.name', 'CAFE POS') }}

            </div>

            <div class="flex gap-4">

                @if (Route::has('login'))

                    @auth

                        <a href="{{ url('/dashboard') }}"
                            class="px-5 py-2 bg-orange-800 text-white rounded-lg hover:bg-orange-900 transition shadow-md">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-5 py-2 text-stone-600 font-medium hover:text-orange-800 transition">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-5 py-2 border-2 border-orange-800 text-orange-800 rounded-lg font-bold hover:bg-orange-800 hover:text-white transition">Register</a>
                        @endif

                    @endauth

                @endif

            </div>

        </nav>



        <main class="text-center px-6 mt-12">

            <div
                class="inline-block px-4 py-1.5 mb-6 text-sm font-semibold tracking-wide text-orange-800 uppercase bg-orange-100 rounded-full">

                Sistem management & kasir untuk usaha Anda

            </div>

            <h1 class="text-5xl lg:text-7xl font-black text-stone-800 leading-tight mb-6">

                Kelola Usaha Anda <br>

                <span class="text-orange-700 text-outline">Tanpa Ribet.</span>

            </h1>

            <p class="text-lg text-stone-600 max-w-2xl mx-auto mb-10 leading-relaxed">

                Pantau stok bahan, kelola transaksi POS, hingga laporan laba rugi dalam satu sistem terintegrasi.
                Dirancang khusus untuk efisiensi bisnis Anda.

            </p>



            <div class="flex flex-col sm:flex-row justify-center gap-4">

                <a href="{{ route('login') }}"
                    class="px-8 py-4 bg-orange-800 text-white text-lg font-bold rounded-xl shadow-xl hover:bg-orange-900 transform hover:-translate-y-1 transition duration-300">

                    Mulai Transaksi

                </a>

                <div
                    class="px-8 py-4 bg-white border border-stone-200 text-stone-700 text-lg font-medium rounded-xl shadow-sm italic">

                    "Starting is always a good idea"

                </div>

            </div>



            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto text-left">

                <div class="p-6 bg-white rounded-2xl shadow-sm border border-stone-100">

                    <div class="text-3xl mb-3">📊</div>

                    <h3 class="font-bold text-lg mb-2">Laporan Real-time</h3>

                    <p class="text-stone-500 text-sm">Pantau omzet, HPP, dan laba bersih secara akurat setiap hari.</p>

                </div>

                <div class="p-6 bg-white rounded-2xl shadow-sm border border-stone-100">

                    <div class="text-3xl mb-3">📦</div>

                    <h3 class="font-bold text-lg mb-2">Manajemen Stok</h3>

                    <p class="text-stone-500 text-sm">Notifikasi otomatis saat stok bahan baku mulai menipis.</p>

                </div>

                <div class="p-6 bg-white rounded-2xl shadow-sm border border-stone-100">

                    <div class="text-3xl mb-3">⚡</div>

                    <h3 class="font-bold text-lg mb-2">POS Kilat</h3>

                    <p class="text-stone-500 text-sm">Input pesanan langsung ke dapur tanpa sistem antrean yang rumit.
                    </p>

                </div>

            </div>

        </main>



        <footer class="mt-20 py-10 text-stone-400 text-sm">

            &copy; {{ date('Y') }} {{ config('app.name') }}. Built for Enterpreuner Lovers.

        </footer>

    </div>

</body>

</html>
