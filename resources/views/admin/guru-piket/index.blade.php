@extends('layouts.app')

@section('title', 'Kelola Jadwal Guru Piket Bulanan - Jurnal Esemkita')

@php
    /** @var array<int|string, string> $namaBulanList */
    /** @var int|string $bulanSelected */
    /** @var int|string $tahunSelected */
    /** @var array $datesInMonth */
    /** @var array $weeksInMonth */
@endphp

@section('content')
<div class="space-y-4">
    <!-- Header Page & Filter Bulan/Tahun -->
    <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-[#2D3543] rounded-xl p-4 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900 dark:text-slate-100 tracking-tight flex items-center space-x-2">
                <i data-lucide="calendar-range" class="w-5 h-5 text-brand-600 dark:text-brand-400"></i>
                <span>Kelola Jadwal Guru Piket KBM (Bulanan)</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Pengaturan penugasan guru piket harian per tanggal spesifik dalam 1 bulan (Shift Pagi, Siang & Piket Waka).
            </p>
        </div>

        <!-- Filter Bulan & Tahun -->
        <form action="{{ route('admin.guru-piket.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <div class="w-36">
                <select name="bulan" onchange="this.form.submit()" class="block w-full h-9 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-[#2D394C] rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-500">
                    @foreach($namaBulanList as $num => $nama)
                        <option value="{{ $num }}" {{ (int)$bulanSelected === (int)$num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-24">
                <select name="tahun" onchange="this.form.submit()" class="block w-full h-9 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-[#2D394C] rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-500">
                    @foreach(range(2024, 2035) as $t)
                        <option value="{{ $t }}" {{ (int)$tahunSelected === (int)$t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <button type="button" onclick="openModalTambah('')" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all inline-flex items-center justify-center gap-2 shadow-2xs hover:shadow-md cursor-pointer whitespace-nowrap shrink-0">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span> Tambah Penugasan</span>
            </button>
        </form>
    </div>

    <!-- NOTIFIKASI BULAN YANG DIPILIH -->
    <div class="flex items-center justify-between px-1">
        <h2 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center space-x-1.5">
            <span>Daftar Tanggal: {{ $namaBulanList[$bulanSelected] ?? ($namaBulanList[(int)$bulanSelected] ?? '') }} {{ $tahunSelected }}</span>
            <span class="px-2 py-0.5 bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200 dark:border-brand-800 rounded-full text-[10px]">
                {{ count($datesInMonth) }} Hari Kerja
            </span>
        </h2>
    </div>

    <!-- TAMPILAN PER MINGGU -->
    @foreach($weeksInMonth as $weekNum => $daysGroup)
        <div class="space-y-2">
            <div class="flex items-center space-x-2 pt-1">
                <span class="h-px flex-1 bg-slate-200 dark:bg-[#2D3543]"></span>
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest bg-slate-100 dark:bg-[#1C1F26] px-2.5 py-0.5 rounded-full border border-slate-200 dark:border-[#2D3543]">
                    Minggu ke-{{ $weekNum }}
                </span>
                <span class="h-px flex-1 bg-slate-200 dark:bg-[#2D3543]"></span>
            </div>

            <!-- GRID TANGGAL DALAM MINGGU INI -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 items-start">
                @foreach($daysGroup as $d)
                    @php
                        $tgl = $d['tanggal'];
                        $allHarian = collect($piketPerTanggal[$tgl] ?? []);
                        $itemsPagi = $allHarian->filter(fn($i) => ($i->shift ?? 'Pagi') === 'Pagi');
                        $itemsSiang = $allHarian->filter(fn($i) => ($i->shift ?? 'Pagi') === 'Siang');

                        $koordPagi = $itemsPagi->where('peran_piket', 'Koordinator');
                        $petugasPagi = $itemsPagi->where('peran_piket', 'Petugas');
                        
                        $koordSiang = $itemsSiang->where('peran_piket', 'Koordinator');
                        $petugasSiang = $itemsSiang->where('peran_piket', 'Petugas');
                        
                        $piketWaka = $allHarian->where('peran_piket', 'Piket Waka');
                        $isToday = $d['is_today'];
                    @endphp

                    <div class="bg-white dark:bg-[#242A35] border {{ $isToday ? 'border-amber-400 dark:border-amber-500 ring-2 ring-amber-400/20 shadow-md' : 'border-slate-200 dark:border-[#2D3543]' }} rounded-xl shadow-xs overflow-hidden flex flex-col transition-all">
                        <!-- Card Header Tanggal -->
                        <div class="px-3 py-2.5 {{ $isToday ? 'bg-amber-50 dark:bg-amber-950/40 border-b border-amber-200 dark:border-amber-900/50' : 'bg-slate-50 dark:bg-[#1F2531] border-b border-slate-200 dark:border-[#2D3543]' }} flex items-center justify-between">
                            <div class="flex items-center space-x-1.5 truncate">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 {{ $isToday ? 'text-amber-600 dark:text-amber-400' : 'text-slate-600 dark:text-slate-400' }}"></i>
                                <span class="font-bold text-xs {{ $isToday ? 'text-amber-950 dark:text-amber-200' : 'text-slate-900 dark:text-slate-100' }} uppercase tracking-wider truncate">
                                    {{ $d['nama_hari'] }}, {{ $d['tgl_num'] }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-1 shrink-0">
                                @if($isToday)
                                    <span class="px-1.5 py-0.2 bg-amber-500 text-white text-[9px] font-extrabold rounded uppercase tracking-wider">HARI INI</span>
                                @endif
                                <span class="px-1.5 py-0.2 bg-slate-900 dark:bg-slate-700 text-white rounded text-[10px] font-bold font-mono">
                                    {{ $allHarian->count() }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-2.5 space-y-2.5 flex-1 text-xs">

                            <!-- SEKSI SHIFT PAGI -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between bg-amber-50/80 dark:bg-amber-950/30 px-2 py-0.5 rounded border border-amber-200/70 dark:border-amber-900/50">
                                    <span class="font-bold text-[10.5px] text-amber-900 dark:text-amber-300 flex items-center space-x-1">
                                        <i data-lucide="sun" class="w-3 h-3 text-amber-600 dark:text-amber-400 inline"></i>
                                        <span>PAGI (07.00-11.00)</span>
                                    </span>
                                    <button onclick="openModalTambah('{{ $tgl }}', 'Pagi')" class="text-[10px] font-bold text-amber-700 dark:text-amber-400 hover:underline cursor-pointer">+ Tambah</button>
                                </div>

                                <!-- Koordinator Pagi -->
                                @foreach($koordPagi as $item)
                                    <div class="p-1.5 bg-amber-50/40 dark:bg-amber-950/20 border border-amber-300/80 dark:border-amber-900/60 rounded-lg flex items-center justify-between">
                                        <div class="truncate">
                                            <div class="flex items-center space-x-1">
                                                <span class="px-1.5 py-0.2 bg-amber-200 dark:bg-amber-900 text-amber-900 dark:text-amber-200 text-[9px] font-bold rounded">KOORDINATOR</span>
                                            </div>
                                            <h4 class="font-bold text-slate-900 dark:text-slate-100 text-[11px] truncate mt-0.5">{{ $item->guru ? $item->guru->nama_guru : '-' }}</h4>
                                        </div>
                                        <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Hapus koordinator ini?');" class="shrink-0 ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600 p-0.5 cursor-pointer"><i data-lucide="x-circle" class="w-3.5 h-3.5"></i></button>
                                        </form>
                                    </div>
                                @endforeach

                                <!-- Petugas Pagi -->
                                @foreach($petugasPagi as $item)
                                    <div class="p-1.5 bg-slate-50 dark:bg-[#1A212D] border border-slate-200 dark:border-[#2D394C] rounded-lg flex items-center justify-between hover:bg-slate-100 dark:hover:bg-[#222C3D] transition-all">
                                        <div class="truncate">
                                            <h4 class="font-semibold text-slate-800 dark:text-slate-200 text-[11px] truncate">{{ $item->guru ? $item->guru->nama_guru : '-' }}</h4>
                                            @if($item->keterangan)
                                                <p class="text-[9px] text-slate-400 truncate">{{ $item->keterangan }}</p>
                                            @endif
                                        </div>
                                        <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Hapus petugas ini?');" class="shrink-0 ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-300 hover:text-rose-600 p-0.5 cursor-pointer"><i data-lucide="trash-2" class="w-3 h-3"></i></button>
                                        </form>
                                    </div>
                                @endforeach

                                @if($itemsPagi->isEmpty())
                                    <p class="text-[10px] text-slate-400 italic px-1">Belum ada petugas pagi.</p>
                                @endif
                            </div>

                            <!-- SEKSI SHIFT SIANG -->
                            <div class="space-y-1.5 pt-1 border-t border-slate-100 dark:border-[#2D3543]">
                                <div class="flex items-center justify-between bg-sky-50/80 dark:bg-sky-950/30 px-2 py-0.5 rounded border border-sky-200/70 dark:border-sky-900/50">
                                    <span class="font-bold text-[10.5px] text-sky-900 dark:text-sky-300 flex items-center space-x-1">
                                        <i data-lucide="moon-star" class="w-3 h-3 text-sky-600 dark:text-sky-400 inline"></i>
                                        <span>SIANG (11.00-15.00)</span>
                                    </span>
                                    <button onclick="openModalTambah('{{ $tgl }}', 'Siang')" class="text-[10px] font-bold text-sky-700 dark:text-sky-400 hover:underline cursor-pointer">+ Tambah</button>
                                </div>

                                <!-- Koordinator Siang -->
                                @foreach($koordSiang as $item)
                                    <div class="p-1.5 bg-sky-50/40 dark:bg-sky-950/20 border border-sky-300/80 dark:border-sky-900/60 rounded-lg flex items-center justify-between">
                                        <div class="truncate">
                                            <div class="flex items-center space-x-1">
                                                <span class="px-1.5 py-0.2 bg-sky-200 dark:bg-sky-900 text-sky-900 dark:text-sky-200 text-[9px] font-bold rounded">KOORDINATOR</span>
                                            </div>
                                            <h4 class="font-bold text-slate-900 dark:text-slate-100 text-[11px] truncate mt-0.5">{{ $item->guru ? $item->guru->nama_guru : '-' }}</h4>
                                        </div>
                                        <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Hapus koordinator ini?');" class="shrink-0 ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600 p-0.5 cursor-pointer"><i data-lucide="x-circle" class="w-3.5 h-3.5"></i></button>
                                        </form>
                                    </div>
                                @endforeach

                                <!-- Petugas Siang -->
                                @foreach($petugasSiang as $item)
                                    <div class="p-1.5 bg-slate-50 dark:bg-[#1A212D] border border-slate-200 dark:border-[#2D394C] rounded-lg flex items-center justify-between hover:bg-slate-100 dark:hover:bg-[#222C3D] transition-all">
                                        <div class="truncate">
                                            <h4 class="font-semibold text-slate-800 dark:text-slate-200 text-[11px] truncate">{{ $item->guru ? $item->guru->nama_guru : '-' }}</h4>
                                            @if($item->keterangan)
                                                <p class="text-[9px] text-slate-400 truncate">{{ $item->keterangan }}</p>
                                            @endif
                                        </div>
                                        <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Hapus petugas ini?');" class="shrink-0 ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-300 hover:text-rose-600 p-0.5 cursor-pointer"><i data-lucide="trash-2" class="w-3 h-3"></i></button>
                                        </form>
                                    </div>
                                @endforeach

                                @if($itemsSiang->isEmpty())
                                    <p class="text-[10px] text-slate-400 italic px-1">Belum ada petugas siang.</p>
                                @endif
                            </div>

                            <!-- SEKSI PIKET WAKA -->
                            <div class="space-y-1.5 pt-1 border-t border-slate-100 dark:border-[#2D3543]">
                                <div class="flex items-center justify-between bg-purple-50/80 dark:bg-purple-950/30 px-2 py-0.5 rounded border border-purple-200/70 dark:border-purple-900/50">
                                    <span class="font-bold text-[10.5px] text-purple-900 dark:text-purple-300 flex items-center space-x-1">
                                        <i data-lucide="shield" class="w-3 h-3 text-purple-600 dark:text-purple-400 inline"></i>
                                        <span>PIKET WAKA</span>
                                    </span>
                                    <button onclick="openModalTambah('{{ $tgl }}', 'Pagi', 'Piket Waka')" class="text-[10px] font-bold text-purple-700 dark:text-purple-400 hover:underline cursor-pointer">+ Tambah</button>
                                </div>

                                @foreach($piketWaka as $item)
                                    <div class="p-1.5 bg-purple-50/40 dark:bg-purple-950/20 border border-purple-300/80 dark:border-purple-900/60 rounded-lg flex items-center justify-between">
                                        <div class="truncate">
                                            <h4 class="font-bold text-purple-950 dark:text-purple-200 text-[11px] truncate">{{ $item->guru ? $item->guru->nama_guru : '-' }}</h4>
                                        </div>
                                        <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Hapus Piket Waka ini?');" class="shrink-0 ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600 p-0.5 cursor-pointer"><i data-lucide="x-circle" class="w-3.5 h-3.5"></i></button>
                                        </form>
                                    </div>
                                @endforeach

                                @if($piketWaka->isEmpty())
                                    <p class="text-[10px] text-slate-400 italic px-1">Belum ada Piket Waka.</p>
                                @endif
                            </div>

                        </div>

                        <!-- Footer Card -->
                        <div class="p-2 bg-slate-50 dark:bg-[#1F2531] border-t border-slate-200 dark:border-[#2D3543]">
                            <button onclick="openModalTambah('{{ $tgl }}')" class="w-full h-7.5 bg-white dark:bg-[#242A35] border border-slate-200 dark:border-[#2D3543] hover:bg-slate-100 dark:hover:bg-[#2A3240] text-slate-700 dark:text-slate-200 rounded-lg text-xs font-bold transition-colors flex items-center justify-center space-x-1 cursor-pointer">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Tambah Guru Piket</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<!-- Modal Tambah Guru Piket -->
<div id="modalTambah" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#242A35] rounded-xl border border-slate-200 dark:border-[#2D3543] shadow-xl max-w-md w-full p-4 space-y-3">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-[#2D3543]">
            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                <i data-lucide="user-plus" class="w-3.5 h-3.5 text-slate-700 dark:text-slate-300"></i>
                <span>Tambah Penugasan Guru Piket</span>
            </h3>
            <button onclick="closeModalTambah()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.guru-piket.store') }}" method="POST" class="space-y-2.5">
            @csrf
            
            <div>
                <label for="tanggalInput" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Piket *</label>
                <input type="date" name="tanggal_khusus" id="tanggalInput" required 
                    class="block w-full h-9 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-[#2D394C] rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label for="shiftSelect" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Shift Waktu *</label>
                    <select name="shift" id="shiftSelect" required class="block w-full h-9 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-[#2D394C] rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-500">
                        <option value="Pagi">Pagi (07.00 - 11.00)</option>
                        <option value="Siang">Siang (11.00 - 15.00)</option>
                    </select>
                </div>

                <div>
                    <label for="peranSelect" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Peran / Jabatan *</label>
                    <select name="peran_piket" id="peranSelect" required class="block w-full h-9 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-[#2D394C] rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-500">
                        <option value="Petugas">Petugas Piket KBM</option>
                        <option value="Koordinator">Koordinator Piket KBM</option>
                        <option value="Piket Waka">Piket Waka</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="guruSelect" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pilih Guru *</label>
                <select name="id_guru" id="guruSelect" required data-placeholder="Cari / pilih guru..." class="searchable-select block w-full h-9 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-[#2D394C] rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-500">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($guruList as $g)
                        <option value="{{ $g->id_guru }}">{{ $g->nama_guru }} ({{ $g->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Keterangan / Pos Piket (Opsional)</label>
                <input type="text" name="keterangan" id="keterangan" placeholder="Misal: Pos Lobi Utama / Gerbang Barat" 
                    class="block w-full h-9 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-[#2D394C] rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-brand-500">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100 dark:border-[#2D3543]">
                <button type="button" onclick="closeModalTambah()" class="h-9 px-4 border border-slate-200 dark:border-[#2D3543] rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#2A3240] cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-9 px-5 bg-[#1E2538] hover:bg-[#121724] dark:bg-brand-600 dark:hover:bg-brand-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-xs flex items-center justify-center space-x-2">
                    <span>Simpan Penugasan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function setSelectValue(elId, val) {
        const el = document.getElementById(elId);
        if (!el || !val) return;
        el.value = val;
        if (el.tomselect) {
            el.tomselect.setValue(val, true);
        }
    }

    function openModalTambah(tanggal = '', shift = 'Pagi', peran = 'Petugas') {
        const modal = document.getElementById('modalTambah');
        modal.classList.remove('hidden');

        const tglInput = document.getElementById('tanggalInput');
        if (tglInput) {
            tglInput.value = tanggal ? tanggal : new Date().toISOString().split('T')[0];
        }

        if (shift) setSelectValue('shiftSelect', shift);
        if (peran) setSelectValue('peranSelect', peran);

        const guruSelect = document.getElementById('guruSelect');
        if (guruSelect) {
            if (guruSelect.tomselect) {
                guruSelect.tomselect.destroy();
            }
            guruSelect.value = '';
            if (typeof TomSelect !== 'undefined') {
                new TomSelect(guruSelect, {
                    create: false,
                    maxOptions: 300,
                    placeholder: 'Cari / pilih guru...',
                    allowEmptyOption: true,
                    onItemAdd: function() {
                        this.blur();
                    }
                });
            }
        }

        const ketInput = document.getElementById('keterangan');
        if (ketInput) ketInput.value = '';
    }

    function closeModalTambah() {
        const guruSelect = document.getElementById('guruSelect');
        if (guruSelect && guruSelect.tomselect) guruSelect.tomselect.close();
        document.getElementById('modalTambah').classList.add('hidden');
    }
</script>
@endsection
