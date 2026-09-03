@extends('layouts.app')

@section('title', 'Kelola Pengguna - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Kelola Pengguna (Users)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Atur akun akses masuk sistem Jurnal Esemkita untuk seluruh peran</p>
        </div>
        <div>
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-3.5 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-slate-600"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Alert / Notifikasi -->
    @if (session('success'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs font-semibold flex items-center space-x-2 shadow-2xs">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-xs font-semibold shadow-2xs">
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
        <div id="formPanel" class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-3.5 lg:sticky lg:top-18 z-10">
            <div class="flex items-center space-x-2 pb-2.5 border-b border-slate-100">
                <div class="w-6 h-6 rounded bg-[#1E293B] text-white flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Tambah Pengguna Baru</h3>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">Pilih Peran (Role) *</label>
                    <select name="role" id="role" required
                        class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
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
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B]">
                </div>

                <div>
                    <label for="username" class="block text-xs font-semibold text-slate-700 mb-1">Username Login (NIP / NISN) *</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Masukkan NIP / NISN" required
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B]">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password *</label>
                    <input type="password" name="password" id="password" placeholder="Minimal 4 karakter" required
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B]">
                </div>

                <div>
                    <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP (Opsional)</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B]">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Pengguna</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER PENGGUNA -->
        <div id="tableCol" class="lg:col-span-2 space-y-3.5">
            <!-- DATA TABLE CARD (DENGAN CONTROL BAR SESUAI GAMBAR 5) -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden flex flex-col">
                <!-- Top Control Bar (Search & Counter) -->
                <div class="p-3.5 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="relative flex-1 max-w-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="search" id="liveSearchUsers" value="{{ request('search') }}" placeholder="Cari Username, Nama, atau Peran..." 
                            class="block w-full pl-9 pr-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B] transition-all">
                    </form>

                    <div class="flex items-center space-x-2 text-xs text-slate-500 font-semibold">
                        <span>Total: <strong class="text-slate-900">{{ $users->total() }}</strong> Akun</span>
                        @if (request('search'))
                            <a href="{{ route('admin.users.index') }}" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded text-xs transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Scrollable Table Body -->
                <div class="overflow-x-auto max-h-[calc(100vh-270px)] min-h-[380px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs" id="tableUsers">
                        <thead class="sticky top-0 bg-slate-50 border-b border-slate-200 z-10">
                            <tr class="text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-3.5 w-12 text-center">No</th>
                                <th class="py-3 px-3.5">Nama Lengkap & Username</th>
                                <th class="py-3 px-3.5 w-36">Peran (Role)</th>
                                <th class="py-3 px-3.5 text-center w-24">Status</th>
                                <th class="py-3 px-3.5 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700" id="usersTbody">
                            @forelse ($users as $idx => $u)
                                <tr class="hover:bg-slate-50/80 transition-colors user-row" 
                                    data-search="{{ strtolower($u->username . ' ' . $u->role . ' ' . ($u->nama_display ?? '')) }}">
                                    <td class="py-2.5 px-3.5 text-center font-medium text-slate-400 text-xs tabular-nums">
                                        {{ $users->firstItem() + $idx }}
                                    </td>
                                    <td class="py-2.5 px-3.5">
                                        <p class="font-bold text-slate-900 text-xs leading-tight">{{ $u->nama_display }}</p>
                                        <p class="text-[11px] text-slate-500 font-mono mt-0.5">User: {{ $u->username }}</p>
                                    </td>
                                    <td class="py-2.5 px-3.5">
                                        <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-semibold border
                                            @if($u->role === 'staf_tu') bg-blue-50 text-blue-700 border-blue-200
                                            @elseif($u->role === 'guru') bg-emerald-50 text-emerald-700 border-emerald-200
                                            @elseif($u->role === 'guru_piket') bg-amber-50 text-amber-700 border-amber-200
                                            @elseif($u->role === 'kepala_sekolah') bg-purple-50 text-purple-700 border-purple-200
                                            @elseif(str_contains($u->role, 'wakasis')) bg-indigo-50 text-indigo-700 border-indigo-200
                                            @elseif($u->role === 'satpam') bg-rose-50 text-rose-700 border-rose-200
                                            @else bg-slate-100 text-slate-700 border-slate-200 @endif">
                                            {{ str_replace('_', ' ', $u->role) }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10.5px] font-bold border {{ $u->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                            {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <a href="{{ route('admin.users.edit', $u->id) }}" class="w-7 h-7 rounded border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors" title="Edit Akun">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna {{ addslashes($u->username) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 rounded border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer" title="Hapus Pengguna">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                        Tidak ada data pengguna yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BAR (SESUAI GAMBAR 5) -->
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