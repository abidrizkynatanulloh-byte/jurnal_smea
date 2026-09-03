<!DOCTYPE html>
<html lang="id" class="bg-[#F4F6FA]">
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
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #F8FAFC;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        
        /* Luxury Enterprise Micro Shadows & Chamfered Ring Borders */
        .card-elevated {
            background-color: #FFFFFF;
            border: 1px solid rgba(209, 217, 235, 0.65);
            box-shadow: 0 1px 3px 0 rgba(16, 24, 40, 0.03), 0 1px 2px -1px rgba(16, 24, 40, 0.03);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-elevated:hover {
            border-color: rgba(134, 151, 195, 0.5);
            box-shadow: 0 8px 20px -3px rgba(16, 24, 40, 0.06), 0 3px 6px -2px rgba(16, 24, 40, 0.03);
        }
        
        /* Tactile Big Tech Primary Button with Inner Bevel */
        .btn-tactile {
            background: linear-gradient(180deg, #4A5D8A 0%, #354264 100%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 1px 3px 0 rgba(64, 80, 120, 0.25), inset 0 1px 0 0 rgba(255, 255, 255, 0.22);
            transition: all 0.15s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-tactile:hover {
            background: linear-gradient(180deg, #42537C 0%, #2E3A58 100%);
            box-shadow: 0 3px 6px 0 rgba(64, 80, 120, 0.32), inset 0 1px 0 0 rgba(255, 255, 255, 0.28);
        }
        .btn-tactile:active {
            transform: scale(0.985);
            box-shadow: 0 1px 2px 0 rgba(64, 80, 120, 0.2);
        }

        /* Modern Crisp Inset Inputs */
        .input-enterprise {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            transition: all 0.15s ease;
        }
        .input-enterprise:focus {
            background-color: #FFFFFF;
            border-color: #405078;
            box-shadow: 0 0 0 3px rgba(64, 80, 120, 0.14);
        }

        /* Ambient Backdrop */
        .bg-mesh-canvas {
            background-color: #F8FAFC;
            background-image: radial-gradient(1200px circle at 50% 0px, rgba(134, 151, 195, 0.08) 0%, transparent 70%);
        }

        /* Ultra-Sleek Scrollbars */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        .custom-sidebar-scroll::-webkit-scrollbar {
            width: 3px;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 9999px;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="min-h-screen font-sans antialiased text-[#1E2538] bg-[#F8FAFC] flex flex-col md:flex-row relative">

    @auth
    <!-- ============================================================== -->
    <!-- SIDEBAR NAVIGATION (Obsidian Executive Navy #111726)            -->
    <!-- ============================================================== -->
    <aside class="w-full md:w-60 bg-gradient-to-b from-[#111726] via-[#141B2D] to-[#0E131F] text-white flex-shrink-0 flex flex-col border-r border-[#242D45]/70 shadow-2xl md:h-screen md:sticky md:top-0 md:overflow-y-auto z-40">
        <!-- Brand Header -->
        <div class="h-14 flex items-center px-5 border-b border-white/[0.08] justify-between shrink-0 bg-white/[0.02]">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#405078] via-[#4F6291] to-[#303D5C] flex items-center justify-center text-white shadow-[0_0_12px_rgba(134,151,195,0.25)] ring-1 ring-white/25">
                    <i data-lucide="book-open-check" class="w-4 h-4"></i>
                </div>
                <div>
                    <span class="font-extrabold text-sm tracking-tight text-white block leading-tight">JURNAL SMEA</span>
                    <span class="block text-[8px] font-bold text-[#8697C3] uppercase tracking-widest">Enterprise Platform</span>
                </div>
            </div>
            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-toggle" class="md:hidden p-1 text-gray-400 hover:text-white rounded-lg hover:bg-white/5 focus:outline-none">
                <i data-lucide="menu" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav id="sidebar-nav" class="flex-1 px-3 py-3.5 space-y-1 hidden md:block overflow-y-auto custom-scrollbar">
            
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
                <a href="{{ route('admin.waka.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('admin.waka.*') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="crown" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Kelola Waka</span>
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

            @endif

            {{-- ROLE 2: GURU MATA PELAJARAN (Juga untuk Wakasis karena mereka tetap mengajar) --}}
            @if(in_array(Auth::user()->role, ['guru', 'wakasis_siswa', 'wakasis_guru']))
                <div class="pb-1.5 pt-4">
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
            @endif

            {{-- ROLE 3: GURU PIKET (Eksklusif) --}}
            @if(Auth::user()->role === 'guru_piket')
                <div class="pb-1.5 pt-4">
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
            @endif

            {{-- ROLE 4: WAKIL KESISWAAN (SISWA) --}}
            @if(Auth::user()->role === 'wakasis_siswa')
                <div class="pb-1.5 pt-4">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Kesiswaan</span>
                </div>
                <a href="{{ route('wakasis.siswa.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('wakasis.siswa.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="check-square" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Persetujuan Dispensasi</span>
                </a>
            @endif

            {{-- ROLE 5: WAKA KURIKULUM & SDM (IZIN GURU) --}}
            @if(Auth::user()->role === 'wakasis_guru')
                <div class="pb-1.5 pt-4">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Kurikulum & SDM</span>
                </div>
                <a href="{{ route('wakasis.guru.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('wakasis.guru.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="file-check-2" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Verifikasi Izin Guru</span>
                </a>

            @endif

            {{-- ROLE 6: KEPALA SEKOLAH --}}
            @if(Auth::user()->role === 'kepala_sekolah')
                <div class="pb-1.5 pt-4">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Pimpinan</span>
                </div>
                <a href="{{ route('kepsek.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('kepsek.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Executive Dashboard</span>
                </a>
            @endif

            {{-- ROLE 7: SATPAM (POS KEAMANAN) --}}
            @if(Auth::user()->role === 'satpam')
                <div class="pb-1.5 pt-4">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Keamanan</span>
                </div>
                <a href="{{ route('satpam.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('satpam.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="shield" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Validasi Gerbang Siswa</span>
                </a>
            @endif

            {{-- ROLE 8: WALI MURID / ORANG TUA --}}
            @if(Auth::user()->role === 'wali_murid')
                <div class="pb-1.5 pt-4">
                    <span class="px-3 text-[10px] font-bold text-[#8697C3]/70 uppercase tracking-wider">Wali Murid</span>
                </div>
                <a href="{{ route('wali.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ Route::is('wali.dashboard') ? 'bg-[#405078] text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="user-check" class="w-4 h-4 text-[#8697C3]"></i>
                    <span>Monitoring Presensi Anak</span>
                </a>
            @endif

            <!-- User Info Card in Sidebar Bottom (Acrylic Glass Style) -->
            <div class="pt-4 border-t border-white/10 mt-5 space-y-2">
                <div class="px-3 py-2 rounded-xl bg-white/[0.05] border border-white/10 backdrop-blur-xs flex items-center space-x-2.5 hover:bg-white/[0.08] transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#4A5D8A] to-[#364467] text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs ring-1 ring-white/20">
                        {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                    </div>
                    <div class="overflow-hidden flex-1">
                        <p class="text-xs font-bold text-white truncate leading-tight">{{ Auth::user()->nama_display ?? Auth::user()->username }}</p>
                        <p class="text-[9.5px] text-[#8697C3] uppercase tracking-wider truncate font-semibold mt-0.5">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-400/90 hover:bg-rose-500/10 hover:text-rose-300 transition-colors cursor-pointer">
                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>
    @endauth

    <main class="flex-1 flex flex-col min-w-0 bg-mesh-canvas">
        @auth
        <!-- Top Navigation Header (Frosted Glassmorphism) -->
        <header class="h-14 bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-5 md:px-7 flex-shrink-0 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
            <div class="flex items-center space-x-2.5">
                <span class="inline-flex items-center px-2.5 py-0.5 bg-gradient-to-r from-[#405078]/10 to-[#5F72A3]/10 text-[#405078] border border-[#405078]/20 rounded-full text-[11px] font-bold uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#405078] mr-1.5"></span>
                    {{ str_replace('_', ' ', Auth::user()->role) }}
                </span>
                <span class="hidden md:inline text-xs text-slate-300">•</span>
                <span class="hidden md:inline text-xs text-slate-500 font-medium">SMK Negeri 1 (SMEA)</span>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Date Display -->
                <div class="hidden sm:flex items-center space-x-2 text-xs text-slate-600 font-medium bg-slate-50 px-3 py-1 rounded-lg border border-slate-200/80">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#405078]"></i>
                    <span>{{ date('l, d M Y') }}</span>
                </div>
                
                <!-- Quick User Status -->
                <div class="flex items-center space-x-2.5 pl-3 border-l border-slate-200">
                    <div class="relative">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#405078] to-[#2B3650] text-white flex items-center justify-center font-bold text-xs shadow-xs ring-2 ring-slate-100">
                            {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                        </div>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                    </div>
                    <div class="hidden lg:block text-left">
                        <p class="text-xs font-bold text-[#1E2538] leading-tight">{{ Auth::user()->nama_display ?? Auth::user()->username }}</p>
                        <p class="text-[10px] text-emerald-600 font-semibold leading-tight mt-0.5">Online</p>
                    </div>
                </div>
            </div>
        </header>
        @endauth

        <!-- Body Page Contents -->
        <div class="flex-1 p-4 md:p-6 max-w-[1600px] w-full mx-auto">
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
