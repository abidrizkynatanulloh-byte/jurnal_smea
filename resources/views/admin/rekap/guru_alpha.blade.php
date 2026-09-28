@extends('layouts.app')

@section('title', 'Rekapitulasi Guru Alpha & Ketidakhadiran Mengajar')

@section('content')
<div class="space-y-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Rekapitulasi Guru Alpha</h1>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 text-xs font-semibold border border-rose-200/60 dark:border-rose-900/50">
                    <i data-lucide="alert-octagon" class="w-3.5 h-3.5 mr-1"></i> Monitoring Evaluasi
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Pantau akumulasi ketidakhadiran guru mengajar per bulan & sepanjang tahun untuk tindak lanjut Kepala Sekolah & Waka.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-white dark:bg-[#202020] text-slate-700 dark:text-slate-200 text-xs font-semibold border border-slate-200 dark:border-slate-700 shadow-2xs hover:bg-slate-50 dark:hover:bg-slate-800 transition cursor-pointer">
                <i data-lucide="printer" class="w-3.5 h-3.5 text-slate-500"></i>
                <span>Cetak Laporan</span>
            </button>
            <a href="{{ route('admin.rekap.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-[#166876] hover:bg-[#104F5A] text-white text-xs font-semibold shadow-xs transition">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                <span>Rekap Harian Jurnal</span>
            </a>
        </div>
    </div>

    <!-- 4 EXECUTIVE KPI CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- KPI 1: Total Guru -->
        <div class="bg-white dark:bg-[#202020] border border-slate-200 dark:border-slate-800 rounded-xl p-3 shadow-2xs">
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Guru</p>
                <div class="p-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg">
                    <i data-lucide="users" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="mt-1">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $kpi['total_guru'] }}</h3>
                <p class="text-[10px] text-slate-500 dark:text-slate-400">Guru Terdaftar</p>
            </div>
        </div>

        <!-- KPI 2: Guru Alpha Bulan Ini -->
        <div class="bg-white dark:bg-[#202020] border border-slate-200 dark:border-slate-800 rounded-xl p-3 shadow-2xs">
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Guru Alpha ({{ $namaBulanList[$bulan] }})</p>
                <div class="p-1 bg-amber-50 dark:bg-amber-950/40 text-amber-600 rounded-lg border border-amber-200/50 dark:border-amber-900/50">
                    <i data-lucide="user-x" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="mt-1">
                <h3 class="text-xl font-bold text-amber-600">{{ $kpi['guru_alpha_bulan_ini'] }} <span class="text-xs font-normal text-slate-500">Guru</span></h3>
                <p class="text-[10px] text-slate-500 dark:text-slate-400">{{ $kpi['total_sesi_alpha_bulan'] }} total sesi tertunggak</p>
            </div>
        </div>

        <!-- KPI 3: Butuh Tindak Lanjut Bulan Ini -->
        <div class="bg-white dark:bg-[#202020] border border-slate-200 dark:border-slate-800 rounded-xl p-3 shadow-2xs">
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Perlu Tindak Lanjut</p>
                <div class="p-1 bg-rose-50 dark:bg-rose-950/40 text-rose-600 rounded-lg border border-rose-200/50 dark:border-rose-900/50">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="mt-1">
                <h3 class="text-xl font-bold text-rose-600">{{ $kpi['guru_peringatan_bulan'] }} <span class="text-xs font-normal text-slate-500">Guru</span></h3>
                <p class="text-[10px] text-rose-500 font-medium">≥ 3 Sesi Alpha di {{ $namaBulanList[$bulan] }}</p>
            </div>
        </div>

        <!-- KPI 4: Akumulasi Alpha Tahunan -->
        <div class="bg-white dark:bg-[#202020] border border-slate-200 dark:border-slate-800 rounded-xl p-3 shadow-2xs">
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Alpha Th. {{ $tahun }}</p>
                <div class="p-1 bg-purple-50 dark:bg-purple-950/40 text-purple-600 rounded-lg border border-purple-200/50 dark:border-purple-900/50">
                    <i data-lucide="history" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="mt-1">
                <h3 class="text-xl font-bold text-purple-700 dark:text-purple-400">{{ $kpi['total_sesi_alpha_tahun'] }} <span class="text-xs font-normal text-slate-500">Sesi</span></h3>
                <p class="text-[10px] text-slate-500 dark:text-slate-400">{{ $kpi['guru_peringatan_tahun'] }} guru perlu evaluasi tahunan</p>
            </div>
        </div>
    </div>

    <!-- Filter & Tab Controls -->
    <div class="bg-white dark:bg-[#202020] border border-slate-200 dark:border-slate-800 rounded-xl p-3 shadow-2xs">
        <form method="GET" action="{{ route('admin.rekap.guruAlpha') }}" class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <input type="hidden" name="tab" value="{{ $tab }}" id="currentTabInput">

            <!-- Tabs: Rekap Bulanan vs Matriks 12 Bulan -->
            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-800 rounded-lg border border-slate-200/60 dark:border-slate-700 text-xs">
                <button type="button" onclick="switchTab('bulanan')" class="px-3 py-1.5 rounded-md font-semibold transition cursor-pointer {{ $tab === 'bulanan' ? 'bg-white dark:bg-[#2A2A2A] text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5 inline mr-1 -mt-0.5"></i> Rekap Bulanan
                </button>
                <button type="button" onclick="switchTab('matriks')" class="px-3 py-1.5 rounded-md font-semibold transition cursor-pointer {{ $tab === 'matriks' ? 'bg-white dark:bg-[#2A2A2A] text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                    <i data-lucide="grid" class="w-3.5 h-3.5 inline mr-1 -mt-0.5"></i> Matriks 12 Bulan (Jan - Des)
                </button>
            </div>

            <!-- Filter Dropdowns & Search -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Dropdown Tahun -->
                <div class="flex items-center space-x-1.5">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Tahun:</label>
                    <select name="tahun" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs rounded-lg px-2.5 py-1.5 font-medium focus:ring-1 focus:ring-[#166876]">
                        @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Dropdown Bulan (Khusus Tab Bulanan) -->
                @if($tab === 'bulanan')
                <div class="flex items-center space-x-1.5">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Bulan:</label>
                    <select name="bulan" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs rounded-lg px-2.5 py-1.5 font-medium focus:ring-1 focus:ring-[#166876]">
                        @foreach($namaBulanList as $num => $namaBln)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $namaBln }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Search Guru -->
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama guru / NIP..." class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs rounded-lg pl-7 pr-3 py-1.5 font-medium w-48 focus:w-56 transition-all focus:ring-1 focus:ring-[#166876]">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2 top-2"></i>
                </div>

                <button type="submit" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold transition cursor-pointer">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 1: REKAP DETAIL BULANAN TERPILIH                                      -->
    <!-- ========================================================================= -->
    @if($tab === 'bulanan')
    <div class="bg-white dark:bg-[#202020] border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-2xs">
        <div class="p-3 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/30">
            <div>
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Daftar Rekap Kehadiran Guru — Bulan {{ $namaBulanList[$bulan] }} {{ $tahun }}
                </h3>
                <p class="text-[10px] text-slate-500 dark:text-slate-400">Urutan teratas menampilkan guru dengan jumlah sesi Alpha terbanyak.</p>
            </div>
            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $rekapBulanan->count() }} Guru</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-100/75 dark:bg-slate-900 text-slate-600 dark:text-slate-300 font-semibold border-b border-slate-200 dark:border-slate-800 text-[11px]">
                        <th class="px-3 py-2.5 w-10 text-center">No</th>
                        <th class="px-3 py-2.5">Nama Guru & NIP</th>
                        <th class="px-3 py-2.5 text-center">Sesi Wajib</th>
                        <th class="px-3 py-2.5 text-center">Hadir (Terisi)</th>
                        <th class="px-3 py-2.5 text-center">Izin Sah</th>
                        <th class="px-3 py-2.5 text-center">Alpha</th>
                        <th class="px-3 py-2.5 text-center">Kehadiran</th>
                        <th class="px-3 py-2.5 text-center">Rekomendasi Tindak Lanjut</th>
                        <th class="px-3 py-2.5 text-center">Rincian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($rekapBulanan as $idx => $g)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40 transition {{ $g['total_alpha'] >= 3 ? 'bg-rose-50/30 dark:bg-rose-950/10' : '' }}">
                        <td class="px-3 py-2.5 text-center text-slate-400 font-medium">{{ $idx + 1 }}</td>
                        <td class="px-3 py-2.5">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $g['nama_guru'] }}</div>
                            <div class="text-[10px] text-slate-400 font-mono">NIP: {{ $g['nip'] }} • {{ $g['total_jadwal_mingguan'] }} jam/minggu</div>
                        </td>
                        <td class="px-3 py-2.5 text-center font-semibold text-slate-700 dark:text-slate-300">{{ $g['total_sesi_wajib'] }}</td>
                        <td class="px-3 py-2.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 font-bold text-[11px]">
                                {{ $g['total_hadir'] }}
                            </span>
                        </td>
                        <td class="px-3 py-2.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 font-semibold text-[11px]">
                                {{ $g['total_izin'] }}
                            </span>
                        </td>
                        <td class="px-3 py-2.5 text-center">
                            @if($g['total_alpha'] > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded {{ $g['total_alpha'] >= 3 ? 'bg-rose-600 text-white font-extrabold shadow-2xs' : 'bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300 font-bold' }} text-[11px]">
                                    {{ $g['total_alpha'] }} Sesi
                                </span>
                            @else
                                <span class="text-slate-400 font-semibold">0</span>
                            @endif
                        </td>
                        <td class="px-3 py-2.5 text-center">
                            <div class="flex items-center justify-center space-x-1.5">
                                <div class="w-14 bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $g['persen_hadir'] >= 90 ? 'bg-emerald-500' : ($g['persen_hadir'] >= 75 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $g['persen_hadir'] }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300">{{ $g['persen_hadir'] }}%</span>
                            </div>
                        </td>
                        <td class="px-3 py-2.5 text-center">
                            @if($g['total_alpha'] >= 3)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-400 text-[10.5px] font-bold border border-rose-200 dark:border-rose-900">
                                    <i data-lucide="alert-triangle" class="w-3 h-3 mr-1 text-rose-600"></i> Peringatan Kepsek & Waka
                                </span>
                            @elseif($g['total_alpha'] >= 1)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 text-[10.5px] font-semibold border border-amber-200 dark:border-amber-900">
                                    <i data-lucide="info" class="w-3 h-3 mr-1 text-amber-600"></i> Perlu Perhatian
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 text-[10.5px] font-semibold border border-emerald-200 dark:border-emerald-900">
                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1 text-emerald-600"></i> Tertib Mengajar
                                </span>
                            @endif
                        </td>
                        <td class="px-3 py-2.5 text-center">
                            @if($g['total_alpha'] > 0)
                                <button type="button" onclick="showAlphaModal('{{ addslashes($g['nama_guru']) }}', '{{ $g['nip'] }}', {{ json_encode($g['rincian_alpha']) }})" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-[11px] font-semibold transition cursor-pointer shadow-2xs">
                                    Lihat ({{ $g['total_alpha'] }})
                                </button>
                            @else
                                <span class="text-[10px] text-slate-400 italic">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-slate-400 text-xs">
                            Tidak ada data guru yang cocok dengan pencarian / filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- ========================================================================= -->
    <!-- TAB 2: MATRIKS AKUMULASI 12 BULAN (JANUARI - DESEMBER)                   -->
    <!-- ========================================================================= -->
    @if($tab === 'matriks')
    <div class="bg-white dark:bg-[#202020] border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-2xs">
        <div class="p-3 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/30">
            <div>
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Matriks Akumulasi Alpha Guru Sepanjang Tahun {{ $tahun }}
                </h3>
                <p class="text-[10px] text-slate-500 dark:text-slate-400">Menampilkan tren jumlah sesi Alpha guru setiap bulan dari Januari hingga Desember.</p>
            </div>
            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $rekapMatriks12Bulan->count() }} Guru</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-100/75 dark:bg-slate-900 text-slate-600 dark:text-slate-300 font-semibold border-b border-slate-200 dark:border-slate-800 text-[10.5px]">
                        <th class="px-2.5 py-2.5 w-8 text-center">No</th>
                        <th class="px-3 py-2.5 min-w-[180px]">Nama Guru & NIP</th>
                        @for($m = 1; $m <= 12; $m++)
                            <th class="px-2 py-2.5 text-center w-12">{{ substr($namaBulanList[$m], 0, 3) }}</th>
                        @endfor
                        <th class="px-3 py-2.5 text-center font-extrabold text-slate-900 dark:text-white bg-slate-200/50 dark:bg-slate-800/80 min-w-[90px]">Total Alpha</th>
                        <th class="px-3 py-2.5 text-center min-w-[150px]">Status Tindak Lanjut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($rekapMatriks12Bulan as $idx => $mg)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40 transition {{ $mg['total_alpha_tahunan'] >= 5 ? 'bg-rose-50/30 dark:bg-rose-950/10' : '' }}">
                        <td class="px-2.5 py-2 text-center text-slate-400 font-medium">{{ $idx + 1 }}</td>
                        <td class="px-3 py-2">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $mg['nama_guru'] }}</div>
                            <div class="text-[9.5px] text-slate-400 font-mono">NIP: {{ $mg['nip'] }}</div>
                        </td>
                        @for($m = 1; $m <= 12; $m++)
                            @php
                                $val = $mg['alpha_per_bulan'][$m] ?? 0;
                            @endphp
                            <td class="px-2 py-2 text-center text-[11px]">
                                @if($val >= 3)
                                    <span class="inline-block w-6 py-0.5 rounded bg-rose-600 text-white font-extrabold text-[10px] shadow-2xs">{{ $val }}</span>
                                @elseif($val > 0)
                                    <span class="inline-block w-6 py-0.5 rounded bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 font-bold text-[10px]">{{ $val }}</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600 font-mono">-</span>
                                @endif
                            </td>
                        @endfor
                        <td class="px-3 py-2 text-center bg-slate-100/40 dark:bg-slate-800/40">
                            @if($mg['total_alpha_tahunan'] > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md {{ $mg['total_alpha_tahunan'] >= 5 ? 'bg-rose-600 text-white font-extrabold' : 'bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300 font-bold' }} text-[11px]">
                                    {{ $mg['total_alpha_tahunan'] }} Sesi
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 font-bold text-[10.5px]">0 Sesi</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            @if($mg['total_alpha_tahunan'] >= 5)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-400 text-[10px] font-bold border border-rose-200 dark:border-rose-900">
                                    <i data-lucide="alert-octagon" class="w-3 h-3 mr-1 text-rose-600"></i> Peringatan Kinerja Kepsek
                                </span>
                            @elseif($mg['total_alpha_tahunan'] >= 1)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 text-[10px] font-semibold border border-amber-200 dark:border-amber-900">
                                    <i data-lucide="info" class="w-3 h-3 mr-1 text-amber-600"></i> Perhatian Waka Kurikulum
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 text-[10px] font-semibold border border-emerald-200 dark:border-emerald-900">
                                    <i data-lucide="check" class="w-3 h-3 mr-1 text-emerald-600"></i> Sangat Tertib
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="16" class="px-4 py-8 text-center text-slate-400 text-xs">
                            Tidak ada data guru yang cocok dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

<!-- ========================================================================= -->
<!-- MODAL DETAIL RINCIAN SESI ALPHA GURU                                      -->
<!-- ========================================================================= -->
<div id="modalAlphaDetail" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-200 p-4">
    <div class="bg-white dark:bg-[#202020] border border-slate-200 dark:border-slate-800 rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden transform scale-95 transition-transform duration-200" id="modalAlphaCard">
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-rose-50/50 dark:bg-rose-950/20">
            <div class="flex items-center space-x-2">
                <div class="p-1.5 bg-rose-100 dark:bg-rose-900 text-rose-700 dark:text-rose-300 rounded-lg">
                    <i data-lucide="calendar-x" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white" id="modalGuruNama">Rincian Sesi Alpha</h4>
                    <p class="text-[10px] text-slate-500 font-mono" id="modalGuruNip">NIP: -</p>
                </div>
            </div>
            <button type="button" onclick="closeAlphaModal()" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="p-4 max-h-96 overflow-y-auto space-y-2 text-xs" id="modalAlphaContent">
            <!-- Dynamic session items injected here -->
        </div>

        <div class="p-3 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex justify-end">
            <button type="button" onclick="closeAlphaModal()" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-lg text-xs font-semibold transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        document.getElementById('currentTabInput').value = tabName;
        document.getElementById('currentTabInput').form.submit();
    }

    function showAlphaModal(namaGuru, nip, rincian) {
        document.getElementById('modalGuruNama').textContent = namaGuru;
        document.getElementById('modalGuruNip').textContent = 'NIP: ' + (nip || '-');
        
        const container = document.getElementById('modalAlphaContent');
        container.innerHTML = '';

        if (!rincian || rincian.length === 0) {
            container.innerHTML = '<p class="text-center text-slate-400 py-4 italic">Tidak ada rincian sesi alpha.</p>';
        } else {
            rincian.forEach((item, idx) => {
                const card = document.createElement('div');
                card.className = 'p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 flex items-start justify-between gap-2';
                card.innerHTML = `
                    <div class="space-y-0.5">
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 font-bold text-[10px]">
                                Sesi #${idx + 1}
                            </span>
                            <span class="font-bold text-slate-900 dark:text-white text-xs">${item.hari}, ${item.tanggal}</span>
                        </div>
                        <p class="text-[11px] text-slate-700 dark:text-slate-300 font-semibold">${item.mapel}</p>
                        <p class="text-[10px] text-slate-500">Kelas: <span class="font-medium text-slate-700 dark:text-slate-300">${item.kelas}</span> • Ruang: ${item.ruangan}</p>
                    </div>
                    <span class="text-[10px] font-mono text-rose-600 dark:text-rose-400 font-bold bg-rose-50 dark:bg-rose-950/40 px-2 py-1 rounded border border-rose-200/50 dark:border-rose-900/50 shrink-0">
                        ${item.jam_ke}
                    </span>
                `;
                container.appendChild(card);
            });
        }

        const modal = document.getElementById('modalAlphaDetail');
        const modalContent = document.getElementById('modalAlphaCard');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
        lucide.createIcons();
    }

    function closeAlphaModal() {
        const modal = document.getElementById('modalAlphaDetail');
        const modalContent = document.getElementById('modalAlphaCard');
        modal.classList.add('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
    }
</script>
@endsection
