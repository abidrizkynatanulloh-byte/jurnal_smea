@extends('layouts.app')

@section('title', 'Kelola Guru Piket Permanen - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Kelola Guru Piket Permanen</h1>
            <p class="text-xs text-gray-500 mt-1">
                Atur jadwal guru piket rutin dari hari Senin hingga Jumat. Penugasan akan aktif setiap minggu sampai diubah kembali.
            </p>
        </div>
        <div>
            <button onclick="openModalTambah()" class="py-2.5 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-xs transition-all shadow-sm flex items-center space-x-2 cursor-pointer">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Tambah Guru Piket</span>
            </button>
        </div>
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center space-x-2">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold flex items-center space-x-2">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- GRID 5 HARI (SENIN - JUMAT) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
        @foreach($hariList as $hari)
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden flex flex-col h-full">
                <!-- Card Header -->
                <div class="px-5 py-4 bg-[#F8FAFC] border-b border-[#D1D9EB] flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="calendar-days" class="w-4 h-4 text-[#405078]"></i>
                        <span class="font-bold text-[#1E2538] text-sm">{{ $hari }}</span>
                    </div>
                    <span class="px-2 py-0.5 bg-[#405078]/10 text-[#405078] rounded-full text-[10px] font-bold">
                        {{ count($piketPerHari[$hari]) }} Guru
                    </span>
                </div>

                <!-- Card Body -->
                <div class="p-4 flex-1 space-y-3">
                    @forelse($piketPerHari[$hari] as $item)
                        <div class="p-3 bg-gray-50/80 border border-gray-200/80 rounded-xl flex items-center justify-between hover:border-[#405078]/30 transition-all">
                            <div class="flex items-center space-x-3 overflow-hidden">
                                <div class="w-8 h-8 rounded-lg bg-[#405078]/10 text-[#405078] font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($item->guru ? $item->guru->nama_guru : 'G', 0, 1)) }}
                                </div>
                                <div class="truncate">
                                    <h4 class="text-xs font-bold text-gray-800 truncate" title="{{ $item->guru ? $item->guru->nama_guru : '-' }}">
                                        {{ $item->guru ? $item->guru->nama_guru : '-' }}
                                    </h4>
                                    <p class="text-[10px] text-gray-400">NIP: {{ $item->guru ? $item->guru->nip ?? '-' : '-' }}</p>
                                </div>
                            </div>
                            <form action="{{ route('admin.guru-piket.destroy', $item->id_piket) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan piket ini?');" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Penugasan">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400 italic">
                            <i data-lucide="user-x" class="w-6 h-6 mx-auto mb-1.5 text-gray-300"></i>
                            <p class="text-xs">Belum ada guru piket.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Card Footer Button -->
                <div class="p-3 bg-[#F8FAFC] border-t border-[#D1D9EB]">
                    <button onclick="openModalTambah('{{ $hari }}')" class="w-full py-2 px-3 bg-white border border-[#D1D9EB] hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah ke {{ $hari }}</span>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- MODAL TAMBAH GURU PIKET -->
<div id="modalTambah" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-5 animate-in fade-in zoom-in duration-150">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                <i data-lucide="user-check" class="w-5 h-5 text-[#405078]"></i>
                <span>Menugaskan Guru Piket</span>
            </h3>
            <button onclick="closeModalTambah()" class="text-gray-400 hover:text-gray-600 rounded-lg p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.guru-piket.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="hari_select" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Hari Piket</label>
                <select name="hari" id="hari_select" required
                    class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 cursor-pointer">
                    @foreach($hariList as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="id_guru" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Guru</label>
                <select name="id_guru" id="id_guru" required
                    class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 cursor-pointer">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($daftarGuru as $guru)
                        <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-2">
                <button type="button" onclick="closeModalTambah()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2.5 bg-[#405078] hover:bg-[#2F3C5C] text-white font-bold text-xs rounded-xl transition-colors shadow-sm cursor-pointer flex items-center space-x-1.5">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Penugasan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalTambah(hari = 'Senin') {
        const modal = document.getElementById('modalTambah');
        const hariSelect = document.getElementById('hari_select');
        if (hariSelect) {
            hariSelect.value = hari;
        }
        modal.classList.remove('hidden');
    }

    function closeModalTambah() {
        const modal = document.getElementById('modalTambah');
        modal.classList.add('hidden');
    }
</script>
@endsection
