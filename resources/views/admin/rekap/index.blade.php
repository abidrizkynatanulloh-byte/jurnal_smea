@extends('layouts.app')

@section('title', 'Rekap Jurnal & Kehadiran - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-2.5">
    <!-- Header Halaman (Clean Executive Bar - FIXED) -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-3">
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Rekapitulasi Jurnal & Kehadiran</h1>
            <span class="px-2.5 py-0.5 text-xs font-bold bg-emerald-50 dark:bg-emerald-950/70 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/80 rounded-lg font-mono tabular-nums shadow-2xs">
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
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                        class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800 cursor-pointer">
                </div>
            </div>

            <div class="flex-1 w-full relative z-20">
                <label for="kelas" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Filter Kelas</label>
                <div class="relative">
                    <select name="kelas" id="kelas" onchange="this.form.submit()"
                        class="searchable-select w-full h-8 pl-2.5 pr-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-900 dark:text-slate-200 font-medium focus:outline-none focus:border-slate-800 cursor-pointer appearance-none">
                        <option value="">Semua Kelas</option>
                        @foreach($daftarKelas as $kls)
                            <option value="{{ $kls->id_kelas }}" {{ $filterKelas == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex space-x-1.5 w-full sm:w-auto">
                <a href="{{ route('admin.rekap.index') }}" class="h-8 px-3 border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1">
                    <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </div>

    <!-- SCROLLABLE BODY AREA -->
    <div class="flex-1 min-h-0 overflow-y-auto space-y-3">
        <!-- SECTION 0: CHARTS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 shrink-0">
            <!-- Area Chart (Trend 7 Hari) -->
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-2xs overflow-hidden flex flex-col">
                <div class="px-3.5 py-2 border-b border-slate-200 bg-slate-50 flex items-center space-x-2">
                    <i data-lucide="trending-up" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span class="font-bold text-slate-900 text-xs uppercase tracking-tight">Tren Jurnal Terisi (7 Hari Terakhir)</span>
                </div>
                <div class="p-3 flex-1 min-h-[220px]">
                    <div id="areaChart"></div>
                </div>
            </div>

            <!-- Pie Chart (Proporsi Hari Ini) -->
            <div class="bg-white border border-slate-200 rounded-lg shadow-2xs overflow-hidden flex flex-col">
                <div class="px-3.5 py-2 border-b border-slate-200 bg-slate-50 flex items-center space-x-2">
                    <i data-lucide="pie-chart" class="w-3.5 h-3.5 text-blue-600"></i>
                    <span class="font-bold text-slate-900 text-xs uppercase tracking-tight">Persentase Hari Ini</span>
                </div>
                <div class="p-3 flex-1 flex flex-col items-center justify-center min-h-[220px]">
                    <div id="pieChart" class="w-full flex justify-center"></div>
                    <div class="mt-3 text-center w-full">
                        @php
                            $totalPie = $chartData['pie']['Terisi'] + $chartData['pie']['Alpa'] + $chartData['pie']['Izin'] + $chartData['pie']['Terjadwal'];
                            $pctTerisi = $totalPie > 0 ? round(($chartData['pie']['Terisi'] / $totalPie) * 100, 1) : 0;
                        @endphp
                        <div class="text-xs font-bold text-slate-800">Tingkat Pengisian: {{ $pctTerisi }}%</div>
                        <div class="text-[11px] text-slate-500">{{ $chartData['pie']['Terisi'] }} dari {{ $totalPie }} Jadwal Terisi</div>
                    </div>
                </div>
            </div>
        </div>
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
                        @php
                            $namaGuruVal = $ga->guru ? $ga->guru->nama_guru : 'Guru Tidak Terdaftar';
                            $nipVal      = $ga->guru ? $ga->guru->nip : '-';
                            $mapelVal    = $ga->mapel ? $ga->mapel->nama_mapel : '-';
                            $kelasVal    = $ga->kelas ? $ga->kelas->nama_kelas : '-';
                            $ruanganVal  = $ga->ruangan ? $ga->ruangan->nama_ruangan : '-';
                        @endphp
                        <div class="guru-card py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 transition-all hover:bg-slate-100/70 px-2.5 rounded-lg cursor-pointer group"
                            data-index="{{ $index }}"
                            data-nama="{{ strtolower($namaGuruVal . ' ' . $mapelVal . ' ' . $kelasVal) }}"
                            onclick="openDetailModal('{{ addslashes($namaGuruVal) }}', '{{ addslashes($nipVal) }}', '{{ addslashes($mapelVal) }}', '{{ addslashes($kelasVal) }}', '{{ addslashes($ruanganVal) }}', '{{ $ga->jam_mulai }}', '{{ $ga->jam_selesai }}', '{{ addslashes($ga->status_rekap) }}', '{{ $tanggal }}', '{{ $namaHari }}')">
                            <div class="flex items-center space-x-3">
                                <div class="w-7 h-7 rounded-md flex items-center justify-center text-xs font-bold shrink-0 shadow-2xs
                                    @if($ga->status_rekap === 'Alpa') bg-rose-100 text-rose-800 border border-rose-200
                                    @elseif(str_contains($ga->status_rekap, 'Sah')) bg-blue-100 text-blue-800 border border-blue-200
                                    @else bg-amber-100 text-amber-800 border border-amber-200 @endif">
                                    {{ strtoupper(substr($namaGuruVal, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 leading-tight group-hover:text-indigo-600 transition-colors">
                                        {{ $namaGuruVal }}
                                    </h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5 flex items-center space-x-1">
                                        <span class="font-medium text-slate-700">{{ $mapelVal }}</span>
                                        <span>•</span>
                                        <span>{{ $kelasVal }}</span>
                                        <span>•</span>
                                        <span class="font-mono text-slate-600">Jam {{ $ga->jam_mulai }}-{{ $ga->jam_selesai }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <span class="inline-block px-2.5 py-0.5 rounded text-[10.5px] font-bold border shadow-2xs
                                    @if($ga->status_rekap === 'Alpa') bg-rose-50 text-rose-700 border-rose-200
                                    @elseif(str_contains($ga->status_rekap, 'Sah')) bg-blue-50 text-blue-700 border-blue-200
                                    @else bg-amber-50 text-amber-700 border-amber-200 @endif">
                                    {{ $ga->status_rekap }}
                                </span>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition-all"></i>
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

<!-- MODAL DETAIL STATUS KEHADIRAN GURU -->
<div id="modalDetailStatusGuru" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-xl shadow-xl max-w-md w-full p-5 space-y-4">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-[#1E293B] text-white flex items-center justify-center font-bold shadow-2xs">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Detail Status Sesi Mengajar</h3>
                    <p class="text-[11px] text-slate-500 font-medium" id="modalTanggalHari">-</p>
                </div>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="space-y-3">
            <!-- Guru Info Box -->
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-[#1E293B] text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-2xs" id="modalAvatarGuru">
                    G
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="font-bold text-slate-900 text-xs truncate" id="modalNamaGuru">-</h4>
                    <p class="text-[11px] text-slate-500 font-mono mt-0.5" id="modalNipGuru">NIP: -</p>
                </div>
            </div>

            <!-- Grid Details -->
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="p-2.5 bg-white border border-slate-200 rounded-lg space-y-0.5">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Mata Pelajaran</span>
                    <span class="font-bold text-slate-800 block truncate" id="modalMapel">-</span>
                </div>
                <div class="p-2.5 bg-white border border-slate-200 rounded-lg space-y-0.5">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Kelas</span>
                    <span class="font-bold text-slate-800 block truncate" id="modalKelas">-</span>
                </div>
                <div class="p-2.5 bg-white border border-slate-200 rounded-lg space-y-0.5">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Jam Pelajaran</span>
                    <span class="font-bold text-slate-800 block truncate font-mono" id="modalJam">-</span>
                </div>
                <div class="p-2.5 bg-white border border-slate-200 rounded-lg space-y-0.5">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Ruangan</span>
                    <span class="font-bold text-slate-800 block truncate" id="modalRuangan">-</span>
                </div>
            </div>

            <!-- Status Box -->
            <div class="p-3 border border-slate-200 bg-slate-50/50 rounded-lg flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-semibold uppercase tracking-wider block text-slate-400">Status Kehadiran / Jurnal</span>
                    <span class="text-xs font-bold text-slate-900 mt-0.5 block" id="modalStatusTeks">-</span>
                </div>
                <span id="modalStatusBadge" class="px-2.5 py-1 rounded text-xs font-bold border shadow-2xs">
                    -
                </span>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-end">
            <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition-colors cursor-pointer shadow-2xs">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- ApexCharts Dark/Light Mode Helper ---
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#94A3B8' : '#64748B';
    const gridColor = isDark ? '#2D3543' : '#E2E8F0';
    const tooltipBg = isDark ? '#1E293B' : '#FFFFFF';
    
    // --- Area Chart (Tren 7 Hari) ---
    const areaRawData = {!! json_encode($chartData['area_data']) !!};
    const areaMaxVal = Math.max(5, ...areaRawData);

    const areaOptions = {
        series: [{
            name: 'Jurnal Terisi',
            data: areaRawData
        }],
        chart: {
            type: 'area',
            height: 220,
            toolbar: { show: false },
            fontFamily: 'inherit',
            background: 'transparent'
        },
        colors: ['#10B981'], // emerald-500
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [0, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        markers: {
            size: 4,
            colors: ['#10B981'],
            strokeColors: isDark ? '#1E293B' : '#FFFFFF',
            strokeWidth: 2,
            hover: { size: 6 }
        },
        xaxis: {
            categories: {!! json_encode($chartData['area_labels']) !!},
            axisBorder: { show: false },
            axisTicks: { show: false },
            tickAmount: 7,
            labels: {
                show: true,
                hideOverlappingLabels: false,
                style: { colors: textColor, fontSize: '10.5px', fontWeight: 500 }
            }
        },
        yaxis: {
            min: 0,
            max: areaMaxVal,
            tickAmount: areaMaxVal <= 5 ? areaMaxVal : 5,
            labels: {
                style: { colors: textColor, fontSize: '10.5px', fontWeight: 500 },
                formatter: (val) => Math.round(val)
            }
        },
        grid: { borderColor: gridColor, strokeDashArray: 4, padding: { top: 10, right: 10, bottom: 0, left: 10 } },
        theme: { mode: isDark ? 'dark' : 'light' },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: { formatter: (val) => `${val} Sesi Terisi` }
        }
    };
    new ApexCharts(document.querySelector("#areaChart"), areaOptions).render();

    // --- Pie Chart (Proporsi Hari Ini) ---
    const pieOptions = {
        series: [
            {{ $chartData['pie']['Terisi'] }},
            {{ $chartData['pie']['Alpa'] }},
            {{ $chartData['pie']['Izin'] }},
            {{ $chartData['pie']['Terjadwal'] }}
        ],
        labels: ['Terisi', 'Alpa (Belum Isi)', 'Izin Sah', 'Terjadwal'],
        chart: {
            type: 'donut',
            height: 200,
            fontFamily: 'inherit',
            background: 'transparent'
        },
        colors: ['#10B981', '#F43F5E', '#3B82F6', '#F59E0B'],
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        name: { show: false },
                        value: {
                            show: true,
                            fontSize: '20px',
                            fontWeight: 700,
                            color: isDark ? '#F8FAFC' : '#0F172A'
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        stroke: { show: false },
        legend: { show: false },
        theme: { mode: isDark ? 'dark' : 'light' },
        tooltip: { theme: isDark ? 'dark' : 'light' }
    };
    new ApexCharts(document.querySelector("#pieChart"), pieOptions).render();
});

function openDetailModal(namaGuru, nip, mapel, kelas, ruangan, jamMulai, jamSelesai, status, tanggal, hari) {
    document.getElementById('modalNamaGuru').textContent = namaGuru;
    document.getElementById('modalNipGuru').textContent = 'NIP: ' + (nip && nip !== '-' ? nip : 'Belum diisi');
    document.getElementById('modalMapel').textContent = mapel;
    document.getElementById('modalKelas').textContent = kelas;
    document.getElementById('modalRuangan').textContent = ruangan;
    document.getElementById('modalJam').textContent = 'Jam Ke ' + jamMulai + ' - ' + jamSelesai;
    document.getElementById('modalTanggalHari').textContent = hari + ', ' + tanggal;
    document.getElementById('modalAvatarGuru').textContent = (namaGuru ? namaGuru.charAt(0).toUpperCase() : 'G');

    const badgeEl = document.getElementById('modalStatusBadge');
    const statusTeksEl = document.getElementById('modalStatusTeks');

    badgeEl.textContent = status;
    if (status === 'Alpa') {
        badgeEl.className = 'px-2.5 py-1 rounded text-xs font-bold border bg-rose-50 text-rose-700 border-rose-200';
        statusTeksEl.textContent = 'Alpa (Jurnal Belum Diisi / Jam Lewat)';
    } else if (status.includes('Sah')) {
        badgeEl.className = 'px-2.5 py-1 rounded text-xs font-bold border bg-blue-50 text-blue-700 border-blue-200';
        statusTeksEl.textContent = 'Izin Resmi (' + status + ')';
    } else {
        badgeEl.className = 'px-2.5 py-1 rounded text-xs font-bold border bg-amber-50 text-amber-700 border-amber-200';
        statusTeksEl.textContent = 'Terjadwal (Belum Jam Mengajar / Menunggu Input)';
    }

    document.getElementById('modalDetailStatusGuru').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeDetailModal() {
    document.getElementById('modalDetailStatusGuru').classList.add('hidden');
}

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
