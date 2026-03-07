<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Akun Kasir</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
                    <h3 class="font-bold mb-4 text-gray-700 uppercase text-xs tracking-wider">Tambah Kasir Baru</h3>
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Nama Lengkap</label>
                            <input type="text" name="name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 text-sm" placeholder="Contoh: Budi Santoso" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Email (Login)</label>
                            <input type="email" name="email" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 text-sm" placeholder="budi@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Password</label>
                            <input type="password" name="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 text-sm" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 text-sm" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-bold hover:bg-blue-700 transition shadow-md text-sm">
                            Simpan Akun
                        </button>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold mb-4 text-gray-700 uppercase text-xs tracking-wider">Daftar Karyawan Kasir</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase">
                                    <th class="p-3 border-b font-bold">Nama & Email</th>
                                    <th class="p-3 border-b font-bold text-center">Status</th>
                                    <th class="p-3 border-b font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($users as $user)
                                <tr class="{{ !$user->is_active ? 'bg-gray-50 opacity-70' : '' }} hover:bg-gray-50 transition">
                                    <td class="p-3 border-b">
                                        <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="p-3 border-b text-center">
                                        @if($user->is_active)
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-extrabold rounded-full">AKTIF</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-[10px] font-extrabold rounded-full">NONAKTIF</span>
                                        @endif
                                    </td>
                                    <td class="p-3 border-b text-center">
                                        <div class="flex justify-center gap-3">
                                            <button onclick="openEditModal({{ $user }})" class="text-blue-600 hover:text-blue-800 font-bold text-xs uppercase tracking-tighter">
                                                Edit / Aktifkan
                                            </button>
                                            @if($user->is_active)
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan akun ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs uppercase tracking-tighter">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="p-10 text-center text-gray-400 italic">Belum ada data kasir.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-6 border w-96 shadow-2xl rounded-xl bg-white animate__animated animate__fadeInDown animate__faster">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Edit Data Kasir</h3>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="block text-xs font-bold uppercase text-gray-600">Nama</label>
                    <input type="text" name="name" id="edit_name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 text-sm">
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold uppercase text-gray-600">Email</label>
                    <input type="email" name="email" id="edit_email" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 text-sm">
                </div>
                
                <div class="mb-4 mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100 flex items-center">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <label for="edit_is_active" class="ml-2 text-xs font-bold text-blue-700">Akun ini Aktif (Bisa Login)</label>
                </div>

                <div class="mb-3 border-t pt-3 mt-4">
                    <label class="block text-xs font-bold uppercase text-red-500">Ganti Password</label>
                    <p class="text-[10px] text-gray-400 mb-1 leading-tight">*Kosongkan jika tidak ingin mengganti password</p>
                    <input type="password" name="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 text-sm">
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 shadow-lg transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(user) {
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            
            // Logika Status Aktif/Nonaktif
            const statusCheckbox = document.getElementById('edit_is_active');
            statusCheckbox.checked = (user.is_active == 1);

            document.getElementById('editForm').action = "/owner/users/" + user.id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</x-app-layout>