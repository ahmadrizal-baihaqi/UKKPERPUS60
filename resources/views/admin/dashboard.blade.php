<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Libera Admin</title>

    <link rel="icon" href="{{ asset('storage/images/favicon-96x96.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
    /* Pasang font ke body, tapi KECUALI buat elemen ikon */
    body, button, input, select, textarea, span, p, h1, h2, h3, h4, h5, h6, table, td, th {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* Mastiin FontAwesome tetep pake font-family aslinya */
    .fas, .far, .fad, .fab, .fa {
        font-family: "Font Awesome 6 Free" !important; /* Versi 6 sesuai CDN kita */
        font-weight: 900; /* WAJIB buat ikon solid */
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
    }

    body {
        background-color: #fcfdfe;
        letter-spacing: -0.01em;
    }

    /* Halusin Scrollbar */
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #0d6efd; border-radius: 10px; }
</style>
</head>
<body class="text-gray-900">
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar')

        <div class="flex-1">
            <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-gray-100 px-10 flex items-center justify-between">
                <div>
                    <h2 class="text-[10px] font-black text-[#0d6efd] uppercase tracking-[0.2em] mb-0.5">Control Panel</h2>
                    <p class="text-lg font-extrabold text-gray-800">Ringkasan Statistik</p>
                </div>
                <div class="flex items-center gap-4 border-l pl-6 border-gray-100">
                    <div class="text-right">
                        <p class="text-xs font-black text-gray-800 uppercase tracking-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Administrator</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 border border-gray-100">
                        <i class="fas fa-user-shield text-sm"></i>
                    </div>
                </div>
            </header>

            <main class="p-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="group bg-white p-8 rounded-[2rem] border border-gray-100 flex items-center gap-6 shadow-sm hover:border-[#0d6efd] transition-all duration-300">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-[#0d6efd] group-hover:bg-[#0d6efd] group-hover:text-white transition-all duration-300">
                            <i class="fas fa-book-bookmark text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.15em] mb-1">Total Koleksi</p>
                            <h3 class="text-2xl font-black text-gray-800 tracking-tight">{{ $totalBuku }} <span class="text-xs font-normal text-gray-400 ml-1 italic">Items</span></h3>
                        </div>
                    </div>

                    <div class="group bg-white p-8 rounded-[2rem] border border-gray-100 flex items-center gap-6 shadow-sm hover:border-[#0d6efd] transition-all duration-300">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-[#0d6efd] group-hover:bg-[#0d6efd] group-hover:text-white transition-all duration-300">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.15em] mb-1">Anggota Aktif</p>
                            <h3 class="text-2xl font-black text-gray-800 tracking-tight">{{ $totalAnggota }} <span class="text-xs font-normal text-gray-400 ml-1 italic">Users</span></h3>
                        </div>
                    </div>

                    <div class="group bg-white p-8 rounded-[2rem] border border-gray-100 flex items-center gap-6 shadow-sm hover:border-orange-400 transition-all duration-300">
                        <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-exchange-alt text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.15em] mb-1">Peminjaman</p>
                            <h3 class="text-2xl font-black text-gray-800 tracking-tight">{{ $totalPinjaman }} <span class="text-xs font-normal text-gray-400 ml-1 italic">Active</span></h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-gray-100 p-10 shadow-sm">
                    <div class="flex justify-between items-end mb-10">
                        <div>
                            <h3 class="text-xl font-black text-gray-800 tracking-tight">Log Transaksi Terbaru</h3>
                            <p class="text-gray-400 text-xs mt-1 font-medium italic font-normal">Data peminjaman buku dalam periode terakhir.</p>
                        </div>
                        <a href="{{ route('admin.reports') }}" class="px-5 py-2.5 text-[#0d6efd] text-[10px] font-black uppercase tracking-widest border border-blue-100 rounded-xl hover:bg-[#0d6efd] hover:text-white transition-all duration-300">
                            View All Logs
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-gray-400 text-[9px] uppercase tracking-[0.25em] border-b border-gray-50">
                                    <th class="pb-5 font-black">Student Name</th>
                                    <th class="pb-5 font-black">Book Title</th>
                                    <th class="pb-5 font-black text-center">Date Borrowed</th>
                                    <th class="pb-5 font-black text-right">Activity Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($recentLoans as $loan)
                                <tr class="group hover:bg-gray-50/50 transition-all">
                                    <td class="py-5 font-bold text-gray-800 text-sm">{{ $loan->user->name }}</td>
                                    <td class="py-5 text-gray-500 font-medium text-sm">{{ $loan->book->judul }}</td>
                                    <td class="py-5 text-center text-gray-400 font-bold text-[11px]">
                                        {{ strtoupper(\Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y')) }}
                                    </td>
                                    <td class="py-5 text-right text-xs">
                                        @if($loan->status == 'dipinjam')
                                            <span class="px-4 py-1.5 rounded-lg bg-orange-50 text-orange-600 font-black uppercase tracking-widest border border-orange-100" style="font-size: 9px;">On Loan</span>
                                        @else
                                            <span class="px-4 py-1.5 rounded-lg bg-blue-50 text-[#0d6efd] font-black uppercase tracking-widest border border-blue-100" style="font-size: 9px;">Returned</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-20 text-center text-gray-300 text-[10px] font-black uppercase tracking-widest">No recent records available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
