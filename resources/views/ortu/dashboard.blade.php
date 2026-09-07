@extends('layouts.app')

@section('title', 'Portal Orang Tua - Monitoring Kehadiran Anak')

@section('content')
<div class="space-y-4 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Portal Orang Tua / Wali Murid</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pantau kehadiran dan aktivitas belajar ananda di sekolah • <span class="font-bold text-slate-700">{{ $namaHari }}, {{ \Carbon\Carbon::parse($hariIni)->locale('id')->isoFormat('D MMMM Y') }}</span>
            </p>
        </div>
        <div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200 shadow-2xs space-x-1.5">
                <i data-lucide="user" class="w-3.5 h-3.5 text-slate-500"></i>
                <span>Akun Wali Murid</span>
            </span>
        </div>
    </div>

    @if(!$siswa)
        <div class="p-6 bg-amber-50 border border-amber-200 rounded-xl text-center text-amber-800 text-xs">
            <i data-lucide="alert-circle" class="w-6 h-6 mx-auto mb-1.5 text-amber-600"></i>
            Data profil siswa belum terhubung dengan akun ini. Silakan hubungi bagian TU sekolah.
        </div>
    @else
        <!-- STUDENT PROFILE CARD -->
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs flex flex-col sm:flex-row items-center sm:items-start gap-4">
            <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700 font-bold text-lg shadow-2xs shrink-0">
                {{ substr($siswa->nama_siswa, 0, 2) }}
            </div>
            <div class="flex-1 text-center sm:text-left space-y-1">
                <h2 class="text-base font-bold text-slate-900">{{ $siswa->nama_siswa }}</h2>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5 text-xs text-slate-500">
                    <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-700 font-medium text-[11px]">NIS: {{ $siswa->nis }}</span>
                    <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-700 font-medium text-[11px]">NISN: {{ $siswa->nisn }}</span>
                    <span class="px-2 py-0.5 bg-indigo-50 border border-indigo-100 text-indigo-700 font-semibold rounded text-[11px]">
                        Kelas: {{ $siswa->kelas ? $siswa->kelas->nama_kelas : '-' }}
                    </span>
                </div>
            </div>
            <!-- Status Berada di Sekolah -->
            <div class="text-center sm:text-right shrink-0">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Status Keberadaan</span>
                @if($dispenHariIni && $dispenHariIni->status === 'Sedang di Luar')
                    <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                        Sedang Izin Keluar Sekolah
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                        Berada di Lingkungan Sekolah
                    </span>
                @endif
            </div>
        </div>

        <!-- STATISTIK BULANAN -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs flex items-center space-x-3">
                <div class="p-2.5 bg-blue-50 text-blue-600 rounded-lg">
                    <i data-lucide="thermometer" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sakit Bulan Ini</p>
                    <h3 class="text-lg font-bold text-slate-900 mt-0.5">{{ $rekapBulanIni['sakit'] }} Sesi</h3>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs flex items-center space-x-3">
                <div class="p-2.5 bg-amber-50 text-amber-600 rounded-lg">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Izin Bulan Ini</p>
                    <h3 class="text-lg font-bold text-slate-900 mt-0.5">{{ $rekapBulanIni['izin'] }} Sesi</h3>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs flex items-center space-x-3">
                <div class="p-2.5 bg-rose-50 text-rose-600 rounded-lg">
                    <i data-lucide="user-x" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Alpa / Tanpa Ket.</p>
                    <h3 class="text-lg font-bold text-rose-600 mt-0.5">{{ $rekapBulanIni['alpa'] }} Sesi</h3>
                </div>
            </div>
        </div>

        <!-- PRESENSI HARI INI PER JAM PELAJARAN (JP) -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-1.5">
                    <i data-lucide="calendar-check" class="w-4 h-4 text-slate-500"></i>
                    <h3 class="font-bold text-slate-900 text-xs">Presensi Jam Pelajaran Hari Ini ({{ $namaHari }})</h3>
                </div>
                <span class="text-[11px] text-slate-400">Tercatat per sesi guru</span>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @forelse($presensiPerJp as $p)
                    <div class="p-3 hover:bg-slate-50/80 transition-colors flex items-center justify-between">
                        <div class="space-y-0.5">
                            <div class="flex items-center space-x-2">
                                <span class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-bold rounded">
                                    {{ $p['jam_ke'] }}
                                </span>
                                <h4 class="font-bold text-xs text-slate-900">{{ $p['mapel'] }}</h4>
                            </div>
                            <p class="text-[11px] text-slate-500">
                                Guru Pengajar: <span class="font-medium text-slate-700">{{ $p['guru'] }}</span> • Ruang: {{ $p['ruangan'] }}
                            </p>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $p['badge'] }}">
                                {{ $p['status'] }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 italic text-xs">
                        Tidak ada jadwal pembelajaran untuk kelas ini pada hari ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIWAYAT KETIDAKHADIRAN -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-1.5">
                    <i data-lucide="history" class="w-4 h-4 text-slate-500"></i>
                    <h3 class="font-bold text-slate-900 text-xs">Riwayat Ketidakhadiran (Detail)</h3>
                </div>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @if(isset($riwayatAbsen) && count($riwayatAbsen) > 0)
                    @foreach($riwayatAbsen as $riwayat)
                        <div class="p-3 hover:bg-slate-50/80 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <div class="space-y-0.5">
                                <h4 class="font-bold text-xs text-slate-900">
                                    {{ \Carbon\Carbon::parse($riwayat['tanggal'])->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                </h4>
                                <p class="text-[11px] text-slate-500">
                                    <span class="font-medium text-slate-600">Waktu:</span> {{ $riwayat['detail_jam'] }}
                                </p>
                            </div>
                            <div>
                                @php
                                    $badge = match ($riwayat['keterangan']) {
                                        'Sakit' => 'bg-blue-50 text-blue-700 border border-blue-200/60',
                                        'Izin'  => 'bg-amber-50 text-amber-700 border border-amber-200/60',
                                        default => 'bg-rose-50 text-rose-700 border border-rose-200/60',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $badge }}">
                                    {{ $riwayat['keterangan'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-6 text-center text-slate-400 italic text-xs">
                        Anak Anda memiliki catatan kehadiran yang sempurna.
                    </div>
                @endif
            </div>
        </div>

        <!-- INFORMASI IZIN KELUAR SEKOLAH (JIKA ADA) -->
        @if($dispenHariIni)
            <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs space-y-2">
                <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5 pb-2 border-b border-slate-100">
                    <i data-lucide="log-out" class="w-4 h-4 text-slate-500"></i>
                    <span>Catatan Izin Keluar Sekolah Hari Ini</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold text-[11px]">Keperluan Izin:</span>
                        <span class="font-bold text-slate-800 text-xs">{{ $dispenHariIni->keperluan }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold text-[11px]">Waktu Rencana:</span>
                        <span class="text-slate-700 text-xs">{{ substr($dispenHariIni->jam_keluar_rencana, 0, 5) }} s/d {{ $dispenHariIni->jam_kembali_rencana ? substr($dispenHariIni->jam_kembali_rencana, 0, 5) : '-' }} WIB</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold text-[11px]">Status Gerbang:</span>
                        <span class="font-bold text-xs {{ $dispenHariIni->status === 'Sudah Kembali' ? 'text-emerald-700' : 'text-amber-700' }}">
                            {{ $dispenHariIni->status }}
                            @if($dispenHariIni->jam_keluar_aktual)
                                (Keluar: {{ substr($dispenHariIni->jam_keluar_aktual, 0, 5) }})
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
