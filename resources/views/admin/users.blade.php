<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anggota - Libera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100" x-data="{ openModal: false, editModal: false, userId: '', userName: '', userEmail: '' }">

    <div class="flex h-screen">
        @include('admin.partials.sidebar')

        <div class="flex-1 p-10 overflow-y-auto">
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Manajemen Anggota</h2>
                    <p class="text-gray-500">Kelola data siswa yang terdaftar di perpustakaan.</p>
                </div>
                <button @click="openModal = true" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl shadow-md hover:bg-indigo-700 transition flex items-center">
                    <i class="fas fa-user-plus mr-2"></i> Tambah Anggota
                </button>
            </header>

            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
            @endif

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">NAMA SISWA</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">EMAIL</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $u)
                        <tr class="hover:bg-gray-50 transition text-sm">
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $u->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $u->email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-2">
                                    <button @click="editModal = true; userId = '{{ $u->id }}'; userName = '{{ $u->name }}'; userEmail = '{{ $u->email }}'"
                                            class="bg-amber-100 text-amber-600 p-2 rounded-lg hover:bg-amber-200 transition">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" onsubmit="return confirm('Hapus anggota ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-200 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data anggota.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.away="openModal = false">
            <div class="p-6 border-b border-gray-100 bg-indigo-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-indigo-900">Tambah Anggota Baru</h3>
                <button @click="openModal = false" class="text-2xl text-indigo-400">&times;</button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" class="p-8 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="openModal = false" class="text-gray-500 font-bold">Batal</button>
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">Simpan Anggota</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.away="editModal = false">
            <div class="p-6 border-b border-gray-100 bg-amber-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-amber-700">Edit Data Anggota</h3>
                <button @click="editModal = false" class="text-2xl text-amber-400">&times;</button>
            </div>
            <form :action="'/admin/users/' + userId" method="POST" class="p-8 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" x-model="userName" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none focus:ring-2 focus:ring-amber-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" x-model="userEmail" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none focus:ring-2 focus:ring-amber-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password Baru (Kosongkan jika tidak ganti)</label>
                    <input type="password" name="password" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="editModal = false" class="text-gray-500 font-bold">Batal</button>
                    <button type="submit" class="bg-amber-500 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-amber-600 transition">Update Anggota</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
