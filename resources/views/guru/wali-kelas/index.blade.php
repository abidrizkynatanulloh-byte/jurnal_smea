@extends('layouts.app')

@section('title', 'Rekap Presensi Wali Kelas - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <div class="flex items-center space-x-1.5 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Rekapitulasi Presensi Kelas Binaan</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Monitoring ketidakhadiran siswa dan deteksi dini siswa yang membutuhkan atensi wali kelas
            </p>
        </div>

        <!-- Filter / Info Kelas Binaan -->
        <div>
            @if($daftarKelas->count() > 1)
                <form action="{{ route('guru.wali-kelas') }}" method="GET" class="flex items-center space-x-2">
                    <label class="text-xs font-bold text-slate-500">Pilih Kelas:</label>
                    <select name="kelas_id" onchange="this.form.submit()"
                        class="h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#1E2538] shadow-2xs cursor-pointer">
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ $kelasAktif && $kelasAktif->id_kelas == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @elseif($kelasAktif)
                <div class="h-8 px-3 bg-white border border-slate-200 rounded-lg shadow-2xs text-xs font-bold text-slate-800 flex items-center space-x-1.5">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-slate-600"></i>
                    <span>Kelas Binaan: {{ $kelasAktif->nama_kelas }}</span>
                </div>
            @endif
        </div>
    </div>

    @php
        $siswaAlpaPerhatian = $rekapSiswa->where('perlu_perhatian_alpa', true);
        $siswaAlpaRingan    = $rekapSiswa->where('alpa', '>', 0)->where('perlu_perhatian_alpa', false);
        $siswaTelat         = $rekapSiswa->where('perlu_bimbingan_telat', true);
    @endphp

    {{-- Banner 1: PERINGATAN / PERHATIAN KHUSUS (Alpa 3 Hari Berturut-turut ATAU Total Alpa >= 7 Hari) --}}
    @if($siswaAlpaPerhatian->isNotEmpty())
        <div class="p-3.5 bg-rose-100 dark:bg-rose-950/60 border border-rose-300 dark:border-rose-800 rounded-xl text-rose-950 dark:text-rose-100 shadow-2xs space-y-1.5 mb-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="shield-alert" class="w-4 h-4 text-rose-600 dark:text-rose-400"></i>
                    <h3 class="font-bold text-xs text-rose-900 dark:text-rose-200 uppercase tracking-wide">
                        PERINGATAN SISWA PERLU PERHATIAN KHUSUS ({{ $siswaAlpaPerhatian->count() }} Siswa)
                    </h3>
                </div>
                <span class="px-2 py-0.5 bg-rose-600 text-white rounded text-[10px] font-bold uppercase tracking-wider shadow-2xs">
                    Perlu Tindak Lanjut Segera
                </span>
            </div>
            <p class="text-[11px] text-rose-800 dark:text-rose-300">
                Siswa di bawah ini terdeteksi <strong>Alpa ≥ 3 hari berturut-turut</strong> atau akumulasi <strong>Total Alpa ≥ 7 hari</strong>. Segera lakukan bimbingan, koordinasi dengan Guru BK, dan pemanggilan Orang Tua:
            </p>
            <div class="flex flex-wrap gap-1.5 pt-0.5">
                @foreach($siswaAlpaPerhatian as $sp)
                    <span class="px-2.5 py-1 bg-white dark:bg-rose-900/50 border border-rose-300 dark:border-rose-700 rounded-lg text-xs font-bold text-rose-900 dark:text-rose-100 shadow-2xs inline-flex items-center space-x-1.5">
                        <span>{{ $sp['nama_siswa'] }}</span>
                        @if($sp['alpa_berturut_3'])
                            <span class="px-1.5 py-0.5 bg-rose-600 text-white rounded text-[10px] font-mono font-bold">
                                {{ $sp['max_berturut_alpa'] }} Hari Berturut-turut
                            </span>
                        @endif
                        @if($sp['alpa_total_7'])
                            <span class="px-1.5 py-0.5 bg-purple-700 text-white rounded text-[10px] font-mono font-bold">
                                Total {{ $sp['alpa'] }}x Alpa
                            </span>
                        @endif
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Banner 2: BOX MONITORING SISWA ALPA (1 - 6 KALI, BELUM 3 HARI BERTURUT) --}}
    @if($siswaAlpaRingan->isNotEmpty())
        <div class="p-3.5 bg-rose-50/80 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900 rounded-xl text-rose-900 dark:text-rose-200 shadow-2xs space-y-1.5 mb-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="user-x" class="w-4 h-4 text-rose-600 dark:text-rose-400"></i>
                    <h3 class="font-bold text-xs text-rose-800 dark:text-rose-300">
                        Perhatian Siswa Alpa ({{ $siswaAlpaRingan->count() }} Siswa)
                    </h3>
                </div>
                <span class="text-[10.5px] font-bold text-rose-600 dark:text-rose-400 font-mono">
                    Monitoring Awal
                </span>
            </div>
            <p class="text-[11px] text-rose-700 dark:text-rose-400">
                Siswa di bawah ini tercatat Alpa (tanpa keterangan) dan memerlukan konfirmasi dengan siswa / wali murid:
            </p>
            <div class="flex flex-wrap gap-1.5 pt-0.5">
                @foreach($siswaAlpaRingan as $sar)
                    <span class="px-2.5 py-1 bg-white dark:bg-rose-900/40 border border-rose-200 dark:border-rose-800 rounded-lg text-xs font-bold text-rose-800 dark:text-rose-200 shadow-2xs inline-flex items-center space-x-1.5">
                        <span>{{ $sar['nama_siswa'] }}</span>
                        <span class="px-1.5 py-0.2 bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300 rounded text-[10px] font-mono">
                            {{ $sar['alpa'] }}x Alpa
                        </span>
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Banner 3: PERINGATAN KEDISIPLINAN TERLAMBAT (Total Telat >= 3 Kali) --}}
    @if($siswaTelat->isNotEmpty())
        <div class="p-3.5 bg-amber-50/90 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-xl text-amber-900 dark:text-amber-200 shadow-2xs space-y-1.5 mb-3">
            <div class="flex items-center space-x-2">
                <i data-lucide="clock" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
                <h3 class="font-bold text-xs text-amber-800 dark:text-amber-300">Peringatan Kedisiplinan: {{ $siswaTelat->count() }} Siswa Sering Terlambat (≥ 3 Kali)</h3>
            </div>
            <p class="text-[11px] text-amber-700 dark:text-amber-400">Siswa di bawah ini memiliki frekuensi keterlambatan yang cukup tinggi dan memerlukan pembinaan kedisiplinan:</p>
            <div class="flex flex-wrap gap-1.5 pt-0.5">
                @foreach($siswaTelat as $stt)
                    <span class="px-2 py-0.5 bg-amber-600 text-white rounded-md text-[11px] font-bold shadow-2xs">
                        {{ $stt['nama_siswa'] }} ({{ $stt['telat'] }}x Telat • Rasio {{ $stt['persentase_telat'] }}%)
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- TABEL REKAPITULASI PRESENSI KELAS (URUTAN NOMOR ABSEN UTUH 1 s/d SELESAI) -->
    <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i data-lucide="users" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs">
                    Daftar Siswa {{ $kelasAktif ? $kelasAktif->nama_kelas : '' }} ({{ $rekapSiswa->count() }} Siswa Sesuai Urutan Absen)
                </h3>
            </div>
            <span class="text-[11px] text-slate-400 font-semibold">Semester Berjalan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-[#1A2836] border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-2.5 px-3 text-center w-10">No</th>
                        <th class="py-2.5 px-3 w-24">NIS</th>
                        <th class="py-2.5 px-3">Nama Lengkap Siswa</th>
                        <th class="py-2.5 px-2.5 text-center w-16">Sakit</th>
                        <th class="py-2.5 px-2.5 text-center w-16">Izin</th>
                        <th class="py-2.5 px-2.5 text-center w-16 text-rose-600 dark:text-rose-400">Alpa</th>
                        <th class="py-2.5 px-2.5 text-center w-16 text-amber-600 dark:text-amber-400">Telat</th>
                        <th class="py-2.5 px-2.5 text-center w-16">Dispen</th>
                        <th class="py-2.5 px-2.5 text-center w-24">Persentase</th>
                        <th class="py-2.5 px-3 text-center w-40">Status & Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($rekapSiswa as $idx => $s)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors {{ $s['perlu_perhatian_alpa'] ? 'bg-red-50/40 dark:bg-red-950/30' : ($s['alpa'] > 0 ? 'bg-rose-50/15 dark:bg-rose-950/10' : ($s['perlu_bimbingan_telat'] ? 'bg-amber-50/20 dark:bg-amber-950/20' : '')) }}">
                            <td class="py-2 px-3 text-center text-slate-400 dark:text-slate-500 text-xs font-mono">{{ $idx + 1 }}</td>
                            <td class="py-2 px-3 font-semibold text-slate-600 dark:text-slate-400 text-xs font-mono">{{ $s['nis'] }}</td>
                            <td class="py-2 px-3 font-bold text-slate-800 dark:text-slate-100 text-xs">{{ $s['nama_siswa'] }}</td>
                            <td class="py-2 px-2.5 text-center font-semibold text-blue-600 dark:text-blue-400 text-xs">{{ $s['sakit'] }}</td>
                            <td class="py-2 px-2.5 text-center font-semibold text-purple-600 dark:text-purple-400 text-xs">{{ $s['izin'] }}</td>
                            <td class="py-2 px-2.5 text-center text-xs">
                                @if($s['alpa'] > 0)
                                    <span class="inline-flex items-center justify-center min-w-[26px] px-1.5 py-0.5 rounded bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 font-bold border border-rose-200 dark:border-rose-800">
                                        {{ $s['alpa'] }}x
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 font-medium">0</span>
                                @endif
                            </td>
                            <td class="py-2 px-2.5 text-center text-xs">
                                @if($s['telat'] > 0)
                                    <span class="inline-flex items-center justify-center min-w-[26px] px-1.5 py-0.5 rounded bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-bold border border-amber-200 dark:border-amber-800">
                                        {{ $s['telat'] }}x
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 font-medium">0x</span>
                                @endif
                            </td>
                            <td class="py-2 px-2.5 text-center font-semibold text-slate-600 dark:text-slate-400 text-xs">{{ $s['dispen'] }}</td>
                            <td class="py-2 px-2.5 text-center text-xs">
                                <div class="flex flex-col items-center">
                                    <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400">Hdr: {{ $s['persentase_hadir'] }}%</span>
                                    @if($s['telat'] > 0)
                                        <span class="text-[9.5px] font-semibold text-amber-600 dark:text-amber-400">Tlt: {{ $s['persentase_telat'] }}%</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2 px-3 text-center" x-data="{ modalTerbuka: false }">
                                <div class="flex items-center justify-center space-x-1.5">
                                    @if($s['alpa_berturut_3'])
                                        <span class="inline-flex items-center px-2 py-0.5 bg-red-100 dark:bg-red-950/70 text-red-800 dark:text-red-300 text-[10px] font-bold rounded-md border border-red-300 dark:border-red-800 animate-pulse">
                                            Alpa {{ $s['max_berturut_alpa'] }}x Berturut
                                        </span>
                                    @elseif($s['alpa_total_7'])
                                        <span class="inline-flex items-center px-2 py-0.5 bg-purple-100 dark:bg-purple-950/70 text-purple-800 dark:text-purple-300 text-[10px] font-bold rounded-md border border-purple-300 dark:border-purple-800">
                                            Alpa Total {{ $s['alpa'] }}x
                                        </span>
                                    @elseif($s['alpa'] > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 text-[10px] font-bold rounded-md border border-rose-200 dark:border-rose-800">
                                            Alpa ({{ $s['alpa'] }}x)
                                        </span>
                                    @elseif($s['perlu_bimbingan_telat'])
                                        <span class="inline-flex items-center px-2 py-0.5 bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-bold rounded-md border border-amber-300 dark:border-amber-800">
                                            Sering Telat ({{ $s['telat'] }}x)
                                        </span>
                                    @elseif($s['telat'] > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 text-[10px] font-semibold rounded-md border border-amber-200 dark:border-amber-800">
                                            Telat ({{ $s['telat'] }}x)
                                        </span>
                                    @elseif($s['total_absen'] == 0 && $s['telat'] == 0)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold rounded-md border border-emerald-200 dark:border-emerald-800">
                                            Disiplin (100%)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-medium rounded-md border border-slate-200 dark:border-slate-700">
                                            Normal
                                        </span>
                                    @endif
                                    
                                    <button @click="modalTerbuka = true" class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 rounded border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer" title="Lihat Detail Riwayat">
                                        <i data-lucide="info" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                                    </button>
                                </div>

                                <!-- Modal Riwayat Ketidakhadiran, Keterlambatan & Dispensasi -->
                                <div x-show="modalTerbuka" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-center justify-center min-h-screen p-4 text-center">
                                        <div x-show="modalTerbuka" @click="modalTerbuka = false" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" aria-hidden="true"></div>
                                        <div x-show="modalTerbuka" x-transition x-data="{ activeTab: 'absen' }" class="inline-block align-bottom bg-white dark:bg-[#242A35] rounded-xl text-left overflow-hidden shadow-xl transform transition-all max-w-lg w-full z-10 border border-slate-200 dark:border-slate-800">
                                            <div class="bg-white dark:bg-[#242A35] p-4">
                                                <div class="w-full">
                                                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                                                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100" id="modal-title">
                                                             Detail Riwayat - {{ $s['nama_siswa'] }}
                                                        </h3>
                                                        <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500">NIS: {{ $s['nis'] }}</span>
                                                    </div>

                                                    <!-- Tab Buttons -->
                                                    <div class="flex mt-3 bg-slate-100 dark:bg-slate-800 rounded-lg p-0.5 space-x-1">
                                                        <button @click="activeTab = 'absen'" :class="activeTab === 'absen' ? 'bg-white dark:bg-[#1A212D] shadow-2xs text-slate-900 dark:text-slate-100 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'" class="flex-1 py-1.5 px-2 rounded-md text-xs font-semibold transition-all">
                                                            <span>Absen ({{ $s['total_absen'] }})</span>
                                                        </button>
                                                        <button @click="activeTab = 'telat'" :class="activeTab === 'telat' ? 'bg-white dark:bg-[#1A212D] shadow-2xs text-amber-700 dark:text-amber-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'" class="flex-1 py-1.5 px-2 rounded-md text-xs font-semibold transition-all">
                                                            <span>Terlambat ({{ $s['telat'] }})</span>
                                                        </button>
                                                        <button @click="activeTab = 'dispen'" :class="activeTab === 'dispen' ? 'bg-white dark:bg-[#1A212D] shadow-2xs text-purple-700 dark:text-purple-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'" class="flex-1 py-1.5 px-2 rounded-md text-xs font-semibold transition-all">
                                                            <span>Dispensasi ({{ $s['dispen'] }})</span>
                                                        </button>
                                                    </div>

                                                    <!-- Tab 1: Ketidakhadiran (Sakit, Izin, Alpa) -->
                                                    <div x-show="activeTab === 'absen'" class="mt-3 space-y-2 max-h-60 overflow-y-auto pr-1">
                                                        @forelse($s['riwayat_absen'] as $riwayat)
                                                            <div class="flex justify-between items-start p-2.5 bg-slate-50 dark:bg-[#1A212D] rounded-lg border border-slate-200/60 dark:border-slate-800">
                                                                <div class="text-left flex-1 pr-2">
                                                                    <div class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($riwayat['tanggal'])->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
                                                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5"><span class="font-semibold text-slate-600 dark:text-slate-300">Waktu:</span> {{ $riwayat['detail_jam'] }}</div>
                                                                    @if(!empty($riwayat['alasan']) && $riwayat['alasan'] !== '-')
                                                                        <div class="text-[10.5px] text-slate-600 dark:text-slate-400 mt-0.5">
                                                                            <span class="font-semibold text-slate-700 dark:text-slate-300">Catatan/Alasan:</span> {{ $riwayat['alasan'] }}
                                                                        </div>
                                                                    @endif
                                                                    @if(!empty($riwayat['sumber']))
                                                                        <div class="text-[9.5px] text-slate-400 dark:text-slate-500 mt-0.5">
                                                                            via {{ $riwayat['sumber'] }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    @php
                                                                        $badge = match ($riwayat['keterangan']) {
                                                                            'Sakit'     => 'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
                                                                            'Izin'      => 'bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800',
                                                                            'Terlambat' => 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800',
                                                                            default     => 'bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800',
                                                                        };
                                                                    @endphp
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $badge }}">
                                                                        {{ $riwayat['keterangan'] }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-center text-slate-400 dark:text-slate-500 text-xs italic py-4">Belum ada riwayat ketidakhadiran.</div>
                                                        @endforelse
                                                    </div>

                                                    <!-- Tab 2: Keterlambatan -->
                                                    <div x-show="activeTab === 'telat'" style="display: none;" class="mt-3 space-y-2 max-h-60 overflow-y-auto pr-1">
                                                        @forelse($s['riwayat_telat'] as $telat)
                                                            <div class="p-2.5 bg-amber-50/70 dark:bg-amber-950/30 rounded-lg border border-amber-200/80 dark:border-amber-800/60">
                                                                <div class="flex justify-between items-start">
                                                                    <div class="text-left flex-1">
                                                                        <div class="font-bold text-xs text-slate-900 dark:text-slate-100">{{ \Carbon\Carbon::parse($telat['tanggal'])->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
                                                                        <div class="text-[11px] text-slate-600 dark:text-slate-400 mt-0.5">
                                                                            <span class="font-semibold">Alasan / Sesi:</span> {{ $telat['alasan'] }}
                                                                        </div>
                                                                        @if($telat['tindakan'] && $telat['tindakan'] !== '-')
                                                                            <div class="text-[10.5px] text-amber-800 dark:text-amber-300 font-semibold mt-0.5">
                                                                                Pembinaan: {{ $telat['tindakan'] }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="ml-2 text-right">
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                                                            {{ $telat['jam_terlambat'] }}
                                                                        </span>
                                                                        <span class="block text-[9.5px] text-slate-400 dark:text-slate-500 mt-0.5 font-medium">via {{ $telat['sumber'] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-center text-slate-400 dark:text-slate-500 text-xs italic py-4">Belum ada riwayat keterlambatan.</div>
                                                        @endforelse
                                                    </div>

                                                    <!-- Tab 3: Dispensasi -->
                                                    <div x-show="activeTab === 'dispen'" style="display: none;" class="mt-3 space-y-2 max-h-60 overflow-y-auto pr-1">
                                                        @forelse($s['riwayat_dispen'] as $dispen)
                                                            <div class="p-2.5 bg-purple-50/50 dark:bg-purple-950/30 rounded-lg border border-purple-100 dark:border-purple-900/60">
                                                                <div class="flex justify-between items-start">
                                                                    <div class="text-left flex-1">
                                                                        <div class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ $dispen['periode_teks'] ?? \Carbon\Carbon::parse($dispen['tanggal'])->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
                                                                        <div class="text-[11px] text-slate-600 dark:text-slate-400 mt-0.5">
                                                                            <span class="font-semibold">Keperluan:</span> {{ $dispen['keperluan'] }}
                                                                        </div>
                                                                        @if($dispen['jam_ke'])
                                                                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Jam ke: {{ $dispen['jam_ke'] }}</div>
                                                                        @endif
                                                                        @if($dispen['jam_keluar'] || $dispen['jam_kembali'])
                                                                            <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                                                                                @if($dispen['jam_keluar'])
                                                                                    Keluar: <span class="font-semibold">{{ $dispen['jam_keluar'] }}</span>
                                                                                @endif
                                                                                @if($dispen['jam_kembali'])
                                                                                    · Kembali: <span class="font-semibold">{{ $dispen['jam_kembali'] }}</span>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="ml-2">
                                                                        @php
                                                                            $statusBadge = match ($dispen['status']) {
                                                                                'Disetujui'      => 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800',
                                                                                'Sedang di Luar' => 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800',
                                                                                'Sudah Kembali'  => 'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
                                                                                'Ditolak'        => 'bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800',
                                                                                default          => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700',
                                                                            };
                                                                        @endphp
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $statusBadge }}">
                                                                            {{ $dispen['status'] }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-center text-slate-400 dark:text-slate-500 text-xs italic py-4">Belum ada riwayat dispensasi.</div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-slate-50 dark:bg-[#1A212D] px-4 py-2.5 sm:flex sm:flex-row-reverse border-t border-slate-100 dark:border-slate-800">
                                                <button 
                                                    type="button" 
                                                    @click="modalTerbuka = false" 
                                                    class="h-9 px-5 min-w-[85px] bg-[#1E2538] hover:bg-[#121724] dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-xs hover:shadow-md"
                                                >
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-6 text-center text-slate-400 italic text-xs">
                                Tidak ada data siswa di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
