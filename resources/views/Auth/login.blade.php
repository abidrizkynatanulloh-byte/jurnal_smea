<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jurnal & Monitoring Esemkita</title>
    
    <!-- Instant Theme Loader (Zero Flicker) -->
    <script>
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons CDN -->
    <script src="https://cdn.jsdelivr.net/npm/lucide/dist/umd/lucide.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bg-page: #EEF2F6;
            --bg-left: #FFFFFF;
            --text-title: #0F172A;
            --text-subtitle: #334155;
            --text-label: #1E293B;
            --input-border-wrap: #CBD5E1;
            --input-bg-inner: #FFFFFF;
            --input-text: #0F172A;
            --input-ph: #64748B;
            --wave-fill: #FFFFFF;
            --wave-ridge-1: #94A3B8;
            --wave-ridge-2: #64748B;
            --wave-ridge-3: #CBD5E1;
            --wave-shadow: rgba(0, 0, 0, 0.08);
            --card-fill: rgba(255, 255, 255, 0.86);
            --card-border: rgba(255, 255, 255, 0.95);
            --card-pill-bg: rgba(255, 255, 255, 0.95);
            --card-pill-border: rgba(203, 213, 225, 0.9);
            --card-title: #0F172A;
            --card-subtitle: #334155;
            --card-item-bg: rgba(255, 255, 255, 0.95);
            --card-item-border: rgba(203, 213, 225, 0.95);
            --card-item-text: #0F172A;
            --card-item-icon-bg: #E0F2FE;
            --card-item-icon: #0284C7;
            --bottom-motto-bg: rgba(255, 255, 255, 0.92);
            --bottom-motto-border: rgba(203, 213, 225, 0.8);
            --bottom-motto-text: #1E293B;
            --bg-overlay-start: rgba(238, 242, 246, 0.5);
            --bg-overlay-end: rgba(226, 232, 240, 0.7);
        }

        html.dark {
            --bg-page: #0E131F;
            --bg-left: #0E131F;
            --text-title: #FFFFFF;
            --text-subtitle: #CBD5E1;
            --text-label: #E2E8F0;
            --input-border-wrap: #1E2838;
            --input-bg-inner: #141C29;
            --input-text: #F8FAFC;
            --input-ph: #64748B;
            --wave-fill: #0E131F;
            --wave-ridge-1: #1A2433;
            --wave-ridge-2: #38BDF8;
            --wave-ridge-3: #233144;
            --wave-shadow: rgba(0, 0, 0, 0.6);
            --card-fill: rgba(12, 18, 30, 0.92);
            --card-border: rgba(148, 210, 255, 0.28);
            --card-pill-bg: #1E2D42;
            --card-pill-border: #3B6090;
            --card-title: #FFFFFF;
            --card-subtitle: #F1F5F9;
            --card-item-bg: rgba(255, 255, 255, 0.10);
            --card-item-border: rgba(255, 255, 255, 0.20);
            --card-item-text: #FFFFFF;
            --card-item-icon-bg: rgba(56, 189, 248, 0.20);
            --card-item-icon: #38BDF8;
            --bottom-motto-bg: rgba(255, 255, 255, 0.08);
            --bottom-motto-border: rgba(255, 255, 255, 0.18);
            --bottom-motto-text: #FFFFFF;
            --bg-overlay-start: rgba(14, 19, 31, 0.6);
            --bg-overlay-end: rgba(15, 23, 42, 0.85);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-title);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
