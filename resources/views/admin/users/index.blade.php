@extends('layouts.app')

@section('title', 'Kelola Pengguna - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Kelola Pengguna (Users)</h1>
            <p class="text-xs text-gray-500 mt-1">
                Atur akun akses masuk sistem Jurnal Esemkita
            </p>
        </div>
    </div>

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH USER BARU -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-8">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-[#405078]"></i>
                <span>Tambah Pengguna Baru</span>
            </h3>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="role" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Peran (Role)</label>
                    <select name="role" id="role" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="nama" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso, S.Pd" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="username" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Username Login (NIP / NISN / USN)</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Masukkan NIP / NISN / USN" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="password" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="no_hp" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nomor HP (Opsional)</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Pengguna</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & FILTER PENGGUNA -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- FILTER CARD -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-5">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Cari Pengguna</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan NIP / NISN / Nama / Role..." 
                                class="block w-full pl-9 pr-4 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                        </div>
                    </div>

                    <div class="flex space-x-2 w-full sm:w-auto">
                        <button type="submit" class="flex-1 sm:flex-initial px-5 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer shadow-sm">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span>Cari</span>
                        </button>
                        @if (request('search'))
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-[#D1D9EB] hover:bg-gray-50 text-gray-500 hover:text-[#1E2538] rounded-xl text-sm font-semibold transition-colors flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- TABLE CARD -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-6">Username / NIP / NISN</th>
                                <th class="py-4 px-6">Nama Pengguna</th>
                                <th class="py-4 px-6">Peran (Role)</th>
                                <th class="py-4 px-6 w-32">Status</th>
                                <th class="py-4 px-6 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($users as $index => $u)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-6 text-center font-medium text-gray-400">{{ $users->firstItem() + $index }}</td>
                                    <td class="py-4 px-6 font-semibold text-[#1E2538]">{{ $u->username }}</td>
                                    <td class="py-4 px-6 font-medium text-[#1E2538]">
                                        @if ($u->guru)
                                            {{ $u->guru->nama_guru }}
                                        @elseif ($u->stafTu)
                                            {{ $u->stafTu->nama_staf }}
                                        @elseif ($u->satpam)
                                            {{ $u->satpam->nama_satpam }}
                                        @elseif ($u->siswa)
                                            <span class="text-xs text-gray-400">Wali dari:</span> <br>
                                            <span class="font-bold text-gray-700">{{ $u->siswa->nama_siswa }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 bg-[#405078]/10 text-[#405078] rounded-md text-xs font-bold uppercase">
                                            {{ str_replace('_', ' ', $u->role) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($u->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full space-x-1">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                                <span>Aktif</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 bg-gray-100 text-gray-600 text-xs font-bold rounded-full space-x-1">
                                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                                <span>Nonaktif</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center space-x-2.5">
                                            <a href="{{ route('admin.users.edit', $u->id) }}" class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer" title="Edit Pengguna">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </a>
                                            
                                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun {{ $u->username }}?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus / Nonaktifkan Pengguna">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400 italic">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        Tidak ada data pengguna yang cocok dengan kriteria pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION FOOTER -->
                @if ($users->hasPages())
                    <div class="px-6 py-4 bg-[#F8FAFC] border-t border-[#D1D9EB] flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            Menampilkan halaman <span class="font-semibold text-[#1E2538]">{{ $users->currentPage() }}</span> dari <span class="font-semibold text-[#1E2538]">{{ $users->lastPage() }}</span>
                        </div>
                        <div class="inline-flex space-x-1.5">
                            @if ($users->previousPageUrl())
                                <a href="{{ $users->previousPageUrl() }}" class="px-3.5 py-1.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
                                    <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                                    <span>Sebelumnya</span>
                                </a>
                            @endif
                            @if ($users->nextPageUrl())
                                <a href="{{ $users->nextPageUrl() }}" class="px-3.5 py-1.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
                                    <span>Selanjutnya</span>
                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection