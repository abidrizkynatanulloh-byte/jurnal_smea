@extends('layouts.app')

@section('title', 'Manajemen Jadwal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <div>
            <h1 class="text-xl font-extrabold text-[#1E2538] tracking-tight">Jadwal Mengajar KBM</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola dan petakan jadwal mengajar guru per hari dan jam pelajaran</p>
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
        
        <!-- BAGIAN 1: FORM TAMBAH JADWAL (STICKY & FOLDABLE) -->
        <div id="formPanel" class="bg-white border border-slate-200 rounded-2xl p-4.5 shadow-xs space-y-3 lg:sticky lg:top-20 z-10 transition-all duration-300">
            <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <i data-lucide="calendar-plus" class="w-4 h-4 text-[#405078]"></i>
                    <h3 class="font-bold text-[#1E2538] text-xs uppercase tracking-wider">Tambah Jadwal Baru</h3>
                </div>
            </div>
            
            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="id_kelas" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kelas *</label>
                    <select name="id_kelas" id="id_kelas" required
                        class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label for="hari" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hari *</label>
                        <select name="hari" id="hari" required
                            class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-1.5">
                        <div>
                            <label for="jam_mulai" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mulai</label>
                            <input type="number" name="jam_mulai" id="jam_mulai" min="1" max="15" value="{{ old('jam_mulai', 1) }}" required
                                class="block w-full px-2 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] text-center focus:outline-none focus:border-[#405078]">
                        </div>
                        <div>
                            <label for="jam_selesai" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Selesai</label>
                            <input type="number" name="jam_selesai" id="jam_selesai" min="1" max="15" value="{{ old('jam_selesai', 2) }}" required
                                class="block w-full px-2 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] text-center focus:outline-none focus:border-[#405078]">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="kode_mapel" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mata Pelajaran *</label>
                    <select name="kode_mapel" id="kode_mapel" required
                        class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach ($mapelList as $m)
                            <option value="{{ $m->kode_mapel }}" {{ old('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                                {{ $m->nama_mapel }} ({{ $m->kode_mapel }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_guru" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Guru Pengampu *</label>
                    <select name="id_guru" id="id_guru" required
                        class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($guruList as $g)
                            <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_ruangan" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ruangan / Lab *</label>
                    <select name="id_ruangan" id="id_ruangan" required
                        class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach ($ruanganList as $r)
                            <option value="{{ $r->id_ruangan }}" {{ old('id_ruangan') == $r->id_ruangan ? 'selected' : '' }}>
                                {{ $r->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-1.5">
                    <button type="submit" class="w-full py-2 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Jadwal</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER JADWAL -->
        <div id="tableCol" class="lg:col-span-2 space-y-3.5">
            
            <!-- STICKY FILTER CARD (DENGAN LIVE INSTANT SEARCH) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-3.5 shadow-xs sticky top-20 z-20">
                <form action="{{ route('admin.jadwal.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pencarian Cepat (Ketik Langsung Tersaring)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-[#8697C3]"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchJadwal" value="{{ request('search') }}" placeholder="Ketik Kelas, Guru, atau Mapel..." 
                                class="block w-full pl-8.5 pr-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                        </div>
                    </div>

                    <div class="w-full sm:w-36">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hari</label>
                        <select name="hari" onchange="this.form.submit()" class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                            <option value="">Semua Hari</option>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                                <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full sm:w-40">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kelas</label>
                        <select name="id_kelas" onchange="this.form.submit()" class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if (request('search') || request('hari') || request('id_kelas'))
                        <a href="{{ route('admin.jadwal.index') }}" class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- TABLE CARD (FIXED SCROLL DENGAN STICKY HEADER) -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden flex flex-col">
                <div class="overflow-x-auto max-h-[calc(100vh-270px)] min-h-[380px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs" id="tableJadwal">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-slate-500 font-semibold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-2.5 px-3 w-28">Waktu</th>
                                <th class="py-2.5 px-3 w-32">Kelas & Ruang</th>
                                <th class="py-2.5 px-3">Mata Pelajaran</th>
                                <th class="py-2.5 px-3">Guru Pengampu</th>
                                <th class="py-2.5 px-3 text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600" id="jadwalTbody">
                            @forelse ($jadwalList as $j)
                                <tr class="hover:bg-slate-50/60 transition-colors jadwal-row" 
                                    data-search="{{ strtolower(($j->kelas ? $j->kelas->nama_kelas : '') . ' ' . ($j->guru ? $j->guru->nama_guru : '') . ' ' . ($j->mapel ? $j->mapel->nama_mapel : '') . ' ' . $j->hari) }}">
                                    <td class="py-2 px-3">
                                        <div class="flex items-center space-x-1.5">
                                            <span class="px-1.5 py-0.5 bg-[#405078]/10 text-[#405078] rounded font-bold text-[10.5px]">
                                                {{ $j->hari }}
                                            </span>
                                            <span class="text-slate-500 font-medium text-[11px]">Jam {{ $j->jam_mulai }}–{{ $j->jam_selesai }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2 px-3">
                                        <span class="px-1.5 py-0.5 bg-slate-100 text-slate-700 rounded text-[11px] font-bold">{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</span>
                                    </td>
                                    <td class="py-2 px-3 font-bold text-[#405078] text-xs">
                                        {{ $j->mapel ? $j->mapel->nama_mapel : '-' }}
                                    </td>
                                    <td class="py-2 px-3 font-medium text-[#1E2538] text-xs leading-tight">
                                        {{ $j->guru ? $j->guru->nama_guru : '-' }}
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <form action="{{ route('admin.jadwal.destroy', $j->id_jadwal) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Jadwal">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 italic text-xs">
                                        Tidak ada data jadwal yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BAR (SESUAI GAMBAR 2) -->
                <x-pagination-bar :paginator="$jadwalList" />
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
        const searchInput = document.getElementById('liveSearchJadwal');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.jadwal-row');
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