</head>
<body class="h-full antialiased overflow-x-hidden selection:bg-cyan-500/30 selection:text-cyan-200">

    <!-- Split-Screen Viewport -->
    <div id="mainViewport" class="relative min-h-screen w-full lg:h-screen lg:overflow-hidden flex flex-col lg:flex-row">

        <!-- ========================================================== -->
        <!-- BACKGROUND LAYER: PHOTO & AMBIENT GLOW                      -->
        <!-- ========================================================== -->
        <div class="absolute inset-0 w-full h-full pointer-events-none overflow-hidden z-0">
            <!-- Architectural Photo -->
            <div class="w-full h-full bg-cover bg-center filter brightness-[0.92] dark:brightness-[0.70] contrast-[1.05]"
                 style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=2000&q=80');">
            </div>

            <!-- Tint Overlay Adapts to Mode -->
            <div class="absolute inset-0 transition-colors duration-300"
                 style="background: linear-gradient(105deg, var(--bg-overlay-start) 0%, var(--bg-overlay-end) 100%);"></div>
            
            <!-- Soft Ambient Glow Orbs -->
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-cyan-500/10 dark:bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-1/4 right-1/3 w-80 h-80 bg-indigo-500/10 dark:bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- ========================================================== -->
        <!-- MOBILE VIEWPORT (MATCHING REFERENCE UI 1:1)               -->
        <!-- ========================================================== -->
        <!-- ========================================================== -->
        <!-- MOBILE VIEWPORT (100% DEVICE FIT - ZERO SCROLLING)        -->
        <!-- ========================================================== -->
        <div id="mobileLayout" class="flex lg:hidden relative z-30 w-full h-[100dvh] flex-col justify-between overflow-hidden">
            
            <!-- 1. TOP SECTION: INFORMASI & BRANDING (EXPOSING ~42% OF VIEWPORT HEIGHT) -->
            <div class="px-5 pt-7 sm:pt-8 pb-3 relative z-20 transition-colors duration-300 shrink-0" style="background-color: var(--bg-left);">
                <!-- Branding Header & Theme Toggle -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-[#141C2A] border border-cyan-500/30 flex items-center justify-center text-cyan-600 dark:text-cyan-400 shadow-xs relative">
                            <i data-lucide="compass" class="w-4.5 h-4.5 text-cyan-600 dark:text-cyan-400"></i>
                            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-500 rounded-full ring-2 ring-[var(--bg-left)]"></span>
                        </div>
                        <div>
                            <span class="font-extrabold text-xs tracking-wider text-slate-900 dark:text-white uppercase block">JURNAL ESEMKITA</span>
                            <span class="text-[9px] font-semibold text-cyan-600 dark:text-cyan-400 uppercase tracking-widest block -mt-0.5">MONITORING TERPADU</span>
                        </div>
                    </div>

                    <!-- Theme Toggle Button -->
                    <button type="button" onclick="toggleTheme()" class="theme-toggle-btn h-8.5 w-8.5 rounded-xl border border-slate-300 dark:border-slate-700/60 bg-white/80 dark:bg-[#141C29]/80 backdrop-blur-md text-slate-700 dark:text-slate-300 hover:text-cyan-600 dark:hover:text-cyan-300 transition-all flex items-center justify-center cursor-pointer shadow-xs" title="Ganti Mode Siang / Malam">
                        <span class="theme-toggle-icon-wrap flex items-center justify-center">
                            <i data-lucide="sun" class="w-4.5 h-4.5 text-amber-500 hidden dark:inline-block"></i>
                            <i data-lucide="moon" class="w-4.5 h-4.5 text-slate-700 dark:hidden inline-block"></i>
                        </span>
                    </button>
                </div>

                <!-- Headline & Subtitle -->
                <div class="mt-3">
                    <h1 class="text-lg sm:text-xl font-extrabold tracking-tight leading-tight text-slate-900 dark:text-white">
                        Pusat Monitoring dan Pembelajaran Sekolah Real-Time
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Sistem terintegrasi untuk pencatatan jurnal, absensi, monitoring kegiatan kelas, dan perizinan sekolah.
                    </p>
                </div>

                <!-- Structured Feature List Cards (6 Features in 2 Columns) -->
                <div class="grid grid-cols-2 gap-2 mt-3">
                    <!-- Feature 1 -->
                    <div class="flex items-center space-x-2 p-2 rounded-xl bg-slate-100/90 dark:bg-slate-900/80 border border-slate-200 dark:border-cyan-500/25 text-slate-800 dark:text-slate-200 text-xs font-semibold shadow-2xs">
                        <div class="w-6 h-6 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                            <i data-lucide="clipboard-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="truncate text-[11px]">Jurnal & Absensi</span>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-center space-x-2 p-2 rounded-xl bg-slate-100/90 dark:bg-slate-900/80 border border-slate-200 dark:border-cyan-500/25 text-slate-800 dark:text-slate-200 text-xs font-semibold shadow-2xs">
                        <div class="w-6 h-6 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                            <i data-lucide="key" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="truncate text-[11px]">Validasi Satpam</span>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-center space-x-2 p-2 rounded-xl bg-slate-100/90 dark:bg-slate-900/80 border border-slate-200 dark:border-cyan-500/25 text-slate-800 dark:text-slate-200 text-xs font-semibold shadow-2xs">
                        <div class="w-6 h-6 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="truncate text-[11px]">Monitoring Guru</span>
                    </div>

                    <!-- Feature 4 -->
                    <div class="flex items-center space-x-2 p-2 rounded-xl bg-slate-100/90 dark:bg-slate-900/80 border border-slate-200 dark:border-cyan-500/25 text-slate-800 dark:text-slate-200 text-xs font-semibold shadow-2xs">
                        <div class="w-6 h-6 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="truncate text-[11px]">Alokasi Kelas</span>
                    </div>

                    <!-- Feature 5 -->
                    <div class="flex items-center space-x-2 p-2 rounded-xl bg-slate-100/90 dark:bg-slate-900/80 border border-slate-200 dark:border-cyan-500/25 text-slate-800 dark:text-slate-200 text-xs font-semibold shadow-2xs">
                        <div class="w-6 h-6 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                            <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="truncate text-[11px]">Real-Time Stats</span>
                    </div>

                    <!-- Feature 6 -->
                    <div class="flex items-center space-x-2 p-2 rounded-xl bg-slate-100/90 dark:bg-slate-900/80 border border-slate-200 dark:border-cyan-500/25 text-slate-800 dark:text-slate-200 text-xs font-semibold shadow-2xs">
                        <div class="w-6 h-6 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                            <i data-lucide="database" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="truncate text-[11px]">Audit Log & Rekap</span>
                    </div>
                </div>
            </div>

            <!-- 2. MIDDLE LAYER: DYNAMIC FLOWING WAVE -->
            <div class="relative w-full h-12 -mt-0.5 flex items-center pointer-events-none z-20 overflow-visible shrink-0">
                <svg id="mobileWaveSvg" class="w-full h-full overflow-visible pointer-events-none" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="mWaveGradCyan" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#0284C7" />
                            <stop offset="35%" stop-color="#38BDF8" />
                            <stop offset="70%" stop-color="#7DD3FC" />
                            <stop offset="100%" stop-color="#0284C7" />
                        </linearGradient>
                        <linearGradient id="mWaveGradSilver" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#334155" />
                            <stop offset="30%" stop-color="#94A3B8" />
                            <stop offset="60%" stop-color="#CBD5E1" />
                            <stop offset="100%" stop-color="#475569" />
                        </linearGradient>
                        <linearGradient id="mWaveGradHighlight" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="rgba(56, 189, 248, 0.2)" />
                            <stop offset="50%" stop-color="rgba(255, 255, 255, 0.95)" />
                            <stop offset="100%" stop-color="rgba(56, 189, 248, 0.2)" />
                        </linearGradient>
                        <filter id="mWaveGlow" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#0284C7" flood-opacity="0.5" />
                        </filter>
                    </defs>

                    <!-- Solid Fill from Top Section Down to Wave Curve -->
                    <path id="mWaveFillPath" style="fill: var(--bg-left); transition: fill 0.3s ease;" d="" />
                    <!-- Shadow Base -->
                    <path id="mWavePathShadow" fill="none" stroke="var(--wave-shadow)" stroke-width="12" stroke-linecap="round" d="" />
                    <!-- Metallic Silver Ribbon -->
                    <path id="mWavePathSilver" fill="none" stroke="url(#mWaveGradSilver)" stroke-width="6" stroke-linecap="round" d="" />
                    <!-- Cyan Fluid Wave Ribbon -->
                    <path id="mWavePathCyan1" fill="none" stroke="url(#mWaveGradCyan)" stroke-width="4" stroke-linecap="round" filter="url(#mWaveGlow)" d="" />
                    <!-- Bright Light Beam Highlight -->
                    <path id="mWavePathHighlight" fill="none" stroke="url(#mWaveGradHighlight)" stroke-width="2" stroke-linecap="round" d="" />
                    <!-- Secondary Cyan Echo Wave -->
                    <path id="mWavePathCyan2" fill="none" stroke="rgba(56, 189, 248, 0.4)" stroke-width="2.5" stroke-linecap="round" d="" />
                </svg>
            </div>

            <!-- 3. BOTTOM SECTION: FROSTED GLASS LOGIN CARD -->
            <div class="relative z-30 w-full px-4 py-2 flex-1 flex flex-col justify-between overflow-hidden">
                <div class="relative rounded-2xl p-4 sm:p-5 bg-white/90 dark:bg-[#0C1220]/95 border border-slate-200 dark:border-sky-400/30 shadow-xl dark:shadow-[0_-10px_35px_rgba(0,0,0,0.6)] backdrop-blur-2xl my-auto">

                    <!-- Mobile Card Header -->
                    <div class="mb-3 pb-2 border-b border-slate-200/60 dark:border-slate-800/80 flex items-center justify-between">
                        <div>
                            <h2 class="text-xs font-bold text-slate-900 dark:text-white flex items-center space-x-1.5">
                                <i data-lucide="log-in" class="w-3.5 h-3.5 text-cyan-600 dark:text-cyan-400"></i>
                                <span>Form Login Pengguna</span>
                            </h2>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 ring-4 ring-emerald-500/20"></span>
                    </div>

                    <!-- Flash Message: Success -->
                    @if (session('success'))
                        <div class="mb-3 px-3 py-2 bg-emerald-50 dark:bg-[#122D28]/90 border border-emerald-300 dark:border-[#1E5C4E]/70 rounded-xl text-emerald-800 dark:text-emerald-300 flex items-center space-x-2 shadow-sm text-xs">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                            <span class="font-medium text-[11px]">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Flash Message: Errors -->
                    @if ($errors->any())
                        <div class="mb-3 px-3 py-2 bg-rose-50 dark:bg-[#38151B]/90 border border-rose-300 dark:border-[#69232F]/80 rounded-xl text-rose-800 dark:text-rose-300 text-xs shadow-sm">
                            <div class="flex items-start space-x-2">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-rose-600 dark:text-rose-400 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-bold text-[11px]">Gagal Masuk:</p>
                                    <ul class="list-disc list-inside mt-0.5 space-y-0.5 text-[10.5px]">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST" class="space-y-3">
                        @csrf

                        <!-- Username Input -->
                        <div>
                            <label for="username_mobile" class="block text-[11px] font-semibold mb-1 text-slate-700 dark:text-slate-200">Username</label>
                            <div class="relative rounded-xl flex items-center border border-slate-300 dark:border-sky-500/40 bg-slate-50 dark:bg-[#141C29] focus-within:border-cyan-500 dark:focus-within:border-cyan-400 focus-within:ring-2 focus-within:ring-cyan-500/20 transition-all shadow-xs">
                                <div class="pl-3 pr-1 text-slate-400 shrink-0">
                                    <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="username" 
                                    id="username_mobile" 
                                    value="{{ old('username') }}" 
                                    placeholder="your-nip@esemkita / username" 
                                    required
                                    autocomplete="username"
                                    class="w-full px-2 py-2 bg-transparent text-xs text-slate-900 dark:text-white focus:outline-none transition-colors"
                                >
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="password_mobile" class="block text-[11px] font-semibold mb-1 text-slate-700 dark:text-slate-200">Password</label>
                            <div class="relative rounded-xl flex items-center border border-slate-300 dark:border-sky-500/40 bg-slate-50 dark:bg-[#141C29] focus-within:border-cyan-500 dark:focus-within:border-cyan-400 focus-within:ring-2 focus-within:ring-cyan-500/20 transition-all shadow-xs">
                                <div class="pl-3 pr-1 text-slate-400 shrink-0">
                                    <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                                </div>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password_mobile" 
                                    placeholder="Your Password" 
                                    required
                                    autocomplete="current-password"
                                    class="w-full px-2 py-2 bg-transparent text-xs text-slate-900 dark:text-white focus:outline-none transition-colors pr-9"
                                >
                                <button type="button" onclick="togglePasswordVisibility('password_mobile', 'passwordToggleIcon_mobile')" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-cyan-600 dark:hover:text-cyan-400 transition-colors z-20 cursor-pointer" title="Lihat/Sembunyikan Password">
                                    <i id="passwordToggleIcon_mobile" data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between pt-0.5 text-[11px]">
                            <label class="flex items-center space-x-1.5 cursor-pointer select-none">
                                <input 
                                    type="checkbox" 
                                    name="remember" 
                                    value="1" 
                                    class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-[#141C29] text-cyan-600 focus:ring-cyan-500/20 accent-cyan-500 cursor-pointer"
                                >
                                <span class="font-medium text-slate-600 dark:text-slate-300">Remember me</span>
                            </label>
                            <a href="javascript:void(0)" onclick="showForgotPasswordAlert()" class="hover:text-cyan-600 dark:hover:text-cyan-400 font-medium text-cyan-600 dark:text-cyan-300 transition-colors">
                                Forgot Password?
                            </a>
                        </div>

                        <!-- Log In Button -->
                        <div class="pt-1">
                            <button 
                                type="submit" 
                                class="w-full py-2.5 px-4 rounded-xl font-bold text-xs text-white tracking-wide bg-gradient-to-r from-[#0284C7] to-[#0369A1] hover:from-[#0369A1] hover:to-[#075985] border border-sky-400/50 shadow-md shadow-sky-500/20 transition-all duration-200 flex items-center justify-center space-x-2 cursor-pointer"
                            >
                                <span>Log In</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-cyan-200"></i>
                            </button>
                        </div>
                    </form>

                </div>

                <!-- Footer Status Below Card -->
                <div class="text-center text-[10.5px] text-slate-500 dark:text-slate-400 font-medium py-1 shrink-0">
                    Esemkita SMEA • Server Online v2.0 Enterprise
                </div>
            </div>

        </div>

        <!-- ========================================================== -->
        <!-- DESKTOP LEFT PANEL: LOGIN FORM                             -->
        <!-- ========================================================== -->
        <div id="leftPanel" class="hidden lg:flex relative z-30 w-[42%] xl:w-[39%] min-h-screen flex-col justify-between px-7 sm:px-12 lg:px-14 xl:px-18 py-8 lg:py-10 shrink-0 lg:bg-transparent bg-[var(--bg-left)] transition-colors duration-300">
            
            <!-- Top Branding & Functional Theme Toggle -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3.5">
                    <!-- Glowing Badge Logo -->
                    <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-[#141C2A] border border-cyan-500/30 dark:border-[#22364E] flex items-center justify-center text-cyan-600 dark:text-cyan-400 shadow-[0_0_18px_rgba(56,189,248,0.25)] relative">
                        <i data-lucide="compass" class="w-5 h-5 text-cyan-500 dark:text-cyan-300"></i>
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-500 rounded-full ring-2 ring-[var(--bg-left)]"></span>
                    </div>
                    <div>
                        <span class="font-extrabold text-[15px] tracking-wider text-slate-800 dark:text-[#D1DBE8] uppercase block">JURNAL ESEMKITA</span>
                        <span class="text-[10px] font-semibold text-slate-500 dark:text-[#5A6D87] uppercase tracking-widest block -mt-0.5">MONITORING TERPADU</span>
                    </div>
                </div>

                <!-- Theme Toggle Button (Light & Dark Fully Functional) -->
                <button type="button" onclick="toggleTheme()" id="themeToggleBtn"
                    class="theme-toggle-btn h-9 w-9 rounded-xl border border-slate-300 dark:border-[#233144] bg-white dark:bg-[#141C29] text-slate-700 dark:text-slate-300 hover:text-cyan-500 dark:hover:text-cyan-300 hover:border-cyan-500/50 transition-all flex items-center justify-center cursor-pointer shadow-xs"
                    title="Ganti Mode Siang / Malam">
                    <span id="themeToggleIconWrap" class="theme-toggle-icon-wrap flex items-center justify-center">
                        <i data-lucide="sun" class="w-4.5 h-4.5 text-amber-500 hidden dark:inline-block"></i>
                        <i data-lucide="moon" class="w-4.5 h-4.5 text-slate-700 dark:hidden inline-block"></i>
                    </span>
                </button>
            </div>

            <!-- Main Form Section -->
            <div class="my-auto py-6 max-w-md w-full">
                <!-- Title & Description -->
                <div class="mb-6">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight" style="color: var(--text-title);">
                        Login to Jurnal Esemkita
                    </h1>
                    <p class="text-xs mt-2 leading-relaxed font-normal" style="color: var(--text-subtitle);">
                        Sistem terintegrasi untuk pencatatan jurnal, absensi, monitoring kegiatan kelas, dan perizinan sekolah.
                    </p>
                </div>

                <!-- Flash Message: Success -->
                @if (session('success'))
                    <div class="mb-5 px-4 py-2.5 bg-emerald-50 dark:bg-[#122D28]/90 border border-emerald-300 dark:border-[#1E5C4E]/70 rounded-xl text-emerald-800 dark:text-emerald-300 flex items-center space-x-2.5 shadow-sm text-xs">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Flash Message: Errors -->
                @if ($errors->any())
                    <div class="mb-5 px-4 py-3 bg-rose-50 dark:bg-[#38151B]/90 border border-rose-300 dark:border-[#69232F]/80 rounded-xl text-rose-800 dark:text-rose-300 text-xs shadow-sm">
                        <div class="flex items-start space-x-2.5">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0 mt-0.5"></i>
                            <div>
                                <p class="font-bold">Gagal Masuk:</p>
                                <ul class="list-disc list-inside mt-0.5 space-y-0.5 text-[11px]">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- 1. Input Username -->
                    <div>
                        <label for="username" class="block text-[11.5px] font-semibold mb-1.5" style="color: var(--text-label);">Username</label>
                        <div class="relative rounded-xl flex items-center border border-slate-300 dark:border-[#233144] bg-white dark:bg-[#141C29] focus-within:border-cyan-500 dark:focus-within:border-cyan-400 focus-within:ring-2 focus-within:ring-cyan-500/20 transition-all">
                            <div class="pl-3.5 pr-1 text-slate-400 shrink-0">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <input 
                                type="text" 
                                name="username" 
                                id="username" 
                                value="{{ old('username') }}" 
                                placeholder="your-nip@esemkita / username" 
                                required
                                autocomplete="username"
                                class="w-full px-3.5 py-3 bg-transparent text-xs sm:text-sm focus:outline-none transition-colors"
                                style="color: var(--input-text);"
                            >
                        </div>
                    </div>

                    <!-- 2. Input Password -->
                    <div>
                        <label for="password" class="block text-[11.5px] font-semibold mb-1.5" style="color: var(--text-label);">Password</label>
                        <div class="relative rounded-xl flex items-center border border-slate-300 dark:border-[#233144] bg-white dark:bg-[#141C29] focus-within:border-cyan-500 dark:focus-within:border-cyan-400 focus-within:ring-2 focus-within:ring-cyan-500/20 transition-all">
                            <div class="pl-3.5 pr-1 text-slate-400 shrink-0">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                placeholder="Your Password" 
                                required
                                autocomplete="current-password"
                                class="w-full px-3.5 py-3 bg-transparent text-xs sm:text-sm focus:outline-none transition-colors pr-11"
                                style="color: var(--input-text);"
                            >
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-cyan-500 dark:hover:text-cyan-400 transition-colors z-20 cursor-pointer" title="Lihat/Sembunyikan Password">
                                <i id="passwordToggleIcon" data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- 3. Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-0.5 text-xs">
                        <label class="flex items-center space-x-2.5 cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                value="1" 
                                class="w-4 h-4 rounded border-slate-300 dark:border-[#2E3D52] text-cyan-600 focus:ring-cyan-500/20 accent-cyan-500 cursor-pointer"
                            >
                            <span class="text-[11.5px] font-medium" style="color: var(--text-subtitle);">Remember me</span>
                        </label>
                        <a href="javascript:void(0)" onclick="showForgotPasswordAlert()" class="hover:text-cyan-500 dark:hover:text-cyan-400 text-[11.5px] font-medium transition-colors" style="color: var(--text-subtitle);">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- 4. Log In Button -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="w-full py-3.5 px-6 rounded-xl font-bold text-xs sm:text-sm text-white tracking-wide bg-gradient-to-r from-[#0284C7] to-[#0369A1] dark:from-[#2C3E55] dark:to-[#1B293A] hover:from-[#0369A1] hover:to-[#075985] dark:hover:from-[#354C68] dark:hover:to-[#223348] border border-sky-400/40 dark:border-[#4A6E94]/40 shadow-lg shadow-sky-500/20 dark:shadow-[0_0_20px_rgba(56,189,248,0.18)] transition-all duration-200 flex items-center justify-center space-x-2 cursor-pointer"
                        >
                            <span>Log In</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 text-cyan-200 dark:text-cyan-400"></i>
                        </button>
                    </div>
                </form>

                <!-- Footer Status -->
                <div class="mt-8 pt-5 border-t border-slate-200 dark:border-[#1C2636] flex items-center justify-between text-[11px] text-slate-500 dark:text-[#526379]">
                    <span class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Server Online • v2.0 Enterprise</span>
                    </span>
                    <span>Support TU: ext. 102</span>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="text-[11px] text-slate-400 dark:text-[#48566A] pt-4">
                &copy; {{ date('Y') }} SMK Negeri 1 Jurnal Esemkita. All rights reserved.
            </div>
        </div>

        <!-- ========================================================== -->
        <!-- FULL-SCREEN SYNCHRONIZED SVG WAVE & ADAPTIVE GLASS CARD    -->
        <!-- Sesuai Gambar 1: Gelombang Besar & Sisi Kanan Ikut Goyang  -->
        <!-- ========================================================== -->
        <svg id="fullWaveSvg" class="hidden lg:block absolute inset-0 w-full h-full pointer-events-none z-20 overflow-visible" preserveAspectRatio="none">
            <defs>
                <!-- Metallic Ridge Gradients -->
                <linearGradient id="waveRidgeGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="var(--wave-ridge-1)" />
                    <stop offset="50%" stop-color="var(--wave-ridge-2)" />
                    <stop offset="100%" stop-color="var(--wave-ridge-3)" />
                </linearGradient>
                <linearGradient id="waveHighlightGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="rgba(103, 232, 249, 0.7)" />
                    <stop offset="100%" stop-color="rgba(255, 255, 255, 0.2)" />
                </linearGradient>

                <!-- Glass Card Left Edge Specular Reflection -->
                <linearGradient id="cardLeftHighlightGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="rgba(255, 255, 255, 0.4)" />
                    <stop offset="40%" stop-color="rgba(56, 189, 248, 0.45)" />
                    <stop offset="70%" stop-color="rgba(255, 255, 255, 0.2)" />
                    <stop offset="100%" stop-color="rgba(56, 189, 248, 0.35)" />
                </linearGradient>

                <!-- Soft Drop Shadow Filter for Card -->
                <filter id="cardDropShadow" x="-10%" y="-10%" width="125%" height="125%">
                    <feDropShadow dx="0" dy="20" stdDeviation="24" flood-color="#000000" flood-opacity="0.32" />
                </filter>

                <!-- Glass Card Shape Clip -->
                <clipPath id="glassCardClip" clipPathUnits="userSpaceOnUse">
                    <path id="glassCardClipPath" d="" />
                </clipPath>
            </defs>

            <!-- 1. Left Panel Wave Fill (Besar Sesuai Gambar 1) -->
            <path id="waveFillPath" style="fill: var(--wave-fill); transition: fill 0.3s ease;" d="" />

            <!-- 2. Middle 3D Beveled Ridge (Garis Tengah Tebal & Bergelombang) -->
            <path id="waveShadowPath" fill="none" stroke="var(--wave-shadow)" stroke-width="14" stroke-linecap="round" d="" />
            <path id="waveRidgePath" fill="none" stroke="url(#waveRidgeGrad)" stroke-width="6" stroke-linecap="round" d="" />
            <path id="waveHighlightPath" fill="none" stroke="url(#waveHighlightGrad)" stroke-width="2" stroke-linecap="round" d="" />

            <!-- 3. Right Glass Card Background (Garis Kiri Ikut Goyang Sesuai Gelombang) -->
            <path id="glassCardBgPath" style="fill: var(--card-fill); stroke: var(--card-border); stroke-width: 1.5; transition: fill 0.3s ease, stroke 0.3s ease;" filter="url(#cardDropShadow)" d="" />
            <!-- Specular Highlight along the Wavy Left Edge of the Glass Card -->
            <path id="glassCardEdgeHighlight" fill="none" stroke="url(#cardLeftHighlightGrad)" stroke-width="2" stroke-linecap="round" d="" />
        </svg>

        <!-- ========================================================== -->
        <!-- FROSTED GLASS BLUR LAYER                                  -->
        <!-- Coordinates match 1:1 with the full-screen SVG ClipPath   -->
        <!-- ========================================================== -->
        <div id="glassBlurLayer" class="hidden lg:block absolute inset-0 pointer-events-none z-[22] overflow-hidden"
             style="clip-path: url(#glassCardClip); -webkit-clip-path: url(#glassCardClip); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);">
        </div>

        <!-- ========================================================== -->
        <!-- RIGHT PANEL: CONTENT OVER SYNCHRONIZED GLASS CARD          -->
        <!-- ========================================================== -->
        <div id="rightContentWrap" class="hidden lg:flex relative z-[25] flex-1 items-center justify-center lg:justify-end xl:justify-center p-6 sm:p-10 lg:p-12 lg:pr-14 xl:pr-20">
            
            <!-- Floating Container with Generous Padding From Wavy Left Edge -->
            <div id="glassCardContent" class="relative max-w-md lg:max-w-lg xl:max-w-xl w-full p-7 sm:p-8 xl:p-9 space-y-6 overflow-visible transition-all pl-10 sm:pl-12 lg:pl-14">
                
                <!-- Top Badge & Subtitle -->
                <div class="relative z-10 flex items-center space-x-3">
                    <span class="px-3.5 py-1 text-[10.5px] font-bold rounded-full uppercase tracking-wider shadow-xs"
                          style="background-color: var(--card-pill-bg); border: 1px solid var(--card-pill-border); color: #0284C7;" class="dark:text-sky-400">
                        SISTEM TERINTEGRASI
                    </span>
                    <span class="text-[13px] font-semibold" style="color: var(--card-subtitle);">Monitoring & Jurnal</span>
                </div>

                <!-- Card Heading -->
                <div class="relative z-10">
                    <h2 class="text-xl sm:text-2xl xl:text-[26px] font-extrabold leading-snug tracking-tight" style="color: var(--card-title);">
                        Pusat Monitoring dan Pembelajaran Sekolah Real-Time
                    </h2>
                    <p class="text-[13px] mt-2.5 leading-relaxed font-medium" style="color: var(--card-subtitle);">
                        Menghubungkan 7 pilar sekolah: Tata Usaha, Guru, Guru Piket, Waka Kurikulum/Kesiswaan, Kepala Sekolah, Keamanan Satpam, serta Wali Murid dalam satu ekosistem digital.
                    </p>
                </div>

                <!-- 4 Feature Cards (2x2 Grid) -->
                <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <!-- Feature 1 -->
                    <div class="flex items-center space-x-3 p-3.5 rounded-xl transition-all text-xs font-bold group shadow-2xs"
                         style="background-color: var(--card-item-bg); border: 1px solid var(--card-item-border); color: var(--card-item-text);">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform"
                             style="background-color: var(--card-item-icon-bg); color: var(--card-item-icon);">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                        </div>
                        <span class="truncate">Jurnal & Absensi Kelas</span>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-center space-x-3 p-3.5 rounded-xl transition-all text-xs font-bold group shadow-2xs"
                         style="background-color: var(--card-item-bg); border: 1px solid var(--card-item-border); color: var(--card-item-text);">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform"
                             style="background-color: var(--card-item-icon-bg); color: var(--card-item-icon);">
                            <i data-lucide="key" class="w-4 h-4"></i>
                        </div>
                        <span class="truncate">Dispen & Validasi Satpam</span>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-center space-x-3 p-3.5 rounded-xl transition-all text-xs font-bold group shadow-2xs"
                         style="background-color: var(--card-item-bg); border: 1px solid var(--card-item-border); color: var(--card-item-text);">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform"
                             style="background-color: var(--card-item-icon-bg); color: var(--card-item-icon);">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <span class="truncate">Monitoring Guru Terlambat</span>
                    </div>

                    <!-- Feature 4 -->
                    <div class="flex items-center space-x-3 p-3.5 rounded-xl transition-all text-xs font-bold group shadow-2xs"
                         style="background-color: var(--card-item-bg); border: 1px solid var(--card-item-border); color: var(--card-item-text);">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform"
                             style="background-color: var(--card-item-icon-bg); color: var(--card-item-icon);">
                            <i data-lucide="database" class="w-4 h-4"></i>
                        </div>
                        <span class="truncate">Rekapitulasi & Audit Log</span>
                    </div>
                </div>

                <!-- Bottom Motto -->
                <div class="relative z-10 px-4 py-3 rounded-xl text-[12px] font-semibold flex items-center justify-between shadow-2xs"
                     style="background-color: var(--bottom-motto-bg); border: 1px solid var(--bottom-motto-border); color: var(--bottom-motto-text);">
                    <span>Esemkita SMEA • Menuju Sekolah Unggul & Transparan</span>
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-cyan-500 dark:text-cyan-400 shrink-0"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            applyTheme();
            if (window.innerWidth >= 1024) {
                initSynchronizedWaveAndCard();
            } else {
                initMobileWave();
            }

            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth >= 1024) {
                        initSynchronizedWaveAndCard();
                    } else {
                        initMobileWave();
                    }
                }, 200);
            });
        });

        // Toggle Password Show/Hide (Mobile & Desktop)
        function togglePasswordVisibility(fieldId = 'password', iconId = 'passwordToggleIcon') {
            const passwordInput = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (!passwordInput) return;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                if (icon) icon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                if (icon) icon.setAttribute('data-lucide', 'eye');
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Apply theme icon and state
        function applyTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.theme-toggle-icon-wrap').forEach(wrap => {
                wrap.innerHTML = isDark 
                    ? '<i data-lucide="sun" class="w-4 h-4 text-amber-400"></i>' 
                    : '<i data-lucide="moon" class="w-4 h-4 text-slate-700"></i>';
            });
            document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
                btn.setAttribute('title', isDark ? 'Beralih ke Mode Siang (Terang)' : 'Beralih ke Mode Malam (Gelap)');
            });
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Functional Light / Dark Mode Toggle
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            applyTheme();
        }

        // Dynamic Multi-Ribbon Mobile Wave (Smooth Wave Motion & Top Fill)
        let mobileWaveRunning = false;
        function initMobileWave() {
            const fillPath = document.getElementById('mWaveFillPath');
            const shadowPath = document.getElementById('mWavePathShadow');
            const silverPath = document.getElementById('mWavePathSilver');
            const cyanPath1 = document.getElementById('mWavePathCyan1');
            const highlightPath = document.getElementById('mWavePathHighlight');
            const cyanPath2 = document.getElementById('mWavePathCyan2');
            const svg = document.getElementById('mobileWaveSvg');

            if (!shadowPath || !cyanPath1 || !svg) return;
            if (mobileWaveRunning) return;
            mobileWaveRunning = true;

            let startTime = null;

            function animateMobile(time) {
                if (window.innerWidth >= 1024) {
                    mobileWaveRunning = false;
                    return;
                }

                if (!startTime) startTime = time;
                const elapsed = (time - startTime) * 0.001;

                const W = svg.clientWidth || window.innerWidth;
                const H = svg.clientHeight || 80;
                const midY = H * 0.45;

                const steps = 36;
                const dx = W / steps;

                let pts1 = [];
                let pts2 = [];

                for (let i = 0; i <= steps; i++) {
                    const x = i * dx;
                    const u = x / W;

                    const w1 = Math.sin((u * 2.6 - 0.2) * Math.PI + elapsed * 1.6) * 11;
                    const w2 = Math.cos((u * 4.2) * Math.PI - elapsed * 1.9) * 5;
                    const y1 = midY + w1 + w2;

                    const w3 = Math.sin((u * 3.0) * Math.PI + elapsed * 1.3 + 0.8) * 7;
                    const y2 = midY + w3;

                    pts1.push({ x, y: y1 });
                    pts2.push({ x, y: y2 });
                }

                let d1 = `M ${pts1[0].x.toFixed(1)},${pts1[0].y.toFixed(1)}`;
                for (let i = 1; i < pts1.length; i++) {
                    d1 += ` L ${pts1[i].x.toFixed(1)},${pts1[i].y.toFixed(1)}`;
                }

                let d2 = `M ${pts2[0].x.toFixed(1)},${pts2[0].y.toFixed(1)}`;
                for (let i = 1; i < pts2.length; i++) {
                    d2 += ` L ${pts2[i].x.toFixed(1)},${pts2[i].y.toFixed(1)}`;
                }

                if (fillPath) {
                    let fillD = `M 0,0 L ${pts1[0].x.toFixed(1)},${pts1[0].y.toFixed(1)}`;
                    for (let i = 1; i < pts1.length; i++) {
                        fillD += ` L ${pts1[i].x.toFixed(1)},${pts1[i].y.toFixed(1)}`;
                    }
                    fillD += ` L ${W.toFixed(1)},0 Z`;
                    fillPath.setAttribute('d', fillD);
                }

                shadowPath.setAttribute('d', d1);
                if (silverPath) silverPath.setAttribute('d', d1);
                cyanPath1.setAttribute('d', d1);
                if (highlightPath) highlightPath.setAttribute('d', d1);
                if (cyanPath2) cyanPath2.setAttribute('d', d2);

                requestAnimationFrame(animateMobile);
            }

            requestAnimationFrame(animateMobile);
        }

        // Synchronized Dynamic Wave & Right Card Wave (Desktop Smooth Motion)
        let desktopWaveRunning = false;
        function initSynchronizedWaveAndCard() {
            const fullWaveSvg = document.getElementById('fullWaveSvg');
            const leftPanel = document.getElementById('leftPanel');
            const glassCardContent = document.getElementById('glassCardContent');

            const fillPath = document.getElementById('waveFillPath');
            const shadowPath = document.getElementById('waveShadowPath');
            const ridgePath = document.getElementById('waveRidgePath');
            const highlightPath = document.getElementById('waveHighlightPath');
            const cardBgPath = document.getElementById('glassCardBgPath');
            const cardClipPath = document.getElementById('glassCardClipPath');
            const cardEdgeHighlight = document.getElementById('glassCardEdgeHighlight');

            if (!fullWaveSvg || !fillPath) return;
            if (desktopWaveRunning) return;
            desktopWaveRunning = true;

            let startTime = null;

            function animate(time) {
                if (window.innerWidth < 1024) {
                    desktopWaveRunning = false;
                    return;
                }

                if (!startTime) startTime = time;
                const elapsed = (time - startTime) * 0.001; // seconds

                const H = window.innerHeight;
                const W = window.innerWidth;

                // Responsive anchor: ~40% of window width on desktop
                const leftPanelWidth = leftPanel ? leftPanel.clientWidth : W * 0.40;
                const baseMid = leftPanelWidth + 28;

                // Moderate & smooth wave configuration
                const amp1 = 30;
                const amp2 = 12;
                const sOffset = 18;
                const steps = 48;
                const dy = H / steps;

                function getWaveX(y, t) {
                    const u = y / H;
                    const w1 = -Math.sin((u * 2.2 - 0.15) * Math.PI + t * 1.1) * amp1;
                    const w2 = Math.cos((u * 3.4) * Math.PI + t * 1.5) * amp2;
                    const sShape = Math.sin(u * Math.PI) * sOffset;
                    return baseMid + w1 + w2 + sShape;
                }

                let wavePoints = [];
                for (let i = 0; i <= steps; i++) {
                    const y = i * dy;
                    const x = getWaveX(y, elapsed);
                    wavePoints.push({ x, y });
                }

                let waveCurveStr = `M ${wavePoints[0].x.toFixed(1)},${wavePoints[0].y.toFixed(1)}`;
                for (let i = 1; i < wavePoints.length; i++) {
                    waveCurveStr += ` L ${wavePoints[i].x.toFixed(1)},${wavePoints[i].y.toFixed(1)}`;
                }

                const fillStr = `M 0,0 L ${wavePoints[0].x.toFixed(1)},0 ` + 
                                waveCurveStr.substring(waveCurveStr.indexOf('L')) + 
                                ` L 0,${H} Z`;

                fillPath.setAttribute('d', fillStr);
                shadowPath.setAttribute('d', waveCurveStr);
                ridgePath.setAttribute('d', waveCurveStr);
                highlightPath.setAttribute('d', waveCurveStr);

                // Synchronize Right Glass Card
                if (glassCardContent && cardBgPath && cardClipPath) {
                    const cardRect = glassCardContent.getBoundingClientRect();
                    const rRadius = 24;
                    const gap = 24;

                    const cardTop = Math.max(16, cardRect.top - 12);
                    const cardBottom = Math.min(H - 16, cardRect.bottom + 12);
                    const cardRight = Math.min(W - 24, cardRect.right + 16);

                    const cardSteps = 24;
                    const startY = cardTop + rRadius;
                    const endY = cardBottom - rRadius;
                    const cardDy = (endY - startY) / cardSteps;

                    let cardLeftPoints = [];
                    for (let i = 0; i <= cardSteps; i++) {
                        const y = startY + i * cardDy;
                        const x = getWaveX(y, elapsed) + gap;
                        cardLeftPoints.push({ x, y });
                    }

                    const topLeftX = cardLeftPoints[0].x;
                    const bottomLeftX = cardLeftPoints[cardLeftPoints.length - 1].x;

                    let cardPathStr = `M ${(topLeftX + rRadius).toFixed(1)},${cardTop} `;
                    cardPathStr += `L ${(cardRight - rRadius).toFixed(1)},${cardTop} `;
                    cardPathStr += `A ${rRadius} ${rRadius} 0 0 1 ${cardRight},${cardTop + rRadius} `;
                    cardPathStr += `L ${cardRight},${cardBottom - rRadius} `;
                    cardPathStr += `A ${rRadius} ${rRadius} 0 0 1 ${(cardRight - rRadius).toFixed(1)},${cardBottom} `;
                    cardPathStr += `L ${(bottomLeftX + rRadius).toFixed(1)},${cardBottom} `;
                    cardPathStr += `A ${rRadius} ${rRadius} 0 0 1 ${bottomLeftX.toFixed(1)},${(cardBottom - rRadius).toFixed(1)} `;
                    
                    for (let i = cardLeftPoints.length - 2; i >= 1; i--) {
                        cardPathStr += `L ${cardLeftPoints[i].x.toFixed(1)},${cardLeftPoints[i].y.toFixed(1)} `;
                    }

                    cardPathStr += `L ${topLeftX.toFixed(1)},${(cardTop + rRadius).toFixed(1)} `;
                    cardPathStr += `A ${rRadius} ${rRadius} 0 0 1 ${(topLeftX + rRadius).toFixed(1)},${cardTop} Z`;

                    cardBgPath.setAttribute('d', cardPathStr);
                    cardClipPath.setAttribute('d', cardPathStr);

                    if (cardEdgeHighlight) {
                        let edgeHighlightStr = `M ${(topLeftX + rRadius).toFixed(1)},${cardTop} `;
                        edgeHighlightStr += `A ${rRadius} ${rRadius} 0 0 0 ${topLeftX.toFixed(1)},${(cardTop + rRadius).toFixed(1)} `;
                        for (let i = 1; i < cardLeftPoints.length - 1; i++) {
                            edgeHighlightStr += `L ${cardLeftPoints[i].x.toFixed(1)},${cardLeftPoints[i].y.toFixed(1)} `;
                        }
                        edgeHighlightStr += `L ${bottomLeftX.toFixed(1)},${(cardBottom - rRadius).toFixed(1)} `;
                        edgeHighlightStr += `A ${rRadius} ${rRadius} 0 0 0 ${(bottomLeftX + rRadius).toFixed(1)},${cardBottom}`;
                        cardEdgeHighlight.setAttribute('d', edgeHighlightStr);
                    }
                }

                requestAnimationFrame(animate);
            }

            requestAnimationFrame(animate);
        }

        function showForgotPasswordAlert() {
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: 'Lupa Kata Sandi?',
                html: '<div class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 py-1">Silakan hubungi admin <b>Tata Usaha sekolah</b> untuk mereset kata sandi akun Anda.</div>',
                icon: 'info',
                confirmButtonColor: '#0284C7',
                confirmButtonText: 'Saya Mengerti',
                background: isDark ? '#151B26' : '#FFFFFF',
                color: isDark ? '#F1F5F9' : '#0F172A',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl p-5',
                    title: 'text-base font-bold text-slate-900 dark:text-white',
                    confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-bold shadow-xs cursor-pointer'
                }
            });
        }
    </script>
</body>
</html>
