<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan - Libera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        @include('admin.partials.sidebar')
        <div class="flex-1 p-10 overflow-y-auto">
            <header class="flex justify-between items-center mb-8">
                <div><h2 class="text-3xl font-bold text-gray-800">Laporan Peminjaman</h2><p class="text-gray-500">Histori transaksi lengkap.</p></div>
                <button onclick="window.print()" class="bg-white border text-gray-700 px-5 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition"><i class="fas fa-print mr-2"></i> Cetak</button>
            </header>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-sm">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-400 font-black text-[10px] uppercase">
                        <tr><th class="px-6 py-4">TANGGAL</th><th class="px-6 py-4">SISWA</th><th class="px-6 py-4">BUKU</th><th class="px-6 py-4">BATAS</th><th class="px-6 py-4 text-center">STATUS</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($loans as $loan)
                        <tr class="hover:bg-gray-50 text-gray-700">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-bold text-indigo-900">{{ $loan->user->name }}</td>
                            <td class="px-6 py-4">{{ $loan->book->judul }}</td>
                            <td class="px-6 py-4 text-red-500 font-bold">{{ \Carbon\Carbon::parse($loan->batas_kembali)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase {{ $loan->status == 'dipinjam' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $loan->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
