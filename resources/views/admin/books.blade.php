<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Buku - Libera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100" x-data="{ openModal: false, editModal: false, bookId: '', bookJudul: '', bookPenulis: '', bookPenerbit: '', bookTahun: '', bookStok: '', bookCategory: '' }">

    <div class="flex h-screen">
        @include('admin.partials.sidebar')

        <div class="flex-1 p-10 overflow-y-auto">
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Koleksi Buku</h2>
                    <p class="text-gray-500">Kelola data buku dan stok perpustakaan.</p>
                </div>
                <button @click="openModal = true" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl shadow-md hover:bg-indigo-700 transition flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Buku
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
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">COVER</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">JUDUL & PENULIS</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">KATEGORI</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">STOK</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($books as $b)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <img src="{{ asset('storage/'.$b->cover) }}" class="w-12 h-16 object-cover rounded shadow-sm mx-auto">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $b->judul }}</div>
                                <div class="text-xs text-gray-400">{{ $b->penulis }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <span class="bg-indigo-50 text-indigo-600 px-2 py-1 rounded-md text-[10px] font-bold uppercase">
                                    {{ $b->category->nama_kategori }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-black text-gray-700">{{ $b->stok }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-2">
                                    <button @click="editModal = true; bookId = '{{ $b->id }}'; bookJudul = '{{ $b->judul }}'; bookPenulis = '{{ $b->penulis }}'; bookPenerbit = '{{ $b->penerbit }}'; bookTahun = '{{ $b->tahun_terbit }}'; bookStok = '{{ $b->stok }}'; bookCategory = '{{ $b->category_id }}'"
                                            class="bg-amber-100 text-amber-600 p-2 rounded-lg hover:bg-amber-200 transition">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.books.delete', $b->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')">
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
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data buku.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" x-transition>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden" @click.away="openModal = false">
            <div class="p-6 border-b flex justify-between items-center bg-indigo-50">
                <h3 class="text-xl font-bold text-indigo-900">Tambah Buku Baru</h3>
                <button @click="openModal = false" class="text-2xl">&times;</button>
            </div>
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="p-8 grid grid-cols-2 gap-4">
                @csrf
                <div class="col-span-2">
                    <label class="block text-sm font-semibold mb-1">Judul Buku</label>
                    <input type="text" name="judul" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Kategori</label>
                    <select name="category_id" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none" required>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Stok</label>
                    <input type="number" name="stok" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Penulis</label>
                    <input type="text" name="penulis" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Penerbit</label>
                    <input type="text" name="penerbit" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" class="w-full border rounded-xl p-2.5 border-gray-300 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Cover Buku</label>
                    <input type="file" name="cover" class="w-full text-xs" required>
                </div>
                <div class="col-span-2 flex justify-end space-x-3 mt-4">
                    <button type="button" @click="openModal = false" class="text-gray-500 font-bold px-4">Batal</button>
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" x-transition>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden" @click.away="editModal = false">
            <div class="p-6 border-b flex justify-between items-center bg-amber-50">
                <h3 class="text-xl font-bold text-amber-700">Edit Data Buku</h3>
                <button @click="editModal = false" class="text-2xl">&times;</button>
            </div>
            <form :action="'/admin/books/' + bookId" method="POST" enctype="multipart/form-data" class="p-8 grid grid-cols-2 gap-4">
                @csrf @method('PUT')
                <div class="col-span-2">
                    <label class="block text-sm font-semibold mb-1">Judul Buku</label>
                    <input type="text" name="judul" x-model="bookJudul" class="w-full border rounded-xl p-2.5 border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Kategori</label>
                    <select name="category_id" x-model="bookCategory" class="w-full border rounded-xl p-2.5 border-gray-300" required>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Stok</label>
                    <input type="number" name="stok" min="1" x-model="bookStok" class="w-full border rounded-xl p-2.5 border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Penulis</label>
                    <input type="text" name="penulis" x-model="bookPenulis" class="w-full border rounded-xl p-2.5 border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Penerbit</label>
                    <input type="text" name="penerbit" x-model="bookPenerbit" class="w-full border rounded-xl p-2.5 border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" x-model="bookTahun" class="w-full border rounded-xl p-2.5 border-gray-300" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Ganti Cover (Opsional)</label>
                    <input type="file" name="cover" class="w-full text-xs">
                </div>
                <div class="col-span-2 flex justify-end space-x-3 mt-4">
                    <button type="button" @click="editModal = false" class="text-gray-500 font-bold px-4">Batal</button>
                    <button type="submit" class="bg-amber-500 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-amber-600 transition">Update Buku</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
