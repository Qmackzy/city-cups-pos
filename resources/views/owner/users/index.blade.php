<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-stone-800 leading-tight">
            {{ __('Kelola Akun Kasir') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-stone-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-200 h-fit sticky top-6">
                    <h3 class="font-black mb-6 text-stone-800 uppercase text-xs tracking-[0.2em]">Tambah Kasir Baru</h3>
                    <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase mb-1">Nama Lengkap</label>
                            <input type="text" name="name"
                                class="w-full border-stone-200 rounded-xl shadow-sm focus:ring-orange-500 focus:border-orange-500 text-sm"
                                placeholder="Contoh: Budi Santoso" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase mb-1">Email (Login)</label>
                            <input type="email" name="email"
                                class="w-full border-stone-200 rounded-xl shadow-sm focus:ring-orange-500 focus:border-orange-500 text-sm"
                                placeholder="kasir@email.com" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase mb-1">Password</label>
                            <input type="password" name="password"
                                class="w-full border-stone-200 rounded-xl shadow-sm focus:ring-orange-500 focus:border-orange-500 text-sm"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase mb-1">Konfirmasi
                                Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full border-stone-200 rounded-xl shadow-sm focus:ring-orange-500 focus:border-orange-500 text-sm"
                                required>
                        </div>
                        <button type="submit"
                            class="w-full bg-orange-800 text-white py-3 rounded-xl font-bold hover:bg-orange-900 transition shadow-lg shadow-orange-900/20 text-sm mt-4">
                            Simpan Akun Kasir
                        </button>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-stone-200">
                    <h3 class="font-black mb-6 text-stone-800 uppercase text-xs tracking-[0.2em]">Daftar Karyawan Aktif
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-stone-400 text-[10px] uppercase tracking-widest font-black">
                                    <th class="px-4 pb-2">Informasi Kasir</th>
                                    <th class="px-4 pb-2 text-center">Status</th>
                                    <th class="px-4 pb-2 text-right">Manajemen</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($users as $user)
                                    <tr
                                        class="{{ !$user->is_active ? 'opacity-60 bg-stone-50' : 'bg-white' }} border border-stone-100 rounded-xl overflow-hidden transition hover:shadow-md">
                                        <td class="p-4 rounded-l-xl border-y border-l border-stone-100">
                                            <div class="font-bold text-stone-800">{{ $user->name }}</div>
                                            <div class="text-xs text-stone-400">{{ $user->email }}</div>
                                        </td>
                                        <td class="p-4 border-y border-stone-100 text-center">
                                            @if ($user->is_active)
                                                <span
                                                    class="px-3 py-1 bg-green-100 text-green-700 text-[9px] font-black rounded-full border border-green-200">AKTIF</span>
                                            @else
                                                <span
                                                    class="px-3 py-1 bg-stone-200 text-stone-600 text-[9px] font-black rounded-full">NONAKTIF</span>
                                            @endif
                                        </td>
                                        <td class="p-4 rounded-r-xl border-y border-r border-stone-100 text-right">
                                            <div class="flex justify-end gap-4">
                                                <button onclick="openEditModal({{ $user }})"
                                                    class="text-orange-700 hover:text-orange-900 font-black text-[10px] uppercase tracking-tighter">
                                                    Edit / Reset
                                                </button>
                                                @if ($user->is_active)
                                                    <form action="{{ route('users.destroy', $user->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Nonaktifkan kasir ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="text-stone-400 hover:text-red-600 font-black text-[10px] uppercase tracking-tighter transition">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"
                                            class="p-12 text-center text-stone-400 italic bg-stone-50 rounded-2xl border-2 border-dashed border-stone-200">
                                            Belum ada data kasir yang terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editModal"
        class="fixed inset-0 bg-stone-900/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md p-8 rounded-3xl shadow-2xl animate-in fade-in zoom-in duration-200">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-stone-800 tracking-tight text-outline">Update Data Kasir</h3>
                <button onclick="closeModal()"
                    class="text-stone-400 hover:text-stone-800 transition text-2xl">&times;</button>
            </div>

            <form id="editForm" method="POST" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-[10px] font-black uppercase text-stone-500 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="edit_name"
                        class="w-full border-stone-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 text-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-stone-500 mb-1">Email</label>
                    <input type="email" name="email" id="edit_email"
                        class="w-full border-stone-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 text-sm">
                </div>

                <div class="p-4 bg-orange-50 rounded-2xl border border-orange-100 flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                        class="rounded-md border-stone-300 text-orange-800 shadow-sm focus:ring-orange-500 w-5 h-5">
                    <label for="edit_is_active"
                        class="text-xs font-bold text-orange-900 uppercase tracking-tighter">Akun ini dapat mengakses
                        sistem</label>
                </div>

                <div class="pt-4 border-t border-stone-100">
                    <label class="block text-[10px] font-black uppercase text-red-500 mb-2 italic">Ganti Password (Admin
                        Only)</label>
                    <div class="space-y-3">
                        <input type="password" name="password" placeholder="Password Baru"
                            class="w-full border-stone-200 rounded-xl text-sm focus:ring-orange-500">
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru"
                            class="w-full border-stone-200 rounded-xl text-sm focus:ring-orange-500">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()"
                        class="flex-1 px-4 py-3 bg-stone-100 text-stone-600 rounded-xl text-sm font-bold hover:bg-stone-200 transition">Batal</button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-orange-800 text-white rounded-xl text-sm font-bold hover:bg-orange-900 shadow-lg shadow-orange-900/30 transition">Update
                        Akun</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(user) {
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;

            // Handle is_active checkbox
            const statusCheckbox = document.getElementById('edit_is_active');
            statusCheckbox.checked = (user.is_active == 1 || user.is_active == true);

            // Set Form Action URL
            document.getElementById('editForm').action = "/owner/users/" + user.id;

            // Show modal and disable scroll
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking background
        window.onclick = function(event) {
            let modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
    <div x-data="{
        show: {{ session('success') ? 'true' : 'false' }},
        message: '{{ session('success') }}'
    }" x-init="if (show) { setTimeout(() => show = false, 3000) }" x-show="show"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 z-[100] max-w-sm w-full bg-white border-l-4 border-orange-800 shadow-2xl rounded-r-xl p-4 flex items-center gap-3 animate-bounce-subtle"
        style="display: none;">

        <div class="bg-orange-100 p-2 rounded-full">
            <svg class="w-5 h-5 text-orange-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <div>
            <p class="text-xs font-black uppercase tracking-widest text-stone-400">Notifikasi</p>
            <p class="text-sm font-bold text-stone-800" x-text="message"></p>
        </div>

        <button @click="show = false" class="ml-auto text-stone-300 hover:text-stone-500">
            &times;
        </button>
    </div>

    <style>
        @keyframes bounce-subtle {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .animate-bounce-subtle {
            animation: bounce-subtle 2s infinite;
        }
    </style>
</x-app-layout>
