@extends('layouts.app')

@section('title', 'Dashboard Guru - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Dashboard Guru Pengajar</h1>
            <p class="text-xs text-gray-500 mt-1">
                Selamat datang, <span class="font-bold text-[#405078]">{{ $guru->nama_guru ?? 'Bapak/Ibu Guru' }}</span> • {{ $tanggalTeks ?? '' }}
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('guru.jurnal.rekap') }}" class="px-4 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-sm">
                <i data-lucide="history" class="w-4 h-4"></i>
                <span>Riwayat Jurnal Saya</span>
            </a>
        </div>
    </div>

    <!-- STATISTIK RINGKASAN GURU -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Jadwal Semua -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm flex items-start space-x-4">
            <div class="p-3 bg-[#405078]/10 text-[#405078] rounded-xl">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#8697C3]">Jadwal Mingguan</p>
                <h3 class="text-2xl font-black text-[#1E2538] mt-1">{{ $totalJadwalSemua ?? 0 }}</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Total sesi jadwal mengajar</p>
            </div>
        </div>

        <!-- Card 2: Sesi Hari Ini -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm flex items-start space-x-4">
            <div class="p-3 bg-[#8697C3]/15 text-[#405078] rounded-xl">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#8697C3]">Sesi Hari Ini ({{ $namaHariIni ?? '' }})</p>
                <h3 class="text-2xl font-black text-[#1E2538] mt-1">{{ $totalSesiHariIni ?? 0 }}</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Sesi mengajar aktif hari ini</p>
            </div>
        </div>

        <!-- Card 3: Sudah Diisi Hari Ini -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm flex items-start space-x-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#8697C3]">Jurnal Terisi Hari Ini</p>
                <h3 class="text-2xl font-black text-[#1E2538] mt-1">{{ $sudahDiisiHariIni ?? 0 }} / {{ $totalSesiHariIni ?? 0 }}</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Sesi tersimpan</p>
            </div>
        </div>

        <!-- Card 4: Status Belum Diisi Minggu Ini -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm flex items-start space-x-4">
            <div class="p-3 {{ ($belumIsiMingguIni ?? 0) > 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} rounded-xl">
                <i data-lucide="{{ ($belumIsiMingguIni ?? 0) > 0 ? 'alert-triangle' : 'sparkles' }}" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#8697C3]">Tertunggak Minggu Ini</p>
                <h3 class="text-2xl font-black {{ ($belumIsiMingguIni ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-1">
                    {{ $belumIsiMingguIni ?? 0 }} Sesi
                </h3>
                <p class="text-[11px] text-gray-500 mt-0.5">{{ ($belumIsiMingguIni ?? 0) > 0 ? 'Perlu segera diisi' : 'Semua jurnal lengkap' }}</p>
            </div>
        </div>
    </div>

    <!-- TABEL JADWAL HARI INI -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-2.5 h-2.5 bg-[#405078] rounded-full animate-pulse"></div>
                <h3 class="font-bold text-[#1E2538] text-base">Jadwal Mengajar Hari Ini ({{ $namaHariIni ?? '' }})</h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6 w-32">Jam Ke-</th>
                        <th class="py-4 px-6 w-32">Kelas</th>
                        <th class="py-4 px-6">Mata Pelajaran</th>
                        <th class="py-4 px-6">Ruangan</th>
                        <th class="py-4 px-6 text-center w-40">Status Jurnal</th>
                        <th class="py-4 px-6 text-center w-40">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse ($jadwalHariIni as $j)
                        @php $statusWaktu = $j->statusWaktuMengajar(); @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 font-semibold text-[#1E2538]">Jam {{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 bg-gray-100 rounded-md text-xs font-bold text-gray-700">
                                    {{ $j->kelas ? $j->kelas->nama_kelas : '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-medium text-[#1E2538]">{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</td>
                            <td class="py-4 px-6"><span class="inline-flex items-center space-x-1"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400"></i><span>{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</span></span></td>
                            <td class="py-4 px-6 text-center">
                                @if ($j->sudah_diisi)
                                    <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        <span>Sudah Diisi</span>
                                    </span>
                                @elseif ($statusWaktu === 'sekarang')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full space-x-1 animate-pulse">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                        <span>Saatnya Diisi</span>
                                    </span>
                                @elseif ($statusWaktu === 'belum')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                        <span>Belum Mulai</span>
                                    </span>
                                @elseif ($statusWaktu === 'telat')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-700 text-xs font-semibold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        <span>Terlewat (Alpa)</span>
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if ($j->sudah_diisi)
                                    <a href="{{ route('guru.jurnal.show', $j->jurnal->id_jurnal) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-[#405078]/10 hover:bg-[#405078]/20 text-[#405078] rounded-lg text-xs font-semibold transition-colors">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>Lihat Jurnal</span>
                                    </a>
                                @elseif ($statusWaktu === 'sekarang')
                                    <a href="{{ route('guru.jurnal.create', $j->id_jadwal) }}" class="inline-flex items-center space-x-1 px-3.5 py-1.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-lg text-xs font-bold transition-colors shadow-sm">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        <span>Isi Jurnal</span>
                                    </a>
                                @elseif ($statusWaktu === 'belum')
                                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-400 rounded-lg text-xs font-medium cursor-not-allowed">
                                        Menunggu Jam
                                    </span>
                                @elseif ($statusWaktu === 'telat')
                                    <span class="inline-block px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-medium">
                                        Waktu Habis
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 italic">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
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