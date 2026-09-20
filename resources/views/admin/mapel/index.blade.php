@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-2.5">
    <!-- Header Halaman (Sesuai Ukuran Gambar 1 - FIXED) -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-2.5">
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Mata Pelajaran</h1>
            <span class="px-2 py-0.5 text-[11px] font-semibold bg-slate-100 text-slate-700 rounded-md font-mono tabular-nums border border-slate-200">
                {{ $mapelList->total() }} Mapel
            </span>
        </div>
        <div class="flex items-center space-x-2 shrink-0 overflow-x-auto pb-1 sm:pb-0 scrollbar-none">
            <button type="button" onclick="openTambahMapelModal()"
                class="h-10 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shadow-2xs cursor-pointer whitespace-nowrap shrink-0">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>+ Tambah Mapel</span>
            </button>
        </div>
    </div>

    <!-- Layout Flex Utama (Full Viewport Height) -->
    <div class="flex-1 min-h-0 flex flex-col" id="masterDataGrid">
        <!-- DATA TABLE CARD -->
        <div id="tableCol" class="flex-1 flex flex-col h-full min-h-0">
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden flex flex-col h-full min-h-0">
                <!-- Top Control Bar (Search) -->
                <div class="shrink-0 px-3.5 py-2 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <form action="{{ route('admin.mapel.index') }}" method="GET" class="relative flex-1 max-w-sm">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="search" id="liveSearchMapel" value="{{ request('search') }}" placeholder="Cari Kode atau Nama Mapel..." 
                            class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                    </form>

                    <div class="flex items-center space-x-2 text-xs text-slate-500 font-medium shrink-0">
                        @if (request('search'))
                            <a href="{{ route('admin.mapel.index') }}" class="h-8 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold flex items-center transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Table Body (INTERNAL SCROLL SESUAI GAMBAR 1) -->
                <div class="flex-1 overflow-y-auto overflow-x-auto min-h-0">
                    <table class="w-full text-left border-collapse text-xs" id="tableMapel">
                        <thead class="sticky top-0 bg-white border-b border-slate-200 z-10 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="py-2.5 px-3.5 w-40 bg-white">KODE MAPEL</th>
                                <th class="py-2.5 px-3.5 bg-white">NAMA MATA PELAJARAN</th>
                                <th class="py-2.5 px-3.5 text-center w-20 bg-white">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700" id="mapelTbody">
                            @include('admin.mapel.partials.rows', ['mapelList' => $mapelList])
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Pagination Bar (Sesuai Gambar 1 & Gambar 2) -->
                <div class="shrink-0" id="paginationContainer">
                    <x-pagination-bar :paginator="$mapelList" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT MAPEL (Proporsional & Lebih Lega Sesuai Permintaan) -->
<div id="modalEditMapel" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#151B26] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-lg w-full p-6 sm:p-7 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <span class="font-bold text-slate-900 dark:text-slate-100 text-sm uppercase tracking-tight flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                </div>
                <span>Edit Mata Pelajaran</span>
            </span>
            <button type="button" onclick="closeEditMapelModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditMapel" method="POST" class="space-y-3.5" data-confirm="Apakah Anda yakin ingin memperbarui mata pelajaran ini?">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_kode_mapel" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kode Mapel *</label>
                <input type="text" name="kode_mapel" id="edit_kode_mapel" required
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-slate-800 transition-colors">
            </div>

            <div>
                <label for="edit_nama_mapel" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Mata Pelajaran *</label>
                <input type="text" name="nama_mapel" id="edit_nama_mapel" required
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-slate-800 transition-colors">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeEditMapelModal()" class="h-11 px-6 py-2.5 min-w-[100px] border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer flex items-center justify-center shadow-2xs">
                    Batal
                </button>
                <button type="submit" class="h-11 px-7 py-2.5 min-w-[160px] bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-sm font-bold transition-all cursor-pointer shadow-xs hover:shadow-md flex items-center justify-center space-x-2">
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Mapel (Pop-up Default Hidden) -->
<div id="modalTambahMapel" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 overflow-y-auto">
    <div class="bg-white dark:bg-[#1C2433] rounded-2xl max-w-md w-full p-5 sm:p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4 my-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base">Tambah Mapel Baru</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Isi kode dan nama mata pelajaran</p>
                </div>
            </div>
            <button type="button" onclick="closeTambahMapelModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-3.5">
            @csrf
            <div>
                <label for="kode_mapel" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Mapel *</label>
                <input type="text" name="kode_mapel" id="kode_mapel" value="{{ old('kode_mapel') }}" placeholder="Contoh: MP-MAT" required 
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-mono placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors">
            </div>

            <div>
                <label for="nama_mapel" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Mata Pelajaran *</label>
                <input type="text" name="nama_mapel" id="nama_mapel" value="{{ old('nama_mapel') }}" placeholder="Masukkan Nama Mapel" required
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeTambahMapelModal()" class="h-10 px-4 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-10 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-colors shadow-xs flex items-center space-x-1.5 cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Simpan Mapel</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openTambahMapelModal() {
        document.getElementById('modalTambahMapel').classList.remove('hidden');
    }

    function closeTambahMapelModal() {
        document.getElementById('modalTambahMapel').classList.add('hidden');
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

    // Dynamic AJAX Live Search Across All Mapel (Zero Page Reloads)
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchMapel');
        const searchForm = searchInput ? searchInput.closest('form') : null;
        let ajaxTimer = null;

        function performAjaxSearch() {
            if (!searchInput) return;
            const q = searchInput.value.trim();
            const form = searchForm || searchInput.form;

            const formData = new FormData(form);
            formData.set('search', q);
            const params = new URLSearchParams(formData);

            fetch(`{{ route('admin.mapel.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('mapelTbody');
                const pag = document.getElementById('paginationContainer');
                if (tbody && data.html) {
                    tbody.innerHTML = data.html;
                }
                if (pag && data.pagination) {
                    pag.innerHTML = data.pagination;
                }
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            })
            .catch(err => console.error('AJAX Live Search Error:', err));
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(ajaxTimer);
                ajaxTimer = setTimeout(() => {
                    performAjaxSearch();
                }, 200);
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(ajaxTimer);
                    performAjaxSearch();
                }
            });
        }
    });
</script>
@endsection
