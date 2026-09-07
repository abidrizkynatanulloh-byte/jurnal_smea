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
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #F8FAFC;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        /* Pure Crisp Scrollbar (Human High-End Desktop Software) */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        .custom-sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 2px;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>
<body class="min-h-screen md:h-screen md:overflow-hidden font-sans antialiased text-slate-900 bg-[#F8FAFC] flex flex-col md:flex-row relative">

    @auth
    <!-- ============================================================== -->
    <!-- SIDEBAR NAVIGATION (Linear / Vercel Dark Slate #0F172A)         -->
    <!-- ============================================================== -->
    <aside class="w-full md:w-56 bg-[#0F172A] text-white flex-shrink-0 flex flex-col border-r border-slate-800 shadow-xs md:h-screen md:overflow-y-auto z-40">
        <!-- Brand Header -->
        <div class="h-12 flex items-center px-4 border-b border-slate-800 justify-between shrink-0">
            <div class="flex items-center space-x-2.5">
                <div class="w-6 h-6 rounded bg-slate-100 text-slate-900 flex items-center justify-center font-bold shadow-2xs">
                    <i data-lucide="book-open" class="w-3.5 h-3.5 text-slate-900"></i>
                </div>
                <div>
                    <span class="font-bold text-xs tracking-tight text-white block leading-none">JURNAL SMEA</span>
                    <span class="block text-[9px] font-medium text-slate-400 leading-none mt-1">SMK Negeri 1</span>
                </div>
            </div>
            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-toggle" class="md:hidden p-1 text-slate-400 hover:text-white rounded hover:bg-slate-800 focus:outline-none">
                <i data-lucide="menu" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav id="sidebar-nav" class="flex-1 px-2.5 py-3 space-y-0.5 hidden md:block overflow-y-auto custom-sidebar-scroll text-xs">
            
            {{-- ROLE 1: STAF TU / ADMIN --}}
            @if(Auth::user()->role === 'staf_tu')
                <div class="pt-1 pb-1">
                    <span class="px-2 text-[9.5px] font-semibold text-slate-500 uppercase tracking-widest">Utama</span>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md font-medium transition-colors {{ Route::is('admin.dashboard') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 {{ Route::is('admin.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Dashboard Admin</span>
                </a>

                <div class="pt-3 pb-1">
                    <span class="px-2 text-[9.5px] font-semibold text-slate-500 uppercase tracking-widest">Master Data</span>
                </div>
                <a href="{{ route('admin.guru.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md font-medium transition-colors {{ Route::is('admin.guru.*') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="users" class="w-3.5 h-3.5 {{ Route::is('admin.guru.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Data Guru & Pegawai</span>
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md font-medium transition-colors {{ Route::is('admin.siswa.*') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="graduation-cap" class="w-3.5 h-3.5 {{ Route::is('admin.siswa.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Data Siswa</span>
                </a>
                <a href="{{ route('admin.mapel.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md font-medium transition-colors {{ Route::is('admin.mapel.*') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="book-marked" class="w-3.5 h-3.5 {{ Route::is('admin.mapel.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Mata Pelajaran</span>
                </a>
                <a href="{{ route('admin.jam.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md font-medium transition-colors {{ Route::is('admin.jam.*') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="clock" class="w-3.5 h-3.5 {{ Route::is('admin.jam.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Master Jam Pelajaran</span>
                </a>
                <a href="{{ route('admin.jadwal.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md font-medium transition-colors {{ Route::is('admin.jadwal.*') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 {{ Route::is('admin.jadwal.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Jadwal Mengajar</span>
                </a>
                <a href="{{ route('admin.guru-piket.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md font-medium transition-colors {{ Route::is('admin.guru-piket.*') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="user-check" class="w-3.5 h-3.5 {{ Route::is('admin.guru-piket.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Kelola Guru Piket</span>
                </a>
                <a href="{{ route('admin.waka.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md font-medium transition-colors {{ Route::is('admin.waka.*') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="shield" class="w-3.5 h-3.5 {{ Route::is('admin.waka.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Kelola Waka</span>
                </a>

                <div class="pt-3 pb-1">
                    <span class="px-2 text-[9.5px] font-semibold text-slate-500 uppercase tracking-widest">Laporan & Pengguna</span>
                </div>
                <a href="{{ route('admin.rekap.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md font-medium transition-colors {{ Route::is('admin.rekap.*') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="clipboard-list" class="w-3.5 h-3.5 {{ Route::is('admin.rekap.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Rekap Jurnal & Absensi</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md font-medium transition-colors {{ Route::is('admin.users.*') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 {{ Route::is('admin.users.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Kelola Pengguna</span>
                </a>

            @endif

            {{-- ROLE 2: GURU MATA PELAJARAN (Juga untuk Wakasis karena mereka tetap mengajar) --}}
            @if(in_array(Auth::user()->role, ['guru', 'wakasis_siswa', 'wakasis_guru']))
                <div class="pb-1 pt-3">
                    <span class="px-2 text-[9.5px] font-semibold text-slate-500 uppercase tracking-widest">Pembelajaran</span>
                </div>
                <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('guru.dashboard') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 {{ Route::is('guru.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Jadwal & Jurnal Hari Ini</span>
                </a>
                <a href="{{ route('guru.jurnal.rekap') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('guru.jurnal.rekap') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="history" class="w-3.5 h-3.5 {{ Route::is('guru.jurnal.rekap') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Riwayat Jurnal Saya</span>
                </a>
                @if(Auth::user()->guru && Auth::user()->guru->isWaliKelas())
                    <a href="{{ route('guru.wali-kelas') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('guru.wali-kelas') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                        <i data-lucide="users" class="w-3.5 h-3.5 {{ Route::is('guru.wali-kelas') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Rekap Wali Kelas</span>
                    </a>
                @endif
                <a href="{{ route('guru.izin.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('guru.izin.*') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="calendar-off" class="w-3.5 h-3.5 {{ Route::is('guru.izin.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Pengajuan Izin Mengajar</span>
                </a>

                @if(Auth::user()->guru && Auth::user()->guru->isPiketHariIni())
                    <div class="pt-3 pb-1">
                        <span class="px-2 text-[9.5px] font-semibold text-slate-500 uppercase tracking-widest">Guru Piket Hari Ini</span>
                    </div>
                    <a href="{{ route('piket.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('piket.dashboard') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                        <i data-lucide="clipboard-list" class="w-3.5 h-3.5 {{ Route::is('piket.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Input Dispen & Siswa Telat</span>
                    </a>
                    <a href="{{ route('piket.monitoring-kelas') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('piket.monitoring-kelas') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                        <i data-lucide="monitor" class="w-3.5 h-3.5 {{ Route::is('piket.monitoring-kelas') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Monitoring Kondisi Kelas</span>
                    </a>
                @endif
            @endif

            {{-- ROLE 3: GURU PIKET (Eksklusif) --}}
            @if(Auth::user()->role === 'guru_piket')
                <div class="pb-1 pt-3">
                    <span class="px-2 text-[9.5px] font-semibold text-slate-500 uppercase tracking-widest">Piket Monitoring</span>
                </div>
                <a href="{{ route('piket.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('piket.dashboard') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="clipboard-list" class="w-3.5 h-3.5 {{ Route::is('piket.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Input Dispen & Siswa Telat</span>
                </a>
                <a href="{{ route('piket.monitoring-kelas') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('piket.monitoring-kelas') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="monitor" class="w-3.5 h-3.5 {{ Route::is('piket.monitoring-kelas') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Monitoring Kondisi Kelas</span>
                </a>
            @endif

            {{-- ROLE 4: KEPALA SEKOLAH --}}
            @if(Auth::user()->role === 'kepala_sekolah')
                <div class="pb-1 pt-3">
                    <span class="px-2 text-[9.5px] font-semibold text-slate-500 uppercase tracking-widest">Eksekutif</span>
                </div>
                <a href="{{ route('kepsek.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('kepsek.dashboard') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="bar-chart-3" class="w-3.5 h-3.5 {{ Route::is('kepsek.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Executive Summary</span>
                </a>
                <a href="{{ route('kepsek.rekap.kegiatan') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('kepsek.rekap.kegiatan') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="book-open" class="w-3.5 h-3.5 {{ Route::is('kepsek.rekap.kegiatan') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Jurnal & Guru Pengganti</span>
                </a>
                <a href="{{ route('kepsek.rekap.kepatuhan') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('kepsek.rekap.kepatuhan') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="file-check" class="w-3.5 h-3.5 {{ Route::is('kepsek.rekap.kepatuhan') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Kepatuhan Guru Mengajar</span>
                </a>
                <a href="{{ route('kepsek.rekap.guru-piket') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('kepsek.rekap.guru-piket') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="user-check" class="w-3.5 h-3.5 {{ Route::is('kepsek.rekap.guru-piket') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Kinerja Guru Piket</span>
                </a>
            @endif

            {{-- ROLE 5: WAKIL KESISWAAN (WAKASIS) --}}
            @if(in_array(Auth::user()->role, ['wakasis_siswa', 'wakasis_guru']))
                <div class="pb-1 pt-3">
                    <span class="px-2 text-[9.5px] font-semibold text-slate-500 uppercase tracking-widest">Kesiswaan</span>
                </div>
                <a href="{{ route('wakasis.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('wakasis.dashboard') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="shield-alert" class="w-3.5 h-3.5 {{ Route::is('wakasis.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Analitik Kedisiplinan</span>
                </a>
                <a href="{{ route('wakasis.rekap.ketidakhadiran') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('wakasis.rekap.ketidakhadiran') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="users-2" class="w-3.5 h-3.5 {{ Route::is('wakasis.rekap.ketidakhadiran') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Absensi & Siswa Bolos</span>
                </a>
                <a href="{{ route('wakasis.rekap.keterlambatan') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('wakasis.rekap.keterlambatan') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="clock" class="w-3.5 h-3.5 {{ Route::is('wakasis.rekap.keterlambatan') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Keterlambatan Siswa</span>
                </a>
                <a href="{{ route('wakasis.rekap.dispensasi') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('wakasis.rekap.dispensasi') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="file-badge-2" class="w-3.5 h-3.5 {{ Route::is('wakasis.rekap.dispensasi') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Dispensasi & Meninggalkan KBM</span>
                </a>
            @endif

            {{-- ROLE 6: SATBER / SATPAM --}}
            @if(Auth::user()->role === 'satpam')
                <div class="pb-1 pt-3">
                    <span class="px-2 text-[9.5px] font-semibold text-slate-500 uppercase tracking-widest">Keamanan Gerbang</span>
                </div>
                <a href="{{ route('satpam.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('satpam.dashboard') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="scan-face" class="w-3.5 h-3.5 {{ Route::is('satpam.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Pencatatan Gerbang</span>
                </a>
                <a href="{{ route('satpam.riwayat') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-md text-xs font-medium transition-colors {{ Route::is('satpam.riwayat') ? 'bg-slate-800 text-white font-semibold shadow-2xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i data-lucide="clock" class="w-3.5 h-3.5 {{ Route::is('satpam.riwayat') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Log Tamu & Siswa</span>
                </a>
            @endif

            <!-- User Info Card in Sidebar Bottom (Linear / Stripe Sleek Minimalist) -->
            <div class="pt-3 border-t border-slate-800 mt-4 space-y-1.5 shrink-0">
                <div class="px-2.5 py-2 rounded-md bg-slate-900 border border-slate-800 flex items-center space-x-2.5">
                    <div class="w-6.5 h-6.5 rounded bg-slate-800 text-slate-200 font-bold text-[11px] flex items-center justify-center shrink-0 border border-slate-700">
                        {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                    </div>
                    <div class="overflow-hidden flex-1">
                        <p class="text-[11px] font-semibold text-white truncate leading-none">{{ Auth::user()->nama_display ?? Auth::user()->username }}</p>
                        <p class="text-[9px] text-slate-400 uppercase tracking-wider truncate font-medium mt-1 leading-none">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-2 px-2.5 py-1.5 rounded-md text-xs font-medium text-slate-400 hover:bg-rose-950/40 hover:text-rose-400 transition-colors cursor-pointer">
                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>
    @endauth

    <main class="flex-1 flex flex-col min-w-0 md:h-screen md:overflow-hidden bg-[#F8FAFC]">
        @auth
        <!-- Top Navigation Header (Human Big-Tech Clean Precision - FIXED) -->
        <header class="h-12 bg-white border-b border-slate-200 shrink-0 flex items-center justify-between px-5 shadow-2xs z-30">
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-800 border border-slate-200 rounded text-[10.5px] font-semibold uppercase tracking-wider">
                    {{ str_replace('_', ' ', Auth::user()->role) }}
                </span>
                <span class="text-xs text-slate-300">/</span>
                <span class="text-xs text-slate-500 font-medium">SMK Negeri 1 (SMEA)</span>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Date Display -->
                <div class="hidden sm:flex items-center space-x-1.5 text-xs text-slate-600 font-medium bg-slate-50 px-2.5 py-1 rounded border border-slate-200">
                    <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i>
                    <span>{{ date('l, d M Y') }}</span>
                </div>
                
                <!-- Quick User Status -->
                <div class="flex items-center space-x-2.5 pl-3 border-l border-slate-200">
                    <div class="w-6.5 h-6.5 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-[11px]">
                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                    </div>
                    <div class="hidden lg:block text-left">
                        <p class="text-xs font-semibold text-slate-900 leading-none">{{ Auth::user()->nama_display ?? Auth::user()->username }}</p>
                        <div class="flex items-center space-x-1 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span class="text-[10px] text-slate-500 leading-none">Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        @endauth

        <!-- Body Page Contents: Full Height SaaS Application Canvas -->
        <div class="flex-1 p-3.5 md:p-4 max-w-[1600px] w-full mx-auto flex flex-col min-h-0 overflow-y-auto md:overflow-hidden">
            <!-- Compact Flash Notification -->
            @if (session('success'))
                <div class="shrink-0 mb-3 p-2.5 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-800 text-xs font-semibold flex items-center space-x-2 shadow-2xs">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="shrink-0 mb-3 p-2.5 bg-sky-50 border border-sky-200 rounded-lg text-sky-800 text-xs font-semibold flex items-center space-x-2 shadow-2xs">
                    <i data-lucide="info" class="w-4 h-4 text-sky-600 flex-shrink-0"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="shrink-0 mb-3 p-2.5 bg-rose-50 border border-rose-200 rounded-lg text-rose-800 text-xs font-semibold shadow-2xs">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
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
