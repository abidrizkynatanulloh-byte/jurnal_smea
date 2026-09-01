@extends('layouts.app')

@section('title', 'Portal Orang Tua - Monitoring Kehadiran Anak')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Portal Orang Tua / Wali Murid</h1>
            <p class="text-xs text-gray-500 mt-1">
                Pantau kehadiran dan aktivitas belajar ananda di sekolah • <span class="font-bold text-[#405078]">{{ $namaHari }}, {{ \Carbon\Carbon::parse($hariIni)->locale('id')->isoFormat('D MMMM Y') }}</span>
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-blue-50 text-[#405078] text-xs font-bold border border-[#D1D9EB] shadow-xs space-x-1.5">
                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                <span>Akun Wali Murid</span>
            </span>
        </div>
    </div>

    @if(!$siswa)
        <div class="p-8 bg-amber-50 border border-amber-200 rounded-2xl text-center text-amber-800 text-sm">
            <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2 text-amber-600"></i>
            Data profil siswa belum terhubung dengan akun ini. Silakan hubungi bagian TU sekolah.
        </div>
    @else
        <!-- STUDENT PROFILE CARD -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
            <div class="w-20 h-20 rounded-2xl bg-[#405078]/10 border-2 border-[#8697C3]/30 flex items-center justify-center text-[#405078] font-black text-2xl shadow-inner">
                {{ substr($siswa->nama_siswa, 0, 2) }}
            </div>
            <div class="flex-1 text-center sm:text-left space-y-1">
                <h2 class="text-xl font-bold text-[#1E2538]">{{ $siswa->nama_siswa }}</h2>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 text-xs text-gray-500 pt-1">
                    <span class="px-2.5 py-1 bg-gray-100 rounded-md font-semibold text-gray-700">NIS: {{ $siswa->nis }}</span>
                    <span class="px-2.5 py-1 bg-gray-100 rounded-md font-semibold text-gray-700">NISN: {{ $siswa->nisn }}</span>
                    <span class="px-2.5 py-1 bg-[#405078]/10 text-[#405078] font-bold rounded-md">
                        Kelas: {{ $siswa->kelas ? $siswa->kelas->nama_kelas : '-' }}
                    </span>
                </div>
            </div>
            <!-- Status Berada di Sekolah -->
            <div class="text-center sm:text-right">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Status Keberadaan</span>
                @if($dispenHariIni && $dispenHariIni->status === 'Sedang di Luar')
                    <span class="inline-flex items-center px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                        Sedang Izin Keluar Sekolah
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
                        Berada di Lingkungan Sekolah
                    </span>
                @endif
            </div>
        </div>

        <!-- STATISTIK BULANAN -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <i data-lucide="thermometer" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Sakit Bulan Ini</p>
                    <h3 class="text-2xl font-black text-[#1E2538] mt-0.5">{{ $rekapBulanIni['sakit'] }} Sesi</h3>
                </div>
            </div>

            <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Izin Bulan Ini</p>
                    <h3 class="text-2xl font-black text-[#1E2538] mt-0.5">{{ $rekapBulanIni['izin'] }} Sesi</h3>
                </div>
            </div>

            <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                    <i data-lucide="user-x" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Alpa / Tanpa Ket.</p>
                    <h3 class="text-2xl font-black text-rose-600 mt-0.5">{{ $rekapBulanIni['alpa'] }} Sesi</h3>
                </div>
            </div>
        </div>

        <!-- PRESENSI HARI INI PER JAM PELAJARAN (JP) -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="calendar-check" class="w-5 h-5 text-[#405078]"></i>
                    <h3 class="font-bold text-[#1E2538] text-base">Presensi Jam Pelajaran Hari Ini ({{ $namaHari }})</h3>
                </div>
                <span class="text-xs text-gray-400 font-semibold">Tercatat per sesi guru</span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($presensiPerJp as $p)
                    <div class="p-5 hover:bg-gray-50/50 transition-colors flex items-center justify-between">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="px-2.5 py-0.5 bg-[#405078]/10 text-[#405078] text-xs font-bold rounded-md">
                                    {{ $p['jam_ke'] }}
                                </span>
                                <h4 class="font-bold text-sm text-[#1E2538]">{{ $p['mapel'] }}</h4>
                            </div>
                            <p class="text-xs text-gray-500">
                                Guru Pengajar: <span class="font-medium text-gray-700">{{ $p['guru'] }}</span> • Ruang: {{ $p['ruangan'] }}
                            </p>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $p['badge'] }}">
                                {{ $p['status'] }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 italic text-xs">
                        Tidak ada jadwal pembelajaran untuk kelas ini pada hari ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- INFORMASI IZIN KELUAR SEKOLAH (JIKA ADA) -->
        @if($dispenHariIni)
            <div class="bg-white border border-[#D1D9EB] rounded-2xl p-6 shadow-sm space-y-3">
                <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2 pb-2 border-b border-gray-100">
                    <i data-lucide="log-out" class="w-5 h-5 text-[#405078]"></i>
                    <span>Catatan Izin Keluar Sekolah Hari Ini</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block font-semibold">Keperluan Izin:</span>
                        <span class="font-bold text-[#1E2538] text-sm">{{ $dispenHariIni->keperluan }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block font-semibold">Waktu Rencana:</span>
                        <span class="text-gray-700">{{ substr($dispenHariIni->jam_keluar_rencana, 0, 5) }} s/d {{ $dispenHariIni->jam_kembali_rencana ? substr($dispenHariIni->jam_kembali_rencana, 0, 5) : '-' }} WIB</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block font-semibold">Status Gerbang:</span>
                        <span class="font-bold {{ $dispenHariIni->status === 'Sudah Kembali' ? 'text-emerald-700' : 'text-amber-700' }}">
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
