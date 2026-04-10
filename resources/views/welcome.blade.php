<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libera - Perpustakaan Digital Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-white">
    <header class="bg-indigo-900 text-white overflow-hidden relative">
        <nav class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center relative z-10">
            <h1 class="text-2xl font-bold"><i class="fas fa-book-reader mr-2"></i> Libera</h1>
            <div class="space-x-4">
                @auth
                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="bg-indigo-600 px-6 py-2 rounded-xl font-semibold hover:bg-indigo-700 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-indigo-300 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-white text-indigo-900 px-6 py-2 rounded-xl font-bold hover:bg-gray-100 transition">Daftar Akun</a>
                @endauth
            </div>
        </nav>

        <div class="max-w-7xl mx-auto px-6 py-24 flex flex-col items-center text-center relative z-10">
            <h2 class="text-5xl font-extrabold mb-6">Membaca Jadi Lebih Mudah dengan <span class="text-indigo-400">Libera</span></h2>
            <p class="text-xl text-indigo-200 mb-10 max-w-2xl text-center">Aplikasi perpustakaan digital sekolah untuk peminjaman buku yang praktis, cepat, dan modern.</p>
            <div class="flex space-x-4">
                <a href="{{ route('register') }}" class="bg-indigo-500 px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:bg-indigo-600 transition">Mulai Sekarang</a>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 px-8 py-4 rounded-2xl font-bold text-lg">
                    <i class="fas fa-laptop-code mr-2"></i> Berbasis Localhost
                </div>
            </div>
        </div>
    </header>

    <section class="py-20 max-w-7xl mx-auto px-6 text-center">
        <h3 class="text-3xl font-bold text-gray-800 mb-12">Kenapa Memilih Libera?</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="p-8 rounded-3xl bg-gray-50 border hover:shadow-lg transition">
                <i class="fas fa-bolt text-4xl text-indigo-600 mb-6"></i>
                <h4 class="text-xl font-bold mb-4">Peminjaman Cepat</h4>
                <p class="text-gray-600">Proses peminjaman buku hanya butuh beberapa klik dari perangkatmu.</p>
            </div>
            <div class="p-8 rounded-3xl bg-gray-50 border hover:shadow-lg transition">
                <i class="fas fa-calendar-check text-4xl text-indigo-600 mb-6"></i>
                <h4 class="text-xl font-bold mb-4">Kontrol Durasi</h4>
                <p class="text-gray-600">Siswa bisa mengatur durasi pinjam maksimal hingga 14 hari.</p>
            </div>
            <div class="p-8 rounded-3xl bg-gray-50 border hover:shadow-lg transition">
                <i class="fas fa-shield-alt text-4xl text-indigo-600 mb-6"></i>
                <h4 class="text-xl font-bold mb-4">Sistem Terintegrasi</h4>
                <p class="text-gray-600">Data buku dan anggota dikelola secara akurat oleh admin perpustakaan.</p>
            </div>
        </div>
    </section>

    <footer class="bg-gray-100 py-10 text-center text-gray-500">
        <p>&copy; 2026 Libera - Project UKK Rekayasa Perangkat Lunak</p>
    </footer>
</body>
</html>
