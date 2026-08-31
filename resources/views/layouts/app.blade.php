<!DOCTYPE html>
<html lang="id" class="h-full bg-light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jurnal Esemkita')</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons CDN -->
    <script src="https://cdn.jsdelivr.net/npm/lucide/dist/umd/lucide.min.js"></script>
</head>
<body class="h-full font-sans antialiased text-dark flex flex-col md:flex-row">

    @auth
    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-dark text-white flex-shrink-0 flex flex-col border-r border-[#19140015]">
        <!-- Brand Header -->
        <div class="h-16 flex items-center px-6 bg-dark border-b border-[#ffffff15] justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-brand rounded-lg text-white">
                    <i data-lucide="book-open" class="w-6 h-6"></i>
                </div>
                <span class="font-bold text-lg tracking-wider text-light">Jurnal Esemkita</span>
            </div>
            <!-- Mobile Menu Toggle Button (handled by JS at bottom) -->
            <button id="mobile-menu-toggle" class="md:hidden p-1 text-gray-400 hover:text-white focus:outline-none">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav id="sidebar-nav" class="flex-1 px-4 py-6 space-y-1.5 hidden md:block overflow-y-auto">
            @if(Auth::user()->role === 'staf_tu')
                <!-- Admin Sidebar -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Route::is('admin.dashboard') ? 'bg-brand text-white' : 'text-gray-300 hover:bg-[#ffffff08] hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Dashboard</span>
                </a>
                <div class="pt-4 pb-2">
                    <span class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelola Data</span>
                </div>
                <a href="{{ route('admin.guru.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Route::is('admin.guru.*') ? 'bg-brand text-white' : 'text-gray-300 hover:bg-[#ffffff08] hover:text-white' }}">
                    <i data-lucide="users-round" class="w-4 h-4"></i>
                    <span>Data Guru & Pegawai</span>
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Route::is('admin.siswa.*') ? 'bg-brand text-white' : 'text-gray-300 hover:bg-[#ffffff08] hover:text-white' }}">
                    <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                    <span>Data Siswa</span>
                </a>
                <a href="{{ route('admin.mapel.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Route::is('admin.mapel.*') ? 'bg-brand text-white' : 'text-gray-300 hover:bg-[#ffffff08] hover:text-white' }}">
                    <i data-lucide="book-marked" class="w-4 h-4"></i>
                    <span>Mata Pelajaran</span>
                </a>
                <a href="{{ route('admin.jam.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Route::is('admin.jam.*') ? 'bg-brand text-white' : 'text-gray-300 hover:bg-[#ffffff08] hover:text-white' }}">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                    <span>Master Jam</span>
                </a>
                <a href="{{ route('admin.jadwal.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Route::is('admin.jadwal.*') ? 'bg-brand text-white' : 'text-gray-300 hover:bg-[#ffffff08] hover:text-white' }}">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>Jadwal Mengajar</span>
                </a>
                <div class="pt-4 pb-2">
                    <span class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan</span>
                </div>
                <a href="{{ route('admin.rekap.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Route::is('admin.rekap.*') ? 'bg-brand text-white' : 'text-gray-300 hover:bg-[#ffffff08] hover:text-white' }}">
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                    <span>Rekap Jurnal</span>
                </a>
                <div class="pt-4 pb-2">
                    <span class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sistem</span>
                </div>
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Route::is('admin.users.*') ? 'bg-brand text-white' : 'text-gray-300 hover:bg-[#ffffff08] hover:text-white' }}">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    <span>Kelola Pengguna</span>
                </a>
            @elseif(Auth::user()->role === 'guru_piket')
                <!-- Guru Piket Sidebar -->
                <a href="{{ route('piket.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Route::is('piket.dashboard') ? 'bg-brand text-white' : 'text-gray-300 hover:bg-[#ffffff08] hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Piket Dashboard</span>
                </a>
            @elseif(Auth::user()->role === 'wakasis_siswa')
                <!-- Wakasis Siswa Sidebar -->
                <a href="{{ route('wakasis.siswa.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Route::is('wakasis.siswa.dashboard') ? 'bg-brand text-white' : 'text-gray-300 hover:bg-[#ffffff08] hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Kesiswaan Dashboard</span>
                </a>
            @endif

            <!-- Logout Button in Sidebar -->
            <div class="pt-6 border-t border-[#ffffff15] mt-6">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>
    @endauth

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col overflow-hidden min-h-screen">
        @auth
        <!-- Top Navigation Header -->
        <header class="h-16 bg-white border-b border-[#19140015] flex items-center justify-between px-6 flex-shrink-0">
            <div class="flex items-center space-x-4">
                <h2 class="text-sm font-medium text-gray-500">
                    Role: <span class="font-semibold text-dark uppercase">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                </h2>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Date Display -->
                <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-500">
                    <i data-lucide="calendar" class="w-4 h-4 text-brand"></i>
                    <span>{{ date('l, d F Y') }}</span>
                </div>
                
                <!-- User Profile -->
                <div class="flex items-center space-x-3 border-l border-gray-200 pl-4">
                    <div class="w-8 h-8 rounded-full bg-brand/10 border border-brand/20 flex items-center justify-center text-brand font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                    </div>
                    <div class="text-left hidden md:block">
                        <p class="text-xs font-semibold text-dark">{{ Auth::user()->username }}</p>
                        <p class="text-[10px] text-gray-400">Online</p>
                    </div>
                </div>
            </div>
        </header>
        @endauth

        <!-- View Content Body -->
        <div class="flex-1 overflow-auto p-6 md:p-8">
            <!-- Flash Message Banner -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl text-green-700 flex items-start space-x-3 shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl text-red-700 flex items-start space-x-3 shadow-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-sm">Terjadi beberapa kesalahan:</p>
                        <ul class="list-disc list-inside mt-1.5 text-xs space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Global Layout Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide Icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Mobile Menu Toggle logic
            const menuToggle = document.getElementById('mobile-menu-toggle');
            const sidebarNav = document.getElementById('sidebar-nav');
            if (menuToggle && sidebarNav) {
                menuToggle.addEventListener('click', () => {
                    sidebarNav.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
