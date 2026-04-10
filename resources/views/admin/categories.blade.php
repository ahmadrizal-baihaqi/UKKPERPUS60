<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori - Libera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100" x-data="{ openModal: false, editModal: false, catId: '', catNama: '' }">

    <div class="flex h-screen">
        @include('admin.partials.sidebar')

        <div class="flex-1 p-10 overflow-y-auto">
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Manajemen Kategori</h2>
                    <p class="text-gray-500">Kelola kategori buku untuk koleksi Anda.</p>
                </div>
                <button @click="openModal = true" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl shadow-md hover:bg-indigo-700 transition flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Kategori
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
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">NO</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">NAMA KATEGORI</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $index => $cat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-700 font-bold">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $cat->nama_kategori }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-2">
                                    <button @click="editModal = true; catId = '{{ $cat->id }}'; catNama = '{{ $cat->nama_kategori }}'"
                                            class="bg-amber-100 text-amber-600 p-2 rounded-lg hover:bg-amber-200 transition">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
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
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.away="openModal = false">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-indigo-50">
                <h3 class="text-lg font-bold text-indigo-900">Tambah Kategori</h3>
                <button @click="openModal = false" class="text-indigo-400 hover:text-indigo-600 text-2xl">&times;</button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" placeholder="Contoh: Fiksi, Sejarah"
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="openModal = false" class="px-5 py-2.5 text-gray-500 font-medium">Batal</button>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.away="editModal = false">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-amber-50">
                <h3 class="text-lg font-bold text-amber-700">Edit Kategori</h3>
                <button @click="editModal = false" class="text-amber-400 hover:text-amber-600 text-2xl">&times;</button>
            </div>
            <form :action="'/admin/categories/' + catId" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" x-model="catNama"
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-amber-500 outline-none transition" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="editModal = false" class="px-5 py-2.5 text-gray-500 font-medium">Batal</button>
                    <button type="submit" class="bg-amber-500 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg hover:bg-amber-600 transition">Update</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
