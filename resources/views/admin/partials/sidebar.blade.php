<div class="w-72 bg-white border-r border-gray-100 flex-shrink-0 flex flex-col h-screen sticky top-0 z-50">
    <div class="p-8 flex flex-col h-full">

        <div class="flex flex-col items-center justify-center mb-16 pt-4">
            <div class="w-40 h-40 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('storage/logos/libera.png') }}" class="w-full h-full object-contain">
            </div>
        </div>

        <nav class="space-y-2 flex-1">
            @php
                $menus = [
                    ['route' => 'admin.dashboard', 'icon' => 'fas fa-th-large', 'label' => 'Dashboard'],
                    ['route' => 'admin.categories', 'icon' => 'fas fa-tags', 'label' => 'Kategori'],
                    ['route' => 'admin.books', 'icon' => 'fas fa-book', 'label' => 'Data Buku'],
                    ['route' => 'admin.users', 'icon' => 'fas fa-users', 'label' => 'Data Anggota'],
                    ['route' => 'admin.reports', 'icon' => 'fas fa-file-alt', 'label' => 'Laporan'],
                ];
            @endphp

            @foreach($menus as $menu)
            <a href="{{ route($menu['route']) }}"
               class="flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300 group {{ request()->routeIs($menu['route']) ? 'bg-[#0d6efd] text-white shadow-xl shadow-blue-100' : 'text-gray-400 hover:bg-gray-50 hover:text-[#0d6efd]' }}">
                <div class="w-6 text-center">
                    <i class="{{ $menu['icon'] }} text-base"></i>
                </div>
                <span class="font-bold text-sm tracking-tight">{{ $menu['label'] }}</span>
            </a>
            @endforeach
        </nav>

        <div class="mt-auto pt-8 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-4 px-5 py-4 w-full rounded-2xl text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all duration-300 font-bold text-sm">
                    <div class="w-6 text-center">
                        <i class="fas fa-power-off text-base"></i>
                    </div>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
