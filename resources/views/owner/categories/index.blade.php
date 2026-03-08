<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-stone-800 uppercase tracking-tight">
                {{ __('Kelola Kategori') }}
            </h2>
            <button x-data="" @click="$dispatch('open-modal', 'add-category')"
                class="inline-flex items-center px-4 py-2 bg-stone-800 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-stone-700 active:bg-stone-900 transition ease-in-out duration-150 shadow-lg shadow-stone-200">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Kategori Baru
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-bold text-sm shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl font-bold text-sm shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm border border-stone-200 rounded-[2rem]">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-stone-50 border-b border-stone-100">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-stone-400 tracking-widest">Nama
                                Kategori</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase text-stone-400 tracking-widest text-center">
                                Jumlah Produk</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase text-stone-400 tracking-widest text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @forelse($categories as $category)
                            <tr class="hover:bg-stone-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-stone-800">{{ $category->name }}</div>
                                    <div class="text-[10px] text-stone-400 font-mono italic">{{ $category->slug }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-100">
                                        {{ $category->products_count }} Produk
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-stone-400 hover:text-red-600 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-stone-400 italic text-sm">
                                    Belum ada kategori. Klik "Kategori Baru" untuk menambah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal name="add-category" focusable>
        <form method="post" action="{{ route('categories.store') }}" class="p-8">
            @csrf
            <h2 class="text-xl font-black text-stone-800 uppercase tracking-tight">
                Tambah Kategori Baru
            </h2>
            <p class="mt-2 text-sm text-stone-500 italic leading-relaxed">
                Kategori akan memudahkan Anda mengelompokkan menu di kasir.
            </p>

            <div class="mt-6">
                <x-input-label for="name" value="Nama Kategori"
                    class="font-bold text-stone-700 text-xs uppercase tracking-widest" />
                <x-text-input id="name" name="name" type="text"
                    class="mt-1 block w-full border-stone-200 focus:border-orange-800 focus:ring-orange-800 rounded-xl shadow-sm"
                    placeholder="Contoh: Coffee, Non-Coffee, Pastry..." required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 bg-stone-100 text-stone-600 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-stone-200 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-orange-800 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-orange-900 shadow-lg shadow-orange-200 transition">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
