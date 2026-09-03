@extends('layouts.app')

@section('title', 'Kelola Guru Piket Permanen - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Kelola Guru Piket Permanen</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Atur jadwal guru piket rutin dari hari Senin hingga Jumat. Penugasan aktif mingguan secara otomatis.
            </p>
        </div>
        <div>
            <button onclick="openModalTambah()" class="py-2 px-3.5 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg font-bold text-xs transition-all shadow-xs flex items-center space-x-1.5 cursor-pointer">
                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Guru Piket</span>
            </button>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 items-start">
        @foreach($hariList as $hari)
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden flex flex-col h-full">
                <!-- Card Header -->
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="calendar-days" class="w-4 h-4 text-slate-600"></i>
                        <span class="font-bold text-slate-900 text-xs uppercase tracking-wider">{{ $hari }}</span>
                    </div>
                    <span class="px-2 py-0.5 bg-slate-200 text-slate-700 rounded-full text-[10.5px] font-bold">
                        {{ count($piketPerHari[$hari]) }} Guru
                    </span>
                </div>

                <!-- Card Body -->
                <div class="p-3.5 flex-1 space-y-2.5">
                    @forelse($piketPerHari[$hari] as $item)
                        <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-between hover:bg-slate-100/70 transition-all">
                            <div class="flex items-center space-x-2.5 overflow-hidden">
                                <div class="w-7 h-7 rounded bg-[#1E293B] text-white font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($item->guru ? $item->guru->nama_guru : 'G', 0, 1)) }}
                                </div>
                                <div class="truncate">
                                    <h4 class="text-xs font-bold text-slate-900 truncate leading-tight" title="{{ $item->guru ? $item->guru->nama_guru : '-' }}">
                                        {{ $item->guru ? $item->guru->nama_guru : '-' }}
                                    </h4>
                                    <p class="text-[10px] text-slate-500 font-mono mt-0.5">NIP: {{ $item->guru ? $item->guru->nip ?? '-' : '-' }}</p>
                                </div>
                            </div>
                            <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan piket ini?');" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-6 h-6 rounded border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer" title="Hapus Penugasan">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="py-7 text-center text-slate-400 italic">
                            <i data-lucide="user-x" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                            <p class="text-xs">Belum ada guru piket.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Card Footer Button -->
                <div class="p-2.5 bg-slate-50 border-t border-slate-200">
                    <button onclick="openModalTambah('{{ $hari }}')" class="w-full py-1.5 px-3 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1 cursor-pointer">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>Tambah ke {{ $hari }}</span>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Tambah Guru Piket -->
<div id="modalTambah" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-xl border border-slate-200 shadow-xl max-w-md w-full p-5 space-y-3.5">
        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm flex items-center space-x-1.5">
                <i data-lucide="user-plus" class="w-4 h-4 text-slate-700"></i>
                <span>Tambah Penugasan Guru Piket</span>
            </h3>
            <button onclick="closeModalTambah()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.guru-piket.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label for="hariSelect" class="block text-xs font-semibold text-slate-700 mb-1">Pilih Hari *</label>
                <select name="hari" id="hariSelect" required class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
                    <option value="">-- Pilih Hari --</option>
                    @foreach($hariList as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="guruSelect" class="block text-xs font-semibold text-slate-700 mb-1">Pilih Guru *</label>
                <select name="id_guru" id="guruSelect" required class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($guruList as $g)
                        <option value="{{ $g->id_guru }}">{{ $g->nama_guru }} ({{ $g->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-semibold text-slate-700 mb-1">Keterangan / Posisi (Opsional)</label>
                <input type="text" name="keterangan" id="keterangan" placeholder="Contoh: Koordinator Gerbang Barat / Piket Lt 2" 
                    class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2.5 border-t border-slate-100">
                <button type="button" onclick="closeModalTambah()" class="px-3.5 py-1.5 border border-slate-300 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-4 py-1.5 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg text-xs font-bold transition-all shadow-xs cursor-pointer">
                    Simpan Penugasan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalTambah(hari = '') {
        const modal = document.getElementById('modalTambah');
        const hariSelect = document.getElementById('hariSelect');
        if (hari) {
            hariSelect.value = hari;
        } else {
            hariSelect.value = '';
        }
        modal.classList.remove('hidden');
    }

    function closeModalTambah() {
        document.getElementById('modalTambah').classList.add('hidden');
    }
</script>
@endsection
