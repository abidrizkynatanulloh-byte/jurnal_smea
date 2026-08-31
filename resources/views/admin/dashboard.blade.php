@extends('layouts.app')

@section('title', 'Dashboard Tata Usaha - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-dark tracking-tight">Dashboard Tata Usaha</h1>
            <p class="text-sm text-gray-500 mt-1">
                <span class="font-semibold text-brand">{{ $tanggalHariIniTeks }}</span> | Ringkasan operasional sekolah hari ini
            </p>
        </div>
    </div>

    <!-- 1. Statistik Grid (Empat Kartu) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Siswa -->
        <div class="bg-white border border-[#19140015] rounded-2xl p-6 shadow-sm flex items-start space-x-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Siswa Aktif</p>
                <h3 class="text-2xl font-bold text-dark mt-1">{{ $totalSiswa }}</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Siswa terdaftar</p>
            </div>
        </div>

        <!-- Card 2: Guru & Staf -->
        <div class="bg-white border border-[#19140015] rounded-2xl p-6 shadow-sm flex items-start space-x-4">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Guru & Pegawai</p>
                <h3 class="text-2xl font-bold text-dark mt-1">{{ $totalPegawai }}</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">{{ $totalGuru }} Guru | {{ $totalStaf }} Staf TU</p>
            </div>
        </div>

        <!-- Card 3: Jadwal -->
        <div class="bg-white border border-[#19140015] rounded-2xl p-6 shadow-sm flex items-start space-x-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Jadwal Mengajar</p>
                <h3 class="text-2xl font-bold text-dark mt-1">{{ $totalJadwal }}</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Jadwal mingguan aktif</p>
            </div>
        </div>

        <!-- Card 4: Kepatuhan -->
        <div class="bg-white border border-[#19140015] rounded-2xl p-6 shadow-sm flex items-start space-x-4">
            <div class="p-3 bg-brand/10 text-brand rounded-xl">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kepatuhan Jurnal</p>
                <h3 class="text-2xl font-bold text-dark mt-1">{{ $persentaseKepatuhan }}%</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">{{ $jurnalTerisiHariIni }} dari {{ $totalJadwalHariIni }} sesi terisi</p>
            </div>
        </div>
    </div>

    <!-- 2. Tabel Jadwal Hari Ini (Slide 10 Data per Tampilan) -->
    <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#19140015] flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
            <div class="flex items-center space-x-3">
                <div class="w-2.5 h-2.5 bg-brand rounded-full animate-pulse"></div>
                <h3 class="font-bold text-dark text-base">Jadwal Mengajar Hari Ini ({{ $namaHariIni }})</h3>
            </div>
            
            <div class="flex items-center space-x-4">
                <span id="slideInfo" class="text-xs font-semibold text-gray-500">Menampilkan 1-10 dari {{ count($jadwalHariIni) }} sesi</span>
                <div class="inline-flex rounded-lg border border-gray-200 p-0.5 bg-gray-50">
                    <button type="button" id="prevBtn" onclick="geserSlide(-1)" class="p-1.5 rounded-md hover:bg-white text-gray-500 hover:text-dark disabled:opacity-40 transition-colors cursor-pointer" disabled>
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <button type="button" id="nextBtn" onclick="geserSlide(1)" class="p-1.5 rounded-md hover:bg-white text-gray-500 hover:text-dark disabled:opacity-40 transition-colors cursor-pointer">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#19140015]">
                        <th class="py-4 px-6 text-center w-16">No</th>
                        <th class="py-4 px-6">Jam Ke-</th>
                        <th class="py-4 px-6">Kelas</th>
                        <th class="py-4 px-6">Guru</th>
                        <th class="py-4 px-6">Mata Pelajaran</th>
                        <th class="py-4 px-6">Ruangan</th>
                        <th class="py-4 px-6">Status Jurnal</th>
                    </tr>
                </thead>
                <tbody id="jadwalTbody" class="divide-y divide-[#19140010] text-sm text-gray-600">
                    @forelse ($jadwalHariIni as $index => $j)
                        <tr class="jadwal-row hover:bg-gray-50/50 transition-colors" data-index="{{ $index }}" style="{{ $index >= 10 ? 'display: none;' : '' }}">
                            <td class="py-4 px-6 text-center font-medium text-gray-400">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-semibold text-dark">Jam ke {{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                            <td class="py-4 px-6"><span class="px-2.5 py-1 bg-gray-100 rounded-md text-xs font-bold text-gray-700">{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</span></td>
                            <td class="py-4 px-6 font-medium text-dark">{{ $j->guru ? $j->guru->nama_guru : '-' }}</td>
                            <td class="py-4 px-6">{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</td>
                            <td class="py-4 px-6"><span class="inline-flex items-center space-x-1"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400"></i><span>{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</span></span></td>
                            <td class="py-4 px-6">
                                @if ($j->status_jurnal === 'Selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        <span>Selesai</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full space-x-1 animate-pulse">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                        <span>Terjadwal</span>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 italic">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                Tidak ada jadwal mengajar pada hari {{ $namaHariIni }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. Perlu Tindakan Widget -->
    <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm p-6">
        <h3 class="font-bold text-dark text-base mb-4 flex items-center space-x-2">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-brand"></i>
            <span>Perlu Tindakan Penting</span>
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Warning 1 -->
            <div class="p-4 bg-amber-50/50 border border-amber-200/60 rounded-xl flex items-start space-x-3.5">
                <div class="p-2 bg-amber-100 text-amber-800 rounded-lg">
                    <i data-lucide="book-x" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-900">Jurnal Mengajar Kemarin</h4>
                    <p class="text-xs text-amber-700 mt-0.5"><span class="font-bold text-brand">{{ $guruBelumIsiKemarin }} sesi mengajar</span> terdeteksi belum mengisi jurnal kemarin.</p>
                </div>
            </div>

            <!-- Warning 2 -->
            <div class="p-4 bg-red-50/30 border border-red-150 rounded-xl flex items-start space-x-3.5">
                <div class="p-2 bg-red-100/80 text-red-800 rounded-lg">
                    <i data-lucide="user-x" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-red-900">Ketidakhadiran Siswa</h4>
                    <p class="text-xs text-red-700 mt-0.5"><span class="font-bold text-brand">{{ $siswaAlpaHariIni }} siswa</span> tercatat tidak hadir tanpa keterangan (Alpa) hari ini.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT JAVASCRIPT SLIDE INSTAN (10 DATA PER SLIDE) -->
<script>
    let currentSlide = 0;
    const perSlide = 10;
    const rows = document.querySelectorAll('.jadwal-row');
    const totalRows = rows.length;
    const totalSlides = Math.ceil(totalRows / perSlide);

    function updateSlideView() {
        const start = currentSlide * perSlide;
        const end = start + perSlide;

        rows.forEach((row, idx) => {
            if (idx >= start && idx < end) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        const tampilSampai = Math.min(end, totalRows);
        const tampilMulai = totalRows > 0 ? start + 1 : 0;
        document.getElementById('slideInfo').innerText = `Menampilkan ${tampilMulai}-${tampilSampai} dari ${totalRows} sesi`;

        document.getElementById('prevBtn').disabled = (currentSlide === 0);
        document.getElementById('nextBtn').disabled = (currentSlide >= totalSlides - 1 || totalSlides === 0);
    }

    function geserSlide(arah) {
        currentSlide += arah;
        if (currentSlide < 0) currentSlide = 0;
        if (currentSlide >= totalSlides) currentSlide = totalSlides - 1;
        updateSlideView();
    }
</script>
@endsection