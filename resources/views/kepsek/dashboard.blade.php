@extends('layouts.app')

@section('title', 'Dashboard Eksekutif Kepala Sekolah')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Executive Dashboard Kepala Sekolah</h1>
            <p class="text-xs text-gray-500 mt-1">
                Monitoring menyeluruh kegiatan belajar mengajar & operasional • <span class="font-bold text-[#405078]">{{ $namaHari }}, {{ \Carbon\Carbon::parse($hariIni)->locale('id')->isoFormat('D MMMM Y') }}</span>
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-[#405078]/10 text-[#405078] text-xs font-bold border border-[#405078]/20 shadow-xs space-x-1.5">
                <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                <span>Akses Pimpinan Utama</span>
            </span>
        </div>
    </div>

    <!-- 4 EXECUTIVE KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- KPI 1: Kehadiran Guru -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#8697C3]">Kehadiran Guru</p>
                <div class="p-2 bg-[#405078]/10 text-[#405078] rounded-lg">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="flex items-baseline space-x-2 mt-2">
                <h3 class="text-3xl font-black text-[#1E2538]">{{ $persenGuruHadir }}%</h3>
                <span class="text-xs text-gray-500 font-medium">({{ $totalJurnalTerisi }}/{{ $totalSesiHariIni }} sesi)</span>
            </div>
            <!-- Progress bar -->
            <div class="w-full bg-gray-100 h-2 rounded-full mt-3 overflow-hidden">
                <div class="bg-[#405078] h-full rounded-full transition-all duration-500" style="width: {{ $persenGuruHadir }}%"></div>
            </div>
        </div>

        <!-- KPI 2: Kelas Aktif Berlangsung -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#8697C3]">Kelas Berlangsung</p>
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                    <i data-lucide="radio" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="flex items-baseline space-x-2 mt-2">
                <h3 class="text-3xl font-black text-emerald-700">{{ $kelasBerlangsung }}</h3>
                <span class="text-xs text-gray-500 font-medium">Ruang aktif</span>
            </div>
            <p class="text-[11px] text-gray-400 mt-2">Dari total {{ $totalSesiHariIni }} sesi hari ini</p>
        </div>

        <!-- KPI 3: Guru Belum Mulai / Terlambat -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#8697C3]">Perlu Perhatian</p>
                <div class="p-2 {{ $guruTerlambat > 0 ? 'bg-rose-50 text-rose-600' : 'bg-gray-100 text-gray-400' }} rounded-lg">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="flex items-baseline space-x-2 mt-2">
                <h3 class="text-3xl font-black {{ $guruTerlambat > 0 ? 'text-rose-600' : 'text-[#1E2538]' }}">{{ $guruTerlambat }}</h3>
                <span class="text-xs text-gray-500 font-medium">Sesi terlambat</span>
            </div>
            <p class="text-[11px] text-gray-400 mt-2">{{ $guruTerlambat > 0 ? 'Guru belum mulai mengajar' : 'Seluruh kelas tepat waktu' }}</p>
        </div>

        <!-- KPI 4: Siswa Di Luar Sekolah -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#8697C3]">Siswa di Luar</p>
                <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                    <i data-lucide="door-open" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="flex items-baseline space-x-2 mt-2">
                <h3 class="text-3xl font-black text-amber-700">{{ $siswaSedangDiLuar }}</h3>
                <span class="text-xs text-gray-500 font-medium">Siswa aktif dispen</span>
            </div>
            <p class="text-[11px] text-gray-400 mt-2">Total {{ $siswaIzinHariIni }} izin hari ini</p>
        </div>
    </div>

    <!-- 2 KOLOM: PENGESAHAN IZIN GURU (TAHAP FINAL) & AUDIT LOG -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        
        <!-- KOLOM 1: PENGESAHAN IZIN GURU FINAL -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#D1D9EB] bg-[#405078]/5 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="file-signature" class="w-5 h-5 text-[#405078]"></i>
                    <h3 class="font-bold text-[#1E2538] text-base">Pengesahan Izin Guru (Tahap 3 - Final)</h3>
                </div>
                <span class="px-2.5 py-0.5 bg-[#405078]/15 text-[#405078] font-bold text-xs rounded-full">
                    {{ $izinGuruPending->count() }} Menunggu
                </span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($izinGuruPending as $iz)
                    <div class="p-5 space-y-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-[#1E2538]">{{ $iz->guru ? $iz->guru->nama_guru : '-' }}</h4>
                                <p class="text-xs text-gray-500">NIP: {{ $iz->guru ? $iz->guru->nip : '-' }}</p>
                            </div>
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg">
                                Disetujui Waka & SDM
                            </span>
                        </div>

                        <div class="bg-[#F8FAFC] border border-[#D1D9EB] p-3 rounded-xl text-xs space-y-1">
                            <p><span class="font-semibold text-gray-500">Periode:</span> {{ \Carbon\Carbon::parse($iz->tanggal_mulai)->locale('id')->isoFormat('D MMM Y') }} s/d {{ \Carbon\Carbon::parse($iz->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</p>
                            <p><span class="font-semibold text-gray-500">Alasan:</span> <span class="font-bold text-[#1E2538]">{{ $iz->alasan }}</span></p>
                            @if($iz->keterangan)
                                <p><span class="font-semibold text-gray-500">Keterangan:</span> {{ $iz->keterangan }}</p>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2 pt-1">
                            <form action="{{ route('kepsek.izin-guru.approve', $iz->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    <span>Sahkan Izin (Setujui)</span>
                                </button>
                            </form>

                            <form action="{{ route('kepsek.izin-guru.reject', $iz->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-colors flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    <span>Tolak Izin</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 italic text-xs">
                        <i data-lucide="check-check" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                        Tidak ada pengajuan izin guru yang menunggu pengesahan saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KOLOM 2: AUDIT TRAIL / LOG SISTEM -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="activity" class="w-5 h-5 text-[#405078]"></i>
                    <h3 class="font-bold text-[#1E2538] text-base">Audit Log Aktivitas Sistem</h3>
                </div>
                <span class="text-xs text-gray-400 font-semibold">Terekam Otomatis</span>
            </div>

            <div class="divide-y divide-gray-100 text-xs">
                @forelse($auditLogs as $log)
                    <div class="p-4 hover:bg-gray-50/50 transition-colors flex items-start space-x-3">
                        <div class="p-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-lg text-[#405078] mt-0.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="flex-1 space-y-0.5">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-[#1E2538]">{{ $log->action }}</span>
                                <span class="text-[10px] text-gray-400 font-medium">
                                    {{ \Carbon\Carbon::parse($log->created_at)->locale('id')->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-gray-500 leading-snug">{{ $log->description }}</p>
                            <p class="text-[10px] text-[#8697C3]">Oleh: {{ $log->user ? $log->user->username : 'Sistem' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 italic text-xs">
                        Belum ada aktivitas penting yang tercatat dalam audit log.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
