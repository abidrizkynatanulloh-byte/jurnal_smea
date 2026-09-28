@extends('layouts.app')

@section('title', 'Rekap Jurnal & Kehadiran - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-3">
    <!-- Header Halaman -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white dark:bg-[#1C2433] p-4 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xs">
        <div class="space-y-0.5">
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight flex items-center space-x-2.5">
                <div class="p-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 shrink-0">
                    <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                </div>
                <span>Rekapitulasi Monitoring Sesi KBM</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Pemantauan visual balok kehadiran guru & pengisian jurnal per sesi kelas per hari</p>
        </div>

        <div class="flex items-center space-x-2 shrink-0 flex-wrap gap-y-1">
            <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/80 text-emerald-700 dark:text-emerald-300 text-xs font-bold rounded-xl font-mono tabular-nums shadow-2xs flex items-center space-x-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>{{ $jurnalTersimpan->count() }} Terisi</span>
            </span>
            <span class="px-3 py-1 bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800/80 text-rose-700 dark:text-rose-300 text-xs font-bold rounded-xl font-mono tabular-nums shadow-2xs flex items-center space-x-1.5">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                <span>{{ $guruAlpaList->where('status_rekap', 'Alpa')->count() }} Alpa / Belum</span>
            </span>
            @if($guruAlpaList->filter(fn($g) => str_contains($g->status_rekap, 'Sah'))->count() > 0)
            <span class="px-3 py-1 bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800/80 text-amber-700 dark:text-amber-300 text-xs font-bold rounded-xl font-mono tabular-nums shadow-2xs flex items-center space-x-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span>{{ $guruAlpaList->filter(fn($g) => str_contains($g->status_rekap, 'Sah'))->count() }} Izin Sah</span>
            </span>
            @endif

            <form action="{{ route('admin.rekap.kirimWaOrtu') }}" method="POST" class="inline" onsubmit="return confirm('Kirim rekap notifikasi WhatsApp siswa Alpa pada tanggal {{ $tanggal }} ke Orang Tua & Wali Kelas sekarang?')">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <button type="submit" class="h-8 px-3 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5 shadow-2xs cursor-pointer ml-1">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    <span>Kirim WA Rekap ke Ortu</span>
                </button>
            </form>
        </div>
    </div>

    <!-- FILTER CONTROL BAR (TANGGAL + ANGKATAN + KELAS) -->
    <div class="shrink-0 bg-white dark:bg-[#1C2433] border border-slate-200 dark:border-slate-800 rounded-2xl p-3.5 shadow-2xs space-y-3">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            
            <!-- Left: Angkatan Pills Selector [Semua, X, XI, XII] -->
            <div class="flex items-center space-x-1.5 overflow-x-auto pb-1 lg:pb-0 scrollbar-none shrink-0">
                <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mr-1 shrink-0">
                    Angkatan:
                </span>
                <a href="{{ route('admin.rekap.index', array_filter(['tanggal' => $tanggal, 'kelas' => $filterKelas])) }}"
                    class="px-3 py-1.5 text-xs font-bold rounded-xl transition-all shrink-0 {{ empty($tingkat) ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                    Semua
                </a>
                <a href="{{ route('admin.rekap.index', array_filter(['tingkat' => 'X', 'tanggal' => $tanggal, 'kelas' => $filterKelas])) }}"
                    class="px-3 py-1.5 text-xs font-bold rounded-xl transition-all shrink-0 {{ $tingkat === 'X' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                    Kelas X
                </a>
                <a href="{{ route('admin.rekap.index', array_filter(['tingkat' => 'XI', 'tanggal' => $tanggal, 'kelas' => $filterKelas])) }}"
                    class="px-3 py-1.5 text-xs font-bold rounded-xl transition-all shrink-0 {{ $tingkat === 'XI' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                    Kelas XI
                </a>
                <a href="{{ route('admin.rekap.index', array_filter(['tingkat' => 'XII', 'tanggal' => $tanggal, 'kelas' => $filterKelas])) }}"
                    class="px-3 py-1.5 text-xs font-bold rounded-xl transition-all shrink-0 {{ $tingkat === 'XII' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                    Kelas XII
                </a>
            </div>

            <!-- Right: Filter Form Tanggal & Kelas Dropdown -->
            <form action="{{ route('admin.rekap.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                @if($tingkat)
                    <input type="hidden" name="tingkat" value="{{ $tingkat }}">
                @endif

                <div class="relative min-w-[130px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                        class="w-full h-9 pl-9 pr-2.5 bg-slate-50 dark:bg-[#141C29] border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-mono font-semibold focus:outline-none focus:border-blue-600 cursor-pointer">
                </div>

                <div class="relative min-w-[140px]">
                    <select name="kelas" id="kelas" onchange="this.form.submit()"
                        class="w-full h-9 px-3 bg-slate-50 dark:bg-[#141C29] border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-semibold focus:outline-none focus:border-blue-600 cursor-pointer">
                        <option value="">Semua Kelas</option>
                        @foreach($daftarKelas as $kls)
                            <option value="{{ $kls->id_kelas }}" {{ $filterKelas == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <span class="px-3 py-2 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 rounded-xl text-xs font-bold font-mono shrink-0">
                    {{ $namaHari }}, {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}
                </span>

                @if(request('tanggal') || request('kelas') || request('tingkat'))
                <a href="{{ route('admin.rekap.index') }}" class="h-9 px-3 border border-slate-200 dark:border-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-[#232D3F] dark:hover:bg-[#2C384E] text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold transition-colors flex items-center justify-center space-x-1 shrink-0">
                    <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                    <span>Reset</span>
                </a>
                @endif
            </form>

        </div>
    </div>

    <!-- MAIN SCROLLABLE CONTAINER -->
    <div class="flex-1 min-h-0 overflow-y-auto space-y-4">

        <!-- SECTION 1: MATRIKS BALOK TIMELINE KBM PER KELAS (COMPACT & CONTINUOUS) -->
        <div class="bg-white dark:bg-[#1C2433] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xs overflow-hidden">
            <!-- Header Card & Legenda -->
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white dark:bg-[#1C2433]">
                <div class="flex items-center space-x-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></div>
                    <span class="font-bold text-slate-900 dark:text-slate-100 text-xs sm:text-sm uppercase tracking-tight">
                        Timeline Sesi KBM (Balok Menyatu)
                    </span>
                    @if($tingkat)
                        <span class="px-2 py-0.5 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 text-[10px] font-bold">
                            Angkatan {{ $tingkat }}
                        </span>
                    @endif
                </div>

                <!-- Legenda Balok -->
                <div class="flex items-center space-x-3 text-xs flex-wrap gap-y-1">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-xs bg-emerald-500 border border-emerald-400 shrink-0"></span>
                        <span class="text-slate-600 dark:text-slate-300 font-semibold text-[11px]">Terisi (Hadir)</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-xs bg-rose-500 border border-rose-400 shrink-0"></span>
                        <span class="text-slate-600 dark:text-slate-300 font-semibold text-[11px]">Belum Terisi / Alpa</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-xs bg-amber-400 border border-amber-300 shrink-0"></span>
                        <span class="text-slate-600 dark:text-slate-300 font-semibold text-[11px]">Izin Sah</span>
                    </div>
                    <span class="text-[11px] text-slate-400 dark:text-slate-500 italic hidden lg:inline">
                        (Klik balok untuk melihat detail)
                    </span>
                </div>
            </div>

            @php
                $slotHeight = 28; // pixel per jam
                $chartHeight = $maxJam * $slotHeight;
            @endphp

            <!-- AREA CHART GRID BALOK TIMELINE -->
            <div class="p-4 sm:p-6 select-none flex items-start">
                
                <!-- Sumbu Y: Indikator Jam Pelajaran (Mepet Kiri Statis di Luar Scroll) -->
                <div class="shrink-0 w-6 sm:w-7 select-none flex flex-col justify-start pr-1.5 border-r border-slate-200/90 dark:border-slate-800">
                    <div class="flex flex-col justify-between" style="height: {{ $chartHeight }}px;">
                        @for ($jam = $maxJam; $jam >= 1; $jam--)
                            <div class="flex items-center justify-end pr-0.5 text-[11px] font-mono font-bold text-slate-400 dark:text-slate-500" style="height: {{ $slotHeight }}px;">
                                {{ $jam }}
                            </div>
                        @endfor
                    </div>
                    <!-- Spacer Bawah (Sejajar dengan tinggi tulisan nama kelas) -->
                    <div class="h-28 sm:h-32 mt-2.5"></div>
                </div>

                <!-- Area Kolom Kelas yang Dapat Di-scroll Horizontal -->
                <div class="flex-1 min-w-0 overflow-x-auto pl-2.5 sm:pl-3 pb-2">
                    <div class="inline-flex items-start space-x-2 sm:space-x-2.5 min-w-max relative">
                        
                        <!-- Garis Grid Horizontal Latar Belakang -->
                        <div class="absolute inset-0 pointer-events-none flex flex-col justify-between" style="height: {{ $chartHeight }}px;">
                            @for ($jam = $maxJam; $jam >= 1; $jam--)
                                <div class="w-full border-b border-slate-100 dark:border-slate-800/80" style="height: {{ $slotHeight }}px;"></div>
                            @endfor
                        </div>

                        <!-- Track Tiap Kelas (Ramping & Tulisan Vertikal) -->
                        @forelse ($matrixKelas as $mk)
                            <div class="w-8 sm:w-10 shrink-0 flex flex-col items-center group relative z-10">
                                
                                <!-- Track Tiang Balok Menyatu Utuh (Seamless Track) -->
                                <div class="w-full relative rounded-md bg-slate-100/80 dark:bg-[#141C29] border border-slate-200 dark:border-slate-700/80 overflow-hidden transition-all group-hover:border-blue-500 group-hover:shadow-xs shadow-2xs" style="height: {{ $chartHeight }}px;">
                                    
                                    @foreach ($mk['sessions'] as $ses)
                                        @php
                                            $bottomOffset = ($ses['jam_mulai'] - 1) * $slotHeight;
                                            $blockHeight = $ses['durasi'] * $slotHeight;
                                            $bgClass = match($ses['status']) {
                                                'terisi' => 'bg-emerald-500 hover:bg-emerald-600 text-white',
                                                'izin'   => 'bg-amber-400 hover:bg-amber-500 text-white',
                                                default  => 'bg-rose-500 hover:bg-rose-600 text-white',
                                            };
                                        @endphp
                                        <!-- BALOK POLOS SESI JAM MENYATU (Klik untuk Detail) -->
                                        <button type="button"
                                            onclick='openDetailBalokModal(@json($ses), @json($mk["nama_kelas"]), @json($tanggal), @json($namaHari))'
                                            title="Jam {{ $ses['jam_mulai'] }}–{{ $ses['jam_selesai'] }}: {{ $ses['nama_guru'] }} ({{ $ses['nama_mapel'] }}) • Status: {{ $ses['status_label'] }}"
                                            class="absolute left-0 right-0 w-full {{ $bgClass }} border-b border-black/10 dark:border-white/10 hover:brightness-110 active:opacity-90 transition-all cursor-pointer z-10"
                                            style="bottom: {{ $bottomOffset }}px; height: {{ $blockHeight }}px;">
                                            <!-- POLOS TANPA TEKS -->
                                        </button>
                                    @endforeach

                                </div>

                                <!-- Label Nama Kelas Vertikal di Bawah (Hemat Tempat) -->
                                <div class="mt-2.5 w-full flex flex-col items-center cursor-pointer"
                                     onclick='filterBySingleClass("{{ $mk['id_kelas'] }}")'
                                     title="Kelas: {{ $mk['nama_kelas'] }} ({{ $mk['terisi_count'] }}/{{ $mk['total_sesi'] }} Sesi Terisi)">
                                    <div class="h-28 sm:h-32 flex items-center justify-center group-hover:text-blue-600 transition-colors">
                                        <span class="font-bold text-[11px] text-slate-700 dark:text-slate-300 tracking-wider whitespace-nowrap [writing-mode:vertical-rl] rotate-180 select-none group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $mk['nama_kelas'] }}
                                        </span>
                                    </div>
                                    <span class="text-[9px] font-mono text-slate-400 dark:text-slate-500 mt-1 font-bold">
                                        {{ $mk['terisi_count'] }}/{{ $mk['total_sesi'] }}
                                    </span>
                                </div>

                            </div>
                        @empty
                            <div class="py-16 text-center text-slate-400 italic text-xs w-full">
                                Tidak ada kelas yang sesuai dengan filter.
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: TABEL DAFTAR JURNAL TERSIMPAN -->
        <div class="bg-white dark:bg-[#1C2433] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xs overflow-hidden flex flex-col">
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-[#1C2433] shrink-0">
                <span class="font-bold text-slate-900 dark:text-slate-100 text-xs sm:text-sm uppercase tracking-tight flex items-center space-x-2">
                    <i data-lucide="book-check" class="w-4 h-4 text-emerald-600"></i>
                    <span>Daftar Jurnal Tersimpan ({{ $jurnalTersimpan->count() }} Terisi)</span>
                </span>
                <!-- Search Jurnal -->
                <div class="relative max-w-xs w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" id="searchJurnal" placeholder="Cari guru, mapel, atau kelas..."
                        class="w-full h-9 pl-9 pr-3 bg-slate-50 dark:bg-[#141C29] border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <div class="overflow-x-auto max-h-[400px]">
                <table class="w-full text-left border-collapse text-xs" id="tabelJurnal">
                    <thead class="sticky top-0 bg-slate-50 dark:bg-[#141C29] border-b border-slate-200 dark:border-slate-700/80 z-10 text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th class="py-2.5 px-3.5 w-12 text-center">No</th>
                            <th class="py-2.5 px-3.5">Guru & Mapel</th>
                            <th class="py-2.5 px-3.5 w-28">Kelas</th>
                            <th class="py-2.5 px-3.5 w-28">Status</th>
                            <th class="py-2.5 px-3.5">Materi Pembelajaran</th>
                            <th class="py-2.5 px-3.5 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @forelse ($jurnalTersimpan as $index => $jt)
                            <tr class="hover:bg-slate-50/90 dark:hover:bg-slate-800/40 transition-colors jurnal-row"
                                data-search="{{ ($jt->jadwal && $jt->jadwal->guru ? strtolower($jt->jadwal->guru->nama_guru) : '') . ' ' . ($jt->jadwal && $jt->jadwal->mapel ? strtolower($jt->jadwal->mapel->nama_mapel) : '') . ' ' . ($jt->jadwal && $jt->jadwal->kelas ? strtolower($jt->jadwal->kelas->nama_kelas) : '') }}">
                                <td class="py-2.5 px-3.5 text-center font-medium text-slate-400 row-number tabular-nums">{{ $index + 1 }}</td>
                                <td class="py-2.5 px-3.5">
                                    <div class="font-bold text-slate-900 dark:text-slate-100 leading-tight">{{ $jt->jadwal && $jt->jadwal->guru ? $jt->jadwal->guru->nama_guru : '-' }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $jt->jadwal && $jt->jadwal->mapel ? $jt->jadwal->mapel->nama_mapel : '-' }}</div>
                                </td>
                                <td class="py-2.5 px-3.5">
                                    <span class="px-2.5 py-0.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                        {{ $jt->jadwal && $jt->jadwal->kelas ? $jt->jadwal->kelas->nama_kelas : '-' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3.5">
                                    <span class="inline-block px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/80 text-[10.5px] font-bold rounded-lg">
                                        {{ $jt->status_kehadiran_guru ?? 'Hadir' }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3.5 font-medium text-slate-800 dark:text-slate-200 max-w-sm truncate" title="{{ $jt->materi }}">{{ $jt->materi ?? '-' }}</td>
                                <td class="py-2.5 px-3.5 text-center">
                                    <a href="{{ route('admin.rekap.show', $jt->id_jurnal) }}" class="inline-flex items-center space-x-1 px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 transition-colors shadow-2xs">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>Detail</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyJurnal">
                                <td colspan="6" class="py-10 text-center text-slate-400 italic text-xs space-y-1">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600"></i>
                                    <p>Belum ada jurnal yang disimpan pada tanggal {{ $tanggal }}.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- MODAL DETAIL SESI BALOK KBM -->
<div id="modalDetailBalok" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#181F2C] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-sm sm:max-w-md w-full p-5 sm:p-6 space-y-4">
        
        <!-- Header Modal -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <span class="font-extrabold text-slate-900 dark:text-slate-100 text-sm uppercase tracking-tight flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center" id="modalBalokIconContainer">
                    <i data-lucide="info" class="w-4 h-4"></i>
                </div>
                <span>Detail Sesi Jam Mengajar</span>
            </span>
            <button type="button" onclick="closeDetailBalokModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4.5 h-4.5"></i>
            </button>
        </div>

        <!-- Body Detail Sesi -->
        <div class="space-y-3.5 text-xs">
            
            <!-- Status Card Alert -->
            <div id="modalBalokStatusCard" class="p-3.5 rounded-xl border flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider block opacity-75">Status KBM & Jurnal</span>
                    <span class="text-sm font-extrabold block mt-0.5" id="modalBalokStatusTeks">-</span>
                </div>
                <span id="modalBalokStatusBadge" class="px-2.5 py-1 rounded-lg text-xs font-bold border">
                    -
                </span>
            </div>

            <!-- Detail Grid Info -->
            <div class="space-y-2 pt-1">
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Guru Pengampu</span>
                    <div class="text-right">
                        <span class="font-extrabold text-slate-900 dark:text-slate-100 block" id="modalBalokGuru">-</span>
                        <span class="text-[11px] font-mono text-slate-400" id="modalBalokNip">NIP: -</span>
                    </div>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Mata Pelajaran</span>
                    <span class="font-bold text-slate-900 dark:text-slate-100 text-right" id="modalBalokMapel">-</span>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Kelas / Rombel</span>
                    <span class="font-bold text-blue-600 dark:text-blue-400" id="modalBalokKelas">-</span>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Jam Pelajaran</span>
                    <span class="font-bold text-slate-900 dark:text-slate-100 font-mono" id="modalBalokJam">-</span>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Ruangan / Lab</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200" id="modalBalokRuangan">-</span>
                </div>
                <div class="py-1.5" id="modalBalokMateriContainer">
                    <span class="text-slate-500 dark:text-slate-400 font-medium block mb-0.5">Materi Pembelajaran</span>
                    <p class="font-medium text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-800 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80" id="modalBalokMateri">
                        -
                    </p>
                </div>
            </div>

        </div>

        <!-- Footer Modal -->
        <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
            <div id="modalBalokActionContainer">
                <a href="#" id="modalBalokDetailJurnalBtn" class="hidden h-9 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-2xs items-center space-x-1.5">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    <span>Lihat Jurnal Lengkap</span>
                </a>
            </div>

            <button type="button" onclick="closeDetailBalokModal()" class="h-9 px-6 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition-colors cursor-pointer">
                Tutup
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
function filterBySingleClass(idKelas) {
    const selectKelas = document.getElementById('kelas');
    if (selectKelas) {
        selectKelas.value = idKelas;
        selectKelas.form.submit();
    }
}

function openDetailBalokModal(sesi, namaKelas, tanggal, hari) {
    document.getElementById('modalBalokGuru').textContent = sesi.nama_guru || '-';
    document.getElementById('modalBalokNip').textContent = 'NIP: ' + (sesi.nip && sesi.nip !== '-' ? sesi.nip : 'Belum diisi');
    document.getElementById('modalBalokMapel').textContent = sesi.nama_mapel || '-';
    document.getElementById('modalBalokKelas').textContent = namaKelas || '-';
    document.getElementById('modalBalokRuangan').textContent = sesi.nama_ruangan || 'Default Ruangan Kelas';
    document.getElementById('modalBalokJam').textContent = `Jam ke ${sesi.jam_mulai} – ${sesi.jam_selesai} (${hari}, ${tanggal})`;

    const cardEl = document.getElementById('modalBalokStatusCard');
    const badgeEl = document.getElementById('modalBalokStatusBadge');
    const statusTeksEl = document.getElementById('modalBalokStatusTeks');
    const materiContainer = document.getElementById('modalBalokMateriContainer');
    const materiEl = document.getElementById('modalBalokMateri');
    const btnJurnal = document.getElementById('modalBalokDetailJurnalBtn');

    if (sesi.status === 'terisi') {
        cardEl.className = 'p-3.5 rounded-xl border bg-emerald-50/80 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800/70 text-emerald-800 dark:text-emerald-300 flex items-center justify-between';
        badgeEl.className = 'px-2.5 py-1 rounded-lg text-xs font-bold border bg-emerald-600 text-white border-emerald-500';
        badgeEl.textContent = 'TERISI';
        statusTeksEl.textContent = 'Guru Hadir & Jurnal Dicatat';
        
        materiContainer.classList.remove('hidden');
        materiEl.textContent = sesi.materi || 'Materi pembelajaran telah dicatat.';

        if (sesi.id_jurnal) {
            btnJurnal.href = `/admin/rekap-jurnal/${sesi.id_jurnal}`;
            btnJurnal.classList.remove('hidden');
            btnJurnal.classList.add('inline-flex');
        } else {
            btnJurnal.classList.add('hidden');
        }
    } else if (sesi.status === 'izin') {
        cardEl.className = 'p-3.5 rounded-xl border bg-amber-50/80 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800/70 text-amber-800 dark:text-amber-300 flex items-center justify-between';
        badgeEl.className = 'px-2.5 py-1 rounded-lg text-xs font-bold border bg-amber-500 text-white border-amber-400';
        badgeEl.textContent = 'IZIN RESMI';
        statusTeksEl.textContent = sesi.status_label || 'Guru Izin Resmi (Sah)';
        
        materiContainer.classList.add('hidden');
        btnJurnal.classList.add('hidden');
    } else {
        // Alpa / Belum Mengisi
        cardEl.className = 'p-3.5 rounded-xl border bg-rose-50/80 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800/70 text-rose-800 dark:text-rose-300 flex items-center justify-between';
        badgeEl.className = 'px-2.5 py-1 rounded-lg text-xs font-bold border bg-rose-600 text-white border-rose-500';
        badgeEl.textContent = 'ALPA / BELUM MENGISI';
        statusTeksEl.textContent = 'Guru Tidak Mengajar / Belum Isi Jurnal';

        materiContainer.classList.remove('hidden');
        materiEl.textContent = 'Belum ada catatan jurnal mengajar yang diinput oleh guru pada sesi jam ini.';
        btnJurnal.classList.add('hidden');
    }

    const modal = document.getElementById('modalDetailBalok');
    if (modal) modal.classList.remove('hidden');
    if (window.lucide) window.lucide.createIcons();
}

function closeDetailBalokModal() {
    const modal = document.getElementById('modalDetailBalok');
    if (modal) modal.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    // Instant Search Tabel Jurnal
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
