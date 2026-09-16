<!DOCTYPE html>
<html lang="id" class="bg-[#EEF1F6] dark:bg-[#1C1F26]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jurnal & Monitoring Esemkita')</title>
    
    <!-- Instant Theme Loader (Zero Flicker) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tom Select (Searchable Dropdowns) -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.default.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

    <!-- SweetAlert2 (Professional Confirmations & Notifications) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS CDN (Ensures instant, 100% reliable styling across Laragon subfolders & virtual hosts) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        @media (min-width: 1024px) {
            html {
                zoom: 90%;
            }
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #EEF1F6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            overflow: hidden;
        }

        /* Light Mode Global Styles (Matching Image 2) */
        .bg-\[\#F8FAFC\],
        .bg-\[\#F4F6FA\] {
            background-color: #EEF1F6 !important;
        }

        thead tr {
            background-color: #E0F2FE !important;
            border-bottom: 1px solid #BAE6FD !important;
        }

        thead th {
            background-color: #E0F2FE !important;
            color: #0369A1 !important;
        }

        /* Pure Crisp Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #E2E8F0;
        }
        ::-webkit-scrollbar-thumb {
            background: #94A3B8;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #64748B;
        }

        html.dark ::-webkit-scrollbar-track {
            background: #181818;
        }
        html.dark ::-webkit-scrollbar-thumb {
            background: #383838;
        }
        html.dark ::-webkit-scrollbar-thumb:hover {
            background: #4E4E4E;
        }

        .custom-sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }
        .custom-sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        /* ================================================================ */
        /* GLOBAL SEAMLESS DARK MODE RULES (Matching Image 3)               */
        /* ================================================================ */
        html.dark body {
            background-color: #1C1F26 !important;
            color: #F1F5F9 !important;
        }

        html.dark .bg-white {
            background-color: #242A35 !important;
        }

        html.dark .bg-\[\#F8FAFC\],
        html.dark .bg-\[\#F4F6FA\],
        html.dark .bg-\[\#EEF1F6\] {
            background-color: #1C1F26 !important;
        }

        html.dark .border-slate-200,
        html.dark .border-slate-100,
        html.dark .border-slate-300,
        html.dark .border-\[\#D1D9EB\] {
            border-color: #2D3543 !important;
        }

        html.dark .divide-slate-100 > * + *,
        html.dark .divide-slate-200 > * + *,
        html.dark .divide-gray-100 > * + * {
            border-color: #2D3543 !important;
        }

        html.dark .text-slate-900,
        html.dark .text-slate-800,
        html.dark .text-gray-900,
        html.dark .text-\[\#1E2538\] {
            color: #F8FAFC !important;
        }

        html.dark .text-slate-700,
        html.dark .text-slate-600,
        html.dark .text-gray-700,
        html.dark .text-gray-600 {
            color: #CBD5E1 !important;
        }

        html.dark .text-slate-500,
        html.dark .text-gray-500 {
            color: #94A3B8 !important;
        }

        html.dark .text-slate-400,
        html.dark .text-gray-400 {
            color: #94A3B8 !important;
        }

        html.dark input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not(.ts-control input),
        html.dark select:not(.tomselected),
        html.dark textarea {
            background-color: #1A212D !important;
            border-color: #2D394C !important;
            color: #F1F5F9 !important;
        }

        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: #94A3B8 !important;
        }

        html.dark .hover\:bg-slate-50:hover,
        html.dark .hover\:bg-slate-50\/80:hover,
        html.dark .hover\:bg-gray-50:hover,
        html.dark .hover\:bg-gray-50\/50:hover {
            background-color: #2A3240 !important;
        }

        html.dark thead tr {
            background-color: #1A2836 !important;
            border-bottom-color: #233F58 !important;
        }

        html.dark thead th {
            background-color: #1A2836 !important;
            color: #93C5FD !important;
            border-bottom-color: #233F58 !important;
        }

        html.dark .bg-slate-50,
        html.dark .bg-slate-100,
        html.dark .bg-gray-50,
        html.dark .bg-gray-100 {
            background-color: #1F2531 !important;
            color: #E2E8F0 !important;
        }

        /* ================================================================ */
        /* ================================================================ */
        /* TOM SELECT COMPACT & NATIVE SELECT PERFECT VERTICAL ALIGNMENT    */
        /* ================================================================ */
        select:not(.tomselected) {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            line-height: normal !important;
        }

        label {
            margin-bottom: 0.35rem !important;
        }

        .ts-wrapper,
        .ts-wrapper.single,
        .ts-wrapper.multi,
        .ts-wrapper.plugin-remove_button {
            font-size: 0.8125rem !important; /* 13px */
            line-height: 1.25rem !important;
            padding: 0 !important;
            margin: 0 !important;
            height: auto !important;
            background: transparent !important;
            background-color: transparent !important;
            border: none !important;
            border-color: transparent !important;
            box-shadow: none !important;
        }

        /* Complete Reset for TomSelect Backgrounds & Colors (Light & Dark Mode) */
        .ts-wrapper,
        .ts-wrapper *,
        .ts-control,
        .ts-control *,
        .ts-control input,
        .ts-control input:focus,
        .ts-control.focus,
        .ts-wrapper.focus,
        .ts-wrapper.input-active,
        .ts-wrapper.input-active *,
        .ts-wrapper.input-active .ts-control,
        .ts-wrapper.input-active .ts-control input {
            box-shadow: none !important;
            outline: none !important;
        }

        .ts-control {
            display: flex !important;
            align-items: center !important;
            min-height: 2.25rem !important;
            height: 2.25rem !important;
            padding: 0 2rem 0 0.75rem !important;
            border-radius: 0.625rem !important; /* 10px rounded-lg */
            border: 1px solid #CBD5E1 !important;
            background-color: #FFFFFF !important;
            color: inherit !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
            margin: 0 !important;
        }
        .ts-control > .item {
            display: inline-flex !important;
            align-items: center !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 0.8125rem !important;
            font-weight: 500 !important;
            line-height: 1 !important;
            white-space: nowrap !important;
            color: inherit !important;
            background: transparent !important;
        }
        .ts-control input {
            display: inline-flex !important;
            align-items: center !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 0.8125rem !important;
            line-height: 1 !important;
            color: inherit !important;
            background: transparent !important;
        }

        /* Perfectly position dropdown chevron icon in center vertical line */
        .ts-wrapper.single .ts-control::after {
            top: 50% !important;
            margin-top: 0 !important;
            transform: translateY(-50%) !important;
            right: 0.75rem !important;
            border-color: #64748B transparent transparent transparent !important;
        }

        /* Sembunyikan item pilihan lama saat user mengklik/mengetik pencarian agar bersih seperti aplikasi profesional */
        .ts-wrapper.input-active .ts-control .item {
            display: none !important;
        }

        /* Light Mode Dropdown */
        .ts-dropdown {
            border-radius: 0.625rem !important;
            border: 1px solid #CBD5E1 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15) !important;
            font-size: 0.8125rem !important;
            background: #FFFFFF !important;
            z-index: 99999 !important;
            margin-top: 4px !important;
            overflow: hidden !important;
        }
        .ts-dropdown .option {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.8125rem !important;
            color: #334155 !important;
            cursor: pointer !important;
        }
        .ts-dropdown .active {
            background-color: #F1F5F9 !important;
            color: #0F172A !important;
            font-weight: 600 !important;
        }

        /* Dark Mode TomSelect Overrides */
        html.dark .ts-control {
            border-color: #2D394C !important;
            background-color: #1A212D !important;
        }
        html.dark .ts-wrapper.single .ts-control::after {
            border-color: #94A3B8 transparent transparent transparent !important;
        }
        html.dark .ts-wrapper,
        html.dark .ts-wrapper *,
        html.dark .ts-control,
        html.dark .ts-control *,
        html.dark .ts-control input,
        html.dark .ts-control input:focus,
        html.dark .ts-wrapper.input-active,
        html.dark .ts-wrapper.input-active *,
        html.dark .ts-wrapper.input-active .ts-control,
        html.dark .ts-wrapper.input-active .ts-control input {
            background: transparent !important;
            background-color: transparent !important;
            color: #F1F5F9 !important;
        }
        html.dark .ts-wrapper .ts-control input::placeholder {
            color: #94A3B8 !important;
        }
        html.dark .ts-wrapper .ts-dropdown,
        html.dark .ts-dropdown {
            background: #151B26 !important;
            background-color: #151B26 !important;
            border-color: #2B3548 !important;
            color: #F1F5F9 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
        }
        html.dark .ts-dropdown .option {
            color: #CBD5E1 !important;
            background: transparent !important;
        }
        html.dark .ts-dropdown .option:hover,
        html.dark .ts-dropdown .active {
            background-color: #222C3D !important;
            background: #222C3D !important;
            color: #FFFFFF !important;
        }
    </style>
</head>
<body class="h-full overflow-hidden font-sans antialiased text-slate-900 dark:text-slate-100 bg-[#EEF1F6] dark:bg-[#1C1F26] flex flex-col md:flex-row relative">

    @auth
    <!-- ============================================================== -->
    <!-- OFF-CANVAS BACKDROP OVERLAY (KHUSUS MOBILE)                     -->
    <!-- ============================================================== -->
    <div id="sidebar-backdrop" onclick="closeSidebarDrawer()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"></div>

    <!-- ============================================================== -->
    <!-- SIDEBAR NAVIGATION (DRAWER DI MOBILE, STATIS DI DESKTOP)       -->
    <!-- ============================================================== -->
    <aside id="sidebar-drawer" class="fixed inset-y-0 left-0 w-72 max-w-[85vw] h-full z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:w-56 md:h-full md:z-40 bg-[#166876] dark:bg-[#181818] text-white flex-shrink-0 flex flex-col border-r border-[#104F5A] dark:border-[#252525] shadow-2xl md:shadow-xl md:rounded-r-2xl overflow-hidden transition-colors">
        <!-- Brand Header -->
        <div class="h-12 flex items-center px-4 border-b border-[#104F5A] dark:border-[#252525] justify-between shrink-0">
            <div class="flex items-center space-x-2.5">
                <!-- Warm Golden Amber Logo Box (Gambar 2 & 3) -->
                <div class="w-6 h-6 rounded bg-[#E4CC67] dark:bg-[#E0C47B] text-[#166876] dark:text-[#181818] flex items-center justify-center font-bold shadow-2xs">
                    <i data-lucide="book-open" class="w-3.5 h-3.5 text-[#166876] dark:text-[#181818]"></i>
                </div>
                <div>
                    <span class="font-bold text-xs tracking-tight text-[#E4CC67] dark:text-[#E0C47B] block leading-none">JURNAL SMEA</span>
                    <span class="block text-[9px] font-medium text-[#D1FAF4] dark:text-slate-400 leading-none mt-1">SMK Negeri 1 Boyolangu</span>
                </div>
            </div>
            <!-- Tombol Tutup Drawer di Mobile -->
            <button type="button" onclick="closeSidebarDrawer()" class="md:hidden p-1.5 text-[#C7E8EA] hover:text-white rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer" title="Tutup Menu">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Navigation Links (Selalu tampil di dalam drawer pada mobile, dan statis di desktop) -->
        <nav id="sidebar-nav" class="flex-1 px-2.5 py-3 space-y-0.5 overflow-y-auto custom-sidebar-scroll text-xs block">
            
            {{-- ROLE 1: STAF TU / ADMIN --}}
            @if(Auth::user()->role === 'staf_tu')
                <div class="pt-1 pb-1">
                    <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Utama</span>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.dashboard') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 {{ Route::is('admin.dashboard') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Dashboard Admin</span>
                </a>

                <div class="pt-3 pb-1">
                    <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Master Data</span>
                </div>
                <a href="{{ route('admin.guru.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.guru.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="users" class="w-3.5 h-3.5 {{ Route::is('admin.guru.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Data Guru & Pegawai</span>
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.siswa.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="graduation-cap" class="w-3.5 h-3.5 {{ Route::is('admin.siswa.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Data Siswa</span>
                </a>
                <a href="{{ route('admin.kelas.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.kelas.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="building-2" class="w-3.5 h-3.5 {{ Route::is('admin.kelas.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Data Per Kelas</span>
                </a>
                <a href="{{ route('admin.mapel.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.mapel.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="book-open" class="w-3.5 h-3.5 {{ Route::is('admin.mapel.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Mata Pelajaran</span>
                </a>
                <a href="{{ route('admin.jam.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.jam.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="clock" class="w-3.5 h-3.5 {{ Route::is('admin.jam.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Master Jam Pelajaran</span>
                </a>
                <a href="{{ route('admin.jadwal.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.jadwal.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 {{ Route::is('admin.jadwal.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Jadwal Mengajar</span>
                </a>
                <a href="{{ route('admin.guru-piket.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.guru-piket.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="user-check" class="w-3.5 h-3.5 {{ Route::is('admin.guru-piket.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Kelola Guru Piket</span>
                </a>
                <a href="{{ route('admin.waka.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.waka.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="shield" class="w-3.5 h-3.5 {{ Route::is('admin.waka.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Kelola Waka</span>
                </a>

                <div class="pt-3 pb-1">
                    <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Laporan & Pengguna</span>
                </div>
                <a href="{{ route('admin.rekap.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.rekap.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="clipboard-list" class="w-3.5 h-3.5 {{ Route::is('admin.rekap.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Rekap Jurnal & Absensi</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.users.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 {{ Route::is('admin.users.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Kelola Pengguna</span>
                </a>

            @endif

            {{-- ROLE 2: GURU BISA MONITOR KELAS & INPUT JURNAL --}}
            @if(in_array(Auth::user()->role, ['guru', 'guru_piket', 'wakasis_guru', 'waka_kurikulum', 'waka_sdm']))
                <div class="pt-3 pb-1">
                    <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">KBM & Pengajaran</span>
                </div>
                <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('guru.dashboard') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 {{ Route::is('guru.dashboard') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Jadwal Mengajar Saya</span>
                </a>
                <a href="{{ route('guru.jurnal.history') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('guru.jurnal.history') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="history" class="w-3.5 h-3.5 {{ Route::is('guru.jurnal.history') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Riwayat Jurnal Mengajar</span>
                </a>
                <a href="{{ route('guru.izin.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('guru.izin.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 {{ Route::is('guru.izin.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Pengajuan Izin Mengajar</span>
                </a>
            @endif

            {{-- MENU KHUSUS WALI KELAS --}}
            @if(Auth::user()->guru && Auth::user()->guru->isWaliKelas())
                <div class="pt-3 pb-1">
                    <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Wali Kelas</span>
                </div>
                <a href="{{ route('guru.wali-kelas') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('guru.wali-kelas') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="users" class="w-3.5 h-3.5 {{ Route::is('guru.wali-kelas') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Rekap Presensi Kelas Saya</span>
                </a>
            @endif

            {{-- MENU KHUSUS GURU PIKET --}}
            @if(Auth::user()->guru && Auth::user()->guru->isPiketHariIni())
                <div class="pt-3 pb-1">
                    <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Tugas Piket</span>
                </div>
                <a href="{{ route('piket.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('piket.dashboard') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="user-check" class="w-3.5 h-3.5 {{ Route::is('piket.dashboard') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Dashboard Guru Piket</span>
                </a>
                <a href="{{ route('piket.monitoring-kelas') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('piket.monitoring-kelas') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="monitor" class="w-3.5 h-3.5 {{ Route::is('piket.monitoring-kelas') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Monitoring Kondisi Kelas</span>
                </a>
            @endif

            {{-- ROLE 3: GURU PIKET (Eksklusif) --}}
            @if(Auth::user()->role === 'guru_piket')
                <div class="pb-1 pt-3">
                    <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Piket Monitoring</span>
                </div>
                <a href="{{ route('piket.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('piket.dashboard') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="clipboard-list" class="w-3.5 h-3.5 {{ Route::is('piket.dashboard') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Input Dispen & Siswa Telat</span>
                </a>
                <a href="{{ route('piket.monitoring-kelas') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('piket.monitoring-kelas') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="monitor" class="w-3.5 h-3.5 {{ Route::is('piket.monitoring-kelas') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Monitoring Kondisi Kelas</span>
                </a>
            @endif

            {{-- ROLE 4: KEPALA SEKOLAH --}}
            @if(Auth::user()->role === 'kepala_sekolah')
                <div class="pb-1 pt-3">
                    <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Eksekutif</span>
                </div>
                <a href="{{ route('kepsek.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('kepsek.dashboard') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="bar-chart-3" class="w-3.5 h-3.5 {{ Route::is('kepsek.dashboard') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Executive Summary</span>
                </a>
                <a href="{{ route('admin.rekap.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('admin.rekap.index') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="book-open" class="w-3.5 h-3.5 {{ Route::is('admin.rekap.index') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Rekap Jurnal & Mengajar</span>
                </a>
                <a href="{{ route('admin.rekap.kepatuhan') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('admin.rekap.kepatuhan') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="file-check" class="w-3.5 h-3.5 {{ Route::is('admin.rekap.kepatuhan') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Kepatuhan Guru Mengajar</span>
                </a>
                <a href="{{ route('admin.guru-piket.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('admin.guru-piket.index') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="user-check" class="w-3.5 h-3.5 {{ Route::is('admin.guru-piket.index') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Penugasan Guru Piket</span>
                </a>
            @endif

            {{-- ROLE 5: WAKIL KEPALA SEKOLAH (WAKA) --}}
            @if(in_array(Auth::user()->role, ['wakasis_siswa', 'wakasis_guru', 'waka_kurikulum', 'waka_sdm']))
                <div class="pb-1 pt-3">
                    <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Kesiswaan & SDM</span>
                </div>
                <a href="{{ route('wakasis.siswa.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('wakasis.siswa.dashboard') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="shield-alert" class="w-3.5 h-3.5 {{ Route::is('wakasis.siswa.dashboard') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Dispensasi Siswa</span>
                </a>
                <a href="{{ route('wakasis.guru.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('wakasis.guru.dashboard') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="users-2" class="w-3.5 h-3.5 {{ Route::is('wakasis.guru.dashboard') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Persetujuan Izin Guru</span>
                </a>
                <a href="{{ route('piket.monitoring-kelas') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('piket.monitoring-kelas') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="monitor" class="w-3.5 h-3.5 {{ Route::is('piket.monitoring-kelas') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Monitoring Kelas</span>
                </a>
            @endif

            {{-- ROLE 6: SATBER / SATPAM --}}
            @if(Auth::user()->role === 'satpam')
                <div class="pb-1 pt-3">
                    <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Keamanan Gerbang</span>
                </div>
                <a href="{{ route('satpam.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('satpam.dashboard') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="scan-face" class="w-3.5 h-3.5 {{ Route::is('satpam.dashboard') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Pencatatan Gerbang & Dispen</span>
                </a>
            @endif
            <!-- Bantuan & Pusat Informasi (Semua Role) -->
            <div class="pt-3 pb-1">
                <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Pusat Bantuan</span>
            </div>
            <button type="button" onclick="openBantuanModal()" class="w-full flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium cursor-pointer text-left">
                <i data-lucide="help-circle" class="w-3.5 h-3.5 text-[#A2D2D6] dark:text-[#9CA3AF]"></i>
                <span>Bantuan & Layanan</span>
            </button>

            <!-- User Info Card in Sidebar Bottom -->
            <div class="pt-3 border-t border-[#104F5A] dark:border-[#252525] mt-4 space-y-1.5 shrink-0">
                <div class="px-2.5 py-2 rounded-lg bg-[#104F5A] dark:bg-[#202020] border border-[#135A66] dark:border-[#2A2A2A] flex items-center space-x-2.5">
                    <div class="w-6.5 h-6.5 rounded bg-[#0B3B44] dark:bg-[#2C2C2C] text-[#E4CC67] dark:text-[#E0C47B] font-bold text-[11px] flex items-center justify-center shrink-0 border border-[#166876] dark:border-[#383838]">
                        {{ Auth::user()->initials }}
                    </div>
                    <div class="overflow-hidden flex-1">
                        <p class="text-[11px] font-semibold text-white truncate leading-none">{{ Auth::user()->nama_display ?? Auth::user()->username }}</p>
                        <p class="text-[9px] text-[#A2D2D6] dark:text-slate-400 uppercase tracking-wider truncate font-medium mt-1 leading-none">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                    </div>
                </div>

                <!-- Red Logout Button (Persis Gambar 1 Sesuai Instruksi User) -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-2 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-rose-400 hover:text-rose-300 hover:bg-rose-500/15 transition-colors cursor-pointer" title="Keluar dari Aplikasi">
                        <i data-lucide="log-out" class="w-3.5 h-3.5 text-rose-400"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>
    @endauth

    <main class="flex-1 flex flex-col min-w-0 h-full overflow-hidden bg-[#EEF1F6] dark:bg-[#1C1F26] transition-colors pb-14 md:pb-0">
        @auth
        <!-- Top Navigation Header -->
        <header class="h-12 bg-white dark:bg-[#141412] border-b border-slate-200 dark:border-[#252525] shrink-0 flex items-center justify-between px-3 sm:px-5 shadow-2xs z-30 transition-colors">
            <div class="flex items-center space-x-1.5 sm:space-x-2">
                <!-- Hamburger Button to open Drawer on Mobile -->
                <button type="button" onclick="openSidebarDrawer()" class="md:hidden p-1.5 -ml-1 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer" title="Buka Menu">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 dark:bg-[#222220] text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded text-[10px] sm:text-[10.5px] font-semibold uppercase tracking-wider truncate max-w-[120px] sm:max-w-none">
                    {{ str_replace('_', ' ', Auth::user()->role) }}
                </span>
                <span class="hidden sm:inline text-xs text-slate-300 dark:text-slate-600">/</span>
                <span class="hidden sm:inline text-xs text-slate-500 dark:text-slate-400 font-medium">SMK Negeri 1 (SMEA)</span>
            </div>
            
            <div class="flex items-center space-x-2.5 sm:space-x-3">
                <!-- Bantuan Button -->
                <button type="button" onclick="openBantuanModal()"
                    class="h-7.5 px-2.5 rounded-lg border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-all flex items-center space-x-1.5 shadow-2xs cursor-pointer text-xs font-semibold"
                    title="Pusat Bantuan & Kontak WhatsApp">
                    <i data-lucide="help-circle" class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400"></i>
                    <span class="hidden sm:inline text-[11px]">Bantuan</span>
                </button>

                <!-- Audit Log Button -->
                <button type="button" onclick="openAuditLogModal()"
                    class="h-7.5 px-2.5 rounded-lg border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-all flex items-center space-x-1.5 shadow-2xs cursor-pointer text-xs font-semibold"
                    title="Lihat Log Aktivitas Sistem">
                    <i data-lucide="activity" class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400"></i>
                    <span class="hidden sm:inline text-[11px]">Audit Log</span>
                </button>

                <!-- Theme Toggle Button (Light & Dark Mode) -->
                <button type="button" onclick="toggleTheme()" id="theme-toggle-btn"
                    class="h-7.5 px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/80 transition-all flex items-center space-x-1.5 shadow-2xs cursor-pointer text-xs font-semibold"
                    title="Ganti Mode Terang / Gelap">
                    <i data-lucide="sun" id="theme-icon-sun" class="w-3.5 h-3.5 text-amber-500 hidden dark:inline-block"></i>
                    <i data-lucide="moon" id="theme-icon-moon" class="w-3.5 h-3.5 text-slate-600 dark:hidden inline-block"></i>
                    <span class="hidden sm:inline text-[11px]" id="theme-label">Tema</span>
                </button>

                <!-- Date Display -->
                <div class="hidden sm:flex items-center space-x-1.5 text-xs text-slate-600 dark:text-slate-300 font-medium bg-slate-50 dark:bg-[#222220] px-2.5 py-1 rounded border border-slate-200 dark:border-slate-700">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span>{{ date('l, d M Y') }}</span>
                </div>

                <!-- Live Real-Time Clock Display -->
                <div class="flex items-center space-x-1.5 text-xs text-slate-800 dark:text-slate-100 font-bold font-mono bg-slate-50 dark:bg-[#222220] px-2.5 py-1 rounded border border-slate-200 dark:border-slate-700 shadow-2xs" title="Waktu Real-Time WIB">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400 animate-pulse"></i>
                    <span id="realtime-clock-display">--:--:-- WIB</span>
                </div>
                
                <!-- Quick User Status -->
                <div class="flex items-center space-x-2.5 pl-2.5 sm:pl-3 border-l border-slate-200 dark:border-slate-700">
                    <div class="w-8 h-8 rounded-xl bg-[#166876] dark:bg-[#2C2C2C] text-[#E4CC67] dark:text-[#E0C47B] flex items-center justify-center font-extrabold text-xs shadow-2xs">
                        {{ Auth::user()->initials }}
                    </div>
                    <div class="hidden lg:block text-left">
                        <p class="text-xs font-semibold text-slate-900 dark:text-white leading-none">{{ Auth::user()->nama_display ?? Auth::user()->username }}</p>
                        <div class="flex items-center space-x-1 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 leading-none">Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        @endauth

        <!-- Body Page Contents: Full Height SaaS Application Canvas -->
        <div class="flex-1 p-2.5 sm:p-3 max-w-[1700px] w-full mx-auto flex flex-col min-h-0 overflow-y-auto">
            <!-- Compact Flash Notification -->
            @if (session('success'))
                <div class="shrink-0 mb-3 p-2.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 rounded-lg text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center space-x-2 shadow-2xs">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="shrink-0 mb-3 p-2.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 rounded-lg text-rose-800 dark:text-rose-300 text-xs font-semibold flex items-center space-x-2 shadow-2xs">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 dark:text-rose-400 flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="shrink-0 mb-3 p-2.5 bg-sky-50 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-800/60 rounded-lg text-sky-800 dark:text-sky-300 text-xs font-semibold flex items-center space-x-2 shadow-2xs">
                    <i data-lucide="info" class="w-4 h-4 text-sky-600 dark:text-sky-400 flex-shrink-0"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="shrink-0 mb-3 p-2.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 rounded-lg text-rose-800 dark:text-rose-300 text-xs font-semibold shadow-2xs">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Modal Pusat Bantuan (Semua Role) -->
    <div id="modalBantuan" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#141412] border border-slate-200 dark:border-slate-800 rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform transition-all">
            <!-- Modal Header -->
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <i data-lucide="help-circle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pusat Bantuan & Informasi</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Layanan dukungan pengguna Jurnal Esemkita</p>
                    </div>
                </div>
                <button type="button" onclick="closeBantuanModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-5 space-y-4 text-xs">
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                    Jika Anda mengalami kendala teknis, lupa kata sandi, atau memerlukan panduan penggunaan, silakan hubungi tim kami melalui saluran di bawah ini:
                </p>

                <!-- Contacts & Media Links -->
                <div class="space-y-2.5">
                    <!-- SALIN GAMBAR WHATSAPP ANDA KE: public/images/wa-icon.png -->
                    <a href="https://wa.me/6285807106570?text=Halo%20Admin%20Jurnal%20Esemkita,%20saya%20butuh%20bantuan" target="_blank"
                       class="flex items-center justify-between p-3 rounded-xl border border-emerald-200 dark:border-emerald-900/60 bg-emerald-50/50 dark:bg-emerald-950/30 hover:bg-emerald-100/60 dark:hover:bg-emerald-900/50 text-emerald-900 dark:text-emerald-200 transition-all group">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('images/wa-icon.png') }}" 
                                 onerror="this.onerror=null; this.src='https://cdn-icons-png.flaticon.com/512/3670/3670051.png';" 
                                 class="w-8 h-8 rounded-lg object-cover shrink-0 shadow-xs" alt="WhatsApp Icon">
                            <div>
                                <p class="font-bold text-xs">WhatsApp Support (Admin)</p>
                                <p class="text-[10.5px] text-emerald-700 dark:text-emerald-400">Klik untuk chat otomatis ke WhatsApp</p>
                            </div>
                        </div>
                        <i data-lucide="external-link" class="w-4 h-4 text-emerald-500 group-hover:translate-x-0.5 transition-transform"></i>
                    </a>

                    <!-- SALIN GAMBAR INSTAGRAM ANDA KE: public/images/ig-icon.png -->
                    <a href="https://instagram.com/smkn1smea" target="_blank"
                       class="flex items-center justify-between p-3 rounded-xl border border-pink-200 dark:border-pink-900/60 bg-pink-50/50 dark:bg-pink-950/30 hover:bg-pink-100/60 dark:hover:bg-pink-900/50 text-pink-900 dark:text-pink-200 transition-all group">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('images/ig-icon.png') }}" 
                                 onerror="this.onerror=null; this.src='https://cdn-icons-png.flaticon.com/512/3955/3955024.png';" 
                                 class="w-8 h-8 rounded-lg object-cover shrink-0 shadow-xs" alt="Instagram Icon">
                            <div>
                                <p class="font-bold text-xs">Instagram Resmi</p>
                                <p class="text-[10.5px] text-pink-700 dark:text-pink-400">@smkn1smea</p>
                            </div>
                        </div>
                        <i data-lucide="external-link" class="w-4 h-4 text-pink-500 group-hover:translate-x-0.5 transition-transform"></i>
                    </a>

                    <!-- SALIN GAMBAR TIKTOK ANDA KE: public/images/tiktok-icon.png -->
                    <a href="https://tiktok.com/@smkn1smea" target="_blank"
                       class="flex items-center justify-between p-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-900 dark:text-slate-100 transition-all group">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('images/tiktok-icon.png') }}" 
                                 onerror="this.onerror=null; this.src='https://cdn-icons-png.flaticon.com/512/3046/3046124.png';" 
                                 class="w-8 h-8 rounded-lg object-cover shrink-0 shadow-xs" alt="TikTok Icon">
                            <div>
                                <p class="font-bold text-xs">TikTok Resmi</p>
                                <p class="text-[10.5px] text-slate-500 dark:text-slate-400">@smkn1smea</p>
                            </div>
                        </div>
                        <i data-lucide="external-link" class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                    </a>
                </div>

                <!-- Language Switcher Section -->
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        <i data-lucide="globe" class="w-3.5 h-3.5 inline mr-1 text-slate-400"></i> Bahasa / Language
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="setLanguage('id')" id="lang-id-btn"
                            class="px-3 py-2 rounded-lg border text-xs font-semibold flex items-center justify-center space-x-2 transition-all bg-emerald-50 border-emerald-500 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-600">
                            <span>🇮🇩 Indonesia</span>
                        </button>
                        <button type="button" onclick="setLanguage('en')" id="lang-en-btn"
                            class="px-3 py-2 rounded-lg border text-xs font-semibold flex items-center justify-center space-x-2 transition-all bg-slate-50 border-slate-200 text-slate-600 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 hover:bg-slate-100">
                            <span>🇬🇧 English</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-5 py-3 bg-slate-50 dark:bg-slate-900/80 border-t border-slate-100 dark:border-slate-800 text-right">
                <button type="button" onclick="closeBantuanModal()" class="h-10 px-6 min-w-[100px] bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs sm:text-sm font-bold rounded-xl transition-colors cursor-pointer shadow-2xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Audit Log (Berlaku untuk Semua Role) -->
    <div id="modalAuditLog" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[85vh]">
            <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-[#1E2538]">
                <div class="flex items-center space-x-2">
                    <i data-lucide="activity" class="w-4 h-4 text-indigo-600 dark:text-indigo-400"></i>
                    <h3 class="font-bold text-slate-900 dark:text-white text-xs">Audit Log Aktivitas Sistem (Real-Time)</h3>
                </div>
                <button onclick="closeAuditLogModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                @forelse($globalAuditLogs ?? [] as $log)
                    <div class="py-2.5 flex items-start space-x-3">
                        <div class="p-1.5 bg-slate-100 dark:bg-slate-800 rounded-lg text-indigo-600 dark:text-indigo-400 mt-0.5 shrink-0">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-bold text-slate-900 dark:text-white truncate">{{ $log->action }}</span>
                                <span class="text-[10px] text-slate-400 font-mono shrink-0">
                                    {{ \Carbon\Carbon::parse($log->created_at)->locale('id')->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-300 text-xs mt-0.5 leading-relaxed">{{ $log->description }}</p>
                            <div class="flex items-center space-x-2 mt-1 text-[10px] text-slate-400">
                                <span>Oleh: <strong class="text-slate-700 dark:text-slate-200">{{ $log->user ? $log->user->nama_display : 'Sistem' }}</strong></span>
                                <span>•</span>
                                <span>IP: {{ $log->ip_address ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 italic text-xs">
                        Belum ada aktivitas penting yang tercatat dalam audit log.
                    </div>
                @endforelse
            </div>
            <div class="px-5 py-3 bg-slate-50 dark:bg-slate-900/80 border-t border-slate-100 dark:border-slate-800 text-right">
                <button type="button" onclick="closeAuditLogModal()" class="h-10 px-6 min-w-[100px] bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs sm:text-sm font-bold rounded-xl transition-colors cursor-pointer shadow-2xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Global Layout Scripts -->
    <script>
        function openAuditLogModal() {
            const modal = document.getElementById('modalAuditLog');
            if (modal) {
                modal.classList.remove('hidden');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        }

        function closeAuditLogModal() {
            const modal = document.getElementById('modalAuditLog');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function openBantuanModal() {
            const modal = document.getElementById('modalBantuan');
            if (modal) {
                modal.classList.remove('hidden');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        }

        function closeBantuanModal() {
            const modal = document.getElementById('modalBantuan');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        const i18nDict = {
            'Dashboard Admin': { id: 'Dashboard Admin', en: 'Admin Dashboard' },
            'Dashboard Tata Usaha': { id: 'Dashboard Tata Usaha', en: 'Administration Dashboard' },
            'Data Guru & Pegawai': { id: 'Data Guru & Pegawai', en: 'Teachers & Staff Data' },
            'Data Siswa': { id: 'Data Siswa', en: 'Students Data' },
            'Mata Pelajaran': { id: 'Mata Pelajaran', en: 'Subjects' },
            'Master Jam Pelajaran': { id: 'Master Jam Pelajaran', en: 'Lesson Hours Master' },
            'Jadwal Mengajar': { id: 'Jadwal Mengajar', en: 'Teaching Schedule' },
            'Kelola Guru Piket': { id: 'Kelola Guru Piket', en: 'Duty Teachers Mgmt' },
            'Kelola Waka': { id: 'Kelola Waka', en: 'Vice Principal Mgmt' },
            'Rekap Jurnal & Absensi': { id: 'Rekap Jurnal & Absensi', en: 'Journal & Attendance' },
            'Kelola Pengguna': { id: 'Kelola Pengguna', en: 'User Management' },
            'Jadwal & Jurnal Hari Ini': { id: 'Jadwal & Jurnal Hari Ini', en: "Today's Schedule & Journal" },
            'Riwayat Jurnal Saya': { id: 'Riwayat Jurnal Saya', en: 'My Journal History' },
            'Rekap Wali Kelas': { id: 'Rekap Wali Kelas', en: 'Homeroom Recap' },
            'Pengajuan Izin Mengajar': { id: 'Pengajuan Izin Mengajar', en: 'Teaching Leave Request' },
            'Input Dispen & Siswa Telat': { id: 'Input Dispen & Siswa Telat', en: 'Input Dispen & Late Students' },
            'Monitoring Kondisi Kelas': { id: 'Monitoring Kondisi Kelas', en: 'Class Condition Monitoring' },
            'Executive Summary': { id: 'Executive Summary', en: 'Executive Summary' },
            'Rekap Jurnal & Mengajar': { id: 'Rekap Jurnal & Mengajar', en: 'Teaching & Journal Recap' },
            'Kepatuhan Guru Mengajar': { id: 'Kepatuhan Guru Mengajar', en: 'Teacher Compliance' },
            'Penugasan Guru Piket': { id: 'Penugasan Guru Piket', en: 'Duty Teacher Assignment' },
            'Dispensasi Siswa': { id: 'Dispensasi Siswa', en: 'Student Dispensation' },
            'Persetujuan Izin Guru': { id: 'Persetujuan Izin Guru', en: 'Teacher Leave Approval' },
            'Monitoring Kelas': { id: 'Monitoring Kelas', en: 'Class Monitoring' },
            'Pencatatan Gerbang & Dispen': { id: 'Pencatatan Gerbang & Dispen', en: 'Gate & Dispen Log' },
            'Bantuan & Layanan': { id: 'Bantuan & Layanan', en: 'Help & Services' },
            'Bantuan': { id: 'Bantuan', en: 'Help' },
            'Tema': { id: 'Tema', en: 'Theme' },
            'Keluar (Logout)': { id: 'Keluar (Logout)', en: 'Logout' },
            'Pusat Bantuan': { id: 'Pusat Bantuan', en: 'Help Center' },
            'Master Data': { id: 'Master Data', en: 'Master Data' },
            'Laporan & Pengguna': { id: 'Laporan & Pengguna', en: 'Reports & Users' },
            'Pembelajaran': { id: 'Pembelajaran', en: 'Learning' },
            'Piket Monitoring': { id: 'Piket Monitoring', en: 'Duty Monitoring' },
            'Eksekutif': { id: 'Eksekutif', en: 'Executive' },
            'Kesiswaan & SDM': { id: 'Kesiswaan & SDM', en: 'Students & HR' },
            'Keamanan Gerbang': { id: 'Keamanan Gerbang', en: 'Gate Security' },
            'TOTAL SISWA': { id: 'TOTAL SISWA', en: 'TOTAL STUDENTS' },
            'Data siswa & rombel': { id: 'Data siswa & rombel', en: 'Student & class data' },
            'KEPATUHAN JURNAL': { id: 'KEPATUHAN JURNAL', en: 'JOURNAL COMPLIANCE' },
            'Kepatuhan pengisian': { id: 'Kepatuhan pengisian', en: 'Filling compliance' },
            'DATA ABSENSI SISWA HARI INI': { id: 'DATA ABSENSI SISWA HARI INI', en: "TODAY'S STUDENT ATTENDANCE" },
            'HADIR': { id: 'HADIR', en: 'PRESENT' },
            'ALPA': { id: 'ALPA', en: 'ABSENT' },
            'IZIN': { id: 'IZIN', en: 'PERMISSION' },
            'SAKIT': { id: 'SAKIT', en: 'SICK' },
            'Lihat Rekapitulasi Jurnal': { id: 'Lihat Rekapitulasi Jurnal', en: 'View Journal Recap' },
            'JADWAL MENGAJAR HARI INI': { id: 'JADWAL MENGAJAR HARI INI', en: "TODAY'S TEACHING SCHEDULE" },
            'Pusat Bantuan & Informasi': { id: 'Pusat Bantuan & Informasi', en: 'Help Center & Information' },
            'Layanan dukungan pengguna Jurnal Esemkita': { id: 'Layanan dukungan pengguna Jurnal Esemkita', en: 'User support service for Jurnal Esemkita' },
            'WhatsApp Support (Admin)': { id: 'WhatsApp Support (Admin)', en: 'WhatsApp Support (Admin)' },
            'Klik untuk chat otomatis ke WhatsApp': { id: 'Klik untuk chat otomatis ke WhatsApp', en: 'Click for auto chat on WhatsApp' },
            'Instagram Resmi': { id: 'Instagram Resmi', en: 'Official Instagram' },
            'TikTok Resmi': { id: 'TikTok Resmi', en: 'Official TikTok' },
            'Bahasa / Language': { id: 'Bahasa / Language', en: 'Language / Bahasa' },
            'Tutup': { id: 'Tutup', en: 'Close' },
            'JUMLAH JADWAL': { id: 'JUMLAH JADWAL', en: 'TOTAL SCHEDULES' },
            'KBM mengajar': { id: 'KBM mengajar', en: 'Teaching activity' },
            'PERHATIAN OPERASIONAL': { id: 'PERHATIAN OPERASIONAL', en: 'OPERATIONAL ATTENTION' },
            'Hari Ini': { id: 'Hari Ini', en: 'Today' },
            'Status Jurnal': { id: 'Status Jurnal', en: 'Journal Status' },
            'NO': { id: 'NO', en: 'NO' },
            'JAM KE-': { id: 'JAM KE-', en: 'HOUR NO.' },
            'KELAS': { id: 'KELAS', en: 'CLASS' },
            'RUANGAN': { id: 'RUANGAN', en: 'ROOM' },
            'STATUS JURNAL': { id: 'STATUS JURNAL', en: 'JOURNAL STATUS' }
        };

        function applyLanguage(lang) {
            const targetLang = lang || 'id';
            localStorage.setItem('app_language', targetLang);

            // Update button styles in modal
            const idBtn = document.getElementById('lang-id-btn');
            const enBtn = document.getElementById('lang-en-btn');
            const activeClass = 'bg-emerald-50 border-emerald-500 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-600';
            const inactiveClass = 'bg-slate-50 border-slate-200 text-slate-600 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 hover:bg-slate-100';

            if (idBtn && enBtn) {
                if (targetLang === 'id') {
                    idBtn.className = `px-3 py-2 rounded-lg border text-xs font-semibold flex items-center justify-center space-x-2 transition-all ${activeClass}`;
                    enBtn.className = `px-3 py-2 rounded-lg border text-xs font-semibold flex items-center justify-center space-x-2 transition-all ${inactiveClass}`;
                } else {
                    enBtn.className = `px-3 py-2 rounded-lg border text-xs font-semibold flex items-center justify-center space-x-2 transition-all ${activeClass}`;
                    idBtn.className = `px-3 py-2 rounded-lg border text-xs font-semibold flex items-center justify-center space-x-2 transition-all ${inactiveClass}`;
                }
            }

            // Translate matching text elements across DOM
            const elements = document.querySelectorAll('span, p, h1, h2, h3, h4, h5, button, a, th, td, label, div');
            elements.forEach(el => {
                el.childNodes.forEach(node => {
                    if (node.nodeType === Node.TEXT_NODE) {
                        const trimmed = node.nodeValue.trim();
                        if (!trimmed) return;
                        for (const [key, val] of Object.entries(i18nDict)) {
                            if (targetLang === 'en' && trimmed === val.id) {
                                node.nodeValue = node.nodeValue.replace(val.id, val.en);
                                break;
                            } else if (targetLang === 'id' && trimmed === val.en) {
                                node.nodeValue = node.nodeValue.replace(val.en, val.id);
                                break;
                            }
                        }
                    }
                });
            });
        }

        function setLanguage(lang) {
            applyLanguage(lang);
            if (typeof Swal !== 'undefined') {
                const isDark = document.documentElement.classList.contains('dark');
                Swal.fire({
                    icon: 'success',
                    title: lang === 'id' ? 'Bahasa Indonesia' : 'English Selected',
                    text: lang === 'id' ? 'Bahasa berhasil diubah ke Indonesia.' : 'App language successfully changed to English.',
                    timer: 1800,
                    showConfirmButton: false,
                    background: isDark ? '#151B26' : '#FFFFFF',
                    color: isDark ? '#F1F5F9' : '#0F172A'
                });
            }
        }
        // Light & Dark Mode Toggle
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Initialize Searchable Selects via TomSelect (ALL selects globally)
        function initSearchableSelects() {
            if (typeof TomSelect === 'undefined') return;
            
            // Target ALL select elements, except pagination and explicitly excluded ones
            document.querySelectorAll('select').forEach(function(el) {
                if (el.id === 'perPageSelect' || el.classList.contains('no-search')) return;
                if (!el.tomselect) {
                    try {
                        new TomSelect(el, {
                            create: false,
                            maxOptions: 250,
                            placeholder: el.getAttribute('placeholder') || 'Cari / pilih...',
                            allowEmptyOption: true,
                            dropdownParent: 'body',
                            onChange: function(value) {
                                // Trigger native change event so onchange/auto-submit works
                                el.dispatchEvent(new Event('change', { bubbles: true }));
                                this.blur();
                            }
                        });
                    } catch(e) {
                        console.warn('TomSelect init error:', e);
                    }
                }
            });
        }

        // Live Real-Time Clock
        function startRealtimeClock() {
            const clockEl = document.getElementById('realtime-clock-display');
            if (!clockEl) return;

            function update() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                clockEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
            }

            update();
            setInterval(update, 1000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Apply saved language preference
            const savedLang = localStorage.getItem('app_language') || 'id';
            if (savedLang === 'en') {
                applyLanguage('en');
            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Start Real-Time Live Clock
            startRealtimeClock();

            // Mobile Menu Drawer Functions
            window.openSidebarDrawer = function() {
                const drawer = document.getElementById('sidebar-drawer');
                const backdrop = document.getElementById('sidebar-backdrop');
                if (drawer) drawer.classList.remove('-translate-x-full');
                if (backdrop) {
                    backdrop.classList.remove('opacity-0', 'pointer-events-none');
                    backdrop.classList.add('opacity-100', 'pointer-events-auto');
                }
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            };

            window.closeSidebarDrawer = function() {
                const drawer = document.getElementById('sidebar-drawer');
                const backdrop = document.getElementById('sidebar-backdrop');
                if (drawer) drawer.classList.add('-translate-x-full');
                if (backdrop) {
                    backdrop.classList.add('opacity-0', 'pointer-events-none');
                    backdrop.classList.remove('opacity-100', 'pointer-events-auto');
                }
            };

            // Close drawer on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    window.closeSidebarDrawer();
                }
            });

            // Init Searchable Dropdowns
            initSearchableSelects();

            // Intercept native confirm() form submissions and convert them to SweetAlert2 Popups
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (!form || form.dataset.swalConfirmed === 'true') {
                    if (form && form.dataset) delete form.dataset.swalConfirmed;
                    return true;
                }

                const onsubmitAttr = form.getAttribute('onsubmit') || '';
                if (onsubmitAttr.includes('confirm(') || form.dataset.confirm) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    let msgText = form.dataset.confirm || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                    let match = onsubmitAttr.match(/confirm\((['"])(.*?)\1\)/);
                    if (match && match[2]) {
                        msgText = match[2].replace(/\\n/g, '<br>').replace(/\\'/g, "'").replace(/\\"/g, '"');
                    }

                    const isDark = document.documentElement.classList.contains('dark');

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Konfirmasi Tindakan',
                            html: `<div class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 py-1">${msgText}</div>`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#1E2538',
                            cancelButtonColor: '#64748B',
                            confirmButtonText: 'Ya, Lanjutkan',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            background: isDark ? '#151B26' : '#FFFFFF',
                            color: isDark ? '#F1F5F9' : '#0F172A',
                            customClass: {
                                popup: 'rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl p-5',
                                title: 'text-base font-bold text-slate-900 dark:text-white',
                                confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-bold shadow-xs cursor-pointer',
                                cancelButton: 'px-5 py-2.5 rounded-xl text-xs font-bold shadow-xs cursor-pointer'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.dataset.swalConfirmed = 'true';
                                form.submit();
                            }
                        });
                    } else {
                        form.dataset.swalConfirmed = 'true';
                        form.submit();
                    }

                    return false;
                }
            }, true);

            // Handle Laravel Flash Messages with SweetAlert2
            const isDarkTheme = document.documentElement.classList.contains('dark');
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    background: isDarkTheme ? '#151B26' : '#FFFFFF',
                    color: isDarkTheme ? '#F1F5F9' : '#0F172A',
                    customClass: {
                        popup: 'rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl p-5'
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    background: isDarkTheme ? '#151B26' : '#FFFFFF',
                    color: isDarkTheme ? '#F1F5F9' : '#0F172A',
                    customClass: {
                        popup: 'rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl p-5'
                    }
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: "{{ session('warning') }}",
                    background: isDarkTheme ? '#151B26' : '#FFFFFF',
                    color: isDarkTheme ? '#F1F5F9' : '#0F172A',
                    customClass: {
                        popup: 'rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl p-5'
                    }
                });
            @endif

            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: "{{ session('info') }}",
                    background: isDarkTheme ? '#151B26' : '#FFFFFF',
                    color: isDarkTheme ? '#F1F5F9' : '#0F172A',
                    customClass: {
                        popup: 'rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl p-5'
                    }
                });
            @endif
        });

        // Global Detail & Bukti Surat Approval Modal Function
        function openDetailApprovalModal(cfg) {
            document.getElementById('previewModalTitle').innerText = cfg.title || 'Detail Pengajuan & Bukti Surat';
            document.getElementById('previewModalSubtitle').innerText = cfg.subtitle || 'Verifikasi rincian permohonan';
            document.getElementById('previewApplicantName').innerText = cfg.applicantName || '-';
            document.getElementById('previewApplicantMeta').innerText = cfg.applicantMeta || '-';
            document.getElementById('previewCategory').innerText = cfg.category || '-';
            document.getElementById('previewPeriod').innerText = cfg.period || '-';
            document.getElementById('previewReason').innerText = cfg.reason || '-';

            const extraEl = document.getElementById('previewExtra');
            if (cfg.extra) {
                extraEl.innerText = cfg.extra;
                extraEl.classList.remove('hidden');
            } else {
                extraEl.classList.add('hidden');
            }

            // Proof Container (Photo / File / Fallback)
            const proofContainer = document.getElementById('previewProofContainer');
            proofContainer.innerHTML = '';

            if (cfg.proofUrl) {
                const isImage = /\.(jpg|jpeg|png|webp|gif)($|\?)/i.test(cfg.proofUrl);
                if (isImage) {
                    proofContainer.innerHTML = `
                        <div class="relative group w-full flex flex-col items-center">
                            <img src="${cfg.proofUrl}" alt="Bukti Surat" class="max-h-72 rounded-xl object-contain border border-slate-200 dark:border-slate-700 shadow-sm transition-all hover:scale-[1.01]">
                            <a href="${cfg.proofUrl}" target="_blank" class="mt-2 text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 inline-flex items-center space-x-1">
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                <span>Buka Gambar Ukuran Penuh</span>
                            </a>
                        </div>
                    `;
                } else {
                    proofContainer.innerHTML = `
                        <div class="flex flex-col items-center space-y-2 py-3">
                            <i data-lucide="file-text" class="w-10 h-10 text-slate-400"></i>
                            <p class="text-xs text-slate-600 dark:text-slate-300 font-medium">Dokumen Surat Terlampir</p>
                            <a href="${cfg.proofUrl}" target="_blank" class="px-3.5 py-1.5 bg-[#1E2538] hover:bg-[#121724] text-white rounded-lg text-xs font-bold transition-all shadow-xs inline-flex items-center space-x-1.5">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                <span>Buka / Unduh Dokumen</span>
                            </a>
                        </div>
                    `;
                }
            } else {
                proofContainer.innerHTML = `
                    <div class="py-4 text-slate-400 italic text-xs flex flex-col items-center space-y-1">
                        <i data-lucide="image-off" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                        <span>Pemohon Tidak Melampirkan Bukti Foto/Surat</span>
                    </div>
                `;
            }

            // Footer Action Forms (ACC & Tolak)
            const footer = document.getElementById('previewModalFooter');
            let actionsHtml = '';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            if (cfg.approveUrl) {
                actionsHtml += `
                    <form action="${cfg.approveUrl}" method="POST" class="w-full sm:w-auto">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="w-full sm:w-auto h-9 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center space-x-1.5 shadow-xs cursor-pointer">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span>${cfg.approveText || 'Setujui / ACC'}</span>
                        </button>
                    </form>
                `;
            }

            if (cfg.rejectUrl) {
                const inputName = cfg.rejectInputName || 'catatan';
                actionsHtml += `
                    <form action="${cfg.rejectUrl}" method="POST" class="w-full sm:w-auto flex items-center space-x-1.5">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="text" name="${inputName}" placeholder="Alasan penolakan..." required class="h-9 px-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-rose-500 w-full sm:w-44">
                        <button type="submit" class="h-9 px-3 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-all flex items-center justify-center space-x-1 shrink-0 cursor-pointer">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span>Tolak</span>
                        </button>
                    </form>
                `;
            }

            actionsHtml += `
                <button type="button" onclick="closeDetailApprovalModal()" class="w-full sm:w-auto h-9 px-4 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                    Tutup
                </button>
            `;

            footer.innerHTML = actionsHtml;

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            document.getElementById('modalPreviewDetailApproval').classList.remove('hidden');
        }

        function closeDetailApprovalModal() {
            document.getElementById('modalPreviewDetailApproval').classList.add('hidden');
        }
    </script>

    <!-- GLOBAL MODAL PREVIEW DETAIL APPROVAL HTML -->
    <div id="modalPreviewDetailApproval" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="px-5 py-3.5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between shrink-0 bg-slate-50/80 dark:bg-slate-800/50">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#1E2538] dark:bg-slate-700 text-white flex items-center justify-center">
                        <i data-lucide="file-search" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 id="previewModalTitle" class="font-bold text-sm text-slate-900 dark:text-white leading-tight">Detail Pengajuan & Bukti Surat</h3>
                        <p id="previewModalSubtitle" class="text-[11px] text-slate-500 dark:text-slate-400">Verifikasi rincian permohonan</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetailApprovalModal()" class="w-7 h-7 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 flex items-center justify-center transition-colors cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Body Scrollable -->
            <div class="p-5 overflow-y-auto space-y-3.5 flex-1 text-xs">
                <!-- Pemohon Card -->
                <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-200/80 dark:border-slate-700/60 space-y-0.5">
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Subjek Pemohon</span>
                    <h4 id="previewApplicantName" class="font-bold text-sm text-slate-900 dark:text-white">-</h4>
                    <p id="previewApplicantMeta" class="text-xs text-slate-600 dark:text-slate-300 font-mono">-</p>
                </div>

                <!-- Detail Grid -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/60">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Kategori</span>
                        <span id="previewCategory" class="font-bold text-xs text-slate-800 dark:text-slate-200 mt-0.5 block">-</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/60">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Periode / Waktu</span>
                        <span id="previewPeriod" class="font-bold text-xs text-slate-800 dark:text-slate-200 mt-0.5 block">-</span>
                    </div>
                </div>

                <!-- Alasan Card -->
                <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-200/80 dark:border-slate-700/60 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Alasan / Keperluan</span>
                    <p id="previewReason" class="text-xs text-slate-800 dark:text-slate-200 font-medium leading-relaxed">-</p>
                    <p id="previewExtra" class="text-[11px] text-slate-500 dark:text-slate-400 italic pt-1.5 border-t border-slate-200 dark:border-slate-700 mt-2 hidden"></p>
                </div>

                <!-- Bukti Surat Image Container -->
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Foto Bukti Surat / Dokter / Lampiran</span>
                    <div id="previewProofContainer" class="bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-3 flex flex-col items-center justify-center min-h-[140px] text-center">
                        <!-- Image content injected dynamically -->
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div id="previewModalFooter" class="px-5 py-3 border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/50 flex flex-col sm:flex-row items-center justify-end gap-2 shrink-0">
                <!-- Action Buttons injected dynamically -->
            </div>
        </div>
    </div>

    @auth
    <!-- ============================================================== -->
    <!-- THUMB-FRIENDLY BOTTOM NAVIGATION BAR (KHUSUS MOBILE ONLY)      -->
    <!-- ============================================================== -->
    <nav class="fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-[#181818]/95 backdrop-blur-lg border-t border-slate-200/90 dark:border-slate-800 flex items-center justify-around h-14 md:hidden px-2 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] select-none">
        @if(Auth::user()->role === 'staf_tu')
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold {{ Route::is('admin.dashboard') ? 'text-[#166876] dark:text-sky-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 mb-0.5"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('admin.guru.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold {{ Route::is('admin.guru.*') ? 'text-[#166876] dark:text-sky-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <i data-lucide="users" class="w-4 h-4 mb-0.5"></i>
                <span>Guru</span>
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold {{ Route::is('admin.siswa.*') ? 'text-[#166876] dark:text-sky-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <i data-lucide="graduation-cap" class="w-4 h-4 mb-0.5"></i>
                <span>Siswa</span>
            </a>
            <a href="{{ route('admin.rekap.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold {{ Route::is('admin.rekap.*') ? 'text-[#166876] dark:text-sky-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <i data-lucide="clipboard-list" class="w-4 h-4 mb-0.5"></i>
                <span>Rekap</span>
            </a>
        @elseif(in_array(Auth::user()->role, ['guru', 'guru_piket', 'wakasis_guru', 'waka_kurikulum', 'waka_sdm']))
            <a href="{{ route('guru.dashboard') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold {{ Route::is('guru.dashboard') ? 'text-[#166876] dark:text-sky-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <i data-lucide="calendar" class="w-4 h-4 mb-0.5"></i>
                <span>Jadwal</span>
            </a>
            <a href="{{ route('guru.jurnal.history') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold {{ Route::is('guru.jurnal.history') ? 'text-[#166876] dark:text-sky-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <i data-lucide="history" class="w-4 h-4 mb-0.5"></i>
                <span>Riwayat</span>
            </a>
            <a href="{{ route('guru.izin.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold {{ Route::is('guru.izin.*') ? 'text-[#166876] dark:text-sky-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <i data-lucide="file-text" class="w-4 h-4 mb-0.5"></i>
                <span>Izin</span>
            </a>
            @if(Auth::user()->guru && Auth::user()->guru->isWaliKelas())
                <a href="{{ route('guru.wali-kelas') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold {{ Route::is('guru.wali-kelas') ? 'text-[#166876] dark:text-sky-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                    <i data-lucide="users" class="w-4 h-4 mb-0.5"></i>
                    <span>Kelas</span>
                </a>
            @endif
        @elseif(Auth::user()->role === 'satpam')
            <a href="{{ route('satpam.dashboard') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold {{ Route::is('satpam.dashboard') ? 'text-[#166876] dark:text-sky-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <i data-lucide="scan-face" class="w-4 h-4 mb-0.5"></i>
                <span>Gerbang</span>
            </a>
        @elseif(Auth::user()->role === 'kepala_sekolah')
            <a href="{{ route('kepsek.dashboard') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold {{ Route::is('kepsek.dashboard') ? 'text-[#166876] dark:text-sky-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 mb-0.5"></i>
                <span>Dashboard</span>
            </a>
        @else
            <a href="{{ url('/') }}" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold text-slate-500 dark:text-slate-400">
                <i data-lucide="home" class="w-4 h-4 mb-0.5"></i>
                <span>Beranda</span>
            </a>
        @endif

        <!-- Menu Drawer Opener Button (Always on right on bottom nav) -->
        <button type="button" onclick="openSidebarDrawer()" class="flex flex-col items-center justify-center flex-1 py-1 text-[10px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white cursor-pointer">
            <i data-lucide="menu" class="w-4 h-4 mb-0.5 text-slate-600 dark:text-slate-300"></i>
            <span>Semua Menu</span>
        </button>
    </nav>
    @endauth

    @stack('scripts')
</body>
</html>
