<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F4F6FA]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jurnal & Monitoring Esemkita')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN (Ensures instant, 100% reliable styling across Laragon subfolders & virtual hosts) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#F4F6FA',
                            100: '#E8ECF5',
                            200: '#D1D9EB',
                            300: '#B2BFDE',
                            400: '#8697C3',
                            500: '#5F72A3',
                            600: '#405078',
                            700: '#323E5E',
                            800: '#252E46',
                            900: '#1A2031',
                            DEFAULT: '#405078',
                            hover: '#2F3C5C',
                            light: '#8697C3',
                            soft: '#F4F6FA',
                            border: '#D1D9EB'
                        },
                        navy: {
                            DEFAULT: '#405078',
                            dark: '#2B3650',
                            darker: '#1E2538',
                            light: '#8697C3',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Compiled Local Vite Assets (with auto-detecting asset URL) -->
    @if (file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @elseif (file_exists(public_path('build/manifest.json')))
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
            $jsFile  = $manifest['resources/js/app.js']['file'] ?? null;
        @endphp
        @if ($cssFile)
            <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
        @endif
        @if ($jsFile)
            <script type="module" src="{{ asset('build/' . $jsFile) }}"></script>
        @endif
    @endif

    <!-- Lucide Icons CDN -->
    <script src="https://cdn.jsdelivr.net/npm/lucide/dist/umd/lucide.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-[#1E2538] bg-[#F4F6FA] flex flex-col md:flex-row">

    @auth
    <!-- ============================================================== -->
    <!-- SIDEBAR NAVIGATION (Executive Navy #1E2538 / #405078)           -->
    <!-- ============================================================== -->
    <aside class="w-full md:w-64 bg-[#1E2538] text-white flex-shrink-0 flex flex-col border-r border-[#2B3650] shadow-xl z-20">
        <!-- Brand Header -->
        <div class="h-16 flex items-center px-6 bg-[#1E2538] border-b border-white/10 justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-[#405078] flex items-center justify-center text-white shadow-sm shadow-black/20">
                    <i data-lucide="book-open-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="font-bold text-base tracking-tight text-white block">JURNAL SMEA</span>
                    <span class="block text-[9px] font-semibold text-[#8697C3] uppercase tracking-widest -mt-0.5">Monitoring Sekolah</span>
                </div>
            </div>
            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-toggle" class="md:hidden p-1.5 text-gray-400 hover:text-white rounded-lg hover:bg-white/5 focus:outline-none">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav id="sidebar-nav" class="flex-1 px-4 py-5 space-y-1.5 hidden md:block overflow-y-auto custom-scrollbar">
            
            {{-- ROLE 1: STAF TU / ADMIN --}}
            @if(Auth::user()->role === 'staf_tu')
                <div class="pb-1.5">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Utama</span>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('admin.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Dashboard Admin</span>
                </a>

                <div class="pt-4 pb-1.5">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Master Data</span>
                </div>
                <a href="{{ route('admin.guru.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('admin.guru.*') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="users-round" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Data Guru & Pegawai</span>
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('admin.siswa.*') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="graduation-cap" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Data Siswa</span>
                </a>
                <a href="{{ route('admin.mapel.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('admin.mapel.*') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="book-marked" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Mata Pelajaran</span>
                </a>
                <a href="{{ route('admin.jam.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('admin.jam.*') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="clock" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Master Jam Pelajaran</span>
                </a>
                <a href="{{ route('admin.jadwal.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('admin.jadwal.*') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="calendar" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Jadwal Mengajar</span>
                </a>
                <a href="{{ route('admin.guru-piket.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('admin.guru-piket.*') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="user-check" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Kelola Guru Piket</span>
                </a>

                <div class="pt-4 pb-1.5">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Laporan & Pengguna</span>
                </div>
                <a href="{{ route('admin.rekap.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('admin.rekap.*') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="clipboard-list" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Rekap Jurnal & Absensi</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('admin.users.*') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="shield-check" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Kelola Pengguna</span>
                </a>

            {{-- ROLE 2: GURU MATA PELAJARAN --}}
            @elseif(Auth::user()->role === 'guru')
                <div class="pb-1.5">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Pembelajaran</span>
                </div>
                <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('guru.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Jadwal & Jurnal Hari Ini</span>
                </a>
                <a href="{{ route('guru.jurnal.rekap') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('guru.jurnal.rekap') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="history" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Riwayat Jurnal Saya</span>
                </a>
                @if(Auth::user()->guru && Auth::user()->guru->isWaliKelas())
                    <a href="{{ route('guru.wali-kelas') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('guru.wali-kelas') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="users-2" class="w-4 h-4 text-[#8697C3]"></i>
                        <span>Rekap Wali Kelas</span>
                    </a>
                @endif
                <a href="{{ route('guru.izin.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('guru.izin.*') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="calendar-off" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Pengajuan Izin Mengajar</span>
                </a>

                @if(Auth::user()->guru && Auth::user()->guru->isPiketHariIni())
                    <div class="pt-4 pb-1.5">
                        <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Guru Piket Hari Ini</span>
                    </div>
                    <a href="{{ route('piket.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('piket.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="clipboard-list" class="w-4 h-4 text-[#8697C3]"></i>
                        <span>Input Dispen & Siswa Telat</span>
                    </a>
                    <a href="{{ route('piket.monitoring-kelas') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('piket.monitoring-kelas') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="monitor" class="w-4 h-4 text-[#8697C3]"></i>
                        <span>Monitoring Kondisi Kelas</span>
                    </a>
                @endif

            {{-- ROLE 3: GURU PIKET --}}
            @elseif(Auth::user()->role === 'guru_piket')
                <div class="pb-1.5">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Piket Monitoring</span>
                </div>
                <a href="{{ route('piket.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('piket.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="clipboard-list" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Input & Pantau Dispen</span>
                </a>
                <a href="{{ route('piket.monitoring-kelas') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('piket.monitoring-kelas') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="monitor" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Monitoring Kondisi Kelas</span>
                </a>

            {{-- ROLE 4: WAKIL KESISWAAN (SISWA) --}}
            @elseif(Auth::user()->role === 'wakasis_siswa')
                <div class="pb-1.5">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Kesiswaan</span>
                </div>
                <a href="{{ route('wakasis.siswa.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('wakasis.siswa.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="check-square" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Persetujuan Dispensasi</span>
                </a>

            {{-- ROLE 5: WAKA KURIKULUM & SDM (IZIN GURU) --}}
            @elseif(Auth::user()->role === 'wakasis_guru')
                <div class="pb-1.5">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Kurikulum & SDM</span>
                </div>
                <a href="{{ route('wakasis.guru.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('wakasis.guru.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="file-check-2" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Verifikasi Izin Guru</span>
                </a>

            {{-- ROLE 6: KEPALA SEKOLAH --}}
            @elseif(Auth::user()->role === 'kepala_sekolah')
                <div class="pb-1.5">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Pimpinan</span>
                </div>
                <a href="{{ route('kepsek.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('kepsek.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Executive Dashboard</span>
                </a>

            {{-- ROLE 7: SATPAM (POS KEAMANAN) --}}
            @elseif(Auth::user()->role === 'satpam')
                <div class="pb-1.5">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Keamanan</span>
                </div>
                <a href="{{ route('satpam.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('satpam.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="shield" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Validasi Gerbang Siswa</span>
                </a>

            {{-- ROLE 8: WALI MURID / ORANG TUA --}}
            @elseif(Auth::user()->role === 'wali_murid')
                <div class="pb-1.5">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Wali Murid</span>
                </div>
                <a href="{{ route('wali.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('wali.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="user-check" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Monitoring Presensi Anak</span>
                </a>
            @endif

            <!-- User Info Card in Sidebar Bottom -->
            <div class="pt-6 border-t border-white/10 mt-6 space-y-2">
                <div class="px-3 py-2.5 rounded-xl bg-white/5 border border-white/5 flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-[#405078] text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->username }}</p>
                        <p class="text-[10px] text-[#8697C3] uppercase truncate font-medium">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-3.5 py-2 rounded-xl text-xs font-semibold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>
    @endauth

    <!-- ============================================================== -->
    <!-- MAIN CONTENT AREA                                              -->
    <!-- ============================================================== -->
    <main class="flex-1 flex flex-col min-h-screen overflow-x-hidden bg-[#F4F6FA]">
        @auth
        <!-- Top Navigation Header -->
        <header class="h-16 bg-white border-b border-[#D1D9EB] flex items-center justify-between px-6 md:px-8 flex-shrink-0 shadow-xs z-10">
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 bg-[#405078]/10 text-[#405078] border border-[#405078]/15 rounded-full text-xs font-bold uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#405078] mr-2"></span>
                    {{ str_replace('_', ' ', Auth::user()->role) }}
                </span>
                <span class="hidden md:inline text-xs text-gray-400">•</span>
                <span class="hidden md:inline text-xs text-gray-500 font-medium">SMK Negeri 1 (SMEA)</span>
            </div>
            
            <div class="flex items-center space-x-5">
                <!-- Date Display -->
                <div class="hidden sm:flex items-center space-x-2 text-xs text-gray-500 font-medium bg-[#F8FAFC] px-3.5 py-1.5 rounded-lg border border-[#D1D9EB]">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#405078]"></i>
                    <span>{{ date('l, d M Y') }}</span>
                </div>
                
                <!-- Quick User Status -->
                <div class="flex items-center space-x-3 pl-3 border-l border-gray-200">
                    <div class="w-8 h-8 rounded-full bg-[#405078] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                    </div>
                    <div class="hidden lg:block text-left">
                        <p class="text-xs font-bold text-[#1E2538] leading-tight">{{ Auth::user()->username }}</p>
                        <p class="text-[10px] text-emerald-600 font-semibold flex items-center space-x-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                            <span>Aktif</span>
                        </p>
                    </div>
                </div>
            </div>
        </header>
        @endauth

        <!-- Body Page Contents -->
        <div class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
            <!-- Flash Message Banner -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-600 rounded-r-2xl text-emerald-800 flex items-start space-x-3 shadow-xs">
                    <i data-lucide="check-circle-2" class="w-5 h-5 flex-shrink-0 text-emerald-600 mt-0.5"></i>
                    <p class="text-xs font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6 p-4 bg-sky-50 border-l-4 border-sky-600 rounded-r-2xl text-sky-800 flex items-start space-x-3 shadow-xs">
                    <i data-lucide="info" class="w-5 h-5 flex-shrink-0 text-sky-600 mt-0.5"></i>
                    <p class="text-xs font-semibold">{{ session('info') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-600 rounded-r-2xl text-rose-800 shadow-xs">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 text-rose-600 mt-0.5"></i>
                        <div class="text-xs">
                            <p class="font-bold">Terjadi kesalahan validasi:</p>
                            <ul class="list-disc list-inside mt-1 space-y-0.5 text-rose-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Global Layout Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
    @stack('scripts')
</body>
</html>
