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
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #EEF1F6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
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
            color: #64748B !important;
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
            color: #64748B !important;
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
            color: #64748B !important;
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
<body class="min-h-screen md:h-screen md:overflow-hidden font-sans antialiased text-slate-900 dark:text-slate-100 bg-[#EEF1F6] dark:bg-[#1C1F26] flex flex-col md:flex-row relative">

    @auth
    <!-- ============================================================== -->
    <!-- SIDEBAR NAVIGATION (Deep Teal #166876 / Dark Charcoal #181818) -->
    <!-- Sesuai Desain Gambar 2 (Light) & Gambar 3 (Dark)               -->
    <!-- ============================================================== -->
    <aside class="w-full md:w-56 bg-[#166876] dark:bg-[#181818] text-white flex-shrink-0 flex flex-col border-r border-[#104F5A] dark:border-[#252525] shadow-xl md:h-screen md:overflow-y-auto z-40 md:rounded-r-2xl overflow-hidden transition-colors">
        <!-- Brand Header -->
        <div class="h-12 flex items-center px-4 border-b border-[#104F5A] dark:border-[#252525] justify-between shrink-0">
            <div class="flex items-center space-x-2.5">
                <!-- Warm Golden Amber Logo Box (Gambar 2 & 3) -->
                <div class="w-6 h-6 rounded bg-[#E4CC67] dark:bg-[#E0C47B] text-[#166876] dark:text-[#181818] flex items-center justify-center font-bold shadow-2xs">
                    <i data-lucide="book-open" class="w-3.5 h-3.5 text-[#166876] dark:text-[#181818]"></i>
                </div>
                <div>
                    <span class="font-bold text-xs tracking-tight text-[#E4CC67] dark:text-[#E0C47B] block leading-none">JURNAL SMEA</span>
                    <span class="block text-[9px] font-medium text-[#D1FAF4] dark:text-slate-400 leading-none mt-1">SMK Negeri 1</span>
                </div>
            </div>
            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-toggle" class="md:hidden p-1 text-[#C7E8EA] hover:text-white rounded hover:bg-white/10 focus:outline-none">
                <i data-lucide="menu" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav id="sidebar-nav" class="flex-1 px-2.5 py-3 space-y-0.5 hidden md:block overflow-y-auto custom-sidebar-scroll text-xs">
            
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
                <a href="{{ route('admin.mapel.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg transition-all {{ Route::is('admin.mapel.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="book-marked" class="w-3.5 h-3.5 {{ Route::is('admin.mapel.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
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

            {{-- ROLE 2: GURU MATA PELAJARAN (Juga untuk Wakasis karena mereka tetap mengajar) --}}
            @if(in_array(Auth::user()->role, ['guru', 'wakasis_siswa', 'wakasis_guru']))
                <div class="pb-1 pt-3">
                    <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Pembelajaran</span>
                </div>
                <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('guru.dashboard') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 {{ Route::is('guru.dashboard') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Jadwal & Jurnal Hari Ini</span>
                </a>
                <a href="{{ route('guru.jurnal.rekap') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('guru.jurnal.rekap') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="history" class="w-3.5 h-3.5 {{ Route::is('guru.jurnal.rekap') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Riwayat Jurnal Saya</span>
                </a>
                @if(Auth::user()->guru && Auth::user()->guru->isWaliKelas())
                    <a href="{{ route('guru.wali-kelas') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('guru.wali-kelas') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                        <i data-lucide="users" class="w-3.5 h-3.5 {{ Route::is('guru.wali-kelas') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                        <span>Rekap Wali Kelas</span>
                    </a>
                @endif
                <a href="{{ route('guru.izin.index') }}" class="flex items-center space-x-2.5 px-2.5 h-8 rounded-lg text-xs transition-all {{ Route::is('guru.izin.*') ? 'bg-[#84C4C9] text-[#0F4E5A] font-bold shadow-xs dark:bg-[#2C2C2C] dark:text-white' : 'text-[#C7E8EA] hover:bg-white/10 hover:text-white dark:text-[#9CA3AF] dark:hover:bg-white/5 dark:hover:text-white font-medium' }}">
                    <i data-lucide="calendar-off" class="w-3.5 h-3.5 {{ Route::is('guru.izin.*') ? 'text-[#0F4E5A] dark:text-white' : 'text-[#A2D2D6] dark:text-[#9CA3AF]' }}"></i>
                    <span>Pengajuan Izin Mengajar</span>
                </a>

                @if(Auth::user()->guru && Auth::user()->guru->isPiketHariIni())
                    <div class="pt-3 pb-1">
                        <span class="px-2 text-[9.5px] font-bold text-[#87B8BE] dark:text-[#71717A] uppercase tracking-widest">Guru Piket Hari Ini</span>
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

            {{-- ROLE 5: WAKIL KESISWAAN (WAKASIS) --}}
            @if(in_array(Auth::user()->role, ['wakasis_siswa', 'wakasis_guru']))
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

            <!-- User Info Card in Sidebar Bottom -->
            <div class="pt-3 border-t border-[#104F5A] dark:border-[#252525] mt-4 space-y-1.5 shrink-0">
                <div class="px-2.5 py-2 rounded-lg bg-[#104F5A] dark:bg-[#202020] border border-[#135A66] dark:border-[#2A2A2A] flex items-center space-x-2.5">
                    <div class="w-6.5 h-6.5 rounded bg-[#0B3B44] dark:bg-[#2C2C2C] text-[#E4CC67] dark:text-[#E0C47B] font-bold text-[11px] flex items-center justify-center shrink-0 border border-[#166876] dark:border-[#383838]">
                        {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
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

    <main class="flex-1 flex flex-col min-w-0 md:h-screen md:overflow-hidden bg-[#EEF1F6] dark:bg-[#1C1F26] transition-colors">
        @auth
        <!-- Top Navigation Header -->
        <header class="h-12 bg-white dark:bg-[#141412] border-b border-slate-200 dark:border-[#252525] shrink-0 flex items-center justify-between px-4 sm:px-5 shadow-2xs z-30 transition-colors">
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 dark:bg-[#222220] text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded text-[10.5px] font-semibold uppercase tracking-wider">
                    {{ str_replace('_', ' ', Auth::user()->role) }}
                </span>
                <span class="text-xs text-slate-300 dark:text-slate-600">/</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">SMK Negeri 1 (SMEA)</span>
            </div>
            
            <div class="flex items-center space-x-2.5 sm:space-x-3">
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
                
                <!-- Quick User Status -->
                <div class="flex items-center space-x-2.5 pl-2.5 sm:pl-3 border-l border-slate-200 dark:border-slate-700">
                    <div class="w-8 h-8 rounded-xl bg-[#166876] dark:bg-[#2C2C2C] text-[#E4CC67] dark:text-[#E0C47B] flex items-center justify-center font-extrabold text-xs shadow-2xs">
                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
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
        <div class="flex-1 p-3.5 md:p-4 max-w-[1600px] w-full mx-auto flex flex-col min-h-0 overflow-y-auto">
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

    <!-- Global Layout Scripts -->
    <script>
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
    </script>
    @stack('scripts')
</body>
</html>
