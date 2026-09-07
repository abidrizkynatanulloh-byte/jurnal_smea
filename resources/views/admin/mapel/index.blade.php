@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-3">
    <!-- Header Halaman (Sesuai Ukuran Gambar 1 - FIXED) -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-3">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Mata Pelajaran</h1>
            <span class="px-2.5 py-0.5 text-xs font-semibold bg-slate-200 text-slate-700 rounded-md font-mono tabular-nums">
                {{ $mapelList->total() }} Mapel
            </span>
        </div>
        <div>
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="h-9 px-3.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-2 shadow-2xs cursor-pointer">
                <i data-lucide="panel-left-close" class="w-4 h-4 text-slate-500"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Layout Grid Utama (Full Viewport Height - Sesuai Gambar 1) -->
    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-4 items-stretch" id="masterDataGrid">
        
        <!-- BAGIAN 1: FORM TAMBAH MAPEL (UKURAN & PROPORSI SESUAI GAMBAR 1) -->
        <div id="formPanel" class="bg-white border border-slate-200/90 rounded-xl p-4.5 shadow-2xs space-y-3.5 overflow-y-auto h-full max-h-full">
            <div class="flex items-center space-x-2.5 pb-2.5 border-b border-slate-100 shrink-0">
                <div class="w-6 h-6 rounded-md bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                </div>
                <span class="font-bold text-xs text-slate-900 tracking-tight uppercase">TAMBAH MAPEL BARU</span>
            </div>
            
            <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="kode_mapel" class="block text-xs font-semibold text-slate-700 mb-1">Kode Mapel *</label>
                    <input type="text" name="kode_mapel" id="kode_mapel" value="{{ old('kode_mapel') }}" placeholder="Contoh: MP-MAT" required 
                        class="w-full h-10 px-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div>
                    <label for="nama_mapel" class="block text-xs font-semibold text-slate-700 mb-1">Nama Mata Pelajaran *</label>
                    <input type="text" name="nama_mapel" id="nama_mapel" value="{{ old('nama_mapel') }}" placeholder="Masukkan Nama Mapel" required
                        class="w-full h-10 px-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full h-10 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-2 shadow-xs cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Simpan Mapel</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DATA TABLE CARD (SESUAI GAMBAR 1 & GAMBAR 2) -->
        <div id="tableCol" class="lg:col-span-2 flex flex-col h-full min-h-0">
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden flex flex-col h-full min-h-0">
                <!-- Top Control Bar (Search) -->
                <div class="shrink-0 px-4 py-3 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5">
                    <form action="{{ route('admin.mapel.index') }}" method="GET" class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="search" id="liveSearchMapel" value="{{ request('search') }}" placeholder="Cari Kode atau Nama Mapel..." 
                            class="w-full h-10 pl-9 pr-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                    </form>

                    <div class="flex items-center space-x-2 text-xs text-slate-500 font-medium shrink-0">
                        @if (request('search'))
                            <a href="{{ route('admin.mapel.index') }}" class="h-10 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold flex items-center transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Table Body (INTERNAL SCROLL WITH STICKY THEAD) -->
                <div class="flex-1 overflow-y-auto overflow-x-auto min-h-0">
                    <table class="w-full text-left border-collapse text-xs" id="tableMapel">
                        <thead class="sticky top-0 bg-white border-b border-slate-200 z-10 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="py-3 px-4 w-40 bg-white">KODE MAPEL</th>
                                <th class="py-3 px-4 bg-white">NAMA MATA PELAJARAN</th>
                                <th class="py-3 px-4 text-center w-24 bg-white">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($mapelList as $m)
                                <tr class="hover:bg-slate-50/80 transition-colors mapel-row" data-search="{{ strtolower($m->kode_mapel . ' ' . $m->nama_mapel) }}">
                                    <td class="py-3 px-4 font-semibold text-slate-900 font-mono text-xs tabular-nums">{{ $m->kode_mapel }}</td>
                                    <td class="py-3 px-4 font-medium text-slate-900 text-xs">{{ $m->nama_mapel }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button type="button" 
                                                onclick="openEditMapelModal('{{ $m->kode_mapel }}', '{{ addslashes($m->nama_mapel) }}')"
                                                class="w-7 h-7 rounded-lg border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Edit Mapel">
                                                <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <form action="{{ route('admin.mapel.destroy', $m->kode_mapel) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mapel {{ addslashes($m->nama_mapel) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 rounded-lg border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Hapus Mapel">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-12 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
                                        Tidak ada data mata pelajaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Pagination Bar (PERSIS GAMBAR 2) -->
                <div class="shrink-0">
                    <x-pagination-bar :paginator="$mapelList" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT MAPEL -->
<div id="modalEditMapel" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-xl border border-slate-200 shadow-xl max-w-sm w-full p-5 space-y-3.5">
        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
            <span class="font-bold text-slate-900 text-xs uppercase tracking-tight flex items-center space-x-2">
                <i data-lucide="edit-2" class="w-4 h-4 text-slate-700"></i>
                <span>Edit Mata Pelajaran</span>
            </span>
            <button type="button" onclick="closeEditMapelModal()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditMapel" method="POST" class="space-y-3">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_kode_mapel" class="block text-xs font-semibold text-slate-700 mb-1">Kode Mapel *</label>
                <input type="text" name="kode_mapel" id="edit_kode_mapel" required
                    class="w-full h-10 px-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800">
            </div>

            <div>
                <label for="edit_nama_mapel" class="block text-xs font-semibold text-slate-700 mb-1">Nama Mata Pelajaran *</label>
                <input type="text" name="nama_mapel" id="edit_nama_mapel" required
                    class="w-full h-10 px-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-slate-800">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2.5 border-t border-slate-100">
                <button type="button" onclick="closeEditMapelModal()" class="h-9 px-4 border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-9 px-4 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer shadow-2xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let isFolded = false;
    function toggleFormPanel() {
        const formPanel = document.getElementById('formPanel');
        const tableCol = document.getElementById('tableCol');
        const foldText = document.getElementById('foldText');

        if (!isFolded) {
            formPanel.style.display = 'none';
            tableCol.className = 'lg:col-span-3 flex flex-col h-full min-h-0';
            foldText.innerText = 'Buka Form Tambah';
            isFolded = true;
        } else {
            formPanel.style.display = 'block';
            tableCol.className = 'lg:col-span-2 flex flex-col h-full min-h-0';
            foldText.innerText = 'Sembunyikan Form Tambah';
            isFolded = false;
        }
    }

    function openEditMapelModal(kode, nama) {
        document.getElementById('edit_kode_mapel').value = kode;
        document.getElementById('edit_nama_mapel').value = nama;
        document.getElementById('formEditMapel').action = `/admin/mapel/${kode}`;
        document.getElementById('modalEditMapel').classList.remove('hidden');
    }

    function closeEditMapelModal() {
        document.getElementById('modalEditMapel').classList.add('hidden');
    }

    // Live Instant Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchMapel');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.mapel-row');
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
