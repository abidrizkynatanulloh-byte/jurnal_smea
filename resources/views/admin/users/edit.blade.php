@extends('layouts.app')

@section('title', 'Edit Pengguna - Jurnal Esemkita')

@section('content')
<div class="space-y-6 max-w-lg mx-auto">
    <!-- Page Header & Back Button -->
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.users.index') }}" class="p-2 border border-[#D1D9EB] hover:bg-white text-gray-500 hover:text-[#1E2538] rounded-xl transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Edit Akun Pengguna</h1>
            <p class="text-xs text-gray-500 mt-0.5">Memperbarui informasi kredensial akun login</p>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6">
        <div class="flex items-center space-x-3.5 mb-6 pb-4 border-b border-gray-100">
            <div class="w-12 h-12 rounded-xl bg-[#405078]/10 border border-[#405078]/20 flex items-center justify-center text-[#405078] font-bold text-lg">
                {{ strtoupper(substr($user->username, 0, 2)) }}
            </div>
            <div>
                <h3 class="font-bold text-[#1E2538] text-base">{{ $user->username }}</h3>
                <p class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', $user->role) }}</p>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Username Field -->
            <div>
                <label for="username" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Username Login (NIP / NISN / USN)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required 
                        class="block w-full pl-11 pr-4 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>
            </div>

            <!-- Role Field -->
            <div>
                <label for="role" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Peran (Role)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                    </div>
                    <select name="role" id="role" required
                        class="block w-full pl-11 pr-10 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer appearance-none">
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <!-- Password Field (Optional) -->
            <div>
                <label for="password" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password Baru (Opsional)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                    <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin diubah"
                        class="block w-full pl-11 pr-4 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>
                <p class="text-[10px] text-gray-400 mt-1">Minimal 4 karakter, hanya diisi jika ingin mengubah password akun.</p>
            </div>

            <!-- Status Field (Active/Inactive) -->
            <div>
                <label for="is_active" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Status Akun</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="power" class="w-4 h-4"></i>
                    </div>
                    <select name="is_active" id="is_active" required
                        class="block w-full pl-11 pr-10 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer appearance-none">
                        <option value="1" {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>Aktif (Bisa Login)</option>
                        <option value="0" {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>Nonaktif (Ditangguhkan)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-3 pt-3 border-t border-gray-100">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-1.5 cursor-pointer">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Perubahan</span>
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 border border-[#D1D9EB] hover:bg-gray-50 text-gray-500 hover:text-[#1E2538] rounded-xl text-sm font-semibold transition-colors text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
