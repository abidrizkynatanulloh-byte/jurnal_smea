@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Mata Pelajaran</h1>
            <p class="text-xs text-gray-500 mt-1">
                Total terdaftar: <span class="font-semibold text-[#405078]">{{ $totalMapel }} Mata Pelajaran</span>
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-4 py-2 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <i data-lucide="panel-left" class="w-4 h-4 text-[#405078]"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH MAPEL BARU (DAPAT DI-FOLD) -->
        <div id="formPanel" class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-6 transition-all duration-300">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="book-plus" class="w-5 h-5 text-[#405078]"></i>
                <span>Tambah Mapel Baru</span>
            </h3>
            
            <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="kode_mapel" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kode Mapel *</label>
                    <input type="text" name="kode_mapel" id="kode_mapel" value="{{ old('kode_mapel') }}" placeholder="Contoh: MP-MAT" required 
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="nama_mapel" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Mata Pelajaran *</label>
                    <input type="text" name="nama_mapel" id="nama_mapel" value="{{ old('nama_mapel') }}" placeholder="Masukkan Nama Mapel" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Mapel</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER MAPEL -->
        <div id="tableCol" class="lg:col-span-2 space-y-6">
            
            <!-- STICKY FILTER CARD (DENGAN LIVE INSTANT SEARCH) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-4 sticky top-6 z-20">
                <form action="{{ route('admin.mapel.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pencarian Cepat (Ketik Langsung Tersaring)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" class="w-4 h-4 text-[#8697C3]"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchMapel" value="{{ request('search') }}" placeholder="Ketik Kode atau Nama Mapel..." 
                                class="block w-full pl-9 pr-4 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                        </div>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('admin.mapel.index') }}" class="px-3 py-2 border border-[#D1D9EB] hover:bg-gray-50 text-gray-600 rounded-xl text-xs font-semibold flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- TABLE CARD (FIXED SCROLL DENGAN STICKY HEADER) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto max-h-[580px] overflow-y-auto">
                    <table class="w-full text-left border-collapse" id="tableMapel">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-3 px-4 w-1/3">Kode Mapel</th>
                                <th class="py-3 px-4">Nama Mata Pelajaran</th>
                                <th class="py-3 px-4 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($mapelList as $m)
                                <tr class="hover:bg-gray-50/50 transition-colors mapel-row" data-search="{{ strtolower($m->kode_mapel . ' ' . $m->nama_mapel) }}">
                                    <td class="py-3.5 px-4 font-bold text-[#405078] text-xs">{{ $m->kode_mapel }}</td>
                                    <td class="py-3.5 px-4 font-medium text-[#1E2538] text-xs">{{ $m->nama_mapel }}</td>
                                    <td class="py-3.5 px-4 text-center" x-data="{ editModal: false }">
                                        <button @click="editModal = true" type="button" class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer mr-1" title="Edit Mapel">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>

                                        <!-- MODAL EDIT MAPEL -->
                                        <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 text-left" style="display: none;">
                                            <div @click.away="editModal = false" class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl border border-[#D1D9EB]">
                                                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                                                    <h3 class="text-base font-bold text-[#1E2538]">Edit Mata Pelajaran</h3>
                                                    <button @click="editModal = false" type="button" class="text-gray-400 hover:text-gray-600">
                                                        <i data-lucide="x" class="w-5 h-5"></i>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.mapel.update', $m->kode_mapel) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    @method('PUT')
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Kode Mapel</label>
                                                        <input type="text" value="{{ $m->kode_mapel }}" disabled class="block w-full px-3.5 py-2.5 bg-gray-100 border border-[#D1D9EB] rounded-xl text-sm text-gray-500 font-semibold cursor-not-allowed">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Mata Pelajaran</label>
                                                        <input type="text" name="nama_mapel" value="{{ $m->nama_mapel }}" required class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078]">
                                                    </div>
                                                    <div class="flex justify-end space-x-2 pt-2">
                                                        <button @click="editModal = false" type="button" class="px-4 py-2 border border-[#D1D9EB] rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50">Batal</button>
                                                        <button type="submit" class="px-4 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <form action="{{ route('admin.mapel.destroy', $m->kode_mapel) }}" method="POST" class="inline" onsubmit="return confirm('Hapus mapel {{ addslashes($m->nama_mapel) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Mapel">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-gray-400 italic text-xs">
                                        Tidak ada data mata pelajaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($mapelList->hasPages())
                    <div class="px-6 py-4 bg-[#F8FAFC] border-t border-[#D1D9EB]">
                        {{ $mapelList->links() }}
                    </div>
                @endif
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
            foldText.innerText = 'Tampilkan Form Tambah';
        } else {
            formPanel.classList.remove('hidden');
            tableCol.classList.add('lg:col-span-2');
            tableCol.classList.remove('lg:col-span-3');
            foldText.innerText = 'Sembunyikan Form Tambah';
        }
    }

    // Live Instant Search (Poin 7)
    document.getElementById('liveSearchMapel').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.mapel-row');
        rows.forEach(row => {
            const text = row.getAttribute('data-search') || '';
            if (text.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
