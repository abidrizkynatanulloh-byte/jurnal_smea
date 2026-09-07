@extends('layouts.app')

@section('title', 'Jadwal Mengajar KBM - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-2.5">
    <!-- Header Halaman (Sesuai Gambar 1 - FIXED) -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Jadwal Mengajar KBM</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola dan petakan jadwal mengajar guru per hari dan jam pelajaran</p>
        </div>
        <div>
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="h-8 px-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="panel-left-close" class="w-3.5 h-3.5 text-slate-500"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Layout Grid Utama (Full Viewport Height - Sesuai Gambar 1) -->
    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-3.5 items-stretch" id="masterDataGrid">
        
        <!-- BAGIAN 1: FORM TAMBAH JADWAL (UKURAN & PROPORSI SESUAI GAMBAR 1) -->
        <div id="formPanel" class="bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-2xs space-y-2.5 overflow-y-auto h-full max-h-full">
            <div class="flex items-center space-x-2 pb-2 border-b border-slate-100 shrink-0">
                <div class="w-5 h-5 rounded-md bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="calendar-plus" class="w-3 h-3"></i>
                </div>
                <span class="font-bold text-xs text-slate-900 tracking-tight uppercase">TAMBAH JADWAL BARU</span>
            </div>
            
            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-2">
                @csrf

                <div>
                    <label for="id_kelas" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Kelas *</label>
                    <select name="id_kelas" id="id_kelas" required
                        class="searchable-select w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="hari" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Hari *</label>
                        <select name="hari" id="hari" required
                            class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-1.5">
                        <div>
                            <label for="jam_mulai" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Mulai</label>
                            <input type="number" name="jam_mulai" id="jam_mulai" min="1" max="15" value="{{ old('jam_mulai', 1) }}" required
                                class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 text-center font-mono focus:outline-none focus:border-slate-800">
                        </div>
                        <div>
                            <label for="jam_selesai" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Selesai</label>
                            <input type="number" name="jam_selesai" id="jam_selesai" min="1" max="15" value="{{ old('jam_selesai', 2) }}" required
                                class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 text-center font-mono focus:outline-none focus:border-slate-800">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="kode_mapel" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Mata Pelajaran *</label>
                    <select name="kode_mapel" id="kode_mapel" required
                        class="searchable-select w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach ($mapelList as $m)
                            <option value="{{ $m->kode_mapel }}" {{ old('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                                {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_guru" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Guru Pengampu *</label>
                    <select name="id_guru" id="id_guru" required
                        class="searchable-select w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($guruList as $g)
                            <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_ruangan" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Ruangan Kelas / Lab</label>
                    <select name="id_ruangan" id="id_ruangan"
                        class="searchable-select w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        <option value="">-- Default Ruangan Kelas --</option>
                        @foreach ($ruanganList as $r)
                            <option value="{{ $r->id_ruangan }}" {{ old('id_ruangan') == $r->id_ruangan ? 'selected' : '' }}>
                                {{ $r->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-1">
                    <button type="submit" class="w-full h-8.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Simpan Jadwal</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DATA TABLE CARD (SESUAI GAMBAR 1) -->
        <div id="tableCol" class="lg:col-span-2 flex flex-col h-full min-h-0">
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden flex flex-col h-full min-h-0">
                <!-- Top Control Bar (Search + Semua Hari + Semua Kelas) -->
                <div class="shrink-0 px-3.5 py-2 border-b border-slate-200 bg-white flex flex-col sm:flex-row items-center justify-between gap-2">
                    <form action="{{ route('admin.jadwal.index') }}" method="GET" class="flex flex-1 flex-col sm:flex-row items-center gap-2 w-full">
                        <div class="relative flex-1 w-full">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchJadwal" value="{{ request('search') }}" placeholder="Cari Kelas, Guru, atau Mapel..." 
                                class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                        </div>

                        <div class="w-full sm:w-32">
                            <select name="hari" onchange="this.form.submit()" class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                                <option value="">Semua Hari</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                                    <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full sm:w-36">
                            <select name="id_kelas" onchange="this.form.submit()" class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (request('search') || request('hari') || request('id_kelas'))
                            <a href="{{ route('admin.jadwal.index') }}" class="h-8 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold flex items-center transition-colors shrink-0">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table Body (INTERNAL SCROLL WITH STICKY THEAD - SESUAI GAMBAR 1) -->
                <div class="flex-1 overflow-y-auto overflow-x-auto min-h-0">
                    <table class="w-full text-left border-collapse text-xs" id="tableJadwal">
                        <thead class="sticky top-0 bg-white border-b border-slate-200 z-10 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="py-2.5 px-3.5 w-32 bg-white">WAKTU</th>
                                <th class="py-2.5 px-3.5 w-32 bg-white">KELAS & RUANG</th>
                                <th class="py-2.5 px-3.5 bg-white">MATA PELAJARAN</th>
                                <th class="py-2.5 px-3.5 bg-white">GURU PENGAMPU</th>
                                <th class="py-2.5 px-3.5 text-center w-16 bg-white">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700" id="jadwalTbody">
                            @forelse ($jadwalList as $j)
                                <tr class="hover:bg-slate-50/80 transition-colors jadwal-row" 
                                    data-search="{{ strtolower(($j->kelas ? $j->kelas->nama_kelas : '') . ' ' . ($j->guru ? $j->guru->nama_guru : '') . ' ' . ($j->mapel ? $j->mapel->nama_mapel : '') . ' ' . $j->hari) }}">
                                    <td class="py-2 px-3.5">
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-800 rounded text-[11px] font-semibold">
                                                {{ $j->hari }}
                                            </span>
                                            <span class="text-slate-600 font-medium text-xs">Jam {{ $j->jam_mulai }}–{{ $j->jam_selesai }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2 px-3.5">
                                        <div class="font-bold text-slate-900 text-xs">{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</div>
                                    </td>
                                    <td class="py-2 px-3.5 font-semibold text-slate-900 text-xs">
                                        {{ $j->mapel ? $j->mapel->nama_mapel : '-' }}
                                    </td>
                                    <td class="py-2 px-3.5 font-medium text-slate-800 text-xs leading-tight">
                                        {{ $j->guru ? $j->guru->nama_guru : '-' }}
                                    </td>
                                    <td class="py-2 px-3.5 text-center">
                                        <form action="{{ route('admin.jadwal.destroy', $j->id_jadwal) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Hapus Jadwal">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
                                        Tidak ada data jadwal yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Pagination Bar (Sesuai Gambar 1 & Gambar 2) -->
                <div class="shrink-0">
                    <x-pagination-bar :paginator="$jadwalList" />
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