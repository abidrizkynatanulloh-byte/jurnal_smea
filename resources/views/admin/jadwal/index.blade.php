@extends('layouts.app')

@section('title', 'Manajemen Jadwal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Jadwal Mengajar KBM</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola dan petakan jadwal mengajar guru per hari dan jam pelajaran</p>
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
        
        <!-- BAGIAN 1: FORM TAMBAH JADWAL (STICKY & FOLDABLE) -->
        <div id="formPanel" class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-3.5 lg:sticky lg:top-18 z-10">
            <div class="flex items-center space-x-2 pb-2.5 border-b border-slate-100">
                <div class="w-6 h-6 rounded bg-[#1E293B] text-white flex items-center justify-center">
                    <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Tambah Jadwal Baru</h3>
            </div>
            
            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="id_kelas" class="block text-xs font-semibold text-slate-700 mb-1">Kelas *</label>
                    <select name="id_kelas" id="id_kelas" required
                        class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
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
                        <label for="hari" class="block text-xs font-semibold text-slate-700 mb-1">Hari *</label>
                        <select name="hari" id="hari" required
                            class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-1.5">
                        <div>
                            <label for="jam_mulai" class="block text-xs font-semibold text-slate-700 mb-1">Mulai</label>
                            <input type="number" name="jam_mulai" id="jam_mulai" min="1" max="15" value="{{ old('jam_mulai', 1) }}" required
                                class="block w-full px-2 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 text-center focus:outline-none focus:border-[#1E293B]">
                        </div>
                        <div>
                            <label for="jam_selesai" class="block text-xs font-semibold text-slate-700 mb-1">Selesai</label>
                            <input type="number" name="jam_selesai" id="jam_selesai" min="1" max="15" value="{{ old('jam_selesai', 2) }}" required
                                class="block w-full px-2 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 text-center focus:outline-none focus:border-[#1E293B]">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="kode_mapel" class="block text-xs font-semibold text-slate-700 mb-1">Mata Pelajaran *</label>
                    <select name="kode_mapel" id="kode_mapel" required
                        class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach ($mapelList as $m)
                            <option value="{{ $m->kode_mapel }}" {{ old('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                                {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_guru" class="block text-xs font-semibold text-slate-700 mb-1">Guru Pengampu *</label>
                    <select name="id_guru" id="id_guru" required
                        class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($guruList as $g)
                            <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_ruangan" class="block text-xs font-semibold text-slate-700 mb-1">Ruangan Kelas / Lab</label>
                    <select name="id_ruangan" id="id_ruangan"
                        class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                        <option value="">-- Default Ruangan Kelas --</option>
                        @foreach ($ruanganList as $r)
                            <option value="{{ $r->id_ruangan }}" {{ old('id_ruangan') == $r->id_ruangan ? 'selected' : '' }}>
                                {{ $r->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Jadwal</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER JADWAL -->
        <div id="tableCol" class="lg:col-span-2 space-y-3.5">
            <!-- DATA TABLE CARD (DENGAN CONTROL BAR SESUAI GAMBAR 5) -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden flex flex-col">
                <!-- Top Control Bar (Search, Filters, Counter) -->
                <div class="p-3.5 border-b border-slate-200 bg-white flex flex-col sm:flex-row items-center justify-between gap-3">
                    <form action="{{ route('admin.jadwal.index') }}" method="GET" class="flex flex-1 flex-col sm:flex-row items-center gap-2.5 w-full">
                        <div class="relative flex-1 w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchJadwal" value="{{ request('search') }}" placeholder="Cari Kelas, Guru, atau Mapel..." 
                                class="block w-full pl-9 pr-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B] transition-all">
                        </div>

                        <div class="w-full sm:w-32">
                            <select name="hari" onchange="this.form.submit()" class="block w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                                <option value="">Semua Hari</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                                    <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full sm:w-36">
                            <select name="id_kelas" onchange="this.form.submit()" class="block w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (request('search') || request('hari') || request('id_kelas'))
                            <a href="{{ route('admin.jadwal.index') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold transition-colors">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Scrollable Table Body -->
                <div class="overflow-x-auto max-h-[calc(100vh-270px)] min-h-[380px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs" id="tableJadwal">
                        <thead class="sticky top-0 bg-slate-50 border-b border-slate-200 z-10">
                            <tr class="text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-3.5 w-32">Waktu</th>
                                <th class="py-3 px-3.5 w-32">Kelas & Ruang</th>
                                <th class="py-3 px-3.5">Mata Pelajaran</th>
                                <th class="py-3 px-3.5">Guru Pengampu</th>
                                <th class="py-3 px-3.5 text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700" id="jadwalTbody">
                            @forelse ($jadwalList as $j)
                                <tr class="hover:bg-slate-50/80 transition-colors jadwal-row" 
                                    data-search="{{ strtolower(($j->kelas ? $j->kelas->nama_kelas : '') . ' ' . ($j->guru ? $j->guru->nama_guru : '') . ' ' . ($j->mapel ? $j->mapel->nama_mapel : '') . ' ' . $j->hari) }}">
                                    <td class="py-2.5 px-3.5">
                                        <div class="flex items-center space-x-1.5">
                                            <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-800 rounded font-bold text-[10.5px]">
                                                {{ $j->hari }}
                                            </span>
                                            <span class="text-slate-600 font-semibold text-xs">Jam {{ $j->jam_mulai }}–{{ $j->jam_selesai }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-3.5">
                                        <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-800 rounded text-xs font-bold">{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</span>
                                        <span class="text-[11px] text-slate-500 block mt-0.5">{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</span>
                                    </td>
                                    <td class="py-2.5 px-3.5 font-bold text-slate-900 text-xs">
                                        {{ $j->mapel ? $j->mapel->nama_mapel : '-' }}
                                    </td>
                                    <td class="py-2.5 px-3.5 font-medium text-slate-800 text-xs leading-tight">
                                        {{ $j->guru ? $j->guru->nama_guru : '-' }}
                                    </td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        <form action="{{ route('admin.jadwal.destroy', $j->id_jadwal) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-7 h-7 rounded border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer" title="Hapus Jadwal">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                        Tidak ada data jadwal yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BAR (SESUAI GAMBAR 5) -->
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