<!DOCTYPE html>
<html lang="id" class="h-full dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jurnal & Monitoring Esemkita</title>
    
    <!-- Instant Theme Loader -->
    <script>
        if (localStorage.getItem('theme') === 'light') {
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

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Moving Background Drift Animation */
        @keyframes bgMotion {
            0% {
                transform: scale(1.02) translate(0%, 0%);
            }
            50% {
                transform: scale(1.08) translate(-1.5%, 1%);
            }
            100% {
                transform: scale(1.02) translate(0%, 0%);
            }
        }
        .animate-bg-drift {
            animation: bgMotion 28s ease-in-out infinite alternate;
        }

        /* Subtle Diagonal Glass Sheen Sweep on Right Card ("samar ga se kandel itu") */
        @keyframes sheenSweep {
            0% {
                transform: translateX(-160%) translateY(-160%) rotate(38deg);
                opacity: 0;
            }
            15% {
                opacity: 1;
            }
            50% {
                transform: translateX(160%) translateY(160%) rotate(38deg);
                opacity: 1;
            }
            60% {
                opacity: 0;
            }
            100% {
                transform: translateX(160%) translateY(160%) rotate(38deg);
                opacity: 0;
            }
        }
        .sheen-sweep-layer {
            position: absolute;
            top: -120%;
            left: -120%;
            width: 340%;
            height: 340%;
            background: linear-gradient(
                90deg,
                transparent 0%,
                transparent 30%,
                rgba(255, 255, 255, 0.03) 36%,
                rgba(255, 255, 255, 0.14) 43%,
                rgba(255, 255, 255, 0.22) 47%,
                rgba(255, 255, 255, 0.06) 50%,
                rgba(255, 255, 255, 0.18) 54%,
                rgba(255, 255, 255, 0.03) 58%,
                transparent 68%,
                transparent 100%
            );
            animation: sheenSweep 7s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            pointer-events: none;
        }

        /* Subtle Sheen Sweep for Input Boxes */
        @keyframes inputSheenSweep {
            0% {
                transform: translateX(-180%) rotate(35deg);
                opacity: 0;
            }
            20% {
                opacity: 0.8;
            }
            50% {
                transform: translateX(180%) rotate(35deg);
                opacity: 0.8;
            }
            60% {
                opacity: 0;
            }
            100% {
                transform: translateX(180%) rotate(35deg);
                opacity: 0;
            }
        }
        .input-sheen {
            position: absolute;
            top: -150%;
            left: -50%;
            width: 200%;
            height: 400%;
            background: linear-gradient(
                90deg,
                transparent 0%,
                transparent 40%,
                rgba(255, 255, 255, 0.08) 48%,
                rgba(165, 243, 252, 0.15) 50%,
                rgba(255, 255, 255, 0.08) 52%,
                transparent 60%,
                transparent 100%
            );
            animation: inputSheenSweep 9s ease-in-out infinite;
            pointer-events: none;
        }

        /* Sequential Rotating Border Beam on Input Fields */
        .beam-wrapper {
            position: relative;
            padding: 1.5px;
            border-radius: 12px;
            overflow: hidden;
            background-color: #1E2838;
            transition: all 0.3s ease;
        }
        .beam-wrapper:hover, .beam-wrapper:focus-within {
            background-color: #2D3D54;
        }

        .beam-rotator {
            position: absolute;
            top: -150%;
            left: -150%;
            width: 400%;
            height: 400%;
            pointer-events: none;
            background: conic-gradient(
                from 0deg,
                transparent 0deg,
                transparent 280deg,
                rgba(56, 189, 248, 0.15) 310deg,
                rgba(56, 189, 248, 0.75) 338deg,
                rgba(165, 243, 252, 0.95) 352deg,
                #FFFFFF 360deg
            );
        }

        /* Cycle 1: Username input beam rotates during 0s - 3.5s */
        @keyframes rotateBeamUser {
            0% {
                transform: rotate(0deg);
                opacity: 0;
            }
            5% {
                opacity: 1;
            }
            44% {
                opacity: 1;
            }
            49% {
                transform: rotate(360deg);
                opacity: 0;
            }
            100% {
                transform: rotate(360deg);
                opacity: 0;
            }
        }

        /* Cycle 2: Password input beam rotates during 3.5s - 7s */
        @keyframes rotateBeamPass {
            0% {
                transform: rotate(0deg);
                opacity: 0;
            }
            49% {
                transform: rotate(0deg);
                opacity: 0;
            }
            54% {
                opacity: 1;
            }
            93% {
                opacity: 1;
            }
            98% {
                transform: rotate(360deg);
                opacity: 0;
            }
            100% {
                transform: rotate(360deg);
                opacity: 0;
            }
        }

        .beam-username {
            animation: rotateBeamUser 7s linear infinite;
        }
        .beam-password {
            animation: rotateBeamPass 7s linear infinite;
        }

        /* Continuous rotation when input is focused */
        @keyframes rotateBeamActive {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        .beam-wrapper:focus-within .beam-rotator {
            opacity: 1 !important;
            animation: rotateBeamActive 2.8s linear infinite !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #0E131F;
        }
        ::-webkit-scrollbar-thumb {
            background: #1E2838;
            border-radius: 4px;
        }
    </style>
</head>
<body class="h-full antialiased bg-[#0E131F] text-slate-100 overflow-x-hidden selection:bg-cyan-500/30 selection:text-cyan-200">

    <!-- Split-Screen Canvas -->
    <div class="relative min-h-screen w-full lg:h-screen lg:overflow-hidden flex flex-col lg:flex-row bg-[#0E131F]">

        <!-- ========================================================== -->
        <!-- BACKGROUND LAYER: RIGHT PHOTO WITH MOTION DRIFT           -->
        <!-- ========================================================== -->
        <div class="absolute inset-0 w-full h-full pointer-events-none overflow-hidden z-0">
            <!-- Drift Photo -->
            <div class="w-full h-full bg-cover bg-center animate-bg-drift filter brightness-[0.75] contrast-[1.05]"
                 style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=2000&q=80');">
            </div>

            <!-- Deep Tint Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#0E131F] via-[#0E131F]/80 to-[#0F172A]/70"></div>
            
            <!-- Soft Ambient Glow Orbs -->
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none animate-pulse" style="animation-duration: 8s;"></div>
            <div class="absolute bottom-1/4 right-1/3 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none animate-pulse" style="animation-duration: 11s;"></div>
        </div>

        <!-- ========================================================== -->
        <!-- LEFT PANEL: LOGIN FORM (Dark Slate Canvas #0E131F)         -->
        <!-- ========================================================== -->
        <div id="leftPanel" class="relative z-20 w-full lg:w-[46%] xl:w-[43%] min-h-screen flex flex-col justify-between px-8 sm:px-12 md:px-16 lg:px-14 xl:px-20 py-8 lg:py-10 bg-[#0E131F] shrink-0 shadow-2xl">
            
            <!-- Top Branding & Status -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3.5">
                    <!-- Glowing Badge Logo -->
                    <div class="w-11 h-11 rounded-xl bg-[#141C2A] border border-[#22364E] flex items-center justify-center text-cyan-400 shadow-[0_0_18px_rgba(56,189,248,0.25)] relative">
                        <i data-lucide="compass" class="w-5 h-5 text-cyan-300"></i>
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 rounded-full ring-2 ring-[#0E131F]"></span>
                    </div>
                    <div>
                        <span class="font-extrabold text-[15px] tracking-wider text-[#D1DBE8] uppercase block">JURNAL ESEMKITA</span>
                        <span class="text-[10px] font-semibold text-[#5A6D87] uppercase tracking-widest block -mt-0.5">MONITORING TERPADU</span>
                    </div>
                </div>

                <!-- Subtle Theme Toggle -->
                <button type="button" onclick="toggleTheme()" id="themeToggleBtn"
                    class="h-8 w-8 rounded-lg border border-[#233144] bg-[#141C29] text-slate-400 hover:text-white hover:border-[#384D6B] transition-all flex items-center justify-center cursor-pointer shadow-xs"
                    title="Ganti Mode">
                    <i data-lucide="sun" class="w-4 h-4 text-amber-400 hidden dark:inline-block"></i>
                    <i data-lucide="moon" class="w-4 h-4 text-slate-400 dark:hidden inline-block"></i>
                </button>
            </div>

            <!-- Main Form Section -->
            <div class="my-auto py-6 max-w-md w-full">
                <!-- Title & Description -->
                <div class="mb-6">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                        Login to Jurnal Esemkita
                    </h1>
                    <p class="text-xs text-[#7A8DA6] mt-2 leading-relaxed font-normal">
                        Sistem terintegrasi untuk pencatatan jurnal, absensi, monitoring kegiatan kelas, dan perizinan sekolah.
                    </p>
                </div>

                <!-- Flash Message: Success -->
                @if (session('success'))
                    <div class="mb-5 px-4 py-2.5 bg-[#122D28]/90 border border-[#1E5C4E]/70 rounded-xl text-emerald-300 flex items-center space-x-2.5 shadow-sm text-xs">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Flash Message: Errors -->
                @if ($errors->any())
                    <div class="mb-5 px-4 py-3 bg-[#38151B]/90 border border-[#69232F]/80 rounded-xl text-rose-300 text-xs shadow-sm">
                        <div class="flex items-start space-x-2.5">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400 shrink-0 mt-0.5"></i>
                            <div>
                                <p class="font-bold text-rose-200">Gagal Masuk:</p>
                                <ul class="list-disc list-inside mt-0.5 space-y-0.5 text-[11px] text-rose-300/90">
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

                    <!-- 1. Input Username (Rotating Border Beam 1) -->
                    <div>
                        <label for="username" class="block text-[11.5px] font-semibold text-[#8E9DAE] mb-1.5">Username</label>
                        <div class="beam-wrapper group">
                            <!-- Rotating Border Beam Element -->
                            <div class="beam-rotator beam-username"></div>
                            
                            <!-- Inner Input Box -->
                            <div class="relative bg-[#141C29] rounded-[10.5px] flex items-center overflow-hidden">
                                <!-- Subtle Sheen Sweep -->
                                <div class="input-sheen"></div>
                                
                                <input 
                                    type="text" 
                                    name="username" 
                                    id="username" 
                                    value="{{ old('username') }}" 
                                    placeholder="your-nip@esemkita / username" 
                                    required
                                    autocomplete="username"
                                    class="w-full px-4 py-3 bg-transparent text-xs sm:text-sm text-slate-100 placeholder-[#526379] focus:outline-none transition-colors relative z-10"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- 2. Input Password (Rotating Border Beam 2) -->
                    <div>
                        <label for="password" class="block text-[11.5px] font-semibold text-[#8E9DAE] mb-1.5">Password</label>
                        <div class="beam-wrapper group">
                            <!-- Rotating Border Beam Element (Delayed in sequence) -->
                            <div class="beam-rotator beam-password"></div>
                            
                            <!-- Inner Input Box -->
                            <div class="relative bg-[#141C29] rounded-[10.5px] flex items-center overflow-hidden">
                                <!-- Subtle Sheen Sweep -->
                                <div class="input-sheen"></div>

                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    placeholder="Your Password" 
                                    required
                                    autocomplete="current-password"
                                    class="w-full px-4 py-3 bg-transparent text-xs sm:text-sm text-slate-100 placeholder-[#526379] focus:outline-none transition-colors pr-11 relative z-10"
                                >
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#6C7D93] hover:text-cyan-400 transition-colors z-20 cursor-pointer" title="Lihat/Sembunyikan Password">
                                    <i id="passwordToggleIcon" data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-0.5 text-xs">
                        <label class="flex items-center space-x-2.5 cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                value="1" 
                                class="w-4 h-4 rounded border-[#2E3D52] bg-[#141C29] text-cyan-500 focus:ring-cyan-500/20 focus:ring-offset-0 accent-cyan-500 cursor-pointer"
                            >
                            <span class="text-[#7A8DA6] text-[11.5px] font-medium">Remember me</span>
                        </label>
                        <a href="javascript:void(0)" onclick="alert('Silakan hubungi admin Tata Usaha sekolah untuk mereset kata sandi akun Anda.')" class="text-[#7A8DA6] hover:text-cyan-400 text-[11.5px] font-medium transition-colors">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- 4. Log In Button -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="w-full py-3.5 px-6 rounded-xl font-bold text-xs sm:text-sm text-white tracking-wide bg-gradient-to-b from-[#2C3E55] to-[#1B293A] hover:from-[#354C68] hover:to-[#223348] border border-[#4A6E94]/40 hover:border-[#679BCE]/60 shadow-[0_0_20px_rgba(56,189,248,0.12)] hover:shadow-[0_0_25px_rgba(56,189,248,0.22)] transition-all duration-200 flex items-center justify-center space-x-2 cursor-pointer"
                        >
                            <span>Log In</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 text-cyan-400"></i>
                        </button>
                    </div>
                </form>

                <!-- Footer Status -->
                <div class="mt-8 pt-5 border-t border-[#1C2636] flex items-center justify-between text-[11px] text-[#526379]">
                    <span class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Server Online • v2.0 Enterprise</span>
                    </span>
                    <span>Support TU: ext. 102</span>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="text-[11px] text-[#48566A] pt-4">
                &copy; {{ date('Y') }} SMK Negeri 1 Jurnal Esemkita. All rights reserved.
            </div>

            <!-- ========================================================== -->
            <!-- DYNAMIC MOVING LIQUID WAVE DIVIDER (Sesuai Gambar 4 & 5)   -->
            <!-- ========================================================== -->
            <svg id="waveSvg" class="hidden lg:block absolute top-0 -right-24 xl:-right-28 h-full w-28 xl:w-32 pointer-events-none z-30 overflow-visible" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="waveRidgeGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#1A2433" />
                        <stop offset="50%" stop-color="#3A4D67" />
                        <stop offset="100%" stop-color="#233144" />
                    </linearGradient>
                    <linearGradient id="waveHighlightGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="rgba(103, 232, 249, 0.4)" />
                        <stop offset="100%" stop-color="rgba(255, 255, 255, 0.1)" />
                    </linearGradient>
                </defs>
                <!-- Solid Fill matching left panel background -->
                <path id="waveFillPath" fill="#0E131F" d="" />
                <!-- 3D Beveled Ridge Shadow Stroke -->
                <path id="waveShadowPath" fill="none" stroke="rgba(0, 0, 0, 0.45)" stroke-width="9" stroke-linecap="round" d="" />
                <!-- 3D Beveled Metallic Ridge Stroke -->
                <path id="waveRidgePath" fill="none" stroke="url(#waveRidgeGrad)" stroke-width="5" stroke-linecap="round" d="" />
                <!-- Subtle Crest Glow Stroke -->
                <path id="waveHighlightPath" fill="none" stroke="url(#waveHighlightGrad)" stroke-width="1.8" stroke-linecap="round" d="" />
            </svg>
        </div>

        <!-- ========================================================== -->
        <!-- RIGHT PANEL: FLOATING GLASSMORPHISM CARD WITH SHEEN SWEEP  -->
        <!-- ========================================================== -->
        <div class="relative z-10 flex-1 flex items-center justify-center p-6 sm:p-10 lg:p-12 xl:p-16">
            
            <!-- Glassmorphism Card (Sesuai Gambar 4 & Gambar Sheen Terbaru) -->
            <div class="relative max-w-lg xl:max-w-xl w-full backdrop-blur-2xl bg-[#111824]/65 border border-white/12 rounded-[28px] p-7 sm:p-8 xl:p-9 shadow-[0_24px_60px_rgba(0,0,0,0.55)] space-y-6 overflow-hidden">
                
                <!-- Subtle Diagonal Light Sheen Sweep ("samar ga se kandel itu") -->
                <div class="sheen-sweep-layer"></div>

                <!-- Top Badge & Subtitle -->
                <div class="relative z-10 flex items-center space-x-3">
                    <span class="px-3 py-1 bg-[#1C293A] border border-[#2B3E56] text-cyan-300 text-[10px] font-bold rounded-full uppercase tracking-wider shadow-xs">
                        SISTEM TERINTEGRASI
                    </span>
                    <span class="text-xs font-medium text-[#7E93AE]">Monitoring & Jurnal</span>
                </div>

                <!-- Card Heading -->
                <div class="relative z-10">
                    <h2 class="text-xl sm:text-2xl xl:text-[25px] font-extrabold text-white leading-snug tracking-tight">
                        Pusat Monitoring dan Pembelajaran Sekolah Real-Time
                    </h2>
                    <p class="text-xs text-[#8E9EB3] mt-2.5 leading-relaxed font-normal">
                        Menghubungkan 7 pilar sekolah: Tata Usaha, Guru, Guru Piket, Waka Kurikulum/Kesiswaan, Kepala Sekolah, Keamanan Satpam, serta Wali Murid dalam satu ekosistem digital.
                    </p>
                </div>

                <!-- 4 Feature Cards (2x2 Grid) -->
                <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <!-- Feature 1 -->
                    <div class="flex items-center space-x-3 p-3.5 rounded-xl bg-white/[0.04] border border-white/[0.08] hover:bg-white/[0.07] hover:border-white/[0.14] transition-all text-xs font-semibold text-[#D4E0EE] group">
                        <div class="w-8 h-8 rounded-lg bg-white/[0.06] text-cyan-300 border border-white/[0.08] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                        </div>
                        <span class="truncate">Jurnal & Absensi Kelas</span>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-center space-x-3 p-3.5 rounded-xl bg-white/[0.04] border border-white/[0.08] hover:bg-white/[0.07] hover:border-white/[0.14] transition-all text-xs font-semibold text-[#D4E0EE] group">
                        <div class="w-8 h-8 rounded-lg bg-white/[0.06] text-cyan-300 border border-white/[0.08] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <i data-lucide="key" class="w-4 h-4"></i>
                        </div>
                        <span class="truncate">Dispen & Validasi Satpam</span>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-center space-x-3 p-3.5 rounded-xl bg-white/[0.04] border border-white/[0.08] hover:bg-white/[0.07] hover:border-white/[0.14] transition-all text-xs font-semibold text-[#D4E0EE] group">
                        <div class="w-8 h-8 rounded-lg bg-white/[0.06] text-cyan-300 border border-white/[0.08] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <span class="truncate">Monitoring Guru Terlambat</span>
                    </div>

                    <!-- Feature 4 -->
                    <div class="flex items-center space-x-3 p-3.5 rounded-xl bg-white/[0.04] border border-white/[0.08] hover:bg-white/[0.07] hover:border-white/[0.14] transition-all text-xs font-semibold text-[#D4E0EE] group">
                        <div class="w-8 h-8 rounded-lg bg-white/[0.06] text-cyan-300 border border-white/[0.08] flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <i data-lucide="database" class="w-4 h-4"></i>
                        </div>
                        <span class="truncate">Rekapitulasi & Audit Log</span>
                    </div>
                </div>

                <!-- Bottom Motto -->
                <div class="relative z-10 px-4 py-2.5 rounded-xl bg-white/[0.03] border border-white/[0.06] text-[11px] text-[#7B8FA6] font-medium flex items-center justify-between">
                    <span>Esemkita SMEA • Menuju Sekolah Unggul & Transparan</span>
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-cyan-400 shrink-0"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            initAnimatedWave();
        });

        // Password Show/Hide Toggle
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('passwordToggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Theme Toggle (Light / Dark)
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

        // Animated Liquid Wave Divider Logic
        function initAnimatedWave() {
            const waveSvg = document.getElementById('waveSvg');
            const leftPanel = document.getElementById('leftPanel');
            const fillPath = document.getElementById('waveFillPath');
            const shadowPath = document.getElementById('waveShadowPath');
            const ridgePath = document.getElementById('waveRidgePath');
            const highlightPath = document.getElementById('waveHighlightPath');

            if (!waveSvg || !fillPath) return;

            let startTime = null;

            function animate(time) {
                if (!startTime) startTime = time;
                const elapsed = (time - startTime) * 0.001; // seconds

                const height = leftPanel ? leftPanel.clientHeight : window.innerHeight;
                const width = waveSvg.clientWidth || 110;
                
                // Base anchor parameters for organic S-wave
                const baseMid = width * 0.45;
                const steps = 36;
                const dy = height / steps;

                let points = [];
                for (let i = 0; i <= steps; i++) {
                    const y = i * dy;
                    // Double harmonic fluid wave equation
                    const wave1 = Math.sin(y * 0.0038 + elapsed * 1.6) * 18;
                    const wave2 = Math.cos(y * 0.0075 + elapsed * 2.2) * 12;
                    const sShape = Math.sin((y / height) * Math.PI) * 14;
                    const x = baseMid + wave1 + wave2 + sShape;
                    points.push({ x, y });
                }

                // Construct smooth path string
                let curveStr = `M ${points[0].x.toFixed(1)},${points[0].y.toFixed(1)}`;
                for (let i = 1; i < points.length; i++) {
                    curveStr += ` L ${points[i].x.toFixed(1)},${points[i].y.toFixed(1)}`;
                }

                // Fill path joins to (0,0) and (0,height) on the left panel edge
                const fillStr = `M 0,0 L ${points[0].x.toFixed(1)},0 ` + 
                                curveStr.substring(curveStr.indexOf('L')) + 
                                ` L 0,${height} Z`;

                fillPath.setAttribute('d', fillStr);
                shadowPath.setAttribute('d', curveStr);
                ridgePath.setAttribute('d', curveStr);
                highlightPath.setAttribute('d', curveStr);

                requestAnimationFrame(animate);
            }

            requestAnimationFrame(animate);
        }
    </script>
</body>
</html>