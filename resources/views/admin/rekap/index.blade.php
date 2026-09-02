@extends('layouts.app')

@section('title', 'Rekap Jurnal & Kehadiran - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Rekapitulasi Jurnal & Kehadiran</h1>
            <p class="text-xs text-gray-500 mt-1">
                Pantau laporan pengisian jurnal mengajar harian guru dan absensi siswa
            </p>
        </div>
    </div>

    <!-- FILTER TANGGAL + KELAS -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-5">
        <form action="{{ route('admin.rekap.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label for="tanggal" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Tanggal</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                        class="block w-full pl-11 pr-4 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                </div>
            </div>

            <div class="flex-1 w-full">
                <label for="kelas" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Filter Kelas</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="school" class="w-5 h-5"></i>
                    </div>
                    <select name="kelas" id="kelas"
                        class="block w-full pl-11 pr-4 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer appearance-none">
                        <option value="">Semua Kelas</option>
                        @foreach($daftarKelas as $kls)
                            <option value="{{ $kls->id_kelas }}" {{ $filterKelas == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex space-x-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-initial px-5 py-2.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer shadow-sm">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Terapkan</span>
                </button>
                <a href="{{ route('admin.rekap.index') }}" class="px-5 py-2.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </div>

    <!-- SECTION 1: LAPORAN GURU BELUM ISI JURNAL (SLIDE CARD) -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex flex-col sm:flex-row sm:items-center justify-between bg-rose-50/30 gap-3">
            <div class="flex items-center space-x-2">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600"></i>
                <h3 class="font-bold text-[#1E2538] text-base">Laporan Status Kehadiran Guru</h3>
            </div>
            <div class="flex items-center space-x-2 flex-wrap gap-1.5">
                <span class="px-2.5 py-1 bg-rose-100 text-rose-800 text-xs font-extrabold rounded-full">
                    {{ $guruAlpaList->where('status_rekap', 'Alpa')->count() }} Alpa
                </span>
                @if($guruAlpaList->filter(fn($g) => str_contains($g->status_rekap, 'Sah'))->count() > 0)
                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                    {{ $guruAlpaList->filter(fn($g) => str_contains($g->status_rekap, 'Sah'))->count() }} Izin Sah
                </span>
                @endif
                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">
                    {{ $guruAlpaList->where('status_rekap', 'Terjadwal')->count() }} Terjadwal
                </span>
                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">
                    Total {{ $guruAlpaList->count() }}
                </span>
            </div>
        </div>

        @if($guruAlpaList->count() > 0)
        <div class="p-6" id="slideContainer">
            <!-- Search Guru -->
            <div class="mb-4">
                <div class="relative max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="searchGuru" placeholder="Cari nama guru..."
                        class="block w-full pl-9 pr-4 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>
            </div>

            <!-- List Items (6 per slide) -->
            <div id="guruCardsGrid" class="divide-y divide-gray-100">
                @foreach($guruAlpaList as $index => $ga)
                <div class="guru-card py-3.5 px-2 flex items-center justify-between transition-all hover:bg-gray-50/50"
                    data-guru-name="{{ $ga->guru ? strtolower($ga->guru->nama_guru) : '' }}">
                    <div class="flex items-center space-x-3 min-w-0 flex-1">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0
                            {{ $ga->status_rekap === 'Alpa' ? 'bg-rose-500' : (str_contains($ga->status_rekap, 'Sah') ? 'bg-blue-500' : 'bg-amber-500') }}">
                            {{ $ga->guru ? strtoupper(substr($ga->guru->nama_guru, 0, 1)) : '?' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="font-bold text-[#1E2538] text-sm leading-tight truncate">{{ $ga->guru ? $ga->guru->nama_guru : '-' }}</h4>
                            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $ga->mapel ? $ga->mapel->nama_mapel : '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 shrink-0 ml-3">
                        <div class="flex items-center space-x-1 text-xs text-gray-400">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            <span>Jam {{ $ga->jam_mulai }} - {{ $ga->jam_selesai }}</span>
                        </div>
                        <span class="px-2 py-0.5 bg-gray-100 rounded-md text-xs font-bold text-gray-600">{{ $ga->kelas ? $ga->kelas->nama_kelas : '-' }}</span>
                        @if ($ga->status_rekap === 'Alpa')
                            <span class="inline-flex items-center px-2.5 py-1 bg-rose-100 text-rose-800 border border-rose-300 text-[10px] font-extrabold rounded-full space-x-1">
                                <span class="w-1.5 h-1.5 bg-rose-600 rounded-full animate-ping"></span>
                                <span>ALPA</span>
                            </span>
                        @elseif (str_contains($ga->status_rekap, 'Sah'))
                            <span class="inline-flex items-center px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold rounded-full space-x-1">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                <span>{{ $ga->status_rekap }}</span>
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-semibold rounded-full space-x-1">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                <span>Terjadwal</span>
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Slide Pagination Controls -->
            <div id="slidePagination" class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400" id="slideInfo">Menampilkan 1-6 dari {{ $guruAlpaList->count() }} data</p>
                <div class="flex items-center space-x-2">
                    <button type="button" id="btnPrev"
                        class="px-3.5 py-2 rounded-lg border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 text-sm font-semibold transition-colors flex items-center space-x-1 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        <span>Sebelumnya</span>
                    </button>
                    <div id="pageNumbers" class="flex items-center space-x-1"></div>
                    <button type="button" id="btnNext"
                        class="px-3.5 py-2 rounded-lg border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 text-sm font-semibold transition-colors flex items-center space-x-1 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                        <span>Selanjutnya</span>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="py-10 text-center">
            <div class="flex flex-col items-center justify-center space-y-1.5">
                <i data-lucide="check-circle-2" class="w-8 h-8 text-emerald-600"></i>
                <span class="text-emerald-700 font-bold">Luar Biasa! Semua guru telah mengisi jurnal pada tanggal {{ $tanggal }}.</span>
            </div>
        </div>
        @endif
    </div>

    <!-- SECTION 2: JURNAL TERSIMPAN (SELESAI) -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                <i data-lucide="book-check" class="w-5 h-5 text-emerald-600"></i>
                <span>Jurnal Tersimpan ({{ $jurnalTersimpan->count() }} Terisi)</span>
            </h3>
            <!-- Search Jurnal -->
            <div class="relative max-w-xs w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" id="searchJurnal" placeholder="Cari guru / mapel / kelas..."
                    class="block w-full pl-9 pr-4 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
            </div>
        </div>

        <div class="overflow-x-auto max-h-[580px] overflow-y-auto">
            <table class="w-full text-left border-collapse" id="tabelJurnal">
                <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                    <tr class="text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6 w-16 text-center">No</th>
                        <th class="py-4 px-6">Guru & Mapel</th>
                        <th class="py-4 px-6 w-32">Kelas</th>
                        <th class="py-4 px-6 w-32">Kehadiran</th>
                        <th class="py-4 px-6">Materi Pembelajaran</th>
                        <th class="py-4 px-6 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse ($jurnalTersimpan as $index => $jt)
                        <tr class="hover:bg-gray-50/50 transition-colors jurnal-row"
                            data-search="{{ ($jt->jadwal && $jt->jadwal->guru ? strtolower($jt->jadwal->guru->nama_guru) : '') . ' ' . ($jt->jadwal && $jt->jadwal->mapel ? strtolower($jt->jadwal->mapel->nama_mapel) : '') . ' ' . ($jt->jadwal && $jt->jadwal->kelas ? strtolower($jt->jadwal->kelas->nama_kelas) : '') }}">
                            <td class="py-4 px-6 text-center font-medium text-gray-400 row-number">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-[#1E2538]">{{ $jt->jadwal && $jt->jadwal->guru ? $jt->jadwal->guru->nama_guru : '-' }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $jt->jadwal && $jt->jadwal->mapel ? $jt->jadwal->mapel->nama_mapel : '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 bg-gray-100 rounded-md text-xs font-bold text-gray-700">
                                    {{ $jt->jadwal && $jt->jadwal->kelas ? $jt->jadwal->kelas->nama_kelas : '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full space-x-1 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    <span>{{ $jt->status_kehadiran_guru ?? 'Hadir' }}</span>
                                </span>
                            </td>
                            <td class="py-4 px-6 font-medium text-[#1E2538] max-w-sm truncate" title="{{ $jt->materi }}">{{ $jt->materi ?? '-' }}</td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('admin.rekap.show', $jt->id_jurnal) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-[#405078] bg-[#405078]/10 hover:bg-[#405078]/20 transition-colors">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyJurnal">
                            <td colspan="6" class="py-8 text-center text-gray-400 italic">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                Belum ada jurnal yang disimpan pada tanggal {{ $tanggal }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ========== SLIDE PAGINATION FOR GURU CARDS ==========
    const cards = document.querySelectorAll('.guru-card');
    const perPage = 10;
    let currentPage = 1;
    let filteredCards = Array.from(cards);

    function renderSlide() {
        const totalPages = Math.ceil(filteredCards.length / perPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;

        // Hide all cards
        cards.forEach(c => c.style.display = 'none');

        // Show only current page cards from filtered set
        const start = (currentPage - 1) * perPage;
        const end = Math.min(start + perPage, filteredCards.length);
        for (let i = start; i < end; i++) {
            filteredCards[i].style.display = '';
        }

        // Update info text
        const infoEl = document.getElementById('slideInfo');
        if (infoEl) {
            if (filteredCards.length === 0) {
                infoEl.textContent = 'Tidak ada data ditemukan';
            } else {
                infoEl.textContent = `Menampilkan ${start + 1}-${end} dari ${filteredCards.length} data`;
            }
        }

        // Update buttons
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        if (btnPrev) btnPrev.disabled = (currentPage <= 1);
        if (btnNext) btnNext.disabled = (currentPage >= totalPages);

        // Page numbers (max 5 visible with ellipsis)
        const pageNumsEl = document.getElementById('pageNumbers');
        if (pageNumsEl) {
            pageNumsEl.innerHTML = '';
            let pages = [];
            if (totalPages <= 5) {
                for (let i = 1; i <= totalPages; i++) pages.push(i);
            } else {
                pages.push(1);
                if (currentPage > 3) pages.push('...');
                let s = Math.max(2, currentPage - 1);
                let e = Math.min(totalPages - 1, currentPage + 1);
                if (currentPage <= 3) { s = 2; e = 4; }
                if (currentPage >= totalPages - 2) { s = totalPages - 3; e = totalPages - 1; }
                for (let i = s; i <= e; i++) pages.push(i);
                if (currentPage < totalPages - 2) pages.push('...');
                pages.push(totalPages);
            }
            pages.forEach(function(p) {
                if (p === '...') {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.className = 'px-1 text-gray-400 text-xs';
                    pageNumsEl.appendChild(dots);
                } else {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = p;
                    btn.className = p === currentPage
                        ? 'w-8 h-8 rounded-lg bg-[#405078] text-white text-xs font-bold cursor-pointer'
                        : 'w-8 h-8 rounded-lg border border-[#D1D9EB] bg-white text-gray-600 text-xs font-semibold hover:bg-gray-50 cursor-pointer';
                    btn.addEventListener('click', function () {
                        currentPage = p;
                        renderSlide();
                    });
                    pageNumsEl.appendChild(btn);
                }
            });
        }
    }

    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    if (btnPrev) btnPrev.addEventListener('click', function () { if (currentPage > 1) { currentPage--; renderSlide(); } });
    if (btnNext) btnNext.addEventListener('click', function () { currentPage++; renderSlide(); });

    // Search guru
    const searchGuru = document.getElementById('searchGuru');
    if (searchGuru) {
        searchGuru.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            filteredCards = Array.from(cards).filter(c => {
                const name = c.getAttribute('data-guru-name') || '';
                return name.includes(q);
            });
            currentPage = 1;
            renderSlide();
        });
    }

    // Initial render
    if (cards.length > 0) renderSlide();

    // ========== SEARCH JURNAL TABLE ==========
    const searchJurnal = document.getElementById('searchJurnal');
    if (searchJurnal) {
        searchJurnal.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.jurnal-row');
            let visibleCount = 0;
            rows.forEach(function (row) {
                const data = row.getAttribute('data-search') || '';
                if (data.includes(q)) {
                    row.style.display = '';
                    visibleCount++;
                    row.querySelector('.row-number').textContent = visibleCount;
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endpush
@endsection
