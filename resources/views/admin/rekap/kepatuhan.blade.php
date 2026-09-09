@extends('layouts.app')

@section('title', 'Rekap Kepatuhan Jurnal Guru - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Admin</span>
                </a>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Rekapitulasi Kepatuhan Pengisian Jurnal Guru</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Monitoring kedisiplinan pengisian jurnal pembelajaran seluruh guru pengajar minggu ini
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.rekap.index') }}" class="px-3.5 py-1.5 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-500"></i>
                <span>Rekap Harian</span>
            </a>
        </div>
    </div>

    <!-- METRIC SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
        <!-- Card 1: Total Guru -->
        <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs flex items-center space-x-3">
            <div class="w-8 h-8 bg-slate-100 text-slate-700 rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Guru Terjadwal</p>
                <h3 class="text-xl font-bold text-slate-900 mt-0.5 tabular-nums">{{ $totalGuruCount }} Orang</h3>
            </div>
        </div>

        <!-- Card 2: Guru Patuh 100% -->
        <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs flex items-center space-x-3">
            <div class="w-8 h-8 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Disiplin / Patuh (100%)</p>
                <h3 class="text-xl font-bold text-emerald-700 mt-0.5 tabular-nums">{{ $guruPatuhCount }} Guru</h3>
            </div>
        </div>

        <!-- Card 3: Guru Tertunggak -->
        <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs flex items-center space-x-3">
            <div class="w-8 h-8 {{ $guruTidakPatuhCount > 0 ? 'bg-rose-50 text-rose-700 border border-rose-100' : 'bg-slate-100 text-slate-400' }} rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-rose-700">Ada Jurnal Tertunggak</p>
                <h3 class="text-xl font-bold {{ $guruTidakPatuhCount > 0 ? 'text-rose-700' : 'text-slate-700' }} mt-0.5 tabular-nums">
                    {{ $guruTidakPatuhCount }} Guru
                </h3>
            </div>
        </div>
    </div>

    <!-- TABEL KEPATUHAN DENGAN LIVE SEARCH & DETAIL AKORDION -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden flex flex-col">
        <div class="px-3.5 py-2.5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
            <div class="flex items-center space-x-2">
                <i data-lucide="bar-chart-2" class="w-4 h-4 text-slate-700"></i>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Tingkat Kepatuhan Pengajar Minggu Ini</h3>
            </div>

            <!-- Instant Live Search Bar -->
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" id="kepatuhanSearch" placeholder="Cari nama / NIP guru..."
                    class="block w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538]">
            </div>
        </div>

        <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
            <table class="w-full text-left border-collapse text-xs" id="tableKepatuhan">
                <thead class="sticky top-0 bg-white border-b border-slate-200 z-10">
                    <tr class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-2 px-3.5">Nama Guru / Tenaga Pendidik</th>
                        <th class="py-2 px-3.5 text-center w-28">Total Jadwal</th>
                        <th class="py-2 px-3.5 text-center w-24">Sesi Terisi</th>
                        <th class="py-2 px-3.5 text-center w-24">Tertunggak</th>
                        <th class="py-2 px-3.5 w-44">Persentase</th>
                        <th class="py-2 px-3.5 text-center w-32">Status</th>
                        <th class="py-2 px-3.5 text-center w-24">Rincian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($rekapKepatuhan as $idx => $r)
                        <tr class="hover:bg-slate-50/80 transition-colors kepatuhan-row" data-search="{{ strtolower($r['nama_guru'] . ' ' . $r['nip']) }}">
                            <td class="py-2 px-3.5">
                                <p class="font-semibold text-slate-900 text-xs leading-tight">{{ $r['nama_guru'] }}</p>
                                <p class="text-[10px] text-slate-400 font-mono mt-0.5">NIP: {{ $r['nip'] ?? '-' }}</p>
                            </td>
                            <td class="py-2 px-3.5 text-center text-xs font-semibold text-slate-700 tabular-nums">{{ $r['total_jadwal'] }} sesi</td>
                            <td class="py-2 px-3.5 text-center text-xs font-bold text-emerald-700 tabular-nums">{{ $r['sesi_terisi'] }}</td>
                            <td class="py-2 px-3.5 text-center text-xs font-bold {{ $r['sesi_tertunggak'] > 0 ? 'text-rose-600' : 'text-slate-400' }} tabular-nums">
                                {{ $r['sesi_tertunggak'] }}
                            </td>
                            <td class="py-2 px-3.5">
                                <div class="flex items-center space-x-2">
                                    <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $r['persentase'] == 100 ? 'bg-emerald-500' : ($r['persentase'] >= 70 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $r['persentase'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-900 w-8 text-right tabular-nums">{{ $r['persentase'] }}%</span>
                                </div>
                            </td>
                            <td class="py-2 px-3.5 text-center">
                                @if($r['is_patuh'])
                                    <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold rounded">
                                        Patuh (100%)
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold rounded">
                                        Tertunggak
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 px-3.5 text-center">
                                @if(count($r['rincian_sesi']) > 0)
                                    <button type="button" onclick="toggleRincian({{ $idx }})"
                                        class="h-6.5 px-2.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-md text-xs font-semibold transition-colors cursor-pointer inline-flex items-center space-x-1 shadow-2xs">
                                        <i data-lucide="eye" class="w-3 h-3 text-slate-400"></i>
                                        <span>Lihat ({{ count($r['rincian_sesi']) }})</span>
                                    </button>
                                @else
                                    <span class="text-slate-300 text-xs">-</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Row Rincian Seluruh Sesi (Accordion) -->
                        @if(count($r['rincian_sesi']) > 0)
                            <tr id="rincian-{{ $idx }}" class="hidden bg-slate-50/70 border-b border-slate-200">
                                <td colspan="7" class="py-3.5 px-6 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <div class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                            Rincian Sesi Mengajar Minggu Ini oleh {{ $r['nama_guru'] }}:
                                        </div>
                                        <div class="flex items-center space-x-3 text-[11px]">
                                            <span class="inline-flex items-center space-x-1 text-emerald-700 font-semibold">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                <span>Sudah Diisi ({{ $r['sesi_terisi'] }})</span>
                                            </span>
                                            <span class="inline-flex items-center space-x-1 text-rose-700 font-semibold">
                                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                                <span>Belum Diisi ({{ $r['sesi_tertunggak'] }})</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                                        @foreach($r['rincian_sesi'] as $detail)
                                            <div class="p-2.5 bg-white border rounded-lg text-xs space-y-1 shadow-2xs
                                                @if($detail['status_kode'] === 'terisi') border-emerald-200 bg-emerald-50/10
                                                @elseif($detail['status_kode'] === 'izin') border-amber-200 bg-amber-50/10
                                                @elseif($detail['status_kode'] === 'tertunggak') border-rose-200 bg-rose-50/10
                                                @else border-slate-200 bg-slate-50/30 @endif">
                                                
                                                <div class="flex items-center justify-between">
                                                    <span class="font-bold text-slate-900">{{ $detail['hari'] }}, {{ \Carbon\Carbon::parse($detail['tanggal'])->locale('id')->isoFormat('D MMM Y') }}</span>
                                                    <span class="px-1.5 py-0.5 font-bold rounded text-[10px]
                                                        @if($detail['status_kode'] === 'terisi') bg-emerald-100 text-emerald-800
                                                        @elseif($detail['status_kode'] === 'izin') bg-amber-100 text-amber-800
                                                        @elseif($detail['status_kode'] === 'tertunggak') bg-rose-100 text-rose-800
                                                        @else bg-slate-200 text-slate-700 @endif">
                                                        {{ $detail['keterangan'] }}
                                                    </span>
                                                </div>
                                                <p class="text-slate-800 font-semibold">{{ $detail['kelas'] }} • {{ $detail['mapel'] }}</p>
                                                <div class="flex items-center justify-between text-slate-500 text-[11px]">
                                                    <span>{{ $detail['jam'] }} ({{ $detail['ruangan'] }})</span>
                                                    @if($detail['id_jurnal'])
                                                        <a href="{{ route('admin.rekap.show', $detail['id_jurnal']) }}" class="text-xs text-sky-600 hover:text-sky-700 font-bold underline flex items-center space-x-0.5">
                                                            <span>Detail</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 italic text-xs">
                                Tidak ada data guru terjadwal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleRincian(idx) {
        const row = document.getElementById('rincian-' + idx);
        if (row) {
            row.classList.toggle('hidden');
        }
    }

    // Live Instant Search with Auto-close of Open Accordion Rows
    document.getElementById('kepatuhanSearch').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        
        // Auto-close ALL open detail accordion rows when searching!
        document.querySelectorAll('[id^="rincian-"]').forEach(function(rincianRow) {
            rincianRow.classList.add('hidden');
        });

        const rows = document.querySelectorAll('.kepatuhan-row');
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
