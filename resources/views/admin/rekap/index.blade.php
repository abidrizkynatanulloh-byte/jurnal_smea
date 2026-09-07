@extends('layouts.app')

@section('title', 'Rekap Jurnal & Kehadiran - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-2.5">
    <!-- Header Halaman (Clean Executive Bar - FIXED) -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-3">
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Rekapitulasi Jurnal & Kehadiran</h1>
            <span class="px-2 py-0.5 text-[11px] font-semibold bg-slate-200 text-slate-700 rounded-md font-mono tabular-nums">
                {{ $jurnalTersimpan->count() }} Tersimpan
            </span>
        </div>
    </div>

    <!-- FILTER TANGGAL + KELAS (SESUAI GAMBAR 1 - FIXED) -->
    <div class="shrink-0 bg-white border border-slate-200/90 rounded-xl shadow-2xs p-3">
        <form action="{{ route('admin.rekap.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5 items-end">
            <div class="flex-1 w-full">
                <label for="tanggal" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Pilih Tanggal</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                        class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800 cursor-pointer">
                </div>
            </div>

            <div class="flex-1 w-full">
                <label for="kelas" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Filter Kelas</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="school" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kelas" id="kelas"
                        class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer appearance-none">
                        <option value="">Semua Kelas</option>
                        @foreach($daftarKelas as $kls)
                            <option value="{{ $kls->id_kelas }}" {{ $filterKelas == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex space-x-1.5 w-full sm:w-auto">
                <button type="submit" class="h-8 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer shadow-2xs">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan</span>
                </button>
                <a href="{{ route('admin.rekap.index') }}" class="h-8 px-3 border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1">
                    <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </div>

    <!-- SCROLLABLE BODY AREA -->
    <div class="flex-1 min-h-0 overflow-y-auto space-y-3">
        <!-- SECTION 1: STATUS KEHADIRAN GURU -->
        <div class="bg-white border border-slate-200 rounded-lg shadow-2xs overflow-hidden shrink-0">
            <div class="px-3.5 py-2 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between bg-slate-50 gap-2">
                <div class="flex items-center space-x-2">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-rose-600"></i>
                    <span class="font-bold text-slate-900 text-xs uppercase tracking-tight">Status Kehadiran & Jurnal Guru</span>
                </div>
                <div class="flex items-center space-x-1.5 flex-wrap">
                    <span class="px-2 py-0.5 bg-rose-50 border border-rose-200 text-rose-700 text-[11px] font-semibold rounded font-mono tabular-nums">
                        {{ $guruAlpaList->where('status_rekap', 'Alpa')->count() }} Alpa
                    </span>
                    @if($guruAlpaList->filter(fn($g) => str_contains($g->status_rekap, 'Sah'))->count() > 0)
                    <span class="px-2 py-0.5 bg-blue-50 border border-blue-200 text-blue-700 text-[11px] font-semibold rounded font-mono tabular-nums">
                        {{ $guruAlpaList->filter(fn($g) => str_contains($g->status_rekap, 'Sah'))->count() }} Izin Sah
                    </span>
                    @endif
                    <span class="px-2 py-0.5 bg-amber-50 border border-amber-200 text-amber-700 text-[11px] font-semibold rounded font-mono tabular-nums">
                        {{ $guruAlpaList->where('status_rekap', 'Terjadwal')->count() }} Terjadwal
                    </span>
                    <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-600 text-[11px] font-semibold rounded font-mono tabular-nums">
                        Total {{ $guruAlpaList->count() }}
                    </span>
                </div>
            </div>

            @if($guruAlpaList->count() > 0)
            <div class="p-3" id="slideContainer">
                <!-- Search Guru -->
                <div class="mb-2">
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" id="searchGuru" placeholder="Cari nama guru..."
                            class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-300 rounded-md text-xs text-slate-900 focus:outline-none focus:border-slate-900">
                    </div>
                </div>

                <!-- List Items (10 per slide) -->
                <div id="guruCardsGrid" class="divide-y divide-slate-100">
                    @foreach($guruAlpaList as $index => $ga)
                        <div class="guru-card py-2 flex flex-col sm:flex-row sm:items-center justify-between gap-2 transition-colors hover:bg-slate-50/80 px-2 rounded-md"
                            data-index="{{ $index }}"
                            data-nama="{{ strtolower($ga->nama_guru) }}">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-6.5 h-6.5 rounded flex items-center justify-center text-[11px] font-bold shrink-0
                                    @if($ga->status_rekap === 'Alpa') bg-rose-50 text-rose-700 border border-rose-200
                                    @elseif(str_contains($ga->status_rekap, 'Sah')) bg-blue-50 text-blue-700 border border-blue-200
                                    @else bg-slate-100 text-slate-600 border border-slate-200 @endif">
                                    {{ strtoupper(substr($ga->nama_guru, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-900 leading-tight">{{ $ga->nama_guru }}</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $ga->nama_mapel }} • {{ $ga->nama_kelas }} (Jam {{ $ga->jam_mulai }}-{{ $ga->jam_selesai }})
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-block px-2 py-0.5 rounded text-[10.5px] font-semibold border
                                    @if($ga->status_rekap === 'Alpa') bg-rose-50 text-rose-700 border-rose-200
                                    @elseif(str_contains($ga->status_rekap, 'Sah')) bg-blue-50 text-blue-700 border-blue-200
                                    @else bg-amber-50 text-amber-700 border-amber-200 @endif">
                                    {{ $ga->status_rekap }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- PAGINATION BAR UNTUK SLIDE GURU (SESUAI GAMBAR 5) -->
                <div class="pt-2 mt-2 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-2 select-none text-xs">
                    <span id="slideInfo" class="text-slate-500 font-medium font-mono tabular-nums">Menampilkan 1-10 dari {{ $guruAlpaList->count() }} guru</span>
                    <div class="flex items-center space-x-1">
                        <button type="button" id="btnPrev" class="w-8 h-8 rounded bg-[#1B2533] text-white flex items-center justify-center font-bold disabled:opacity-40 transition-colors shadow-2xs" disabled>
                            <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                        </button>
                        <div id="pageNumbers" class="flex items-center space-x-1"></div>
                        <button type="button" id="btnNext" class="w-8 h-8 flex items-center justify-center text-slate-700 hover:text-black font-bold disabled:opacity-30 transition-colors">
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </div>
            </div>
            @else
            <div class="p-6 text-center text-slate-400 italic text-xs">
                <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1 text-emerald-500"></i>
                Semua guru telah mengisi jurnal atau tidak ada jadwal mengajar pada tanggal ini.
            </div>
            @endif
        </div>

        <!-- SECTION 2: TABEL JURNAL TERISI -->
        <div class="bg-white border border-slate-200 rounded-lg shadow-2xs overflow-hidden flex flex-col min-h-[350px]">
            <div class="px-3.5 py-2 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-white shrink-0">
                <span class="font-bold text-slate-900 text-xs uppercase tracking-tight flex items-center space-x-2">
                    <i data-lucide="book-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Jurnal Tersimpan ({{ $jurnalTersimpan->count() }} Terisi)</span>
                </span>
                <!-- Search Jurnal -->
                <div class="relative max-w-xs w-full">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" id="searchJurnal" placeholder="Cari guru / mapel / kelas..."
                        class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-300 rounded-md text-xs text-slate-900 focus:outline-none focus:border-slate-900">
                </div>
            </div>

            <div class="overflow-x-auto overflow-y-auto max-h-[420px]">
                <table class="w-full text-left border-collapse text-xs" id="tabelJurnal">
                    <thead class="sticky top-0 bg-slate-50 border-b border-slate-200 z-10 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <tr>
                            <th class="py-2 px-3 w-12 text-center bg-slate-50">No</th>
                            <th class="py-2 px-3 bg-slate-50">Guru & Mapel</th>
                            <th class="py-2 px-3 w-28 bg-slate-50">Kelas</th>
                            <th class="py-2 px-3 w-28 bg-slate-50">Kehadiran</th>
                            <th class="py-2 px-3 bg-slate-50">Materi Pembelajaran</th>
                            <th class="py-2 px-3 text-center w-20 bg-slate-50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($jurnalTersimpan as $index => $jt)
                            <tr class="hover:bg-slate-50/90 transition-colors jurnal-row"
                                data-search="{{ ($jt->jadwal && $jt->jadwal->guru ? strtolower($jt->jadwal->guru->nama_guru) : '') . ' ' . ($jt->jadwal && $jt->jadwal->mapel ? strtolower($jt->jadwal->mapel->nama_mapel) : '') . ' ' . ($jt->jadwal && $jt->jadwal->kelas ? strtolower($jt->jadwal->kelas->nama_kelas) : '') }}">
                                <td class="py-2 px-3 text-center font-medium text-slate-400 row-number tabular-nums">{{ $index + 1 }}</td>
                                <td class="py-2 px-3">
                                    <div class="font-semibold text-slate-900 leading-tight">{{ $jt->jadwal && $jt->jadwal->guru ? $jt->jadwal->guru->nama_guru : '-' }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $jt->jadwal && $jt->jadwal->mapel ? $jt->jadwal->mapel->nama_mapel : '-' }}</div>
                                </td>
                                <td class="py-2 px-3">
                                    <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[11px] font-medium text-slate-700">
                                        {{ $jt->jadwal && $jt->jadwal->kelas ? $jt->jadwal->kelas->nama_kelas : '-' }}
                                    </span>
                                </td>
                                <td class="py-2 px-3">
                                    <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10.5px] font-semibold rounded">
                                        {{ $jt->status_kehadiran_guru ?? 'Hadir' }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 font-medium text-slate-800 max-w-sm truncate" title="{{ $jt->materi }}">{{ $jt->materi ?? '-' }}</td>
                                <td class="py-2 px-3 text-center">
                                    <a href="{{ route('admin.rekap.show', $jt->id_jurnal) }}" class="inline-flex items-center space-x-1 px-2.5 py-1 rounded border border-slate-200 hover:bg-slate-100 text-xs font-semibold text-slate-700 transition-colors shadow-2xs">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>Detail</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyJurnal">
                                <td colspan="6" class="py-8 text-center text-slate-400 italic text-xs">
                                    <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                                    Belum ada jurnal yang disimpan pada tanggal {{ $tanggal }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ========== SLIDE PAGINATION FOR GURU CARDS (IMAGE 5 STYLE) ==========
    const cards = document.querySelectorAll('.guru-card');
    const perPage = 10;
    let currentPage = 1;
    let filteredCards = Array.from(cards);

    function renderSlide() {
        const totalPages = Math.ceil(filteredCards.length / perPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;

        cards.forEach(c => c.style.display = 'none');

        const start = (currentPage - 1) * perPage;
        const end = Math.min(start + perPage, filteredCards.length);
        for (let i = start; i < end; i++) {
            filteredCards[i].style.display = '';
        }

        const infoEl = document.getElementById('slideInfo');
        if (infoEl) {
            if (filteredCards.length === 0) {
                infoEl.textContent = 'Tidak ada data ditemukan';
            } else {
                infoEl.textContent = `Menampilkan ${start + 1}-${end} dari ${filteredCards.length} data`;
            }
        }

        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        if (btnPrev) btnPrev.disabled = (currentPage <= 1);
        if (btnNext) btnNext.disabled = (currentPage >= totalPages);

        const pageNumsEl = document.getElementById('pageNumbers');
        if (pageNumsEl) {
            pageNumsEl.innerHTML = '';
            for (let p = 1; p <= totalPages; p++) {
                if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = p;
                    if (p === currentPage) {
                        btn.className = 'w-8 h-8 rounded bg-[#1B2533] text-white font-bold text-xs flex items-center justify-center shadow-2xs';
                    } else {
                        btn.className = 'min-w-[2rem] h-8 px-2 flex items-center justify-center text-xs font-medium text-slate-700 hover:text-black hover:bg-slate-100 rounded transition-colors';
                    }
                    btn.addEventListener('click', () => { currentPage = p; renderSlide(); });
                    pageNumsEl.appendChild(btn);
                } else if (p === currentPage - 2 || p === currentPage + 2) {
                    const dots = document.createElement('span');
                    dots.className = 'px-1 text-xs text-slate-400 font-bold';
                    dots.textContent = '...';
                    pageNumsEl.appendChild(dots);
                }
            }
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    if (btnPrev) btnPrev.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderSlide(); } });
    if (btnNext) btnNext.addEventListener('click', () => { currentPage++; renderSlide(); });

    const searchGuruInput = document.getElementById('searchGuru');
    if (searchGuruInput) {
        searchGuruInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            filteredCards = Array.from(cards).filter(c => (c.getAttribute('data-nama') || '').includes(query));
            currentPage = 1;
            renderSlide();
        });
    }

    renderSlide();

    // ========== INSTANT SEARCH TABEL JURNAL ==========
    const searchJurnalInput = document.getElementById('searchJurnal');
    if (searchJurnalInput) {
        searchJurnalInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.jurnal-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.getAttribute('data-search') || '';
                if (text.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                    const rowNum = row.querySelector('.row-number');
                    if (rowNum) rowNum.textContent = visibleCount;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('emptyJurnal');
            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        });
    }
});
</script>
@endpush
@endsection
