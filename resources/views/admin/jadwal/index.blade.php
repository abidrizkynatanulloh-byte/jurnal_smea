@extends('layouts.app')

@section('title', 'Jadwal Mengajar KBM - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-4">
    <!-- Header Halaman -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white dark:bg-[#1C2433] p-4 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xs">
        <div class="space-y-0.5">
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight flex items-center space-x-2.5">
                <div class="p-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 shrink-0">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <span>Jadwal Mengajar KBM</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Kelola dan petakan jadwal mengajar guru per hari, kelas, dan jam pelajaran</p>
        </div>
        <div class="flex items-center space-x-2 shrink-0 overflow-x-auto pb-1 sm:pb-0 scrollbar-none">
            <button type="button" onclick="openTambahJadwalModal()"
                class="h-10 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shadow-2xs cursor-pointer whitespace-nowrap shrink-0">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>+ Tambah Jadwal Baru</span>
            </button>

            <button type="button" onclick="openImportJadwalModal()"
                class="h-10 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shadow-2xs cursor-pointer whitespace-nowrap shrink-0">
                <i data-lucide="file-up" class="w-4 h-4"></i>
                <span>Import CSV</span>
            </button>
        </div>
    </div>

    <!-- MAIN CONTAINER (FULL WIDTH WITH PROPER INNER PADDING) -->
    <div class="flex-1 min-h-0 bg-white dark:bg-[#1C2433] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xs overflow-hidden flex flex-col">
        
        <!-- TOP CONTROL BAR -->
        <div class="shrink-0 p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-[#1C2433] space-y-3.5">
            
            <!-- Row 1: Tingkat Pills + View Mode Switcher -->
            <div class="flex items-center justify-between gap-3 overflow-x-auto">
                <!-- Tingkat Filter Pills -->
                <div class="flex items-center space-x-2 overflow-x-auto pb-0.5 shrink-0">
                    <a href="{{ route('admin.jadwal.index', array_filter(['hari' => request('hari'), 'id_kelas' => request('id_kelas'), 'search' => request('search')])) }}" 
                        class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 flex items-center space-x-1 {{ empty($tingkat) ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                        <span>Semua Kelas</span>
                    </a>
                    <a href="{{ route('admin.jadwal.index', array_filter(['tingkat' => 'X', 'hari' => request('hari'), 'id_kelas' => request('id_kelas'), 'search' => request('search')])) }}" 
                        class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 flex items-center space-x-1 {{ $tingkat === 'X' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                        <span>Kelas X</span>
                    </a>
                    <a href="{{ route('admin.jadwal.index', array_filter(['tingkat' => 'XI', 'hari' => request('hari'), 'id_kelas' => request('id_kelas'), 'search' => request('search')])) }}" 
                        class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 flex items-center space-x-1 {{ $tingkat === 'XI' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                        <span>Kelas XI</span>
                    </a>
                    <a href="{{ route('admin.jadwal.index', array_filter(['tingkat' => 'XII', 'hari' => request('hari'), 'id_kelas' => request('id_kelas'), 'search' => request('search')])) }}" 
                        class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 flex items-center space-x-1 {{ $tingkat === 'XII' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                        <span>Kelas XII</span>
                    </a>
                </div>

                <!-- View Toggle Buttons (Grid vs Table) -->
                <div class="flex items-center space-x-1 bg-slate-100 dark:bg-[#141C29] p-1 rounded-xl border border-slate-200 dark:border-slate-800 shrink-0">
                    <button type="button" onclick="setViewMode('grid')" id="btnViewGrid" class="h-8 px-3 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow-2xs flex items-center space-x-1.5 cursor-pointer">
                        <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Grid Kelas</span>
                    </button>
                    <button type="button" onclick="setViewMode('table')" id="btnViewTable" class="h-8 px-3 rounded-lg text-xs font-semibold transition-all text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 flex items-center space-x-1.5 cursor-pointer">
                        <i data-lucide="table" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Tabel Sesi</span>
                    </button>
                </div>
            </div>

            <!-- Row 2: Hari Quick Selector Pills -->
            <div class="flex items-center space-x-2 overflow-x-auto pb-0.5 border-t border-slate-100 dark:border-slate-800/80 pt-3">
                <span class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider shrink-0 mr-1 flex items-center space-x-1">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5 text-blue-500"></i>
                    <span>HARI:</span>
                </span>

                <a href="{{ route('admin.jadwal.index', array_filter(['hari' => $namaHariIni, 'tingkat' => $tingkat, 'search' => request('search')])) }}"
                    class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all shrink-0 flex items-center space-x-1.5 {{ $hariFilter === $namaHariIni ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 hover:bg-amber-100 border border-amber-200/70 dark:border-amber-800/60' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                    <span>Hari Ini ({{ $namaHariIni }})</span>
                </a>

                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                    <a href="{{ route('admin.jadwal.index', array_filter(['hari' => $h, 'tingkat' => $tingkat, 'search' => request('search')])) }}"
                        class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all shrink-0 {{ $hariFilter === $h ? 'bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                        {{ $h }}
                    </a>
                @endforeach

                <a href="{{ route('admin.jadwal.index', array_filter(['hari' => 'all', 'tingkat' => $tingkat, 'search' => request('search')])) }}"
                    class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all shrink-0 {{ empty($hariFilter) ? 'bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                    Semua Hari
                </a>
            </div>

            <!-- Row 3: Search input + Reset Button (NEAT SPACING & NO ICON OVERLAP) -->
            <form action="{{ route('admin.jadwal.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2.5 w-full pt-1">
                @if(!empty($tingkat))
                    <input type="hidden" name="tingkat" value="{{ $tingkat }}">
                @endif
                @if(!empty($hariFilter))
                    <input type="hidden" name="hari" value="{{ $hariFilter }}">
                @endif

                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="search" id="liveSearchJadwal" value="{{ request('search') }}" placeholder="Cari nama kelas, guru pengampu, atau mata pelajaran..." 
                        class="w-full h-10 pl-10 pr-4 bg-slate-50 dark:bg-[#141C29] border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors">
                </div>

                @if (request('search') || request('hari') || request('id_kelas') || request('tingkat'))
                    <a href="{{ route('admin.jadwal.index') }}" class="h-10 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-[#232D3F] dark:hover:bg-[#2C384E] text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold flex items-center transition-colors shrink-0 space-x-1.5">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset Filter</span>
                    </a>
                @endif
            </form>
        </div>

        <!-- MAIN DISPLAY AREA WITH GENEROUS INNER PADDING (NOT MEPET TO BORDER) -->
        <div class="flex-1 overflow-y-auto min-h-0 p-4 sm:p-6" id="jadwalContentContainer">
            
            <!-- 1. GRID CARD VIEW (PER KELAS HASIL FILTER HARI) -->
            <div id="jadwalGridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @forelse ($kelasList as $kelas)
                    @php
                        $sessions = $jadwalGroupedByKelas->get($kelas->id_kelas, collect());
                        $searchData = strtolower($kelas->nama_kelas . ' ' . $sessions->map(fn($s) => ($s->guru->nama_guru ?? '') . ' ' . ($s->mapel->nama_mapel ?? ''))->implode(' '));
                    @endphp
                    <div class="bg-white dark:bg-[#161D2B] border-2 border-slate-200/90 dark:border-slate-700/80 rounded-2xl p-4 sm:p-4.5 shadow-2xs space-y-4 flex flex-col justify-between hover:border-blue-500 dark:hover:border-blue-500 transition-all jadwal-grid-card"
                        data-search="{{ $searchData }}">
                        
                        <div class="space-y-3.5">
                            <!-- Card Header: Nama Kelas & Sesi Info -->
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs shrink-0 border border-blue-200/60 dark:border-blue-800/60">
                                        <i data-lucide="school" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-sm text-slate-900 dark:text-slate-100 tracking-tight">{{ $kelas->nama_kelas }}</h3>
                                        <p class="text-[11px] text-slate-400 font-medium">
                                            {{ $hariFilter ? "Jadwal Hari {$hariFilter}" : 'Seluruh Hari' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-1.5">
                                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold border {{ $sessions->count() > 0 ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/80' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border-slate-200 dark:border-slate-700' }}">
                                        {{ $sessions->count() }} Sesi
                                    </span>
                                    <button type="button" onclick="openTambahJadwalModal({{ $kelas->id_kelas }})" title="Tambah Sesi untuk Kelas ini" 
                                        class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-[#232D3F] hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 text-slate-700 dark:text-slate-200 flex items-center justify-center transition-all cursor-pointer border border-slate-200/80 dark:border-slate-700/70">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- List Sesi Mengajar di Kelas ini -->
                            <div class="space-y-2.5">
                                @forelse ($sessions as $j)
                                    <div class="p-3.5 rounded-xl bg-slate-50/80 dark:bg-[#1E2636] border border-slate-200/90 dark:border-slate-700/80 space-y-2 hover:border-slate-300 dark:hover:border-slate-600 transition-all relative group shadow-2xs">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-1.5">
                                                <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/60 text-blue-800 dark:text-blue-300 font-bold text-[10px] rounded-md border border-blue-200/60 dark:border-blue-800/60">
                                                    Jam {{ $j->jam_mulai }}–{{ $j->jam_selesai }}
                                                </span>
                                                @if(empty($hariFilter))
                                                    <span class="px-2 py-0.5 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-[10px] rounded-md border border-slate-300/60 dark:border-slate-700">
                                                        {{ $j->hari }}
                                                    </span>
                                                @endif
                                            </div>

                                            <form action="{{ route('admin.jadwal.destroy', $j->id_jadwal) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sesi jadwal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 p-1 transition-colors cursor-pointer rounded-md hover:bg-rose-50 dark:hover:bg-rose-950/40" title="Hapus Sesi">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-slate-100 leading-snug">
                                            {{ $j->mapel ? $j->mapel->nama_mapel : '-' }}
                                        </div>

                                        <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 pt-0.5">
                                            <span class="flex items-center space-x-1 truncate max-w-[160px]" title="{{ $j->guru ? $j->guru->nama_guru : '-' }}">
                                                <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                                                <span class="truncate font-semibold">{{ $j->guru ? $j->guru->nama_guru : '-' }}</span>
                                            </span>

                                            @if($j->ruangan)
                                                <span class="px-2 py-0.5 bg-slate-200/80 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-md text-[10px] font-bold border border-slate-300/50 dark:border-slate-700">
                                                    {{ $j->ruangan->nama_ruangan }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-6 text-center text-slate-400 dark:text-slate-500 italic text-[11px] bg-slate-50/50 dark:bg-slate-900/30 rounded-xl border border-dashed border-slate-200 dark:border-slate-800 space-y-1.5">
                                        <p>Belum ada jadwal mengajar {{ $hariFilter ? "hari {$hariFilter}" : '' }}</p>
                                        <button type="button" onclick="openTambahJadwalModal({{ $kelas->id_kelas }})" class="text-blue-600 dark:text-blue-400 hover:underline font-bold text-[11px] inline-flex items-center space-x-1 mt-0.5">
                                            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                                            <span>Tambah Sesi</span>
                                        </button>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-slate-400 italic text-xs space-y-2">
                        <i data-lucide="inbox" class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-600"></i>
                        <p>Tidak ada kelas yang sesuai dengan filter atau kata kunci pencarian.</p>
                    </div>
                @endforelse
            </div>

            <!-- 2. TABLE VIEW (TABEL HAFALAN SESI JADWAL) -->
            <div id="jadwalTableView" class="hidden rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-white dark:bg-[#1C2433]">
                <table class="w-full text-left border-collapse text-xs" id="tableJadwal">
                    <thead class="sticky top-0 bg-slate-50 dark:bg-[#141C29] border-b border-slate-200 dark:border-slate-800 z-10 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 w-32">WAKTU</th>
                            <th class="py-3.5 px-4 w-36">KELAS & RUANG</th>
                            <th class="py-3.5 px-4">MATA PELAJARAN</th>
                            <th class="py-3.5 px-4">GURU PENGAMPU</th>
                            <th class="py-3.5 px-4 text-center w-20">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300" id="jadwalTbody">
                        @forelse ($jadwalList as $j)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors jadwal-row" 
                                data-search="{{ strtolower(($j->kelas ? $j->kelas->nama_kelas : '') . ' ' . ($j->guru ? $j->guru->nama_guru : '') . ' ' . ($j->mapel ? $j->mapel->nama_mapel : '') . ' ' . $j->hari) }}">
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-md text-[11px] font-semibold">
                                            {{ $j->hari }}
                                        </span>
                                        <span class="text-slate-600 dark:text-slate-400 font-medium text-xs">Jam {{ $j->jam_mulai }}–{{ $j->jam_selesai }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900 dark:text-slate-100 text-xs">{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</div>
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-900 dark:text-slate-100 text-xs">
                                    {{ $j->mapel ? $j->mapel->nama_mapel : '-' }}
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-800 dark:text-slate-200 text-xs leading-tight">
                                    {{ $j->guru ? $j->guru->nama_guru : '-' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <form action="{{ route('admin.jadwal.destroy', $j->id_jadwal) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-7.5 h-7.5 mx-auto rounded-lg border border-slate-200 dark:border-slate-700 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Hapus Jadwal">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
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

                <!-- Bottom Pagination Bar -->
                <div class="shrink-0 border-t border-slate-200 dark:border-slate-800">
                    <x-pagination-bar :paginator="$jadwalList" />
                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL POPUP TAMBAH JADWAL BARU -->
<div id="modalTambahJadwal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#181F2C] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-5 sm:p-6 space-y-4">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <span class="font-extrabold text-slate-900 dark:text-slate-100 text-sm uppercase tracking-tight flex items-center space-x-2.5">
                <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center">
                    <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                </div>
                <span>Tambah Jadwal Baru</span>
            </span>
            <button type="button" onclick="closeTambahJadwalModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4.5 h-4.5"></i>
            </button>
        </div>

        <!-- Form Tambah -->
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <div>
                <label for="modal_id_kelas" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kelas *</label>
                <select name="id_kelas" id="modal_id_kelas" required
                    class="w-full h-9.5 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($allKelasList as $k)
                        <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="modal_hari" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Hari *</label>
                    <select name="hari" id="modal_hari" required
                        class="w-full h-9.5 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                            <option value="{{ $h }}" {{ old('hari', $hariFilter ?: $defaultHari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="modal_jam_mulai" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Mulai</label>
                        <input type="number" name="jam_mulai" id="modal_jam_mulai" min="1" max="15" value="{{ old('jam_mulai', 1) }}" required
                            class="w-full h-9.5 px-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 text-center font-mono focus:outline-none focus:border-blue-600">
                    </div>
                    <div>
                        <label for="modal_jam_selesai" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Selesai</label>
                        <input type="number" name="jam_selesai" id="modal_jam_selesai" min="1" max="15" value="{{ old('jam_selesai', 2) }}" required
                            class="w-full h-9.5 px-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 text-center font-mono focus:outline-none focus:border-blue-600">
                    </div>
                </div>
            </div>

            <div>
                <label for="modal_kode_mapel" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Mata Pelajaran *</label>
                <select name="kode_mapel" id="modal_kode_mapel" required
                    class="w-full h-9.5 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach ($mapelList as $m)
                        <option value="{{ $m->kode_mapel }}" {{ old('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                            {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="modal_id_guru" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Guru Pengampu *</label>
                <select name="id_guru" id="modal_id_guru" required
                    class="w-full h-9.5 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
                    <option value="">-- Pilih Guru --</option>
                    @foreach ($guruList as $g)
                        <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                            {{ $g->nama_guru }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="modal_id_ruangan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Ruangan Kelas / Lab</label>
                <select name="id_ruangan" id="modal_id_ruangan"
                    class="w-full h-9.5 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
                    <option value="">-- Default Ruangan Kelas --</option>
                    @foreach ($ruanganList as $r)
                        <option value="{{ $r->id_ruangan }}" {{ old('id_ruangan') == $r->id_ruangan ? 'selected' : '' }}>
                            {{ $r->nama_ruangan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end space-x-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeTambahJadwalModal()" class="h-10 px-5 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-10 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-xs hover:shadow-md flex items-center justify-center space-x-1.5">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Simpan Jadwal</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL IMPORT JADWAL KBM VIA CSV -->
<div id="modalImportJadwal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#181F2C] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <span class="font-bold text-slate-900 dark:text-slate-100 text-sm uppercase tracking-tight flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center">
                    <i data-lucide="file-up" class="w-3.5 h-3.5"></i>
                </div>
                <span>Import Jadwal Mengajar KBM (CSV)</span>
            </span>
            <button type="button" onclick="closeImportJadwalModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4.5 h-4.5"></i>
            </button>
        </div>

        <form action="{{ route('admin.jadwal.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 rounded-xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-900 dark:text-emerald-300">Petunjuk Format CSV:</span>
                    <a href="{{ route('admin.jadwal.template') }}" class="inline-flex items-center space-x-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 underline">
                        <i data-lucide="download" class="w-3 h-3"></i>
                        <span>Download Template</span>
                    </a>
                </div>
                <ul class="text-[11px] text-emerald-800 dark:text-emerald-300/90 list-disc list-inside space-y-1">
                    <li>Pemisah kolom: koma (<code>,</code>) atau titik koma (<code>;</code>).</li>
                    <li>Header: <code>kelas</code>, <code>hari</code>, <code>jam_mulai</code>, <code>jam_selesai</code>, <code>mapel</code>, <code>guru</code>, <code>ruangan</code>.</li>
                    <li><code>guru</code> dapat diisi NIP atau Nama Lengkap Guru.</li>
                    <li>Jika slot jadwal kelas pada hari & jam tersebut sudah ada, jadwal akan <strong>di-update</strong>.</li>
                </ul>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pilih File Excel (.xls) / CSV *</label>
                <input type="file" name="file_csv" accept=".xls, .xlsx, .csv, .txt" required
                    class="w-full text-xs text-slate-700 dark:text-slate-300 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeImportJadwalModal()" class="h-10 px-5 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-10 px-6 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-xs hover:shadow-md flex items-center justify-center space-x-2">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    <span>Proses Import</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openTambahJadwalModal(idKelas = null) {
        const modal = document.getElementById('modalTambahJadwal');
        if (modal) {
            modal.classList.remove('hidden');
            if (idKelas) {
                const select = document.getElementById('modal_id_kelas');
                if (select) select.value = idKelas;
            }
        }
    }

    function closeTambahJadwalModal() {
        const modal = document.getElementById('modalTambahJadwal');
        if (modal) modal.classList.add('hidden');
    }

    function openImportJadwalModal() {
        document.getElementById('modalImportJadwal').classList.remove('hidden');
    }

    function closeImportJadwalModal() {
        document.getElementById('modalImportJadwal').classList.add('hidden');
    }

    function setViewMode(mode) {
        const gridView = document.getElementById('jadwalGridView');
        const tableView = document.getElementById('jadwalTableView');
        const btnGrid = document.getElementById('btnViewGrid');
        const btnTable = document.getElementById('btnViewTable');

        if (mode === 'grid') {
            if (gridView) gridView.classList.remove('hidden');
            if (tableView) tableView.classList.add('hidden');
            if (btnGrid) btnGrid.className = 'h-8 px-3 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow-2xs flex items-center space-x-1.5 cursor-pointer';
            if (btnTable) btnTable.className = 'h-8 px-3 rounded-lg text-xs font-semibold transition-all text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 flex items-center space-x-1.5 cursor-pointer';
            localStorage.setItem('jadwalViewMode', 'grid');
        } else {
            if (gridView) gridView.classList.add('hidden');
            if (tableView) tableView.classList.remove('hidden');
            if (btnGrid) btnGrid.className = 'h-8 px-3 rounded-lg text-xs font-semibold transition-all text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 flex items-center space-x-1.5 cursor-pointer';
            if (btnTable) btnTable.className = 'h-8 px-3 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow-2xs flex items-center space-x-1.5 cursor-pointer';
            localStorage.setItem('jadwalViewMode', 'table');
        }
    }

    // Live Instant Search
    document.addEventListener('DOMContentLoaded', function() {
        const savedMode = localStorage.getItem('jadwalViewMode') || 'grid';
        setViewMode(savedMode);

        const searchInput = document.getElementById('liveSearchJadwal');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                
                // Live filter Grid Cards
                document.querySelectorAll('.jadwal-grid-card').forEach(card => {
                    const text = card.getAttribute('data-search') || '';
                    if (text.includes(q)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Live filter Table Rows
                document.querySelectorAll('.jadwal-row').forEach(row => {
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