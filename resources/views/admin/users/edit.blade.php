@extends('layouts.app')

@section('title', 'Edit Pengguna - Jurnal Esemkita')

@section('content')
<div class="space-y-4 max-w-md mx-auto">
    <!-- Page Header & Back Button -->
    <div class="flex items-center space-x-2.5">
        <a href="{{ route('admin.users.index') }}" class="w-8 h-8 border border-slate-200 hover:bg-white text-slate-500 hover:text-slate-800 rounded-lg flex items-center justify-center transition-colors shadow-2xs">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Edit Akun Pengguna</h1>
            <p class="text-xs text-slate-500 mt-0.5">Memperbarui informasi kredensial akun login</p>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-4">
        <div class="flex items-center space-x-3 mb-3.5 pb-3 border-b border-slate-100">
            <div class="w-8 h-8 rounded-lg bg-[#1E2538] text-white flex items-center justify-center font-bold text-xs shrink-0">
                {{ $user->initials }}
            </div>
            <div class="min-w-0">
                <h3 class="font-bold text-slate-900 text-xs truncate">{{ $user->username }}</h3>
                <p class="text-[10px] text-slate-400 capitalize">{{ str_replace('_', ' ', $user->role) }}</p>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-2.5">
            @csrf
            @method('PUT')

            <!-- Username Field -->
            <div>
                <label for="username" class="block text-xs font-semibold text-slate-700 mb-1">Username Login (NIP / NISN / USN)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required 
                        class="block w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538] transition-all">
                </div>
            </div>

            <!-- Role Field -->
            <div>
                <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">Peran (Role)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="role" id="role" required
                        class="block w-full h-8 pl-8 pr-7 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538] transition-all cursor-pointer appearance-none">
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- Password Field (Optional) -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password Baru (Opsional)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin diubah"
                        class="block w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538] transition-all">
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5">Minimal 4 karakter, hanya diisi jika ingin mengubah password akun.</p>
            </div>

            <!-- Status Field (Active/Inactive) -->
            <div>
                <label for="is_active" class="block text-xs font-semibold text-slate-700 mb-1">Status Akun</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="power" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="is_active" id="is_active" required
                        class="block w-full h-8 pl-8 pr-7 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538] transition-all cursor-pointer appearance-none">
                        <option value="1" {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>Aktif (Bisa Login)</option>
                        <option value="0" {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>Nonaktif (Ditangguhkan)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2 pt-2.5 border-t border-slate-100">
                <button type="submit" class="flex-1 h-8.5 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg font-semibold text-xs transition-colors shadow-2xs flex items-center justify-center space-x-1.5 cursor-pointer">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                    <span>Simpan Perubahan</span>
                </button>
                <a href="{{ route('admin.users.index') }}" class="h-8.5 px-3.5 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
