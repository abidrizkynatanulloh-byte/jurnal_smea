@extends('layouts.app')

@section('title', 'Kelola Pengguna - Jurnal Esemkita')

@section('content')
<div class="space-y-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <div>
            <h1 class="text-xl font-extrabold text-[#1E2538] tracking-tight">Kelola Pengguna (Users)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Atur akun akses masuk sistem Jurnal Esemkita untuk seluruh peran</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-[#1E2538] rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-[#405078]"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Alert / Notifikasi -->
    @if (session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center space-x-2 shadow-2xs">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold shadow-2xs">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH USER BARU (STICKY & FOLDABLE) -->
        <div id="formPanel" class="bg-white border border-slate-200 rounded-2xl p-4.5 shadow-xs space-y-3 lg:sticky lg:top-20 z-10 transition-all duration-300">
            <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <i data-lucide="user-plus" class="w-4 h-4 text-[#405078]"></i>
                    <h3 class="font-bold text-[#1E2538] text-xs uppercase tracking-wider">Tambah Pengguna Baru</h3>
                </div>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="role" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pilih Peran (Role) *</label>
                    <select name="role" id="role" required
                        class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="nama" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso, S.Pd" required
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                </div>

                <div>
                    <label for="username" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Username Login (NIP / NISN / USN) *</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Masukkan NIP / NISN / USN" required
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Password *</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password" required
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                </div>

                <div>
                    <label for="no_hp" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor HP (Opsional)</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                </div>

                <div class="pt-1.5">
                    <button type="submit" class="w-full py-2 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Pengguna</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER PENGGUNA -->
        <div id="tableCol" class="lg:col-span-2 space-y-3.5">
            
            <!-- STICKY FILTER CARD (DENGAN LIVE INSTANT SEARCH) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-3.5 shadow-xs sticky top-20 z-20">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pencarian Cepat (Ketik Langsung Tersaring)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-[#8697C3]"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchUsers" value="{{ request('search') }}" placeholder="Ketik Username, Nama, atau Peran..." 
                                class="block w-full pl-8.5 pr-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                        </div>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- TABLE CARD (FIXED SCROLL DENGAN STICKY HEADER) -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden flex flex-col">
                <div class="overflow-x-auto max-h-[calc(100vh-270px)] min-h-[380px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs" id="tableUsers">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-slate-500 font-semibold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-2.5 px-3 w-12 text-center">No</th>
                                <th class="py-2.5 px-3">Nama Lengkap & Username</th>
                                <th class="py-2.5 px-3 w-36">Peran (Role)</th>
                                <th class="py-2.5 px-3 text-center w-24">Status</th>
                                <th class="py-2.5 px-3 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600" id="usersTbody">
                            @forelse ($users as $idx => $u)
                                <tr class="hover:bg-slate-50/60 transition-colors user-row" 
                                    data-search="{{ strtolower($u->username . ' ' . $u->role . ' ' . ($u->nama_display ?? '')) }}">
                                    <td class="py-2 px-3 text-center font-medium text-slate-400 text-xs">
                                        {{ $users->firstItem() + $idx }}
                                    </td>
                                    <td class="py-2 px-3">
                                        <p class="font-bold text-[#1E2538] text-xs leading-tight">{{ $u->nama_display }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">User: {{ $u->username }}</p>
                                    </td>
                                    <td class="py-2 px-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold
                                            @if($u->role === 'staf_tu') bg-blue-50 text-blue-700
                                            @elseif($u->role === 'guru') bg-emerald-50 text-emerald-700
                                            @elseif($u->role === 'guru_piket') bg-amber-50 text-amber-700
                                            @elseif($u->role === 'kepala_sekolah') bg-purple-50 text-purple-700
                                            @elseif(str_contains($u->role, 'wakasis')) bg-indigo-50 text-indigo-700
                                            @elseif($u->role === 'satpam') bg-rose-50 text-rose-700
                                            @else bg-slate-100 text-slate-700 @endif">
                                            {{ str_replace('_', ' ', $u->role) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-extrabold {{ $u->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-500' : 'bg-rose-500' }} mr-1"></span>
                                            {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <a href="{{ route('admin.users.edit', $u->id) }}" class="p-1.5 text-[#405078] hover:bg-[#405078]/10 rounded-lg transition-colors" title="Edit Akun">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna {{ addslashes($u->username) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Pengguna">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 italic text-xs">
                                        Tidak ada data pengguna yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BAR (SESUAI GAMBAR 2) -->
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
            foldText.innerText = 'Buka Form Tambah';
        } else {
            formPanel.classList.remove('hidden');
            tableCol.classList.add('lg:col-span-2');
            tableCol.classList.remove('lg:col-span-3');
            foldText.innerText = 'Sembunyikan Form Tambah';
        }
    }

    // Live Instant Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchUsers');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.user-row');
                rows.forEach(row => {
                    const text = row.getAttribute('data-search') || '';
                    if (text.includes(q)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection