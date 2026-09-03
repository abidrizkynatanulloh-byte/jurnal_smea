@extends('layouts.app')

@section('title', 'Dashboard Tata Usaha - Jurnal Esemkita')

@section('content')
<div class="space-y-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <div>
            <h1 class="text-xl font-extrabold text-[#1E2538] tracking-tight">Dashboard Tata Usaha</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                <span class="font-semibold text-[#405078]">{{ $tanggalHariIniTeks }}</span> • Monitoring & rekapitulasi operasional sekolah
            </p>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('admin.rekap.index') }}" class="px-3.5 py-1.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i>
                <span>Lihat Rekapitulasi Jurnal</span>
            </a>
        </div>
    </div>

    <!-- 1. Statistik Grid Utama (Klik untuk membuka halaman detail) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Siswa -->
        <a href="{{ route('admin.siswa.index') }}" class="card-elevated rounded-2xl p-4 flex items-start space-x-3.5 group cursor-pointer relative overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#405078]/15 to-[#405078]/5 text-[#405078] flex items-center justify-center group-hover:scale-105 transition-transform flex-shrink-0 ring-1 ring-[#405078]/20">
                <i data-lucide="graduation-cap" class="w-5 h-5"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Siswa</p>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300 group-hover:text-[#405078] group-hover:translate-x-0.5 transition-all"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5 tracking-tight tabular-nums">{{ number_format($totalSiswa, 0, ',', '.') }}</h3>
                <p class="text-[10.5px] text-slate-500 mt-0.5 truncate">Lihat direktori siswa & rombel</p>
            </div>
        </a>

        <!-- Card 2: Guru & Staf -->
        <a href="{{ route('admin.guru.index') }}" class="card-elevated rounded-2xl p-4 flex items-start space-x-3.5 group cursor-pointer relative overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#8697C3]/20 to-[#8697C3]/5 text-[#405078] flex items-center justify-center group-hover:scale-105 transition-transform flex-shrink-0 ring-1 ring-[#8697C3]/30">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Guru & Pegawai</p>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300 group-hover:text-[#405078] group-hover:translate-x-0.5 transition-all"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5 tracking-tight tabular-nums">{{ $totalPegawai }}</h3>
                <p class="text-[10.5px] text-slate-500 mt-0.5 truncate">{{ $totalGuru }} Guru • {{ $totalStaf }} Staf TU</p>
            </div>
        </a>

        <!-- Card 3: Jadwal -->
        <a href="{{ route('admin.jadwal.index') }}" class="card-elevated rounded-2xl p-4 flex items-start space-x-3.5 group cursor-pointer relative overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#405078]/15 to-[#405078]/5 text-[#405078] flex items-center justify-center group-hover:scale-105 transition-transform flex-shrink-0 ring-1 ring-[#405078]/20">
                <i data-lucide="calendar" class="w-5 h-5"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Jadwal</p>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300 group-hover:text-[#405078] group-hover:translate-x-0.5 transition-all"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5 tracking-tight tabular-nums">{{ $totalJadwal }}</h3>
                <p class="text-[10.5px] text-slate-500 mt-0.5 truncate">Kelola matriks KBM mengajar</p>
            </div>
        </a>

        <!-- Card 4: Kepatuhan -->
        <a href="{{ route('admin.rekap.kepatuhan') }}" class="card-elevated rounded-2xl p-4 flex items-start space-x-3.5 group cursor-pointer relative overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/15 to-emerald-500/5 text-emerald-600 flex items-center justify-center group-hover:scale-105 transition-transform flex-shrink-0 ring-1 ring-emerald-500/20">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kepatuhan Jurnal</p>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition-all"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5 tracking-tight tabular-nums">{{ $persentaseKepatuhan }}%</h3>
                <p class="text-[10.5px] text-slate-500 mt-0.5 truncate">Rekapitulasi kepatuhan per guru</p>
            </div>
        </a>
    </div>

    <!-- 2. REKAP ABSENSI SISWA HARI INI -->
    <div class="card-elevated rounded-2xl p-4.5">
        <div class="flex items-center justify-between mb-3.5">
            <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider flex items-center space-x-2">
                <i data-lucide="user-check" class="w-4 h-4 text-[#405078]"></i>
                <span>Data Absensi Siswa Hari Ini</span>
            </h3>
            <span class="text-[11px] text-slate-500 font-semibold bg-slate-100/80 px-2.5 py-0.5 rounded-full border border-slate-200/60">{{ $tanggalHariIniTeks }}</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <!-- Hadir -->
            <div class="p-3 bg-gradient-to-br from-emerald-50/80 to-emerald-50/30 border border-emerald-200/70 rounded-xl flex items-center space-x-3">
                <div class="p-2 bg-emerald-100 text-emerald-700 rounded-lg">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Hadir</p>
                    <h4 class="text-xl font-black text-emerald-950 leading-tight tabular-nums">{{ $siswaHadirHariIni }}</h4>
                    <p class="text-[10px] text-emerald-700">Siswa aktif KBM</p>
                </div>
            </div>

            <!-- Sakit -->
            <div onclick="openModalSiswaSakit()" class="p-3 bg-gradient-to-br from-amber-50/80 to-amber-50/30 border border-amber-200/70 rounded-xl flex items-center space-x-3 hover:shadow-xs transition-all cursor-pointer group">
                <div class="p-2 bg-amber-100 text-amber-700 rounded-lg group-hover:scale-105 transition-transform">
                    <i data-lucide="stethoscope" class="w-4 h-4"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-amber-800">Sakit</p>
                        <span class="text-[10px] text-amber-700 font-bold underline flex items-center space-x-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span>Lihat</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </span>
                    </div>
                    <h4 class="text-xl font-black text-amber-950 leading-tight tabular-nums">{{ $siswaSakitHariIni }}</h4>
                    <p class="text-[10px] text-amber-700">Terdata sakit</p>
                </div>
            </div>

            <!-- Izin / Dispen -->
            <div onclick="openModalSiswaIzin()" class="p-3 bg-gradient-to-br from-blue-50/80 to-blue-50/30 border border-blue-200/70 rounded-xl flex items-center space-x-3 hover:shadow-xs transition-all cursor-pointer group">
                <div class="p-2 bg-blue-100 text-blue-700 rounded-lg group-hover:scale-105 transition-transform">
                    <i data-lucide="file-badge" class="w-4 h-4"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-blue-800">Izin / Dispen</p>
                        <span class="text-[10px] text-blue-700 font-bold underline flex items-center space-x-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span>Lihat</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </span>
                    </div>
                    <h4 class="text-xl font-black text-blue-950 leading-tight tabular-nums">{{ $siswaIzinTotal }}</h4>
                    <p class="text-[10px] text-blue-700">Izin & dispen sah</p>
                </div>
            </div>

            <!-- Alpa -->
            <div onclick="openModalSiswaAlpa()" class="p-3 bg-gradient-to-br from-rose-50/80 to-rose-50/30 border border-rose-200/70 rounded-xl flex items-center space-x-3 hover:shadow-xs transition-all cursor-pointer group">
                <div class="p-2 bg-rose-100 text-rose-700 rounded-lg group-hover:scale-105 transition-transform">
                    <i data-lucide="user-x" class="w-4 h-4"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-rose-800">Alpa</p>
                        <span class="text-[10px] text-rose-700 font-bold underline flex items-center space-x-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span>Lihat</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </span>
                    </div>
                    <h4 class="text-xl font-black text-rose-950 leading-tight tabular-nums">{{ $siswaAlpaHariIni }}</h4>
                    <p class="text-[10px] text-rose-700">Tanpa keterangan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Perhatian Operasional (Klik untuk membuka daftar detail) -->
    <div class="card-elevated rounded-2xl p-4.5">
        <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider mb-3 flex items-center space-x-2">
            <i data-lucide="alert-triangle" class="w-4 h-4 text-[#405078]"></i>
            <span>Perhatian Operasional (Klik Kartu untuk Melihat List Guru)</span>
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <!-- Warning 1: Jurnal Kemarin -->
            <div onclick="openModalKemarin()" class="p-3.5 bg-gradient-to-r from-amber-50/90 to-amber-50/40 border border-amber-200/80 rounded-xl flex items-start space-x-3 hover:shadow-xs transition-all cursor-pointer group">
                <div class="p-2 bg-amber-100 text-amber-800 rounded-lg group-hover:scale-105 transition-transform flex-shrink-0">
                    <i data-lucide="book-x" class="w-4 h-4"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-amber-950">Jurnal Mengajar Kemarin</h4>
                        <span class="text-[10px] text-amber-800 font-bold underline flex items-center space-x-0.5">
                            <span>Lihat List</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </span>
                    </div>
                    <p class="text-xs text-amber-800/90 mt-0.5"><span class="font-bold text-[#405078]">{{ $guruBelumIsiKemarin }} sesi mengajar</span> terdeteksi belum mengisi jurnal kemarin.</p>
                </div>
            </div>

            <!-- Warning 2: Guru Alpa Hari Ini -->
            <div onclick="openModalAlpa()" class="p-3.5 bg-gradient-to-r from-rose-50/90 to-rose-50/40 border border-rose-200/80 rounded-xl flex items-start space-x-3 hover:shadow-xs transition-all cursor-pointer group">
                <div class="p-2 bg-rose-100 text-rose-800 rounded-lg group-hover:scale-105 transition-transform flex-shrink-0">
                    <i data-lucide="user-x" class="w-4 h-4"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-rose-950">Guru Alpa Hari Ini</h4>
                        <span class="text-[10px] text-rose-800 font-bold underline flex items-center space-x-0.5">
                            <span>Lihat List Alpa</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </span>
                    </div>
                    <p class="text-xs text-rose-800/90 mt-0.5"><span class="font-bold text-rose-700">{{ $guruAlpaHariIni }} guru / {{ count($listGuruAlpaHariIni) }} sesi</span> terdeteksi Alpa (jam lewat).</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Tabel Jadwal Hari Ini (Urutan: ALPA lebih dulu!) -->
    <div class="card-elevated rounded-2xl overflow-hidden">
        <div class="px-4.5 py-3 border-b border-slate-200/70 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2.5 sm:space-y-0 bg-slate-50/50">
            <div class="flex items-center space-x-2.5">
                <div class="w-2 h-2 bg-[#405078] rounded-full animate-pulse"></div>
                <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider">Jadwal Mengajar Hari Ini ({{ $namaHariIni }})</h3>
                <span class="text-[10px] font-extrabold text-rose-700 bg-rose-50 border border-rose-200/80 px-2 py-0.5 rounded-full">Prioritas Alpa</span>
            </div>
            
            <div class="flex items-center space-x-3">
                <span id="slideInfo" class="text-xs font-semibold text-slate-500">Menampilkan 1-10 dari {{ count($jadwalHariIni) }} sesi</span>
                <div class="inline-flex rounded-lg border border-slate-200 p-0.5 bg-white shadow-2xs">
                    <button type="button" id="prevBtn" onclick="geserSlide(-1)" class="p-1 rounded hover:bg-slate-100 text-slate-500 hover:text-[#1E2538] disabled:opacity-40 transition-colors cursor-pointer" disabled>
                        <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                    </button>
                    <button type="button" id="nextBtn" onclick="geserSlide(1)" class="p-1 rounded hover:bg-slate-100 text-slate-500 hover:text-[#1E2538] disabled:opacity-40 transition-colors cursor-pointer">
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto max-h-[480px] overflow-y-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="sticky top-0 bg-slate-50/95 backdrop-blur-xs z-10">
                    <tr class="text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-2.5 px-3 text-center w-12">No</th>
                        <th class="py-2.5 px-3 w-28">Jam Ke-</th>
                        <th class="py-2.5 px-3 w-24">Kelas</th>
                        <th class="py-2.5 px-3">Guru</th>
                        <th class="py-2.5 px-3">Mata Pelajaran</th>
                        <th class="py-2.5 px-3 w-28">Ruangan</th>
                        <th class="py-2.5 px-3 text-center w-36">Status Jurnal</th>
                    </tr>
                </thead>
                <tbody id="jadwalTbody" class="divide-y divide-slate-100 text-slate-600">
                    @forelse ($jadwalHariIni as $index => $j)
                        <tr class="jadwal-row hover:bg-slate-50/60 transition-colors {{ $j->status_jurnal === 'Alpa' ? 'bg-rose-50/30' : '' }}" data-index="{{ $index }}" style="{{ $index >= 10 ? 'display: none;' : '' }}">
                            <td class="py-2 px-3 text-center font-medium text-slate-400 text-xs">{{ $index + 1 }}</td>
                            <td class="py-2 px-3 font-semibold text-[#1E2538]">Jam {{ $j->jam_mulai }}–{{ $j->jam_selesai }}</td>
                            <td class="py-2 px-3"><span class="px-1.5 py-0.5 bg-slate-100 rounded text-[11px] font-bold text-slate-700">{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</span></td>
                            <td class="py-2 px-3 font-medium text-[#1E2538] leading-tight">{{ $j->guru ? $j->guru->nama_guru : '-' }}</td>
                            <td class="py-2 px-3 font-medium text-[#405078]">{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</td>
                            <td class="py-2 px-3 text-slate-500"><span class="inline-flex items-center space-x-1"><i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i><span>{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</span></span></td>
                            <td class="py-2 px-3 text-center">
                                @if ($j->status_jurnal === 'Selesai')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        <span>Selesai</span>
                                    </span>
                                @elseif ($j->status_jurnal === 'Alpa')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-rose-100 text-rose-700 border border-rose-300 text-[11px] font-extrabold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-rose-600 rounded-full animate-ping"></span>
                                        <span>Alpa (Belum Diisi)</span>
                                    </span>
                                @elseif (str_contains($j->status_jurnal, 'Sah'))
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-bold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                        <span>{{ $j->status_jurnal }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-semibold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                        <span>Terjadwal</span>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 italic text-xs">
                                <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
                                Tidak ada jadwal mengajar pada hari {{ $namaHariIni }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL 1: LIST GURU ALPA HARI INI -->
<div id="modalAlpa" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-[#D1D9EB] rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-6 py-4 border-b border-[#D1D9EB] bg-rose-50 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i data-lucide="user-x" class="w-5 h-5 text-rose-600"></i>
                <h3 class="font-bold text-[#1E2538] text-base">Daftar Guru Alpa / Belum Isi Jurnal Hari Ini</h3>
            </div>
            <button onclick="closeModalAlpa()" class="text-gray-400 hover:text-gray-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto space-y-4">
            <p class="text-xs text-gray-500">Berikut daftar sesi mengajar hari ini yang jam mengajarnya telah berakhir namun belum ada catatan jurnal mengajar:</p>
            <div class="overflow-x-auto border border-[#D1D9EB] rounded-xl">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-[#F8FAFC] text-gray-500 font-semibold border-b border-[#D1D9EB]">
                        <tr>
                            <th class="py-3 px-4 text-center w-12">No</th>
                            <th class="py-3 px-4">Nama Guru</th>
                            <th class="py-3 px-4">Kelas & Mapel</th>
                            <th class="py-3 px-4 text-center">Jam Ke-</th>
                            <th class="py-3 px-4">Ruangan</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($listGuruAlpaHariIni as $idx => $g)
                            <tr class="hover:bg-rose-50/20">
                                <td class="py-3 px-4 text-center font-medium text-gray-400">{{ $idx + 1 }}</td>
                                <td class="py-3 px-4 font-bold text-[#1E2538]">{{ $g->guru ? $g->guru->nama_guru : '-' }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-gray-700">{{ $g->kelas ? $g->kelas->nama_kelas : '-' }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $g->mapel ? $g->mapel->nama_mapel : '-' }}</div>
                                </td>
                                <td class="py-3 px-4 text-center font-bold text-rose-700">Jam {{ $g->jam_mulai }} - {{ $j->jam_selesai ?? $g->jam_selesai }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $g->ruangan ? $g->ruangan->nama_ruangan : '-' }}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-0.5 bg-rose-100 text-rose-700 font-extrabold rounded-md text-[10px]">ALPA</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-400 italic">
                                    Tidak ada guru yang terdeteksi Alpa hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="px-6 py-3.5 border-t border-[#D1D9EB] bg-gray-50 flex justify-end">
            <button onclick="closeModalAlpa()" class="px-4 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white text-xs font-bold rounded-xl cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL 2: LIST GURU BELUM ISI KEMARIN -->
<div id="modalKemarin" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-[#D1D9EB] rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-6 py-4 border-b border-[#D1D9EB] bg-amber-50 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i data-lucide="book-x" class="w-5 h-5 text-amber-700"></i>
                <h3 class="font-bold text-[#1E2538] text-base">Daftar Sesi Mengajar Belum Diisi (Kemarin)</h3>
            </div>
            <button onclick="closeModalKemarin()" class="text-gray-400 hover:text-gray-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto space-y-4">
            <p class="text-xs text-gray-500">Berikut daftar sesi mengajar kemarin yang belum diisi oleh guru pengampu:</p>
            <div class="overflow-x-auto border border-[#D1D9EB] rounded-xl">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-[#F8FAFC] text-gray-500 font-semibold border-b border-[#D1D9EB]">
                        <tr>
                            <th class="py-3 px-4 text-center w-12">No</th>
                            <th class="py-3 px-4">Nama Guru</th>
                            <th class="py-3 px-4">Kelas & Mapel</th>
                            <th class="py-3 px-4 text-center">Jam Ke-</th>
                            <th class="py-3 px-4">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($listGuruBelumIsiKemarin as $idx => $g)
                            <tr class="hover:bg-amber-50/20">
                                <td class="py-3 px-4 text-center font-medium text-gray-400">{{ $idx + 1 }}</td>
                                <td class="py-3 px-4 font-bold text-[#1E2538]">{{ $g->guru ? $g->guru->nama_guru : '-' }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-gray-700">{{ $g->kelas ? $g->kelas->nama_kelas : '-' }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $g->mapel ? $g->mapel->nama_mapel : '-' }}</div>
                                </td>
                                <td class="py-3 px-4 text-center font-bold text-amber-700">Jam {{ $g->jam_mulai }} - {{ $g->jam_selesai }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $g->ruangan ? $g->ruangan->nama_ruangan : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400 italic">
                                    Semua jurnal mengajar kemarin telah diisi lengkap.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="px-6 py-3.5 border-t border-[#D1D9EB] bg-gray-50 flex justify-end">
            <button onclick="closeModalKemarin()" class="px-4 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white text-xs font-bold rounded-xl cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL 3: LIST SISWA SAKIT HARI INI -->
<div id="modalSiswaSakit" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-[#D1D9EB] rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-6 py-4 border-b border-[#D1D9EB] bg-amber-50 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i data-lucide="stethoscope" class="w-5 h-5 text-amber-600"></i>
                <h3 class="font-bold text-[#1E2538] text-base">Daftar Siswa Sakit Hari Ini</h3>
                <span class="text-xs font-bold text-amber-700 bg-amber-100 border border-amber-200 px-2 py-0.5 rounded-full">{{ $siswaSakitHariIni }} siswa</span>
            </div>
            <button onclick="closeModalSiswaSakit()" class="text-gray-400 hover:text-gray-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto space-y-4">
            <p class="text-xs text-gray-500">Berikut daftar siswa yang tercatat sakit pada jurnal mengajar hari ini:</p>
            <div class="overflow-x-auto border border-[#D1D9EB] rounded-xl">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-[#F8FAFC] text-gray-500 font-semibold border-b border-[#D1D9EB]">
                        <tr>
                            <th class="py-3 px-4 text-center w-12">No</th>
                            <th class="py-3 px-4">NIS</th>
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4">Kelas</th>
                            <th class="py-3 px-4">Pada Mata Pelajaran</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($listSiswaSakitHariIni as $idx => $d)
                            <tr class="hover:bg-amber-50/20">
                                <td class="py-3 px-4 text-center font-medium text-gray-400">{{ $idx + 1 }}</td>
                                <td class="py-3 px-4 font-mono text-gray-500">{{ $d->id_siswa }}</td>
                                <td class="py-3 px-4 font-bold text-[#1E2538]">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 bg-gray-100 rounded-md text-[10px] font-bold text-gray-700">{{ $d->siswa && $d->siswa->kelas ? $d->siswa->kelas->nama_kelas : '-' }}</span>
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    {{ $d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->mapel ? $d->jurnal->jadwal->mapel->nama_mapel : '-' }}
                                    @if($d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->kelas)
                                        <span class="text-[10px] text-gray-400">({{ $d->jurnal->jadwal->kelas->nama_kelas }})</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 font-extrabold rounded-md text-[10px]">SAKIT</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-400 italic">
                                    Tidak ada siswa yang sakit hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="px-6 py-3.5 border-t border-[#D1D9EB] bg-gray-50 flex justify-end">
            <button onclick="closeModalSiswaSakit()" class="px-4 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white text-xs font-bold rounded-xl cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL 4: LIST SISWA IZIN / DISPEN HARI INI -->
<div id="modalSiswaIzin" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-[#D1D9EB] rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-6 py-4 border-b border-[#D1D9EB] bg-blue-50 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i data-lucide="file-badge" class="w-5 h-5 text-blue-600"></i>
                <h3 class="font-bold text-[#1E2538] text-base">Daftar Siswa Izin / Dispen Hari Ini</h3>
                <span class="text-xs font-bold text-blue-700 bg-blue-100 border border-blue-200 px-2 py-0.5 rounded-full">{{ $siswaIzinTotal }} siswa</span>
            </div>
            <button onclick="closeModalSiswaIzin()" class="text-gray-400 hover:text-gray-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto space-y-5">
            {{-- Bagian 1: Izin dari Jurnal --}}
            @if($listSiswaIzinHariIni->count() > 0)
            <div>
                <p class="text-xs text-gray-500 mb-2 font-semibold">Izin (tercatat di jurnal mengajar):</p>
                <div class="overflow-x-auto border border-[#D1D9EB] rounded-xl">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-[#F8FAFC] text-gray-500 font-semibold border-b border-[#D1D9EB]">
                            <tr>
                                <th class="py-3 px-4 text-center w-12">No</th>
                                <th class="py-3 px-4">NIS</th>
                                <th class="py-3 px-4">Nama Siswa</th>
                                <th class="py-3 px-4">Kelas</th>
                                <th class="py-3 px-4">Pada Mata Pelajaran</th>
                                <th class="py-3 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($listSiswaIzinHariIni as $idx => $d)
                                <tr class="hover:bg-blue-50/20">
                                    <td class="py-3 px-4 text-center font-medium text-gray-400">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4 font-mono text-gray-500">{{ $d->id_siswa }}</td>
                                    <td class="py-3 px-4 font-bold text-[#1E2538]">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 bg-gray-100 rounded-md text-[10px] font-bold text-gray-700">{{ $d->siswa && $d->siswa->kelas ? $d->siswa->kelas->nama_kelas : '-' }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ $d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->mapel ? $d->jurnal->jadwal->mapel->nama_mapel : '-' }}
                                        @if($d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->kelas)
                                            <span class="text-[10px] text-gray-400">({{ $d->jurnal->jadwal->kelas->nama_kelas }})</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 font-extrabold rounded-md text-[10px]">IZIN</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Bagian 2: Dispensasi --}}
            @if($listDispenActive->count() > 0)
            <div>
                <p class="text-xs text-gray-500 mb-2 font-semibold">Dispensasi (surat dispen sah):</p>
                <div class="overflow-x-auto border border-[#D1D9EB] rounded-xl">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-[#F8FAFC] text-gray-500 font-semibold border-b border-[#D1D9EB]">
                            <tr>
                                <th class="py-3 px-4 text-center w-12">No</th>
                                <th class="py-3 px-4">NIS</th>
                                <th class="py-3 px-4">Nama Siswa</th>
                                <th class="py-3 px-4">Kelas</th>
                                <th class="py-3 px-4">Keperluan</th>
                                <th class="py-3 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($listDispenActive as $idx => $dp)
                                <tr class="hover:bg-blue-50/20">
                                    <td class="py-3 px-4 text-center font-medium text-gray-400">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4 font-mono text-gray-500">{{ $dp->nis }}</td>
                                    <td class="py-3 px-4 font-bold text-[#1E2538]">{{ $dp->siswa ? $dp->siswa->nama_siswa : '-' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 bg-gray-100 rounded-md text-[10px] font-bold text-gray-700">{{ $dp->siswa && $dp->siswa->kelas ? $dp->siswa->kelas->nama_kelas : '-' }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">{{ $dp->keperluan ?? '-' }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 font-extrabold rounded-md text-[10px]">DISPEN</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($listSiswaIzinHariIni->count() === 0 && $listDispenActive->count() === 0)
                <div class="py-6 text-center text-gray-400 italic">
                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                    Tidak ada siswa yang izin atau dispen hari ini.
                </div>
            @endif
        </div>
        <div class="px-6 py-3.5 border-t border-[#D1D9EB] bg-gray-50 flex justify-end">
            <button onclick="closeModalSiswaIzin()" class="px-4 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white text-xs font-bold rounded-xl cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL 5: LIST SISWA ALPA HARI INI -->
<div id="modalSiswaAlpa" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-[#D1D9EB] rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-6 py-4 border-b border-[#D1D9EB] bg-rose-50 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i data-lucide="user-x" class="w-5 h-5 text-rose-600"></i>
                <h3 class="font-bold text-[#1E2538] text-base">Daftar Siswa Alpa (Tanpa Keterangan) Hari Ini</h3>
                <span class="text-xs font-bold text-rose-700 bg-rose-100 border border-rose-200 px-2 py-0.5 rounded-full">{{ $siswaAlpaHariIni }} siswa</span>
            </div>
            <button onclick="closeModalSiswaAlpa()" class="text-gray-400 hover:text-gray-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto space-y-4">
            <p class="text-xs text-gray-500">Berikut daftar siswa yang tercatat Alpa (tanpa keterangan) pada jurnal mengajar hari ini:</p>
            <div class="overflow-x-auto border border-[#D1D9EB] rounded-xl">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-[#F8FAFC] text-gray-500 font-semibold border-b border-[#D1D9EB]">
                        <tr>
                            <th class="py-3 px-4 text-center w-12">No</th>
                            <th class="py-3 px-4">NIS</th>
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4">Kelas</th>
                            <th class="py-3 px-4">Pada Mata Pelajaran</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($listSiswaAlpaHariIni as $idx => $d)
                            <tr class="hover:bg-rose-50/20">
                                <td class="py-3 px-4 text-center font-medium text-gray-400">{{ $idx + 1 }}</td>
                                <td class="py-3 px-4 font-mono text-gray-500">{{ $d->id_siswa }}</td>
                                <td class="py-3 px-4 font-bold text-[#1E2538]">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 bg-gray-100 rounded-md text-[10px] font-bold text-gray-700">{{ $d->siswa && $d->siswa->kelas ? $d->siswa->kelas->nama_kelas : '-' }}</span>
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    {{ $d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->mapel ? $d->jurnal->jadwal->mapel->nama_mapel : '-' }}
                                    @if($d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->kelas)
                                        <span class="text-[10px] text-gray-400">({{ $d->jurnal->jadwal->kelas->nama_kelas }})</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-0.5 bg-rose-100 text-rose-700 font-extrabold rounded-md text-[10px]">ALPA</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-400 italic">
                                    Tidak ada siswa yang Alpa hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="px-6 py-3.5 border-t border-[#D1D9EB] bg-gray-50 flex justify-end">
            <button onclick="closeModalSiswaAlpa()" class="px-4 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white text-xs font-bold rounded-xl cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- SCRIPT SLIDER & MODAL -->
<script>
    let slideSekarang = 0;
    const perSlide = 10;
    const semuaBaris = document.querySelectorAll('.jadwal-row');
    const totalData = semuaBaris.length;

    function geserSlide(arah) {
        slideSekarang += arah;
        const awal = slideSekarang * perSlide;
        const akhir = awal + perSlide;

        semuaBaris.forEach((baris, index) => {
            baris.style.display = (index >= awal && index < akhir) ? '' : 'none';
        });

        document.getElementById('prevBtn').disabled = (slideSekarang === 0);
        document.getElementById('nextBtn').disabled = (akhir >= totalData);

        const akhirTeks = Math.min(akhir, totalData);
        const awalTeks = totalData === 0 ? 0 : awal + 1;
        document.getElementById('slideInfo').innerText = 
            `Menampilkan ${awalTeks}-${akhirTeks} dari ${totalData} sesi`;
    }

    // Modal Guru Alpa
    function openModalAlpa() {
        document.getElementById('modalAlpa').classList.remove('hidden');
    }
    function closeModalAlpa() {
        document.getElementById('modalAlpa').classList.add('hidden');
    }

    // Modal Guru Kemarin
    function openModalKemarin() {
        document.getElementById('modalKemarin').classList.remove('hidden');
    }
    function closeModalKemarin() {
        document.getElementById('modalKemarin').classList.add('hidden');
    }

    // Modal Siswa Sakit
    function openModalSiswaSakit() {
        document.getElementById('modalSiswaSakit').classList.remove('hidden');
        lucide.createIcons();
    }
    function closeModalSiswaSakit() {
        document.getElementById('modalSiswaSakit').classList.add('hidden');
    }

    // Modal Siswa Izin/Dispen
    function openModalSiswaIzin() {
        document.getElementById('modalSiswaIzin').classList.remove('hidden');
        lucide.createIcons();
    }
    function closeModalSiswaIzin() {
        document.getElementById('modalSiswaIzin').classList.add('hidden');
    }

    // Modal Siswa Alpa
    function openModalSiswaAlpa() {
        document.getElementById('modalSiswaAlpa').classList.remove('hidden');
        lucide.createIcons();
    }
    function closeModalSiswaAlpa() {
        document.getElementById('modalSiswaAlpa').classList.add('hidden');
    }

    // Tutup modal dengan klik di luar area modal
    document.querySelectorAll('[id^="modalSiswa"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });
</script>
@endsection