<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if (Auth::user()->role == 'owner')
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Manajemen Produk</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('products.index')">{{ __('Daftar Produk') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('ingredients.index')">{{ __('Stok Bahan Baku') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('waste.index')">{{ __('Barang Rusak/Waste') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('expenses.index')">{{ __('Pengeluaran') }}</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Laporan & User</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('owner.laporan')">{{ __('Laporan Penjualan') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('owner.shifts.index')">{{ __('Laporan Shift') }}</x-dropdown-link>
                                    <hr class="border-gray-100">
                                    <x-dropdown-link :href="route('users.index')">{{ __('Kelola Kasir') }}</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    @if (Auth::user()->role == 'kasir')
                        <x-nav-link :href="route('kasir.transaksi')" :active="request()->routeIs('kasir.transaksi')">
                            {{ __('Transaksi Baru') }}
                        </x-nav-link>
                        <x-nav-link :href="route('kasir.shift.close')" :active="request()->routeIs('kasir.shift.close')" class="text-red-600">
                            {{ __('Tutup Kasir') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
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
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-gray-50 border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- Mobile Menu Owner --}}
            @if (Auth::user()->role == 'owner')
                <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Produk & Stok</div>
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">Daftar Produk</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ingredients.index')" :active="request()->routeIs('ingredients.index')">Stok Bahan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('waste.index')" :active="request()->routeIs('waste.index')">Barang Rusak</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.index')">Pengeluaran</x-responsive-nav-link>

                <div
                    class="px-4 py-2 mt-2 text-xs font-semibold text-gray-400 uppercase tracking-wider border-t border-gray-100">
                    Laporan & Admin</div>
                <x-responsive-nav-link :href="route('owner.laporan')" :active="request()->routeIs('owner.laporan')">Laporan Penjualan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('owner.shifts.index')" :active="request()->routeIs('owner.shifts.index')">Laporan Shift</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">Kelola Kasir</x-responsive-nav-link>
            @endif

            {{-- Mobile Menu Kasir --}}
            @if (Auth::user()->role == 'kasir')
                <x-responsive-nav-link :href="route('kasir.transaksi')" :active="request()->routeIs('kasir.transaksi')">
                    {{ __('Transaksi Baru') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('kasir.shift.close')" :active="request()->routeIs('kasir.shift.close')" class="text-red-600 font-bold">
                    {{ __('Tutup Kasir / Selesai Shift') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
