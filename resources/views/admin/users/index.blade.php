@extends('layouts.app')

@section('title', 'Kelola Pengguna - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Kelola Pengguna (Users)</h1>
            <p class="text-xs text-gray-500 mt-1">
                Atur akun akses masuk sistem Jurnal Esemkita untuk seluruh peran
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-4 py-2 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <i data-lucide="panel-left" class="w-4 h-4 text-[#405078]"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH USER BARU (DAPAT DI-FOLD) -->
        <div id="formPanel" class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-6 transition-all duration-300">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-[#405078]"></i>
                <span>Tambah Pengguna Baru</span>
            </h3>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="role" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Peran (Role) *</label>
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
                    <label for="nama" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso, S.Pd" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="username" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Username Login (NIP / NISN / USN) *</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Masukkan NIP / NISN / USN" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="password" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password *</label>
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

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER PENGGUNA -->
        <div id="tableCol" class="lg:col-span-2 space-y-6">
            
            <!-- STICKY FILTER CARD (DENGAN LIVE INSTANT SEARCH) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-4 sticky top-6 z-20">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pencarian Cepat (Ketik Langsung Tersaring)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" class="w-4 h-4 text-[#8697C3]"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchUsers" value="{{ request('search') }}" placeholder="Ketik Username, Nama, atau Peran..." 
                                class="block w-full pl-9 pr-4 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                        </div>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('admin.users.index') }}" class="px-3 py-2 border border-[#D1D9EB] hover:bg-gray-50 text-gray-600 rounded-xl text-xs font-semibold flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- TABLE CARD (FIXED SCROLL DENGAN STICKY HEADER) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto max-h-[580px] overflow-y-auto">
                    <table class="w-full text-left border-collapse" id="tableUsers">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-3 px-4 w-32">Username</th>
                                <th class="py-3 px-4">Nama Lengkap</th>
                                <th class="py-3 px-4 w-40">Role</th>
                                <th class="py-3 px-4 text-center w-24">Status</th>
                                <th class="py-3 px-4 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($users as $u)
                                <tr class="hover:bg-gray-50/50 transition-colors user-row" data-search="{{ strtolower($u->username . ' ' . $u->nama_display . ' ' . $u->role) }}">
                                    <td class="py-3.5 px-4 font-bold text-[#405078] text-xs">{{ $u->username }}</td>
                                    <td class="py-3.5 px-4 font-medium text-[#1E2538] text-xs">{{ $u->nama_display }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold 
                                            @if($u->role === 'staf_tu') bg-purple-50 text-purple-700
                                            @elseif($u->role === 'guru') bg-blue-50 text-blue-700
                                            @elseif($u->role === 'guru_piket') bg-amber-50 text-amber-700
                                            @elseif($u->role === 'kepala_sekolah') bg-emerald-50 text-emerald-700
                                            @elseif($u->role === 'satpam') bg-rose-50 text-rose-700
                                            @elseif($u->role === 'wali_murid') bg-cyan-50 text-cyan-700
                                            @else bg-gray-100 text-gray-700 @endif">
                                            {{ str_replace('_', ' ', strtoupper($u->role)) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if ($u->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <a href="{{ route('admin.users.edit', $u->id) }}" class="p-1.5 text-[#405078] hover:bg-[#405078]/10 rounded-lg transition-colors" title="Edit Password / Role">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna {{ addslashes($u->username) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Pengguna">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 italic text-xs">
                                        Tidak ada data pengguna yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BAR (SESUAI GAMBAR FIGMA 1) -->
                <x-pagination-bar :paginator="$users" />
            </div>
        </div>
    </div>
</div>

<script>
    let formOpen = true;
    function toggleFormPanel() {
        const formPanel = document.getElementById('formPanel');
        const tableCol = document.getElementById('tableCol');
        const foldText = document.getElementById('foldText');
        
        formOpen = !formOpen;
        if (!formOpen) {
            formPanel.classList.add('hidden');
            tableCol.classList.remove('lg:col-span-2');
            tableCol.classList.add('lg:col-span-3');
            foldText.innerText = 'Tampilkan Form Tambah';
        } else {
            formPanel.classList.remove('hidden');
            tableCol.classList.add('lg:col-span-2');
            tableCol.classList.remove('lg:col-span-3');
            foldText.innerText = 'Sembunyikan Form Tambah';
        }
    }

    // Live Instant Search (Poin 7)
    document.getElementById('liveSearchUsers').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.user-row');
        rows.forEach(row => {
            const text = row.getAttribute('data-search') || '';
            if (text.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection