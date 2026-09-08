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

        /* Moving Background Drift */
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
            animation: bgMotion 32s ease-in-out infinite alternate;
        }

        /* Slow, Calm, Subtle Glass Sheen Sweep ("samar & ga kecepeten") */
        @keyframes sheenSweepSlow {
            0% {
                transform: translateX(-180%) translateY(-180%) rotate(38deg);
                opacity: 0;
            }
            3% {
                opacity: 0.85;
            }
            20% {
                transform: translateX(180%) translateY(180%) rotate(38deg);
                opacity: 0.85;
            }
            24% {
                opacity: 0;
            }
            100% {
                transform: translateX(180%) translateY(180%) rotate(38deg);
                opacity: 0;
            }
        }
        .sheen-sweep-slow {
            position: absolute;
            top: -150%;
            left: -150%;
            width: 400%;
            height: 400%;
            background: linear-gradient(
                90deg,
                transparent 0%,
                transparent 38%,
                rgba(255, 255, 255, 0.02) 42%,
                rgba(255, 255, 255, 0.08) 46%,
                rgba(255, 255, 255, 0.14) 49%,
                rgba(255, 255, 255, 0.04) 51%,
                rgba(255, 255, 255, 0.09) 53%,
                rgba(255, 255, 255, 0.02) 56%,
                transparent 62%,
                transparent 100%
            );
            animation: sheenSweepSlow 16s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            pointer-events: none;
        }

        /* Subtle Shimmer for Input Boxes */
        @keyframes inputSheenSlow {
            0% {
                transform: translateX(-180%) rotate(35deg);
                opacity: 0;
            }
            15% {
                opacity: 0.5;
            }
            45% {
                transform: translateX(180%) rotate(35deg);
                opacity: 0.5;
            }
            52% {
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
                rgba(255, 255, 255, 0.05) 48%,
                rgba(165, 243, 252, 0.12) 50%,
                rgba(255, 255, 255, 0.05) 52%,
                transparent 60%,
                transparent 100%
            );
            animation: inputSheenSlow 12s ease-in-out infinite;
            pointer-events: none;
        }

        /* Sequential Rotating Border Beam on Input Fields */
        .beam-wrapper {
            position: relative;
            padding: 1.5px;
            border-radius: 12px;
            overflow: hidden;
            background-color: var(--input-border-wrap);
            transition: all 0.3s ease;
        }
        .beam-wrapper:hover, .beam-wrapper:focus-within {
            background-color: #38BDF8;
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
            0% { transform: rotate(0deg); opacity: 0; }
            5% { opacity: 1; }
            44% { opacity: 1; }
            49% { transform: rotate(360deg); opacity: 0; }
            100% { transform: rotate(360deg); opacity: 0; }
        }

        /* Cycle 2: Password input beam rotates during 3.5s - 7s */
        @keyframes rotateBeamPass {
            0% { transform: rotate(0deg); opacity: 0; }
            49% { transform: rotate(0deg); opacity: 0; }
            54% { opacity: 1; }
            93% { opacity: 1; }
            98% { transform: rotate(360deg); opacity: 0; }
            100% { transform: rotate(360deg); opacity: 0; }
        }

        .beam-username {
            animation: rotateBeamUser 7s linear infinite;
        }
        .beam-password {
            animation: rotateBeamPass 7s linear infinite;
        }

        /* Continuous rotation on active focus */
        @keyframes rotateBeamActive {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .beam-wrapper:focus-within .beam-rotator {
            opacity: 1 !important;
            animation: rotateBeamActive 2.8s linear infinite !important;
        }
    </style>
</head>
<body class="h-full antialiased overflow-x-hidden selection:bg-cyan-500/30 selection:text-cyan-200">

    <!-- Split-Screen Viewport -->
    <div id="mainViewport" class="relative min-h-screen w-full lg:h-screen lg:overflow-hidden flex flex-col lg:flex-row">

        <!-- ========================================================== -->
        <!-- BACKGROUND LAYER: PHOTO WITH MOTION DRIFT & AMBIENT GLOW   -->
        <!-- ========================================================== -->
        <div class="absolute inset-0 w-full h-full pointer-events-none overflow-hidden z-0">
            <!-- Drift Architectural Photo -->
            <div class="w-full h-full bg-cover bg-center animate-bg-drift filter brightness-[0.92] dark:brightness-[0.70] contrast-[1.05]"
                 style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=2000&q=80');">
            </div>

            <!-- Tint Overlay Adapts to Mode -->
            <div class="absolute inset-0 transition-colors duration-300"
                 style="background: linear-gradient(105deg, var(--bg-overlay-start) 0%, var(--bg-overlay-end) 100%);"></div>
            
            <!-- Soft Ambient Glow Orbs -->
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-cyan-500/10 dark:bg-cyan-500/15 rounded-full blur-3xl pointer-events-none animate-pulse" style="animation-duration: 8s;"></div>
            <div class="absolute bottom-1/4 right-1/3 w-80 h-80 bg-indigo-500/10 dark:bg-indigo-500/15 rounded-full blur-3xl pointer-events-none animate-pulse" style="animation-duration: 11s;"></div>
        </div>

        <!-- ========================================================== -->
        <!-- LEFT PANEL: LOGIN FORM                                     -->
        <!-- ========================================================== -->
        <div id="leftPanel" class="relative z-30 w-full lg:w-[42%] xl:w-[39%] min-h-screen flex flex-col justify-between px-7 sm:px-12 lg:px-14 xl:px-18 py-8 lg:py-10 shrink-0 lg:bg-transparent bg-[var(--bg-left)] transition-colors duration-300">
            
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
                    class="h-9 w-9 rounded-xl border border-slate-300 dark:border-[#233144] bg-white dark:bg-[#141C29] text-slate-700 dark:text-slate-300 hover:text-cyan-500 dark:hover:text-cyan-300 hover:border-cyan-500/50 transition-all flex items-center justify-center cursor-pointer shadow-xs"
                    title="Ganti Mode Siang / Malam">
                    <span id="themeToggleIconWrap" class="flex items-center justify-center">
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

                    <!-- 1. Input Username (Rotating Border Beam 1) -->
                    <div>
                        <label for="username" class="block text-[11.5px] font-semibold mb-1.5" style="color: var(--text-label);">Username</label>
                        <div class="beam-wrapper group">
                            <!-- Rotating Border Beam Element -->
                            <div class="beam-rotator beam-username"></div>
                            
                            <!-- Inner Input Box -->
                            <div class="relative rounded-[10.5px] flex items-center overflow-hidden" style="background-color: var(--input-bg-inner);">
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
                                    class="w-full px-4 py-3 bg-transparent text-xs sm:text-sm focus:outline-none transition-colors relative z-10"
                                    style="color: var(--input-text);"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- 2. Input Password (Rotating Border Beam 2) -->
                    <div>
                        <label for="password" class="block text-[11.5px] font-semibold mb-1.5" style="color: var(--text-label);">Password</label>
                        <div class="beam-wrapper group">
                            <!-- Rotating Border Beam Element (Delayed in sequence) -->
                            <div class="beam-rotator beam-password"></div>
                            
                            <!-- Inner Input Box -->
                            <div class="relative rounded-[10.5px] flex items-center overflow-hidden" style="background-color: var(--input-bg-inner);">
                                <!-- Subtle Sheen Sweep -->
                                <div class="input-sheen"></div>

                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    placeholder="Your Password" 
                                    required
                                    autocomplete="current-password"
                                    class="w-full px-4 py-3 bg-transparent text-xs sm:text-sm focus:outline-none transition-colors pr-11 relative z-10"
                                    style="color: var(--input-text);"
                                >
                                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-cyan-500 dark:hover:text-cyan-400 transition-colors z-20 cursor-pointer" title="Lihat/Sembunyikan Password">
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
                                class="w-4 h-4 rounded border-slate-300 dark:border-[#2E3D52] text-cyan-600 focus:ring-cyan-500/20 accent-cyan-500 cursor-pointer"
                            >
                            <span class="text-[11.5px] font-medium" style="color: var(--text-subtitle);">Remember me</span>
                        </label>
                        <a href="javascript:void(0)" onclick="alert('Silakan hubungi admin Tata Usaha sekolah untuk mereset kata sandi akun Anda.')" class="hover:text-cyan-500 dark:hover:text-cyan-400 text-[11.5px] font-medium transition-colors" style="color: var(--text-subtitle);">
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
        <!-- FROSTED GLASS BLUR & CALM SHEEN LAYER                      -->
        <!-- Coordinates match 1:1 with the full-screen SVG ClipPath   -->
        <!-- ========================================================== -->
        <div id="glassBlurLayer" class="hidden lg:block absolute inset-0 pointer-events-none z-[22] overflow-hidden"
             style="clip-path: url(#glassCardClip); -webkit-clip-path: url(#glassCardClip); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);">
            <!-- Slow, Calm, Subtle Diagonal Glass Sheen Sweep ("samar & ga kecepeten") -->
            <div class="sheen-sweep-slow"></div>
        </div>

        <!-- ========================================================== -->
        <!-- RIGHT PANEL: CONTENT OVER SYNCHRONIZED GLASS CARD          -->
        <!-- ========================================================== -->
        <div id="rightContentWrap" class="relative z-[25] flex-1 flex items-center justify-center lg:justify-end xl:justify-center p-6 sm:p-10 lg:p-12 lg:pr-14 xl:pr-20">
            
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
            initSynchronizedWaveAndCard();
        });

        // Toggle Password Show/Hide
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

        // Apply theme icon and state
        function applyTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            const wrap = document.getElementById('themeToggleIconWrap');
            const btn = document.getElementById('themeToggleBtn');
            if (wrap) {
                wrap.innerHTML = isDark 
                    ? '<i data-lucide="sun" class="w-4.5 h-4.5 text-amber-400"></i>' 
                    : '<i data-lucide="moon" class="w-4.5 h-4.5 text-slate-700"></i>';
            }
            if (btn) {
                btn.setAttribute('title', isDark ? 'Beralih ke Mode Siang (Terang)' : 'Beralih ke Mode Malam (Gelap)');
            }
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

        // Synchronized Dynamic Wave & Right Card Wave
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

            let startTime = null;

            function animate(time) {
                if (!startTime) startTime = time;
                const elapsed = (time - startTime) * 0.001; // seconds

                const H = window.innerHeight;
                const W = window.innerWidth;

                // Responsive anchor: ~40% of window width on desktop
                const leftPanelWidth = leftPanel ? leftPanel.clientWidth : W * 0.40;
                const baseMid = leftPanelWidth + 28;

                // Moderate & smooth wave configuration (Sedengan)
                const amp1 = 30;
                const amp2 = 12;
                const sOffset = 18;
                const steps = 48;
                const dy = H / steps;

                function getWaveX(y, t) {
                    const u = y / H;
                    // Primary harmonic fluid wave with Gambar 1 curve phase
                    const w1 = -Math.sin((u * 2.2 - 0.15) * Math.PI + t * 1.1) * amp1;
                    const w2 = Math.cos((u * 3.4) * Math.PI + t * 1.5) * amp2;
                    // S-curve bias matching Gambar 1
                    const sShape = Math.sin(u * Math.PI) * sOffset;
                    return baseMid + w1 + w2 + sShape;
                }

                let wavePoints = [];
                for (let i = 0; i <= steps; i++) {
                    const y = i * dy;
                    const x = getWaveX(y, elapsed);
                    wavePoints.push({ x, y });
                }

                // Construct wave curve string
                let waveCurveStr = `M ${wavePoints[0].x.toFixed(1)},${wavePoints[0].y.toFixed(1)}`;
                for (let i = 1; i < wavePoints.length; i++) {
                    waveCurveStr += ` L ${wavePoints[i].x.toFixed(1)},${wavePoints[i].y.toFixed(1)}`;
                }

                // Left Panel Solid Fill Path
                const fillStr = `M 0,0 L ${wavePoints[0].x.toFixed(1)},0 ` + 
                                waveCurveStr.substring(waveCurveStr.indexOf('L')) + 
                                ` L 0,${H} Z`;

                fillPath.setAttribute('d', fillStr);
                shadowPath.setAttribute('d', waveCurveStr);
                ridgePath.setAttribute('d', waveCurveStr);
                highlightPath.setAttribute('d', waveCurveStr);

                // Synchronize Right Glass Card (4 Smooth Rounded Corners & Wavy Left Boundary)
                if (glassCardContent && cardBgPath && cardClipPath) {
                    const cardRect = glassCardContent.getBoundingClientRect();
                    const rRadius = 24;
                    const gap = 24; // Distance from 3D wave line

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

                    // Closed Card Shape with 4 Smooth Rounded Arcs:
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

                    // Specular Highlight along Top-Left Arc, Wavy Left Edge, and Bottom-Left Arc
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
    </script>
</body>
</html>
