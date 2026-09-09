@extends('layouts.app')

@section('title', 'Dashboard Guru - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Dashboard Guru Pengajar</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Selamat datang, <span class="font-bold text-slate-800">{{ $guru->nama_guru ?? 'Bapak/Ibu Guru' }}</span> • {{ $tanggalTeks ?? '' }}
            </p>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('guru.jurnal.rekap') }}" class="h-9.5 px-6 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shadow-2xs cursor-pointer">
                <i data-lucide="history" class="w-4 h-4"></i>
                <span>Riwayat Jurnal Saya</span>
            </a>
        </div>
    </div>

    <!-- STATISTIK RINGKASAN GURU -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        <!-- Card 1: Total Jadwal Semua -->
        <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs flex items-start space-x-3">
            <div class="w-8 h-8 bg-slate-100 text-slate-700 rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Jadwal Mingguan</p>
                <h3 class="text-xl font-bold text-slate-900 mt-0.5 tabular-nums">{{ $totalJadwalSemua ?? 0 }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Total sesi jadwal mengajar</p>
            </div>
        </div>

        <!-- Card 2: Sesi Hari Ini -->
        <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs flex items-start space-x-3">
            <div class="w-8 h-8 bg-slate-100 text-slate-700 rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sesi Hari Ini ({{ $namaHariIni ?? '' }})</p>
                <h3 class="text-xl font-bold text-slate-900 mt-0.5 tabular-nums">{{ $totalSesiHariIni ?? 0 }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Sesi mengajar aktif hari ini</p>
            </div>
        </div>

        <!-- Card 3: Sudah Diisi Hari Ini -->
        <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs flex items-start space-x-3">
            <div class="w-8 h-8 bg-emerald-50 text-emerald-700 rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Jurnal Terisi Hari Ini</p>
                <h3 class="text-xl font-bold text-slate-900 mt-0.5 tabular-nums">{{ $sudahDiisiHariIni ?? 0 }} / {{ $totalSesiHariIni ?? 0 }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Sesi tersimpan</p>
            </div>
        </div>

        <!-- Card 4: Status Belum Diisi Minggu Ini -->
        <a href="{{ route('guru.jurnal.tertunggak') }}" class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-xs hover:border-rose-300 transition-all flex items-start space-x-3 group cursor-pointer">
            <div class="w-8 h-8 {{ ($belumIsiMingguIni ?? 0) > 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="{{ ($belumIsiMingguIni ?? 0) > 0 ? 'alert-triangle' : 'sparkles' }}" class="w-4 h-4"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold uppercase tracking-wider {{ ($belumIsiMingguIni ?? 0) > 0 ? 'text-rose-600' : 'text-slate-400' }}">Tertunggak Minggu Ini</p>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 group-hover:text-rose-600 transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold {{ ($belumIsiMingguIni ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-0.5 tabular-nums">
                    {{ $belumIsiMingguIni ?? 0 }} Sesi
                </h3>
                <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ ($belumIsiMingguIni ?? 0) > 0 ? 'Klik untuk lihat rincian' : 'Semua jurnal lengkap' }}</p>
            </div>
        </a>
    </div>

    <!-- TABEL JADWAL HARI INI -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-3.5 py-2.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-[#1E2538] rounded-full animate-pulse"></div>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Jadwal Mengajar Hari Ini ({{ $namaHariIni ?? '' }})</h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-white text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-2 px-3.5 w-28">Jam Ke-</th>
                        <th class="py-2 px-3.5 w-24">Kelas</th>
                        <th class="py-2 px-3.5">Mata Pelajaran</th>
                        <th class="py-2 px-3.5">Ruangan</th>
                        <th class="py-2 px-3.5 text-center w-36">Status Jurnal</th>
                        <th class="py-2 px-3.5 text-center w-36">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($jadwalHariIni as $j)
                        @php $statusWaktu = $j->statusWaktuMengajar(); @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2 px-3.5 font-semibold text-slate-900">Jam {{ $j->jam_mulai }}–{{ $j->jam_selesai }}</td>
                            <td class="py-2 px-3.5">
                                <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[11px] font-bold text-slate-700">
                                    {{ $j->kelas ? $j->kelas->nama_kelas : '-' }}
                                </span>
                            </td>
                            <td class="py-2 px-3.5 font-medium text-slate-800">{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</td>
                            <td class="py-2 px-3.5"><span class="inline-flex items-center space-x-1 text-slate-500"><i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i><span>{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</span></span></td>
                            <td class="py-2 px-3.5 text-center">
                                @if ($j->sudah_diisi)
                                    <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold rounded space-x-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        <span>Sudah Diisi</span>
                                    </span>
                                @elseif ($statusWaktu === 'sekarang')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold rounded space-x-1 animate-pulse">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                        <span>Saatnya Diisi</span>
                                    </span>
                                @elseif ($statusWaktu === 'belum')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-500 border border-slate-200 text-[10px] font-medium rounded space-x-1">
                                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                        <span>Belum Mulai</span>
                                    </span>
                                @elseif ($statusWaktu === 'telat')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold rounded space-x-1">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        <span>Terlewat (Alpa)</span>
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-2 px-3.5 text-center">
                                @if ($j->sudah_diisi)
                                    <a href="{{ route('guru.jurnal.show', $j->jurnal->id_jurnal) }}" class="inline-flex items-center space-x-1 h-7 px-2.5 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 rounded-md text-xs font-semibold transition-colors">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        <span>Lihat Jurnal</span>
                                    </a>
                                @elseif ($statusWaktu === 'sekarang')
                                    <a href="{{ route('guru.jurnal.create', $j->id_jadwal) }}" class="inline-flex items-center space-x-1 h-7 px-2.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-md text-xs font-semibold transition-colors shadow-2xs">
                                        <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        <span>Isi Jurnal</span>
                                    </a>
                                @elseif ($statusWaktu === 'belum')
                                    <span class="inline-block px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-400 rounded-md text-[11px] font-medium cursor-not-allowed">
                                        Menunggu Jam
                                    </span>
                                @elseif ($statusWaktu === 'telat')
                                    <span class="inline-block px-2.5 py-1 bg-rose-50 border border-rose-200 text-rose-600 rounded-md text-[11px] font-medium">
                                        Waktu Habis
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 italic text-xs">
                                <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                                Tidak ada jadwal mengajar pada hari ini. Selamat beristirahat!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection