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
                <span> Tambah Jadwal Baru</span>
            </button>

            <button type="button" onclick="openImportJadwalModal()"
                class="h-10 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shadow-2xs cursor-pointer whitespace-nowrap shrink-0">
                <i data-lucide="file-up" class="w-4 h-4"></i>
                <span>Import CSV</span>
            </button>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="flex-1 min-h-0 bg-white dark:bg-[#1C2433] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xs overflow-hidden flex flex-col">
        
        <!-- TOP CONTROL BAR -->
        <div class="shrink-0 p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-[#1C2433] space-y-3.5">
            
            <!-- Filter Bar: Tingkat & Hari Pasti 1 Baris Sejajar ke Samping -->
            <div class="flex items-center space-x-3 overflow-x-auto pb-1 scrollbar-none whitespace-nowrap">
                <!-- Tingkat Filter Pills -->
                <div class="flex items-center space-x-2 shrink-0">
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

                <!-- Pembatas Vertikal -->
                <div class="h-6 w-px bg-slate-200 dark:bg-slate-700/80 shrink-0"></div>

                <!-- Hari Quick Selector Pills -->
                <div class="flex items-center space-x-2 shrink-0">
                    <span class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider shrink-0 mr-1 flex items-center space-x-1">
                        <i data-lucide="calendar-days" class="w-4 h-4 text-blue-500"></i>
                        <span>HARI:</span>
                    </span>

                    <a href="{{ route('admin.jadwal.index', array_filter(['hari' => $namaHariIni, 'tingkat' => $tingkat, 'search' => request('search')])) }}"
                        class="px-3.5 py-2 text-xs font-bold rounded-xl transition-all shrink-0 flex items-center space-x-1.5 {{ $hariFilter === $namaHariIni ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 hover:bg-amber-100 border border-amber-200/70 dark:border-amber-800/60' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                        <span>Hari Ini ({{ $namaHariIni }})</span>
                    </a>

                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                        <a href="{{ route('admin.jadwal.index', array_filter(['hari' => $h, 'tingkat' => $tingkat, 'search' => request('search')])) }}"
                            class="px-3.5 py-2 text-xs font-bold rounded-xl transition-all shrink-0 {{ $hariFilter === $h ? 'bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                            {{ $h }}
                        </a>
                    @endforeach

                    <a href="{{ route('admin.jadwal.index', array_filter(['hari' => 'all', 'tingkat' => $tingkat, 'search' => request('search')])) }}"
                        class="px-3.5 py-2 text-xs font-bold rounded-xl transition-all shrink-0 {{ empty($hariFilter) ? 'bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                        Semua
                    </a>
                </div>
            </div>

            <!-- Row 3: Search input + Reset Button -->
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

        <!-- MAIN DISPLAY AREA (FULL WIDTH BARIS PER KELAS) -->
        <div class="flex-1 overflow-y-auto min-h-0 p-4 sm:p-6" id="jadwalContentContainer">
            
            <div id="jadwalGridView" class="space-y-2.5">
                @forelse ($kelasList as $idx => $kelas)
                    @php
                        $sessions = $jadwalGroupedByKelas->get($kelas->id_kelas, collect())->sortBy('jam_mulai');
                        $searchData = strtolower($kelas->nama_kelas . ' ' . $sessions->map(fn($s) => ($s->guru->nama_guru ?? '') . ' ' . ($s->mapel->nama_mapel ?? ''))->implode(' '));
                    @endphp
                    <div class="bg-white dark:bg-[#141C29] border border-slate-200 dark:border-slate-700/80 rounded-2xl p-3 sm:p-3.5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-2.5 sm:gap-3 hover:border-blue-500 dark:hover:border-blue-500 transition-all jadwal-grid-card"
                        data-search="{{ $searchData }}">
                        
                        <!-- Left: Nama Kelas & No Urut & Tombol Tambah -->
                        <div class="flex items-center justify-between md:justify-start md:w-48 lg:w-56 shrink-0 gap-2 border-b md:border-b-0 md:border-r border-slate-100 dark:border-slate-700/60 pb-2 md:pb-0 md:pr-3">
                            <div class="flex items-center space-x-2 min-w-0">
                                <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center font-bold text-[11px] shrink-0 font-mono">
                                    {{ $idx + 1 }}
                                </span>
                                <h3 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-slate-100 truncate" title="{{ $kelas->nama_kelas }}">
                                    {{ $kelas->nama_kelas }}
                                </h3>
                            </div>
                            
                            <button type="button" onclick="openTambahJadwalModal('{{ $kelas->id_kelas }}', '{{ $hariFilter ?: $defaultHari }}', '{{ addslashes($kelas->nama_kelas) }}')" title="Tambah Sesi untuk Kelas ini" 
                                class="w-6.5 h-6.5 rounded-lg bg-slate-100 dark:bg-[#232D3F] hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-all cursor-pointer border border-slate-200/80 dark:border-slate-700/70 shrink-0">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>

                        <!-- Right: List Sesi Mengajar Berderet Ringkas (Wrap jika banyak) -->
                        <div class="flex-1 flex flex-wrap items-center gap-1.5 min-w-0">
                            @forelse ($sessions as $j)
                                <button type="button" 
                                    onclick="openDetailJadwalModal('{{ $j->id_jadwal }}', '{{ $j->hari }}', '{{ addslashes($kelas->nama_kelas) }}', '{{ $j->jam_mulai }}', '{{ $j->jam_selesai }}', '{{ addslashes($j->mapel->nama_mapel ?? '-') }}', '{{ addslashes($j->guru->nama_guru ?? '-') }}', '{{ addslashes($j->ruangan->nama_ruangan ?? '-') }}')"
                                    class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-blue-50 dark:bg-[#1E2636] dark:hover:bg-blue-950/40 border border-slate-200/90 hover:border-blue-300 dark:border-slate-700/80 dark:hover:border-blue-800 text-xs transition-all cursor-pointer shadow-2xs hover:shadow-xs text-left"
                                    title="Klik info detail: {{ $j->mapel ? $j->mapel->nama_mapel : '-' }} ({{ $j->guru ? $j->guru->nama_guru : '-' }})">
                                    <span class="px-1.5 py-0.5 rounded bg-blue-600 text-white text-[10px] font-bold font-mono shrink-0">
                                        Jam {{ $j->jam_mulai }}{{ $j->jam_mulai != $j->jam_selesai ? '–' . $j->jam_selesai : '' }}
                                    </span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 text-xs truncate max-w-[140px] sm:max-w-[200px]">
                                        {{ $j->mapel ? $j->mapel->nama_mapel : '-' }}
                                    </span>
                                    <i data-lucide="info" class="w-3 h-3 text-slate-400 group-hover:text-blue-500 opacity-60 group-hover:opacity-100 transition-opacity shrink-0"></i>
                                </button>
                            @empty
                                <span class="text-slate-400 dark:text-slate-500 italic text-[11px] py-0.5">
                                    Belum ada sesi jadwal
                                </span>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-slate-400 italic text-xs space-y-2">
                        <i data-lucide="inbox" class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-600"></i>
                        <p>Tidak ada kelas yang sesuai dengan filter atau kata kunci pencarian.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

<!-- MODAL DETAIL SESI JADWAL -->
<div id="modalDetailJadwal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#181F2C] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-sm sm:max-w-md w-full p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <span class="font-extrabold text-slate-900 dark:text-slate-100 text-sm uppercase tracking-tight flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                </div>
                <span>Detail Sesi Pelajaran</span>
            </span>
            <button type="button" onclick="closeDetailJadwalModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4.5 h-4.5"></i>
            </button>
        </div>

        <div class="space-y-3 text-xs">
            <div class="p-3.5 bg-blue-50/60 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/50 rounded-xl space-y-1">
                <span class="text-[10px] font-bold uppercase text-blue-600 dark:text-blue-400 tracking-wider">Mata Pelajaran</span>
                <p class="text-sm font-extrabold text-slate-900 dark:text-slate-100" id="detail_jadwal_mapel">-</p>
            </div>

            <div class="space-y-2 pt-1">
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Guru Pengampu</span>
                    <span class="font-bold text-slate-900 dark:text-slate-100 text-right" id="detail_jadwal_guru">-</span>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Kelas / Rombel</span>
                    <span class="font-bold text-blue-600 dark:text-blue-400" id="detail_jadwal_kelas">-</span>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Hari & Waktu</span>
                    <span class="font-bold text-slate-900 dark:text-slate-100 font-mono" id="detail_jadwal_waktu">-</span>
                </div>
                <div class="flex items-center justify-between py-1.5">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Ruangan / Lab</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200" id="detail_jadwal_ruangan">-</span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
            <form id="formDeleteJadwalDetail" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sesi jadwal ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="h-9 px-3.5 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 text-rose-600 dark:text-rose-400 rounded-xl text-xs font-bold transition-colors border border-rose-200 dark:border-rose-800/60 flex items-center space-x-1.5 cursor-pointer">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span>Hapus Sesi</span>
                </button>
            </form>

            <button type="button" onclick="closeDetailJadwalModal()" class="h-9 px-5 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                Tutup
            </button>
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

            <!-- Kelas Terpilih Info Badge (Jika dibuka via tombol + baris kelas) -->
            <div id="modal_kelas_badge_container" class="hidden p-3 bg-blue-50/80 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800/70 rounded-xl flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block">Kelas Terpilih</span>
                        <span class="text-sm font-extrabold text-slate-900 dark:text-slate-100" id="modal_selected_kelas_name">-</span>
                    </div>
                </div>
                <button type="button" onclick="toggleKelasSelect(true)" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline px-2 py-1 rounded-lg hover:bg-blue-100/50 dark:hover:bg-blue-900/40 transition-colors cursor-pointer">
                    Ganti Kelas
                </button>
            </div>

            <!-- Dropdown Pilih Kelas -->
            <div id="modal_kelas_select_container">
                <label for="modal_id_kelas" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kelas *</label>
                <select name="id_kelas" id="modal_id_kelas" required
                    class="w-full h-9.5 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($allKelasList as $k)
                        <option value="{{ $k->id_kelas }}" data-nama="{{ $k->nama_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label for="modal_hari" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Hari *</label>
                    <select name="hari" id="modal_hari" required
                        class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                            <option value="{{ $h }}" {{ old('hari', $hariFilter ?: $defaultHari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="modal_jam_mulai" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Jam Mulai *</label>
                    <input type="number" name="jam_mulai" id="modal_jam_mulai" min="1" max="15" value="{{ old('jam_mulai', 1) }}" required
                        class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 text-center font-mono font-bold focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="modal_jam_selesai" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Jam Selesai *</label>
                    <input type="number" name="jam_selesai" id="modal_jam_selesai" min="1" max="15" value="{{ old('jam_selesai', 2) }}" required
                        class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 text-center font-mono font-bold focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <div>
                <label for="modal_kode_mapel" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Mata Pelajaran *</label>
                <select name="kode_mapel" id="modal_kode_mapel" required
                    class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
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
                    class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
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
                    class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
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
    function openDetailJadwalModal(idJadwal, hari, kelas, jamMulai, jamSelesai, mapel, guru, ruangan) {
        document.getElementById('detail_jadwal_mapel').innerText = mapel || '-';
        document.getElementById('detail_jadwal_guru').innerText = guru || '-';
        document.getElementById('detail_jadwal_kelas').innerText = kelas || '-';
        document.getElementById('detail_jadwal_waktu').innerText = `${hari}, Jam ${jamMulai}–${jamSelesai}`;
        document.getElementById('detail_jadwal_ruangan').innerText = ruangan || 'Default Ruangan Kelas';

        const form = document.getElementById('formDeleteJadwalDetail');
        if (form) {
            form.action = `/admin/jadwal/${idJadwal}`;
        }

        const modal = document.getElementById('modalDetailJadwal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeDetailJadwalModal() {
        const modal = document.getElementById('modalDetailJadwal');
        if (modal) modal.classList.add('hidden');
    }

    function openTambahJadwalModal(idKelas = null, hari = null, namaKelas = '') {
        const modal = document.getElementById('modalTambahJadwal');
        if (modal) {
            const selectKelas = document.getElementById('modal_id_kelas');
            const badgeContainer = document.getElementById('modal_kelas_badge_container');
            const selectContainer = document.getElementById('modal_kelas_select_container');
            const selectedKelasName = document.getElementById('modal_selected_kelas_name');

            if (selectKelas) {
                selectKelas.value = idKelas ? String(idKelas) : '';
                if (idKelas && !namaKelas) {
                    const selectedOpt = selectKelas.querySelector(`option[value="${idKelas}"]`);
                    if (selectedOpt) {
                        namaKelas = selectedOpt.getAttribute('data-nama') || selectedOpt.textContent.trim();
                    }
                }
            }

            if (idKelas && namaKelas) {
                if (selectedKelasName) selectedKelasName.textContent = namaKelas;
                if (badgeContainer) badgeContainer.classList.remove('hidden');
                if (selectContainer) selectContainer.classList.add('hidden');
            } else {
                if (badgeContainer) badgeContainer.classList.add('hidden');
                if (selectContainer) selectContainer.classList.remove('hidden');
            }

            const selectHari = document.getElementById('modal_hari');
            if (selectHari && hari && hari !== 'all') {
                selectHari.value = hari;
            }
            modal.classList.remove('hidden');
            if (window.lucide) {
                window.lucide.createIcons();
            }
        }
    }

    function toggleKelasSelect(showSelect) {
        const badgeContainer = document.getElementById('modal_kelas_badge_container');
        const selectContainer = document.getElementById('modal_kelas_select_container');
        if (showSelect) {
            if (badgeContainer) badgeContainer.classList.add('hidden');
            if (selectContainer) selectContainer.classList.remove('hidden');
        } else {
            if (badgeContainer) badgeContainer.classList.remove('hidden');
            if (selectContainer) selectContainer.classList.add('hidden');
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

    // Live Instant Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchJadwal');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                
                document.querySelectorAll('.jadwal-grid-card').forEach(card => {
                    const text = card.getAttribute('data-search') || '';
                    if (text.includes(q)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection