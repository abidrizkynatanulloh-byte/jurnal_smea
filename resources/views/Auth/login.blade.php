<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jurnal & Monitoring Esemkita</title>
    
    <!-- Google Fonts: Inter & Instrument Sans -->
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
        .bg-custom-navy {
            background-color: #405078;
        }
        .bg-custom-navy:hover {
            background-color: #2F3C5C;
        }
        .text-custom-navy {
            color: #405078;
        }
        .border-custom-accent {
            border-color: #8697C3;
        }
    </style>
</head>
<body class="h-full antialiased text-[#1E2538] bg-white">

    <!-- Split-Screen Container -->
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ========================================================== -->
        <!-- LEFT SIDE: FORM LOGIN (Sesuai Layout Gambar / Mockup)       -->
        <!-- ========================================================== -->
        <div class="w-full lg:w-[48%] xl:w-[45%] flex flex-col justify-between px-8 sm:px-14 md:px-20 lg:px-16 xl:px-24 py-10 lg:py-14 bg-white z-10">
            
            <!-- Top Branding -->
            <div class="flex items-center space-x-3 mb-8 lg:mb-0">
                <div class="w-10 h-10 rounded-xl bg-[#405078] flex items-center justify-center text-white shadow-md shadow-[#405078]/25">
                    <i data-lucide="book-open-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="font-bold text-lg tracking-tight text-[#405078]">JURNAL ESEMKITA</span>
                    <span class="block text-[10px] font-semibold text-[#8697C3] uppercase tracking-widest -mt-1">Monitoring Terpadu</span>
                </div>
            </div>

            <!-- Form Section -->
            <div class="my-auto py-6 max-w-md w-full">
                <div class="mb-8">
                    <h1 class="text-3xl lg:text-[34px] font-bold text-[#1E2538] tracking-tight">Login to Jurnal Esemkita</h1>
                    <p class="text-sm text-gray-500 mt-2.5 leading-relaxed">
                        Sistem terintegrasi untuk pencatatan jurnal, absensi, monitoring kegiatan kelas, dan perizinan sekolah.
                    </p>
                </div>

                <!-- Flash Message Banner -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-600 rounded-r-xl text-emerald-800 flex items-start space-x-3 shadow-sm text-xs">
                        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-600"></i>
                        <p class="font-medium pt-0.5">{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-600 rounded-r-xl text-rose-800 text-xs shadow-sm">
                        <div class="flex items-start space-x-3">
                            <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 text-rose-600"></i>
                            <div class="font-medium">
                                <p class="font-semibold">Login Gagal:</p>
                                <ul class="list-disc list-inside mt-1 space-y-0.5 text-[11px] text-rose-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf



                    <!-- 2. Input Username (NIP / NISN / USN) -->
                    <div>
                        <label for="username" class="block text-xs font-semibold text-gray-700 mb-1.5">Username</label>
                        <input 
                            type="text" 
                            name="username" 
                            id="username" 
                            value="{{ old('username') }}" 
                            placeholder="your-nip@esemkita / username" 
                            required
                            class="block w-full px-4 py-3 bg-[#F8FAFC] border border-[#D1D9EB] rounded-lg text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all"
                        >
                    </div>

                    <!-- 3. Input Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                placeholder="Your Password" 
                                required
                                class="block w-full px-4 py-3 bg-[#F8FAFC] border border-[#D1D9EB] rounded-lg text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all pr-10"
                            >
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-[#405078]">
                                <i id="passwordToggleIcon" data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- 4. Remember me & Forgot Password -->
                    <div class="flex items-center justify-between pt-1 text-xs">
                        <label class="flex items-center space-x-2.5 cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                value="1" 
                                class="w-4 h-4 rounded border-[#8697C3] text-[#405078] focus:ring-[#405078] accent-[#405078]"
                            >
                            <span class="text-gray-600 font-medium">Remember me</span>
                        </label>
                        <a href="javascript:void(0)" onclick="alert('Silakan hubungi staf Tata Usaha (Admin) sekolah untuk reset kata sandi akun Anda.')" class="text-[#8697C3] hover:text-[#405078] font-semibold transition-colors">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- 5. Submit Button -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="w-full py-3.5 px-6 bg-[#405078] hover:bg-[#2F3C5C] text-white font-bold text-sm rounded-lg shadow-md shadow-[#405078]/25 hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 cursor-pointer"
                        >
                            <span>Log In</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>

                <!-- Quick Help / Information -->
                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
                    <span class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Server Online • v2.0 Enterprise</span>
                    </span>
                    <span>Support TU: ext. 102</span>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="text-xs text-gray-400 pt-6">
                &copy; {{ date('Y') }} SMK Negeri 1 Jurnal Esemkita. All rights reserved.
            </div>
        </div>

        <!-- ========================================================== -->
        <!-- RIGHT SIDE: AESTHETIC VISUAL SHOWCASE (Sesuai Gambar)       -->
        <!-- ========================================================== -->
        <div class="hidden lg:flex lg:w-[52%] xl:w-[55%] relative overflow-hidden bg-cover bg-center items-center justify-center p-12"
             style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1600&q=80');">
            
            <!-- Gradient Overlay matching #405078 & #8697C3 -->
            <div class="absolute inset-0 bg-gradient-to-tr from-[#1E2538]/85 via-[#405078]/60 to-[#8697C3]/40 backdrop-blur-[2px]"></div>

            <!-- Floating Glassmorphism Hero Card -->
            <div class="relative z-10 max-w-lg w-full bg-white/90 backdrop-blur-md rounded-2xl p-8 border border-white/40 shadow-2xl space-y-6">
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-[#405078] text-white text-[10px] font-bold rounded-full uppercase tracking-wider">
                        Sistem Terintegrasi
                    </span>
                    <span class="text-xs font-semibold text-gray-500">Monitoring & Jurnal</span>
                </div>

                <div>
                    <h2 class="text-2xl font-extrabold text-[#1E2538] leading-snug">
                        Pusat Monitoring dan Pembelajaran Sekolah Real-Time
                    </h2>
                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                        Menghubungkan 7 pilar sekolah: Tata Usaha, Guru, Guru Piket, Waka Kurikulum/Kesiswaan, Kepala Sekolah, Keamanan Satpam, serta Wali Murid dalam satu ekosistem digital.
                    </p>
                </div>

                <!-- Feature Pills -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="flex items-center space-x-2.5 p-3 rounded-xl bg-white/80 border border-gray-100 text-xs font-medium text-[#1E2538]">
                        <div class="w-6 h-6 rounded-lg bg-[#405078]/10 text-[#405078] flex items-center justify-center flex-shrink-0">
                            <i data-lucide="clipboard-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <span>Jurnal & Absensi Kelas</span>
                    </div>

                    <div class="flex items-center space-x-2.5 p-3 rounded-xl bg-white/80 border border-gray-100 text-xs font-medium text-[#1E2538]">
                        <div class="w-6 h-6 rounded-lg bg-[#8697C3]/15 text-[#405078] flex items-center justify-center flex-shrink-0">
                            <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                        </div>
                        <span>Dispen & Validasi Satpam</span>
                    </div>

                    <div class="flex items-center space-x-2.5 p-3 rounded-xl bg-white/80 border border-gray-100 text-xs font-medium text-[#1E2538]">
                        <div class="w-6 h-6 rounded-lg bg-[#405078]/10 text-[#405078] flex items-center justify-center flex-shrink-0">
                            <i data-lucide="bell" class="w-3.5 h-3.5"></i>
                        </div>
                        <span>Monitoring Guru Terlambat</span>
                    </div>

                    <div class="flex items-center space-x-2.5 p-3 rounded-xl bg-white/80 border border-gray-100 text-xs font-medium text-[#1E2538]">
                        <div class="w-6 h-6 rounded-lg bg-[#8697C3]/15 text-[#405078] flex items-center justify-center flex-shrink-0">
                            <i data-lucide="line-chart" class="w-3.5 h-3.5"></i>
                        </div>
                        <span>Rekapitulasi & Audit Log</span>
                    </div>
                </div>

                <!-- Quote / Note -->
                <div class="p-3.5 rounded-xl bg-[#405078]/5 border border-[#405078]/10 text-[11px] text-[#405078] font-medium flex items-center justify-between">
                    <span>Esemkita SMEA • Menuju Sekolah Unggul & Transparan</span>
                    <i data-lucide="sparkles" class="w-4 h-4 text-[#8697C3]"></i>
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
        });

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
    </script>
</body>
</html>