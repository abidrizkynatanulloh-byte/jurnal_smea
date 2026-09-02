@extends('layouts.app')

@section('title', 'Rekap Kepatuhan Jurnal Guru - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-[#405078] hover:underline flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Admin</span>
                </a>
            </div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Rekapitulasi Kepatuhan Pengisian Jurnal Guru</h1>
            <p class="text-xs text-gray-500 mt-0.5">
                Monitoring kedisiplinan pengisian jurnal pembelajaran seluruh guru pengajar minggu ini
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.rekap.index') }}" class="px-4 py-2 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-xs">
                <i data-lucide="calendar" class="w-4 h-4 text-[#405078]"></i>
                <span>Rekap Harian</span>
            </a>
        </div>
    </div>

    <!-- METRIC SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Card 1: Total Guru -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-[#405078]/10 text-[#405078] rounded-xl">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Guru Terjadwal</p>
                <h3 class="text-2xl font-black text-[#1E2538] mt-0.5">{{ $totalGuruCount }} Orang</h3>
            </div>
        </div>

        <!-- Card 2: Guru Patuh 100% -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Disiplin / Patuh (100%)</p>
                <h3 class="text-2xl font-black text-emerald-700 mt-0.5">{{ $guruPatuhCount }} Guru</h3>
            </div>
        </div>

        <!-- Card 3: Guru Tertunggak -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm flex items-center space-x-4">
            <div class="p-3 {{ $guruTidakPatuhCount > 0 ? 'bg-rose-50 text-rose-600' : 'bg-gray-100 text-gray-400' }} rounded-xl">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Ada Jurnal Tertunggak</p>
                <h3 class="text-2xl font-black {{ $guruTidakPatuhCount > 0 ? 'text-rose-600' : 'text-gray-700' }} mt-0.5">
                    {{ $guruTidakPatuhCount }} Guru
                </h3>
            </div>
        </div>
    </div>

    <!-- TABEL KEPATUHAN DENGAN LIVE SEARCH & DETAIL AKORDION -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <i data-lucide="bar-chart-2" class="w-5 h-5 text-[#405078]"></i>
                <h3 class="font-bold text-[#1E2538] text-base">Tingkat Kepatuhan Pengajar Minggu Ini</h3>
            </div>

            <!-- Instant Live Search Bar -->
            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" id="kepatuhanSearch" placeholder="Cari nama / NIP guru..."
                    class="block w-full pl-9 pr-4 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15">
            </div>
        </div>

        <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
            <table class="w-full text-left border-collapse" id="tableKepatuhan">
                <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                    <tr class="text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-3.5 px-6">Nama Guru / Tenaga Pendidik</th>
                        <th class="py-3.5 px-6 text-center w-28">Total Jadwal</th>
                        <th class="py-3.5 px-6 text-center w-28">Sesi Terisi</th>
                        <th class="py-3.5 px-6 text-center w-28">Tertunggak</th>
                        <th class="py-3.5 px-6 w-44">Persentase</th>
                        <th class="py-3.5 px-6 text-center w-36">Status</th>
                        <th class="py-3.5 px-6 text-center w-28">Rincian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse($rekapKepatuhan as $idx => $r)
                        <tr class="hover:bg-gray-50/50 transition-colors kepatuhan-row" data-search="{{ strtolower($r['nama_guru'] . ' ' . $r['nip']) }}">
                            <td class="py-4 px-6">
                                <p class="font-bold text-[#1E2538] text-xs">{{ $r['nama_guru'] }}</p>
                                <p class="text-[11px] text-gray-400">NIP: {{ $r['nip'] ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-6 text-center text-xs font-semibold text-gray-700">{{ $r['total_jadwal'] }} sesi</td>
                            <td class="py-4 px-6 text-center text-xs font-bold text-emerald-700">{{ $r['sesi_terisi'] }}</td>
                            <td class="py-4 px-6 text-center text-xs font-bold {{ $r['sesi_tertunggak'] > 0 ? 'text-rose-600' : 'text-gray-400' }}">
                                {{ $r['sesi_tertunggak'] }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-2">
                                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $r['persentase'] == 100 ? 'bg-emerald-500' : ($r['persentase'] >= 70 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $r['persentase'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-[#1E2538] w-9 text-right">{{ $r['persentase'] }}%</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($r['is_patuh'])
                                    <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        <span>Patuh (100%)</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        <span>Tertunggak</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if(count($r['rincian_tertunggak']) > 0)
                                    <button type="button" onclick="toggleRincian({{ $idx }})"
                                        class="px-2.5 py-1 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-[#405078] rounded-lg text-xs font-bold transition-colors shadow-2xs">
                                        Lihat ({{ count($r['rincian_tertunggak']) }})
                                    </button>
                                @else
                                    <span class="text-gray-300 text-xs">-</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Row Rincian Tertunggak (Accordion) -->
                        @if(count($r['rincian_tertunggak']) > 0)
                            <tr id="rincian-{{ $idx }}" class="hidden bg-[#F8FAFC] border-b border-gray-100">
                                <td colspan="7" class="py-4 px-8 space-y-2">
                                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                        Rincian Sesi Belum Diisi oleh {{ $r['nama_guru'] }}:
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                        @foreach($r['rincian_tertunggak'] as $detail)
                                            <div class="p-3 bg-white border border-rose-200/80 rounded-xl text-xs space-y-1 shadow-2xs">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-bold text-[#1E2538]">{{ $detail['hari'] }}, {{ \Carbon\Carbon::parse($detail['tanggal'])->locale('id')->isoFormat('D MMM Y') }}</span>
                                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-700 font-bold rounded text-[10px]">{{ $detail['keterangan'] }}</span>
                                                </div>
                                                <p class="text-gray-700 font-semibold">{{ $detail['kelas'] }} • {{ $detail['mapel'] }}</p>
                                                <p class="text-gray-400 text-[11px]">{{ $detail['jam'] }} ({{ $detail['ruangan'] }})</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 italic text-xs">
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

    // Live Instant Search
    document.getElementById('kepatuhanSearch').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
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
