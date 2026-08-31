<!DOCTYPE html>
<html lang="id" class="h-full bg-[#fdfdfc]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jurnal Esemkita</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons CDN -->
    <script src="https://cdn.jsdelivr.net/npm/lucide/dist/umd/lucide.min.js"></script>
</head>
<body class="h-full font-sans antialiased bg-[#fdfdfc] text-[#1b1b18] flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <!-- Logo and Heading -->
        <div class="text-center mb-8">
            <div class="inline-flex p-3.5 bg-brand/10 text-brand rounded-2xl mb-4 shadow-sm">
                <i data-lucide="book-open" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-dark">Jurnal Esemkita</h1>
            <p class="text-sm text-gray-500 mt-1.5">Sistem Jurnal & Kehadiran Mengajar Sekolah</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm p-8">
            <!-- Flash Message Banner -->
            @if (session('success'))
                <div class="mb-5 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl text-green-700 flex items-start space-x-3">
                    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl text-red-700">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                        <div class="text-xs font-medium">
                            <p class="font-semibold">Login Gagal:</p>
                            <ul class="list-disc list-inside mt-1 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Role Selection -->
                <div>
                    <label for="role" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Pilih Peran (Role)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="shield-question" class="w-5 h-5"></i>
                        </div>
                        <select name="role" id="role" required class="block w-full pl-11 pr-10 py-3 bg-[#fdfdfc] border border-gray-200 rounded-xl text-sm text-dark focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all appearance-none cursor-pointer">
                            <option value="">-- Pilih Role Anda --</option>
                            @foreach ($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Username Input -->
                <div>
                    <label for="username" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Username / NIP / NISN</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input 
                            type="text" 
                            name="username" 
                            id="username" 
                            value="{{ old('username') }}" 
                            placeholder="Masukkan NIP / NISN / USN" 
                            required
                            class="block w-full pl-11 pr-4 py-3 bg-[#fdfdfc] border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all"
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Password</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="key-round" class="w-5 h-5"></i>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="Masukkan Password" 
                            required
                            class="block w-full pl-11 pr-4 py-3 bg-[#fdfdfc] border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all"
                        >
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="remember" value="1" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand accent-brand">
                        <span class="text-xs font-medium text-gray-500">Ingat Saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 bg-brand hover:bg-brand-hover text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2">
                    <span>Masuk ke Sistem</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
        
        <p class="text-center text-xs text-gray-400 mt-6">&copy; {{ date('Y') }} SMK Negeri 1 Jurnal Esemkita. All rights reserved.</p>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>