<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Libera</title>
    <link rel="icon" href="{{ asset('storage/images/favicon-96x96.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        *:not(i) { font-family: 'Plus Jakarta Sans', sans-serif !important; }
    </style>
</head>
<body class="bg-[#f3f4f6] antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center p-6">

        <div class="w-full sm:max-w-md bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden sm:rounded-[2.5rem] border border-gray-100 p-10 md:p-12">

            <div class="flex justify-center mb-10">
                <a href="/">
                    <img src="{{ asset('storage/logos/libera.png') }}" class="w-24 h-24 object-contain">
                </a>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label class="block font-bold text-[10px] text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border-gray-100 bg-gray-50 rounded-2xl py-3.5 px-5 font-bold text-sm outline-none focus:border-[#0d6efd] transition-all border">
                    @error('email') <p class="text-red-500 text-[10px] mt-2 font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5">
                    <label class="block font-bold text-[10px] text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full border-gray-100 bg-gray-50 rounded-2xl py-3.5 px-5 font-bold text-sm outline-none focus:border-[#0d6efd] transition-all border">
                </div>

                <div class="flex items-center justify-between mt-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-200 text-[#0d6efd] focus:ring-[#0d6efd]">
                        <span class="ml-2 text-xs font-bold text-gray-400 uppercase tracking-tighter">Remember me</span>
                    </label>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full bg-[#0d6efd] hover:bg-blue-700 text-white font-black uppercase tracking-[0.2em] text-[11px] py-4 rounded-2xl shadow-xl shadow-blue-100 transition-all active:scale-95">
                        Log In
                    </button>
                </div>
            </form>

            <div class="mt-10 pt-8 border-t border-gray-50 text-center">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-[#0d6efd] hover:underline">Daftar</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
