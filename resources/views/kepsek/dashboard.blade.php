@extends('layouts.app')

@section('title', 'Dashboard Eksekutif Kepala Sekolah')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Executive Dashboard Kepala Sekolah</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Monitoring menyeluruh kegiatan belajar mengajar & operasional • <span class="font-bold text-slate-700">{{ $namaHari }}, {{ \Carbon\Carbon::parse($hariIni)->locale('id')->isoFormat('D MMMM Y') }}</span>
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200/80 shadow-2xs space-x-1.5">
                <i data-lucide="shield" class="w-3.5 h-3.5 text-slate-500"></i>
                <span>Akses Pimpinan Utama</span>
            </span>
        </div>
    </div>

    <!-- 4 EXECUTIVE KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        <!-- KPI 1: Kehadiran Guru -->
        <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kehadiran Guru</p>
                <div class="p-1.5 bg-slate-100 text-slate-700 rounded-lg">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="flex items-baseline space-x-1.5 mt-1.5">
                <h3 class="text-2xl font-bold text-slate-900">{{ $persenGuruHadir }}%</h3>
                <span class="text-xs text-slate-500 font-medium">({{ $totalJurnalTerisi }}/{{ $totalSesiHariIni }} sesi)</span>
            </div>
            <!-- Progress bar -->
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-2 overflow-hidden">
                <div class="bg-[#1E2538] h-full rounded-full transition-all duration-500" style="width: {{ $persenGuruHadir }}%"></div>
            </div>
        </div>

        <!-- KPI 2: Kelas Aktif Berlangsung -->
        <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kelas Berlangsung</p>
                <div class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg border border-emerald-200/60">
                    <i data-lucide="radio" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="flex items-baseline space-x-1.5 mt-1.5">
                <h3 class="text-2xl font-bold text-emerald-700">{{ $kelasBerlangsung }}</h3>
                <span class="text-xs text-slate-500 font-medium">Ruang aktif</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-1.5">Dari total {{ $totalSesiHariIni }} sesi hari ini</p>
        </div>

        <!-- KPI 3: Guru Belum Mulai / Terlambat -->
        <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Perlu Perhatian</p>
                <div class="p-1.5 {{ $guruTerlambat > 0 ? 'bg-rose-50 text-rose-600 border border-rose-200/60' : 'bg-slate-100 text-slate-400' }} rounded-lg">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="flex items-baseline space-x-1.5 mt-1.5">
                <h3 class="text-2xl font-bold {{ $guruTerlambat > 0 ? 'text-rose-600' : 'text-slate-900' }}">{{ $guruTerlambat }}</h3>
                <span class="text-xs text-slate-500 font-medium">Sesi terlambat</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-1.5">{{ $guruTerlambat > 0 ? 'Guru belum mulai mengajar' : 'Seluruh kelas tepat waktu' }}</p>
        </div>

        <!-- KPI 4: Siswa Di Luar Sekolah -->
        <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Siswa di Luar</p>
                <div class="p-1.5 bg-amber-50 text-amber-600 rounded-lg border border-amber-200/60">
                    <i data-lucide="door-open" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="flex items-baseline space-x-1.5 mt-1.5">
                <h3 class="text-2xl font-bold text-amber-700">{{ $siswaSedangDiLuar }}</h3>
                <span class="text-xs text-slate-500 font-medium">Siswa dispen</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-1.5">Total {{ $siswaIzinHariIni }} izin hari ini</p>
        </div>
    </div>

    <!-- 2 KOLOM: PENGESAHAN IZIN GURU (TAHAP FINAL) & AUDIT LOG -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-start">
        
        <!-- KOLOM 1: PENGESAHAN IZIN GURU FINAL -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center space-x-1.5">
                    <i data-lucide="file-signature" class="w-4 h-4 text-slate-600"></i>
                    <h3 class="font-bold text-slate-900 text-xs">Pengesahan Izin Guru (Tahap 3 - Final)</h3>
                </div>
                <span class="px-2 py-0.5 bg-slate-200 text-slate-800 font-bold text-[10px] rounded-full">
                    {{ $izinGuruPending->count() }} Menunggu
                </span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($izinGuruPending as $iz)
                    <div class="p-3.5 space-y-2.5">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-xs text-slate-900">{{ $iz->guru ? $iz->guru->nama_guru : '-' }}</h4>
                                <p class="text-[10px] text-slate-400">NIP: {{ $iz->guru ? $iz->guru->nip : '-' }}</p>
                            </div>
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-semibold rounded-md border border-blue-200/60">
                                ACC Waka & SDM
                            </span>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 p-2.5 rounded-lg text-xs space-y-0.5">
                            <p><span class="font-semibold text-slate-500">Periode:</span> {{ \Carbon\Carbon::parse($iz->tanggal_mulai)->locale('id')->isoFormat('D MMM Y') }} s/d {{ \Carbon\Carbon::parse($iz->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</p>
                            <p><span class="font-semibold text-slate-500">Alasan:</span> <span class="font-bold text-slate-800">{{ $iz->alasan }}</span></p>
                            @if($iz->keterangan)
                                <p><span class="font-semibold text-slate-500">Keterangan:</span> {{ $iz->keterangan }}</p>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2 pt-0.5">
                            <form action="{{ route('kepsek.izin-guru.approve', $iz->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full h-7 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors shadow-2xs flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="check" class="w-3 h-3"></i>
                                    <span>Sahkan Izin (Setujui)</span>
                                </button>
                            </form>

                            <form action="{{ route('kepsek.izin-guru.reject', $iz->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full h-7 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                    <span>Tolak Izin</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 italic text-xs">
                        <i data-lucide="check-check" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                        Tidak ada pengajuan izin guru yang menunggu pengesahan saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KOLOM 2: AUDIT TRAIL / LOG SISTEM -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-1.5">
                    <i data-lucide="activity" class="w-4 h-4 text-slate-600"></i>
                    <h3 class="font-bold text-slate-900 text-xs">Audit Log Aktivitas Sistem</h3>
                </div>
                <span class="text-[10px] text-slate-400 font-semibold">Terekam Otomatis</span>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @forelse($auditLogs as $log)
                    <div class="p-3 hover:bg-slate-50/80 transition-colors flex items-start space-x-2.5">
                        <div class="p-1.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-500 mt-0.5">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                        </div>
                        <div class="flex-1 space-y-0.5">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 text-xs">{{ $log->action }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">
                                    {{ \Carbon\Carbon::parse($log->created_at)->locale('id')->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-slate-600 text-xs leading-snug">{{ $log->description }}</p>
                            <p class="text-[10px] text-slate-400">Oleh: {{ $log->user ? $log->user->username : 'Sistem' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 italic text-xs">
                        Belum ada aktivitas penting yang tercatat dalam audit log.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
