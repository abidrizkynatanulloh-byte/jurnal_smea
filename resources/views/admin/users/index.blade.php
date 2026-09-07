@extends('layouts.app')

@section('title', 'Kelola Pengguna - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-3">
    <!-- Header Halaman (Sesuai Ukuran Gambar 1 - FIXED) -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-3">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Kelola Pengguna</h1>
            <span class="px-2.5 py-0.5 text-xs font-semibold bg-slate-200 text-slate-700 rounded-md font-mono tabular-nums">
                {{ $users->total() }} Akun
            </span>
        </div>
        <div>
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="h-9 px-3.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-2 shadow-2xs cursor-pointer">
                <i data-lucide="panel-left-close" class="w-4 h-4 text-slate-500"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Layout Grid Utama (Full Viewport Height - Sesuai Gambar 1) -->
    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-4 items-stretch" id="masterDataGrid">
        
        <!-- BAGIAN 1: FORM TAMBAH USER (UKURAN & PROPORSI SESUAI GAMBAR 1) -->
        <div id="formPanel" class="bg-white border border-slate-200/90 rounded-xl p-4.5 shadow-2xs space-y-3.5 overflow-y-auto h-full max-h-full">
            <div class="flex items-center space-x-2.5 pb-2.5 border-b border-slate-100 shrink-0">
                <div class="w-6 h-6 rounded-md bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                </div>
                <span class="font-bold text-xs text-slate-900 tracking-tight uppercase">TAMBAH AKUN PENGGUNA</span>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">Peran (Role) *</label>
                    <select name="role" id="role" required
                        class="w-full h-10 px-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso, S.Pd" required
                        class="w-full h-10 px-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div>
                    <label for="username" class="block text-xs font-semibold text-slate-700 mb-1">Username Login (NIP / NISN) *</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Masukkan NIP / NISN" required
                        class="w-full h-10 px-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password *</label>
                    <input type="password" name="password" id="password" placeholder="Minimal 4 karakter" required
                        class="w-full h-10 px-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div>
                    <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP (Opsional)</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                        class="w-full h-10 px-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full h-10 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-2 shadow-xs cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Simpan Pengguna</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DATA TABLE CARD (SESUAI GAMBAR 1 & GAMBAR 2) -->
        <div id="tableCol" class="lg:col-span-2 flex flex-col h-full min-h-0">
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden flex flex-col h-full min-h-0">
                <!-- Top Control Bar (Search) -->
                <div class="shrink-0 px-4 py-3 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="search" id="liveSearchUsers" value="{{ request('search') }}" placeholder="Cari Username, Nama, atau Peran..." 
                            class="w-full h-10 pl-9 pr-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                    </form>

                    <div class="flex items-center space-x-2 text-xs text-slate-500 font-medium shrink-0">
                        @if (request('search'))
                            <a href="{{ route('admin.users.index') }}" class="h-10 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold flex items-center transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Table Body (INTERNAL SCROLL WITH STICKY THEAD) -->
                <div class="flex-1 overflow-y-auto overflow-x-auto min-h-0">
                    <table class="w-full text-left border-collapse text-xs" id="tableUsers">
                        <thead class="sticky top-0 bg-white border-b border-slate-200 z-10 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="py-3 px-4 w-12 text-center bg-white">No</th>
                                <th class="py-3 px-4 bg-white">NAMA LENGKAP & USERNAME</th>
                                <th class="py-3 px-4 w-40 bg-white">PERAN (ROLE)</th>
                                <th class="py-3 px-4 text-center w-28 bg-white">STATUS</th>
                                <th class="py-3 px-4 text-center w-24 bg-white">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700" id="usersTbody">
                            @forelse ($users as $idx => $u)
                                <tr class="hover:bg-slate-50/80 transition-colors user-row" 
                                    data-search="{{ strtolower($u->username . ' ' . $u->role . ' ' . ($u->nama_display ?? '')) }}">
                                    <td class="py-3 px-4 text-center font-medium text-slate-400 text-xs tabular-nums">
                                        {{ $users->firstItem() + $idx }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <p class="font-semibold text-slate-900 text-xs leading-tight">{{ $u->nama_display }}</p>
                                        <p class="text-[11px] text-slate-500 font-mono mt-0.5">User: {{ $u->username }}</p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-medium border
                                            @if($u->role === 'staf_tu') bg-slate-100 text-slate-800 border-slate-200
                                            @elseif($u->role === 'guru') bg-emerald-50 text-emerald-700 border-emerald-200
                                            @elseif($u->role === 'guru_piket') bg-amber-50 text-amber-700 border-amber-200
                                            @elseif($u->role === 'kepala_sekolah') bg-purple-50 text-purple-700 border-purple-200
                                            @elseif(str_contains($u->role, 'wakasis')) bg-indigo-50 text-indigo-700 border-indigo-200
                                            @elseif($u->role === 'satpam') bg-rose-50 text-rose-700 border-rose-200
                                            @else bg-slate-100 text-slate-700 border-slate-200 @endif">
                                            {{ str_replace('_', ' ', $u->role) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10.5px] font-semibold border {{ $u->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                            {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <a href="{{ route('admin.users.edit', $u->id) }}" class="w-7 h-7 rounded-lg border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors shadow-2xs" title="Edit Akun">
                                                <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna {{ addslashes($u->username) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 rounded-lg border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Hapus Pengguna">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
                                        Tidak ada data pengguna yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Pagination Bar (PERSIS GAMBAR 2) -->
                <div class="shrink-0">
                    <x-pagination-bar :paginator="$users" />
                </div>
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