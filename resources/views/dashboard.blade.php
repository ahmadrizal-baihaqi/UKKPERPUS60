<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa Dashboard - Libera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50" x-data="{ borrowModal: false, bookId: '', bookJudul: '' }">

    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-indigo-700 flex items-center">
                <i class="fas fa-book-reader mr-2"></i> Libera
            </h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600 hidden md:block text-sm">Selamat Datang, <strong>{{ Auth::user()->name }}</strong></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-100 transition">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <div class="lg:col-span-2">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-book-open text-indigo-600 mr-2"></i> Jelajahi Buku</h2>

                    <form action="{{ route('dashboard') }}" method="GET" class="relative w-full md:w-80">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari judul atau penulis..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition shadow-sm">
                        <div class="absolute left-3.5 top-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($books as $b)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 flex space-x-5 hover:shadow-md transition group">
                        <div class="relative flex-shrink-0">
                            <img src="{{ asset('storage/'.$b->cover) }}" class="w-28 h-40 object-cover rounded-2xl shadow-md group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 py-1 rounded-md">{{ $b->category->nama_kategori }}</span>
                                <h3 class="font-bold text-gray-900 mt-2 leading-tight line-clamp-2">{{ $b->judul }}</h3>
                                <p class="text-xs text-gray-500 mt-1 italic">Karya: {{ $b->penulis }}</p>
                                <div class="mt-3 flex items-center text-sm text-gray-600">
                                    <i class="fas fa-layer-group mr-2 text-indigo-400"></i>
                                    <span>Stok: <strong>{{ $b->stok }}</strong></span>
                                </div>
                            </div>
                            <button @click="borrowModal = true; bookId = '{{ $b->id }}'; bookJudul = '{{ $b->judul }}'"
                                    class="w-full bg-indigo-600 text-white py-2.5 rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">
                                <i class="fas fa-bookmark mr-2"></i> Pinjam Sekarang
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 py-20 text-center bg-white rounded-3xl border border-dashed border-gray-300">
                        <i class="fas fa-search fa-3x text-gray-200 mb-4"></i>
                        <p class="text-gray-500">Buku yang kamu cari tidak ditemukan.</p>
                        <a href="{{ route('dashboard') }}" class="text-indigo-600 text-sm font-bold mt-2 inline-block">Lihat Semua Buku</a>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-8">
                <h2 class="text-2xl font-bold text-gray-800"><i class="fas fa-history text-indigo-600 mr-2"></i> Pinjaman Saya</h2>
                <div class="space-y-4">
                    @forelse($myLoans as $loan)
                    <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-2 h-full {{ $loan->status == 'dipinjam' ? 'bg-amber-400' : 'bg-green-400' }}"></div>
                        <h4 class="font-bold text-gray-800 pr-4 leading-tight">{{ $loan->book->judul }}</h4>
                        <div class="mt-4 flex flex-col space-y-2 text-xs">
                            <div class="flex items-center text-gray-500">
                                <i class="far fa-calendar-alt mr-2 w-4"></i>
                                Pinjam: {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}
                            </div>
                            <div class="flex items-center {{ $loan->status == 'dipinjam' ? 'text-red-500 font-semibold' : 'text-gray-500' }}">
                                <i class="far fa-clock mr-2 w-4"></i>
                                Batas: {{ \Carbon\Carbon::parse($loan->batas_kembali)->format('d M Y') }}
                            </div>
                        </div>
                        <div class="mt-5 flex items-center justify-between">
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black tracking-wider uppercase {{ $loan->status == 'dipinjam' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                                {{ $loan->status }}
                            </span>
                            @if($loan->status == 'dipinjam')
                            <form action="{{ route('book.return', $loan->id) }}" method="POST">
                                @csrf
                                <button class="bg-gray-900 text-white px-4 py-2 rounded-xl text-[10px] font-bold hover:bg-indigo-700 transition">
                                    Kembalikan <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 bg-indigo-50 rounded-3xl border border-indigo-100 border-dashed">
                        <p class="text-indigo-400 text-sm font-medium">Kamu belum meminjam buku apapun.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <div x-show="borrowModal"
         class="fixed inset-0 z-50 flex items-center justify-center bg-indigo-950/50 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden" @click.away="borrowModal = false">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-indigo-50">
                <h3 class="text-lg font-bold text-indigo-900"><i class="fas fa-info-circle mr-2"></i> Konfirmasi Pinjaman</h3>
                <button @click="borrowModal = false" class="text-indigo-400 hover:text-indigo-600 transition text-2xl">&times;</button>
            </div>
            <form action="{{ route('book.borrow') }}" method="POST" class="p-8">
                @csrf
                <input type="hidden" name="book_id" x-model="bookId">
                <div class="text-center mb-6">
                    <p class="text-gray-500 text-sm">Anda akan meminjam buku:</p>
                    <h4 class="text-xl font-black text-gray-800 mt-1" x-text="bookJudul"></h4>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2 text-center">Berapa hari Anda meminjam?</label>
                    <div class="flex items-center bg-gray-50 rounded-2xl p-2 border">
                        <input type="number" name="durasi" min="1" max="14" value="7"
                               class="w-full bg-transparent text-center text-2xl font-black text-indigo-600 outline-none" required>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 text-center">*Maksimal peminjaman adalah 14 hari.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="borrowModal = false"
                            class="px-6 py-3 rounded-2xl font-bold text-gray-400 hover:bg-gray-100 transition">Batal</button>
                    <button type="submit"
                            class="px-6 py-3 rounded-2xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">
                        Selesaikan
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
