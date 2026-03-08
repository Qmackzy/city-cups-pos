<nav x-data="{ open: false }" class="bg-white border-b border-stone-100 shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-orange-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if (Auth::user()->role == 'owner')
                        {{-- Dropdown Manajemen Produk --}}
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-stone-500 bg-white hover:text-stone-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Manajemen Produk</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('products.index')">Daftar Produk</x-dropdown-link>
                                    <x-dropdown-link :href="route('ingredients.index')">Stok Bahan Baku</x-dropdown-link>
                                    <x-dropdown-link :href="route('waste.index')">Barang Rusak/Waste</x-dropdown-link>
                                    <x-dropdown-link :href="route('expenses.index')">Pengeluaran</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        {{-- Dropdown Laporan & User --}}
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-stone-500 bg-white hover:text-stone-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Laporan & User</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('owner.laporan')">Laporan Penjualan</x-dropdown-link>
                                    <x-dropdown-link :href="route('owner.shifts.index')">Laporan Shift</x-dropdown-link>
                                    <hr class="border-stone-100">
                                    <x-dropdown-link :href="route('users.index')">Kelola Kasir</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    @if (Auth::user()->role == 'kasir')
                        <x-nav-link :href="route('kasir.transaksi')" :active="request()->routeIs('kasir.transaksi')">
                            {{ __('Transaksi Baru') }}
                        </x-nav-link>
                        <x-nav-link :href="route('kasir.shift.close')" :active="request()->routeIs('kasir.shift.close')"
                            class="text-red-600 font-bold hover:text-red-800">
                            {{ __('Tutup Kasir') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-stone-200 text-sm leading-4 font-bold rounded-xl text-stone-600 bg-stone-50 hover:text-stone-800 hover:bg-stone-100 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }} <span
                                    class="text-[10px] opacity-50 font-normal uppercase">({{ Auth::user()->role }})</span>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-red-600 font-bold">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-stone-400 hover:text-stone-500 hover:bg-stone-100 focus:outline-none focus:bg-stone-100 focus:text-stone-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }"
        class="hidden sm:hidden bg-stone-50 border-t border-stone-200 shadow-inner">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (Auth::user()->role == 'owner')
                <div class="px-4 py-3 text-[10px] font-black uppercase text-stone-400 tracking-[0.2em]">Produk & Stok
                </div>
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('products.index')">Daftar Produk</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">Daftar Produk</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ingredients.index')" :active="request()->routeIs('ingredients.index')">Stok Bahan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('waste.index')" :active="request()->routeIs('waste.index')">Barang Rusak</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.index')">Pengeluaran</x-responsive-nav-link>

                <div
                    class="px-4 py-3 mt-2 text-[10px] font-black uppercase text-stone-400 tracking-[0.2em] border-t border-stone-100">
                    Laporan & Admin</div>
                <x-responsive-nav-link :href="route('owner.laporan')" :active="request()->routeIs('owner.laporan')">Laporan Penjualan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('owner.shifts.index')" :active="request()->routeIs('owner.shifts.index')">Laporan Shift</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">Kelola Kasir</x-responsive-nav-link>
            @endif

            @if (Auth::user()->role == 'kasir')
                <x-responsive-nav-link :href="route('kasir.transaksi')" :active="request()->routeIs('kasir.transaksi')"
                    class="font-bold text-orange-800 bg-orange-50">
                    {{ __('Transaksi Baru') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('kasir.shift.close')" :active="request()->routeIs('kasir.shift.close')" class="text-red-700 font-black">
                    {{ __('Tutup Kasir / Selesai Shift') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-6 border-t border-stone-200">
            <div class="px-4 flex items-center gap-3 mb-6">
                <div class="bg-orange-800 text-white p-2 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <div class="font-black text-stone-800 text-sm tracking-tight">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-stone-400 italic lowercase">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="px-4 space-y-3">
                <x-responsive-nav-link :href="route('profile.edit')"
                    class="rounded-xl border border-stone-200 bg-white text-center font-bold">
                    {{ __('Edit Profil Saya') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-3 px-4 py-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl font-black uppercase tracking-widest text-[11px] hover:bg-red-200 active:scale-95 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Keluar Dari Aplikasi') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
