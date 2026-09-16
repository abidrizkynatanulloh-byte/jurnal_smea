@extends('layouts.app')

@section('title', 'Kelola Jadwal Guru Piket - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Kelola Jadwal Guru Piket KBM</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pengaturan petugas piket, koordinator shift (Pagi & Siang), serta Piket Waka harian.
            </p>
        </div>
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs font-semibold flex items-center space-x-2 shadow-2xs">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-xs font-semibold flex items-center space-x-2 shadow-2xs">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- GRID 5 HARI (SENIN - JUMAT) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-2.5 items-start">
        @foreach($hariList as $hari)
            @php
                $allHarian = $piketPerHari[$hari] ?? collect();
                $itemsPagi = $allHarian->filter(fn($i) => ($i->shift ?? 'Pagi') === 'Pagi');
                $itemsSiang = $allHarian->filter(fn($i) => ($i->shift ?? 'Pagi') === 'Siang');

                $koordPagi = $itemsPagi->where('peran_piket', 'Koordinator');
                $petugasPagi = $itemsPagi->where('peran_piket', 'Petugas');
                
                $koordSiang = $itemsSiang->where('peran_piket', 'Koordinator');
                $petugasSiang = $itemsSiang->where('peran_piket', 'Petugas');
                
                $piketWaka = $allHarian->where('peran_piket', 'Piket Waka');
            @endphp

            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden flex flex-col">
                <!-- Card Header -->
                <div class="px-3.5 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="calendar-days" class="w-4 h-4 text-slate-600"></i>
                        <span class="font-bold text-slate-900 text-xs uppercase tracking-wider">{{ $hari }}</span>
                    </div>
                    <span class="px-2 py-0.5 bg-slate-900 text-white border border-slate-800 rounded-md text-[11px] font-bold font-mono">
                        {{ $allHarian->count() }} Guru
                    </span>
                </div>

                <!-- Card Body -->
                <div class="p-3 space-y-3 flex-1 text-xs">

                    <!-- SEKSI SHIFT PAGI -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between bg-amber-50/80 px-2 py-1 rounded-md border border-amber-200/70">
                            <span class="font-bold text-[11px] text-amber-900 flex items-center space-x-1">
                                <i data-lucide="sun" class="w-3 h-3 text-amber-600 inline"></i>
                                <span>PAGI (07.00-11.00)</span>
                            </span>
                            <button onclick="openModalTambah('{{ $hari }}', 'Pagi')" class="text-[10px] font-bold text-amber-700 hover:text-amber-900 cursor-pointer">+ Tambah</button>
                        </div>

                        <!-- Koordinator Pagi -->
                        @foreach($koordPagi as $item)
                            <div class="p-2 bg-amber-50/40 border border-amber-300/80 rounded-lg flex items-center justify-between">
                                <div class="truncate">
                                    <div class="flex items-center space-x-1">
                                        <span class="px-1.5 py-0.2 bg-amber-200 text-amber-900 text-[9px] font-bold rounded">KOORDINATOR</span>
                                    </div>
                                    <h4 class="font-bold text-slate-900 text-[11px] truncate mt-0.5">{{ $item->guru ? $item->guru->nama_guru : '-' }}</h4>
                                </div>
                                <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Hapus koordinator ini?');" class="shrink-0 ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-0.5"><i data-lucide="x-circle" class="w-3.5 h-3.5"></i></button>
                                </form>
                            </div>
                        @endforeach

                        <!-- Petugas Pagi -->
                        @foreach($petugasPagi as $item)
                            <div class="p-1.5 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-between hover:bg-slate-100/80 transition-all">
                                <div class="truncate">
                                    <h4 class="font-semibold text-slate-800 text-[11px] truncate">{{ $item->guru ? $item->guru->nama_guru : '-' }}</h4>
                                    @if($item->keterangan)
                                        <p class="text-[9px] text-slate-400 truncate">{{ $item->keterangan }}</p>
                                    @endif
                                </div>
                                <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Hapus petugas ini?');" class="shrink-0 ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-rose-600 p-0.5"><i data-lucide="trash-2" class="w-3 h-3"></i></button>
                                </form>
                            </div>
                        @endforeach

                        @if($itemsPagi->isEmpty())
                            <p class="text-[10.5px] text-slate-400 italic px-1">Belum ada petugas pagi.</p>
                        @endif
                    </div>

                    <!-- SEKSI SHIFT SIANG -->
                    <div class="space-y-1.5 pt-1 border-t border-slate-100">
                        <div class="flex items-center justify-between bg-sky-50/80 px-2 py-1 rounded-md border border-sky-200/70">
                            <span class="font-bold text-[11px] text-sky-900 flex items-center space-x-1">
                                <i data-lucide="moon-star" class="w-3 h-3 text-sky-600 inline"></i>
                                <span>SIANG (11.00-15.00)</span>
                            </span>
                            <button onclick="openModalTambah('{{ $hari }}', 'Siang')" class="text-[10px] font-bold text-sky-700 hover:text-sky-900 cursor-pointer">+ Tambah</button>
                        </div>

                        <!-- Koordinator Siang -->
                        @foreach($koordSiang as $item)
                            <div class="p-2 bg-sky-50/40 border border-sky-300/80 rounded-lg flex items-center justify-between">
                                <div class="truncate">
                                    <div class="flex items-center space-x-1">
                                        <span class="px-1.5 py-0.2 bg-sky-200 text-sky-900 text-[9px] font-bold rounded">KOORDINATOR</span>
                                    </div>
                                    <h4 class="font-bold text-slate-900 text-[11px] truncate mt-0.5">{{ $item->guru ? $item->guru->nama_guru : '-' }}</h4>
                                </div>
                                <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Hapus koordinator ini?');" class="shrink-0 ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-0.5"><i data-lucide="x-circle" class="w-3.5 h-3.5"></i></button>
                                </form>
                            </div>
                        @endforeach

                        <!-- Petugas Siang -->
                        @foreach($petugasSiang as $item)
                            <div class="p-1.5 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-between hover:bg-slate-100/80 transition-all">
                                <div class="truncate">
                                    <h4 class="font-semibold text-slate-800 text-[11px] truncate">{{ $item->guru ? $item->guru->nama_guru : '-' }}</h4>
                                    @if($item->keterangan)
                                        <p class="text-[9px] text-slate-400 truncate">{{ $item->keterangan }}</p>
                                    @endif
                                </div>
                                <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Hapus petugas ini?');" class="shrink-0 ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-rose-600 p-0.5"><i data-lucide="trash-2" class="w-3 h-3"></i></button>
                                </form>
                            </div>
                        @endforeach

                        @if($itemsSiang->isEmpty())
                            <p class="text-[10.5px] text-slate-400 italic px-1">Belum ada petugas siang.</p>
                        @endif
                    </div>

                    <!-- SEKSI PIKET WAKA -->
                    <div class="space-y-1.5 pt-1 border-t border-slate-100">
                        <div class="flex items-center justify-between bg-purple-50/80 px-2 py-1 rounded-md border border-purple-200/70">
                            <span class="font-bold text-[11px] text-purple-900 flex items-center space-x-1">
                                <i data-lucide="shield" class="w-3 h-3 text-purple-600 inline"></i>
                                <span>PIKET WAKA</span>
                            </span>
                            <button onclick="openModalTambah('{{ $hari }}', 'Pagi', 'Piket Waka')" class="text-[10px] font-bold text-purple-700 hover:text-purple-900 cursor-pointer">+ Tambah</button>
                        </div>

                        @foreach($piketWaka as $item)
                            <div class="p-2 bg-purple-50/40 border border-purple-300/80 rounded-lg flex items-center justify-between">
                                <div class="truncate">
                                    <h4 class="font-bold text-purple-950 text-[11px] truncate">{{ $item->guru ? $item->guru->nama_guru : '-' }}</h4>
                                </div>
                                <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Hapus Piket Waka ini?');" class="shrink-0 ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-0.5"><i data-lucide="x-circle" class="w-3.5 h-3.5"></i></button>
                                </form>
                            </div>
                        @endforeach

                        @if($piketWaka->isEmpty())
                            <p class="text-[10.5px] text-slate-400 italic px-1">Belum ada Piket Waka.</p>
                        @endif
                    </div>

                </div>

                <!-- Footer Card -->
                <div class="p-2 bg-slate-50 border-t border-slate-200">
                    <button onclick="openModalTambah('{{ $hari }}')" class="w-full h-8 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-lg text-xs font-bold transition-colors flex items-center justify-center space-x-1 cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah Penugasan</span>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Tambah Guru Piket -->
<div id="modalTambah" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-xl border border-slate-200 shadow-xl max-w-md w-full p-4 space-y-3">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                <i data-lucide="user-plus" class="w-3.5 h-3.5 text-slate-700"></i>
                <span>Tambah Penugasan Guru Piket</span>
            </h3>
            <button onclick="closeModalTambah()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.guru-piket.store') }}" method="POST" class="space-y-2.5">
            @csrf
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label for="hariSelect" class="block text-xs font-semibold text-slate-700 mb-1">Hari *</label>
                    <select name="hari" id="hariSelect" required class="block w-full h-9 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538]">
                        <option value="">-- Hari --</option>
                        @foreach($hariList as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="shiftSelect" class="block text-xs font-semibold text-slate-700 mb-1">Shift Waktu *</label>
                    <select name="shift" id="shiftSelect" required class="block w-full h-9 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538]">
                        <option value="Pagi">Pagi (07.00 - 11.00)</option>
                        <option value="Siang">Siang (11.00 - 15.00)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="peranSelect" class="block text-xs font-semibold text-slate-700 mb-1">Peran / Jabatan Piket *</label>
                <select name="peran_piket" id="peranSelect" required class="block w-full h-9 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538]">
                    <option value="Petugas">Petugas Piket KBM</option>
                    <option value="Koordinator">Koordinator Piket KBM</option>
                    <option value="Piket Waka">Piket Waka</option>
                </select>
            </div>

            <div>
                <label for="guruSelect" class="block text-xs font-semibold text-slate-700 mb-1">Pilih Guru *</label>
                <select name="id_guru" id="guruSelect" required class="searchable-select block w-full h-9 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538]">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($guruList as $g)
                        <option value="{{ $g->id_guru }}">{{ $g->nama_guru }} ({{ $g->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-semibold text-slate-700 mb-1">Keterangan / Catatan (Opsional)</label>
                <input type="text" name="keterangan" id="keterangan" placeholder="Misal: Pos Lobi Utama / Gerbang Barat" 
                    class="block w-full h-9 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538]">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModalTambah()" class="h-10 px-5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-10 px-6 bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-xs flex items-center justify-center space-x-2">
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

    function openModalTambah(hari = '', shift = 'Pagi', peran = 'Petugas') {
        const modal = document.getElementById('modalTambah');

        if (typeof initSearchableSelects === 'function') {
            initSearchableSelects();
        }

        if (hari) setSelectValue('hariSelect', hari);
        if (shift) setSelectValue('shiftSelect', shift);
        if (peran) setSelectValue('peranSelect', peran);

        const guruSelect = document.getElementById('guruSelect');
        if (guruSelect) {
            guruSelect.value = '';
            if (guruSelect.tomselect) guruSelect.tomselect.setValue('', true);
        }

        const ketInput = document.getElementById('keterangan');
        if (ketInput) ketInput.value = '';

        modal.classList.remove('hidden');
    }

    function closeModalTambah() {
        document.getElementById('modalTambah').classList.add('hidden');
    }
</script>
@endsection
