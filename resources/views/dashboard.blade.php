<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa | Libera</title>
    <link rel="icon" href="{{ asset('storage/images/favicon-96x96.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        *:not(i) { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        body { background-color: #fcfdfe; color: #1a202c; overflow-x: hidden; }
        [x-cloak] { display: none !important; }

        .sidebar { background-color: #ffffff; border-right: 1px solid #f1f5f9; }
        .nav-link { color: #94a3b8; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 1rem; }
        .nav-link.active { color: #0d6efd; background-color: #f0f7ff; }

        .tab-content { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body x-data="{
    tab: 'home',
    search: '',
    filterKategori: 'Semua',
    borrowModal: false,
    bookId: '',
    bookJudul: '',
    // Inject data buku
    allBooks: {{ $books->map(function($b) {
        return [
            'id' => $b->id,
            'judul' => $b->judul,
            'penulis' => $b->penulis,
            'kategori' => $b->category->nama_kategori ?? 'Umum',
            'cover' => asset('storage/'.$b->cover),
            'stok' => $b->stok
        ];
    })->toJson() }}
}">

    <div class="flex min-h-screen">

        <aside class="sidebar w-72 fixed h-full z-50 flex flex-col p-8">
            <div class="flex items-center gap-3 mb-12 px-2">
                <img src="{{ asset('storage/logos/libera.png') }}" class="w-10 h-10">
                <span class="text-2xl font-black tracking-tighter uppercase text-gray-950">Libera<span class="text-[#0d6efd]">.</span></span>
            </div>

            <nav class="flex-1 space-y-3">
                <button @click="tab = 'home'" :class="tab === 'home' ? 'active' : ''" class="nav-link w-full flex items-center p-4 gap-4">
                    <i class="fa-solid fa-house-chimney text-lg"></i>
                    <span class="font-bold text-[11px] uppercase tracking-[0.2em]">Ringkasan</span>
                </button>
                <button @click="tab = 'explore'" :class="tab === 'explore' ? 'active' : ''" class="nav-link w-full flex items-center p-4 gap-4">
                    <i class="fa-solid fa-book-open-reader text-lg"></i>
                    <span class="font-bold text-[11px] uppercase tracking-[0.2em]">Jelajahi Buku</span>
                </button>
                <button @click="tab = 'history'" :class="tab === 'history' ? 'active' : ''" class="nav-link w-full flex items-center p-4 gap-4">
                    <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                    <span class="font-bold text-[11px] uppercase tracking-[0.2em]">Riwayat Pinjam</span>
                </button>
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="mt-auto pt-6 border-t border-gray-50">
                @csrf
                <button type="submit" class="flex items-center gap-4 text-red-400 hover:text-red-600 transition w-full p-4 rounded-2xl hover:bg-red-50">
                    <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                    <span class="font-bold text-[11px] uppercase tracking-[0.2em]">Keluar</span>
                </button>
            </form>
        </aside>

        <div class="ml-72 flex-1 flex flex-col">

            <header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 px-12 py-6 flex justify-between items-center border-b border-gray-50">
                <h2 class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-400" x-text="tab === 'home' ? 'Ringkasan Aktivitas' : (tab === 'explore' ? 'Katalog Digital' : 'Catatan Peminjaman')"></h2>

                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-gray-300 uppercase leading-none">Status Siswa</p>
                        <p class="text-sm font-bold text-gray-900 mt-1 uppercase tracking-tighter">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#0d6efd] font-black border border-blue-100 uppercase italic">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="p-12">

                <div x-show="tab === 'home'" class="tab-content">
                    <div class="mb-12">
                        <h1 class="text-5xl font-black text-gray-950 tracking-tighter uppercase mb-3">Halo, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                        <p class="text-gray-400 font-medium italic leading-relaxed">Selamat datang di panel kontrol perpustakaan Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                        <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Koleksi</p>
                                <h3 class="text-5xl font-black text-gray-950 tracking-tighter">{{ count($books) }}</h3>
                                <p class="text-xs font-bold text-[#0d6efd] mt-2 uppercase tracking-tighter">Buku Terdaftar</p>
                            </div>
                            <div class="w-16 h-16 bg-blue-50 text-[#0d6efd] rounded-2xl flex items-center justify-center text-2xl"><i class="fa-solid fa-layer-group"></i></div>
                        </div>
                        <div class="bg-[#050a15] p-10 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden">
                            <div class="relative z-10">
                                <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">Pinjaman Aktif</p>
                                <h3 class="text-5xl font-black text-[#0d6efd] tracking-tighter">{{ count($myLoans->where('status', 'dipinjam')) }}</h3>
                                <p class="text-xs font-bold text-white/30 mt-2 italic uppercase tracking-tighter">Buku sedang Anda bawa</p>
                            </div>
                            <i class="fa-solid fa-bookmark absolute -bottom-4 -right-4 text-white/5 text-9xl"></i>
                        </div>
                    </div>

                    <div class="mb-8 flex justify-between items-center">
                        <h4 class="text-xl font-black uppercase tracking-tighter italic">Koleksi Buku</h4>
                        <button @click="tab = 'explore'" class="text-[10px] font-black uppercase text-[#0d6efd] tracking-widest hover:underline">Lihat Semua</button>
                    </div>
                    <div class="grid grid-cols-4 gap-8">
                        @foreach($books->take(4) as $b)
                        <div class="group cursor-pointer" @click="tab = 'explore'; search = '{{ $b->judul }}'">
                            <div class="aspect-[2/3] rounded-3xl overflow-hidden bg-gray-100 mb-4 shadow-sm group-hover:shadow-xl transition-all duration-500">
                                <img src="{{ asset('storage/'.$b->cover) }}" class="w-full h-full object-cover">
                            </div>
                            <h5 class="font-black text-[10px] uppercase tracking-tighter line-clamp-1 text-gray-900">{{ $b->judul }}</h5>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div x-show="tab === 'explore'" class="tab-content" x-cloak>
    <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
        <div>
            <h2 class="text-4xl font-black text-gray-950 tracking-tighter uppercase">Katalog Buku</h2>
            <p class="text-gray-400 text-sm mt-1 font-medium">Gunakan filter untuk pencarian lebih spesifik.</p>
        </div>

        <div class="flex gap-4 w-full md:w-auto">
            <select x-model="filterKategori" class="px-6 py-4 rounded-2xl border border-gray-100 bg-white shadow-sm font-bold text-[10px] uppercase tracking-widest outline-none focus:ring-4 focus:ring-blue-50">
                <option value="Semua">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->nama_kategori }}">{{ $cat->nama_kategori }}</option>
                @endforeach
            </select>

            <div class="relative flex-1 md:w-80">
                <input type="text" x-model="search" placeholder="Cari judul..."
                    class="w-full pl-12 pr-6 py-4 rounded-2xl border border-gray-100 bg-white shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-[#0d6efd] outline-none font-bold text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-5 text-[#0d6efd]"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
        <template x-for="book in allBooks.filter(i => (filterKategori === 'Semua' || i.kategori === filterKategori) && (i.judul.toLowerCase().includes(search.toLowerCase()) || i.penulis.toLowerCase().includes(search.toLowerCase())))" :key="book.id">
            <div class="group">
                <div class="relative aspect-[2/3] rounded-[2.5rem] overflow-hidden bg-gray-100 mb-6 shadow-sm group-hover:shadow-2xl group-hover:-translate-y-2 transition-all duration-500 border border-gray-50">
                    <img :src="book.cover" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center p-6 backdrop-blur-[2px]">
                        <button @click="borrowModal = true; bookId = book.id; bookJudul = book.judul"
                            class="w-full bg-white text-gray-950 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#0d6efd] hover:text-white transition-all">Pinjam</button>
                    </div>
                    <div class="absolute top-5 left-5">
                        <span class="bg-white/95 backdrop-blur px-3 py-1 text-[9px] font-black text-[#0d6efd] uppercase tracking-widest rounded-lg shadow-sm" x-text="book.kategori"></span>
                    </div>
                    <div class="absolute bottom-5 right-5">
                        <span class="bg-gray-900/80 backdrop-blur px-3 py-1 text-[9px] font-black text-white uppercase tracking-widest rounded-lg">Stok: <span x-text="book.stok"></span></span>
                    </div>
                </div>
                <div class="px-2">
                    <h4 class="font-black text-sm uppercase tracking-tighter text-gray-950 line-clamp-1" x-text="book.judul"></h4>
                    <p class="text-[9px] font-bold text-gray-400 mt-2 uppercase tracking-widest font-medium" x-text="book.penulis"></p>
                </div>
            </div>
        </template>
    </div>
</div>

<div x-show="tab === 'history'" class="tab-content" x-cloak>
    <h2 class="text-4xl font-black text-gray-950 tracking-tighter uppercase mb-12">Data Riwayat Pinjam</h2>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Judul Buku</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Tgl Pinjam</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Batas Kembali</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Tgl Kembali</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Status</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($myLoans as $loan)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-8 py-8">
                        <p class="font-black text-gray-900 text-sm uppercase tracking-tighter line-clamp-1">{{ $loan->book->judul }}</p>
                        <p class="text-[9px] text-[#0d6efd] mt-1 font-bold uppercase tracking-widest font-medium">{{ $loan->book->category->nama_kategori }}</p>
                    </td>
                    <td class="px-8 py-8 text-xs font-bold text-gray-400 uppercase tracking-tighter">
                        {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d/m/Y') }}
                    </td>
                    <td class="px-8 py-8 text-xs font-bold text-red-400 uppercase tracking-tighter">
                        {{ \Carbon\Carbon::parse($loan->batas_kembali)->format('d/m/Y') }}
                    </td>
                    <td class="px-8 py-8 text-xs font-bold uppercase tracking-tighter">
                        @if($loan->status == 'dikembalikan')
                            <span class="text-green-500">{{ \Carbon\Carbon::parse($loan->updated_at)->format('d/m/Y') }}</span>
                        @else
                            <span class="text-gray-300 font-medium tracking-normal">---</span>
                        @endif
                    </td>
                    <td class="px-8 py-8">
                        <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $loan->status == 'dipinjam' ? 'bg-amber-500/10 text-amber-600' : 'bg-green-500/10 text-green-600' }}">
                            {{ $loan->status }}
                        </span>
                    </td>
                    <td class="px-8 py-8 text-center">
                        @if($loan->status == 'dipinjam')
                        <form action="{{ route('book.return', $loan->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button class="bg-gray-950 text-white px-6 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] hover:bg-[#0d6efd] transition-all shadow-lg shadow-gray-200">Kembalikan</button>
                        </form>
                        @else
                            <i class="fa-solid fa-circle-check text-green-400 text-lg opacity-30"></i>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-10 py-32 text-center text-gray-300 font-black uppercase tracking-[0.2em] text-[10px]">Riwayat Peminjaman Belum Ada</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <div x-show="borrowModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 backdrop-blur-md p-6" x-transition>
        <div class="bg-white rounded-[3.5rem] shadow-2xl w-full max-w-md p-14 relative" @click.away="borrowModal = false">
            <div class="text-center">
                <h3 class="text-2xl font-black text-gray-950 tracking-tighter uppercase mb-2">Pinjam Buku?</h3>
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em] mb-10 italic" x-text="bookJudul"></p>
                <form action="{{ route('book.borrow') }}" method="POST">
                    @csrf
                    <input type="hidden" name="book_id" x-model="bookId">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-4">Durasi Pinjam (Hari)</p>
                    <input type="number" name="durasi" min="1" max="14" value="7" class="w-full bg-gray-50 text-center text-5xl font-black text-[#0d6efd] outline-none p-8 rounded-[2rem] border border-gray-100 mb-10">
                    <button type="submit" class="w-full bg-[#0d6efd] text-white py-5 rounded-[2rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all">Konfirmasi</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
