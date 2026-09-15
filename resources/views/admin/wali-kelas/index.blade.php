@extends('layouts.app')

@section('title', 'Kelola Wali Kelas - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-3">
    <!-- Header Halaman -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-lg bg-[#1E2538] text-white flex items-center justify-center shadow-xs">
                <i data-lucide="user-cog" class="w-4 h-4"></i>
            </div>
            <div>
                <h1 class="text-base font-bold text-slate-900 tracking-tight leading-none">Kelola Wali Kelas</h1>
                <p class="text-[11px] text-slate-500 mt-0.5">Plotting & Penugasan Guru sebagai Wali Kelas untuk Setiap Rombel</p>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-50 text-emerald-800 rounded-lg border border-emerald-200">
                Terisi: {{ $totalPlotting }} / {{ $totalKelas }} Kelas
            </span>
        </div>
    </div>

    <!-- Main Card Container -->
    <div class="flex-1 min-h-0 flex flex-col bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
        <!-- Top Control Bar (Search Input) -->
        <div class="shrink-0 px-3.5 py-2.5 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <form action="{{ route('admin.wali-kelas.index') }}" method="GET" class="flex flex-1 items-center gap-2 w-full">
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="search" id="liveSearchWaliKelas" value="{{ request('search') }}" placeholder="Cari Nama Kelas, NIP, atau Nama Wali Kelas..." 
                        class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                @if (request('search'))
                    <a href="{{ route('admin.wali-kelas.index') }}" class="h-8 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold flex items-center transition-colors shrink-0">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Container -->
        <div class="flex-1 overflow-y-auto overflow-x-auto min-h-0">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="sticky top-0 bg-white border-b border-slate-200 z-10 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-2.5 px-3.5 text-center w-12 bg-white">NO</th>
                        <th class="py-2.5 px-3.5 bg-white w-48">NAMA KELAS</th>
                        <th class="py-2.5 px-3.5 bg-white">WALI KELAS SAAT INI</th>
                        <th class="py-2.5 px-3.5 text-right bg-white w-96">ATUR WALI KELAS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700" id="waliKelasTbody">
                    @include('admin.wali-kelas.partials.rows', ['kelasList' => $kelasList, 'guruList' => $guruList])
                </tbody>
            </table>
        </div>

        <!-- Bottom Pagination Bar -->
        <div class="shrink-0 border-t border-slate-200" id="paginationContainer">
            <x-pagination-bar :paginator="$kelasList" />
        </div>
    </div>
</div>

<!-- Modal Form Reset / Kosongkan Wali Kelas -->
<form id="formResetWaliKelas" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmResetWaliKelas(idKelas, namaKelas) {
        if (confirm(`Apakah Anda yakin ingin mengosongkan Wali Kelas untuk ${namaKelas}?`)) {
            const form = document.getElementById('formResetWaliKelas');
            form.action = `/admin/wali-kelas/${idKelas}`;
            form.submit();
        }
    }

    // Dynamic AJAX Live Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchWaliKelas');
        const searchForm = searchInput ? searchInput.closest('form') : null;
        let ajaxTimer = null;

        function performAjaxSearch() {
            if (!searchInput) return;
            const q = searchInput.value.trim();
            const form = searchForm || searchInput.form;

            const formData = new FormData(form);
            formData.set('search', q);
            const params = new URLSearchParams(formData);

            fetch(`{{ route('admin.wali-kelas.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('waliKelasTbody');
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
                if (typeof initSearchableSelects === 'function') {
                    initSearchableSelects();
                }
            })
            .catch(err => console.error('AJAX Search Error:', err));
        }

        if (typeof initSearchableSelects === 'function') {
            initSearchableSelects();
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
