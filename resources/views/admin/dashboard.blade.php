@extends('layouts.app')

@section('title', 'Dashboard Tata Usaha - Jurnal Esemkita')

@section('content')
<div class="space-y-5">
    <!-- Page Header (Sesuai Desain Gambar) -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <div class="flex items-center space-x-3">
            <!-- Balok Biru Solid di Sisi Kiri -->
            <div class="w-3 self-stretch bg-[#38BDF8] sm:bg-[#0284C7] rounded-l-md shrink-0 min-h-[48px]"></div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Dashboard Tata Usaha</h1>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-200 mt-0.5">
                    {{ $tanggalHariIniTeks }}
                </p>
                <p class="text-xs font-bold text-[#0284C7] dark:text-[#38BDF8] mt-0.5 flex items-center space-x-1">
                    <span>• Monitoring & rekapitulasi operasional sekolah</span>
                </p>
            </div>
        </div>
        <div class="hidden md:flex items-center space-x-2.5">
            <a href="{{ route('admin.rekap.index') }}" class="px-4 py-2 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5 shadow-xs">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                <span>Lihat Rekapitulasi Jurnal</span>
            </a>
        </div>
    </div>

    <!-- 1. Statistik 3 Kartu Utama (Sesuai Gambar Wireframe: Total Siswa, Total Guru, Total Jadwal) -->
    <div class="flex overflow-x-auto snap-x snap-mandatory gap-3 pb-1 md:grid md:grid-cols-3 no-scrollbar">
        <!-- Card 1: Total Siswa (Pastel Hijau Mint dengan Border Hijau Solid) -->
        <a href="{{ route('admin.siswa.index') }}" class="min-w-[210px] sm:min-w-0 flex-1 snap-start bg-[#E8F8F5] dark:bg-[#132924] border-2 border-[#34D399] dark:border-[#10B981] rounded-2xl p-3.5 sm:p-4 shadow-xs hover:shadow-sm transition-all flex flex-col justify-between group cursor-pointer">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-[#A7F3D0] dark:bg-[#1B3B34] text-[#065F46] dark:text-[#34D399] border border-[#6EE7B7] dark:border-[#265349] flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                </div>
                <div class="flex-1 ml-3 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-[10.5px] font-black text-[#047857] dark:text-[#6EE7B7] uppercase tracking-wider">TOTAL SISWA</p>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-[#059669] dark:text-[#A7F3D0] group-hover:translate-x-0.5 transition-all"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-[#065F46] dark:text-[#F0FDF4] mt-0.5 tracking-tight tabular-nums">{{ number_format($totalSiswa, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-[#A7F3D0]/60 dark:border-[#1D423A]">
                <p class="text-xs font-bold text-[#059669] dark:text-[#A7F3D0] truncate">Data Siswa & Rombel</p>
            </div>
        </a>

        <!-- Card 2: Total Guru & Staf (Pastel Biru Muda dengan Border Biru Solid) -->
        <a href="{{ route('admin.guru.index') }}" class="min-w-[210px] sm:min-w-0 flex-1 snap-start bg-[#E0F2FE] dark:bg-[#132337] border-2 border-[#38BDF8] dark:border-[#0284C7] rounded-2xl p-3.5 sm:p-4 shadow-xs hover:shadow-sm transition-all flex flex-col justify-between group cursor-pointer">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-[#BAE6FD] dark:bg-[#19334F] text-[#0369A1] dark:text-[#38BDF8] border border-[#7DD3FC] dark:border-[#23456B] flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div class="flex-1 ml-3 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-[10.5px] font-black text-[#0369A1] dark:text-[#93C5FD] uppercase tracking-wider">TOTAL GURU</p>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-[#0284C7] dark:text-[#BFDBFE] group-hover:translate-x-0.5 transition-all"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-[#075985] dark:text-[#EFF6FF] mt-0.5 tracking-tight tabular-nums">{{ number_format($totalPegawai, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-[#BAE6FD]/60 dark:border-[#1B3654]">
                <p class="text-xs font-bold text-[#0284C7] dark:text-[#BFDBFE] truncate">{{ $totalGuru }} GURU | {{ $totalStaf }} staf TU</p>
            </div>
        </a>

        <!-- Card 3: Total Jadwal (Pastel Kuning Hangat dengan Border Kuning Solid) -->
        <a href="{{ route('admin.jadwal.index') }}" class="min-w-[210px] sm:min-w-0 flex-1 snap-start bg-[#FEF9C3] dark:bg-[#2C1F0E] border-2 border-[#FBBF24] dark:border-[#D97706] rounded-2xl p-3.5 sm:p-4 shadow-xs hover:shadow-sm transition-all flex flex-col justify-between group cursor-pointer">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-[#FDE68A] dark:bg-[#3D2C13] text-[#B45309] dark:text-[#FBBF24] border border-[#FCD34D] dark:border-[#573F1C] flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div class="flex-1 ml-3 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-[10.5px] font-black text-[#B45309] dark:text-[#FCD34D] uppercase tracking-wider">TOTAL JADWAL</p>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-[#D97706] dark:text-[#FDE68A] group-hover:translate-x-0.5 transition-all"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-[#92400E] dark:text-[#FFFBEB] mt-0.5 tracking-tight tabular-nums">{{ number_format($totalJadwal, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-[#FDE68A]/60 dark:border-[#473216]">
                <p class="text-xs font-bold text-[#D97706] dark:text-[#FDE68A] truncate">{{ number_format($totalJadwal, 0, ',', '.') }} TERJADWALKAN</p>
            </div>
        </a>
    </div>

    <!-- 2. DATA ABSENSI SISWA HARI INI (Card Putih Rounded dengan 4 Kotak Status Sesuai Gambar) -->
    <div class="bg-white dark:bg-[#141412] border border-slate-200 dark:border-[#252525] rounded-3xl p-4 sm:p-5 shadow-xs">
        <div class="flex items-center space-x-2.5 mb-3.5">
            <i data-lucide="user-check" class="w-6 h-6 text-slate-800 dark:text-slate-100"></i>
            <h2 class="font-black text-slate-900 dark:text-slate-100 text-sm sm:text-base uppercase tracking-wider">
                DATA ABSENSI SISWA HARI INI
            </h2>
        </div>

        <div class="grid grid-cols-4 gap-2 sm:gap-3">
            <!-- Hadir (Pastel Mint) -->
            <div class="p-2 sm:p-3 bg-[#DCFCE7] dark:bg-[#132924] border-2 border-[#86EFAC] dark:border-[#1D423A] rounded-2xl flex flex-col items-center justify-center text-center shadow-2xs">
                <div class="w-7 h-7 rounded-lg bg-[#BBF7D0] dark:bg-[#1B3B34] text-[#15803D] dark:text-[#4ADE80] flex items-center justify-center flex-shrink-0">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </div>
                <p class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white mt-1.5">HADIR</p>
                <h4 class="text-lg sm:text-2xl font-black text-slate-900 dark:text-white leading-tight tabular-nums mt-0.5">{{ $siswaHadirHariIni }}</h4>
            </div>

            <!-- Sakit (Pastel Kuning) -->
            <div onclick="openModalSiswaSakit()" class="p-2 sm:p-3 bg-[#FEF9C3] dark:bg-[#2C1F0E] border-2 border-[#FDE047] dark:border-[#473216] hover:border-[#FACC15] rounded-2xl flex flex-col items-center justify-center text-center transition-all cursor-pointer group shadow-2xs">
                <div class="w-7 h-7 rounded-lg bg-[#FEF08A] dark:bg-[#3D2C13] text-[#A16207] dark:text-[#FACC15] flex items-center justify-center flex-shrink-0">
                    <i data-lucide="thermometer" class="w-4 h-4"></i>
                </div>
                <p class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white mt-1.5">SAKIT</p>
                <h4 class="text-lg sm:text-2xl font-black text-slate-900 dark:text-white leading-tight tabular-nums mt-0.5">{{ $siswaSakitHariIni }}</h4>
            </div>

            <!-- Izin (Pastel Ungu Lavender Sesuai Gambar) -->
            <div onclick="openModalSiswaIzin()" class="p-2 sm:p-3 bg-[#C084FC]/30 dark:bg-[#382453] border-2 border-[#A855F7] dark:border-[#523577] hover:border-[#9333EA] rounded-2xl flex flex-col items-center justify-center text-center transition-all cursor-pointer group shadow-2xs">
                <div class="w-7 h-7 rounded-lg bg-[#E9D5FF] dark:bg-[#4C1D95] text-[#7E22CE] dark:text-[#C084FC] flex items-center justify-center flex-shrink-0">
                    <i data-lucide="file-badge" class="w-4 h-4"></i>
                </div>
                <p class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white mt-1.5">IZIN</p>
                <h4 class="text-lg sm:text-2xl font-black text-slate-900 dark:text-white leading-tight tabular-nums mt-0.5">{{ $siswaIzinTotal }}</h4>
            </div>

            <!-- Alpha (Pastel Merah Muda / Coral Sesuai Gambar) -->
            <div onclick="openModalSiswaAlpa()" class="p-2 sm:p-3 bg-[#FCA5A5]/40 dark:bg-[#4B1E26] border-2 border-[#F87171] dark:border-[#6B2B36] hover:border-[#EF4444] rounded-2xl flex flex-col items-center justify-center text-center transition-all cursor-pointer group shadow-2xs">
                <div class="w-7 h-7 rounded-lg bg-[#FECDD3] dark:bg-[#5C1D24] text-[#BE123C] dark:text-[#F87171] flex items-center justify-center flex-shrink-0">
                    <i data-lucide="user-x" class="w-4 h-4"></i>
                </div>
                <p class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white mt-1.5">ALPHA</p>
                <h4 class="text-lg sm:text-2xl font-black text-slate-900 dark:text-white leading-tight tabular-nums mt-0.5">{{ $siswaAlpaHariIni }}</h4>
            </div>
        </div>
    </div>

    <!-- 3. TABEL JADWAL MENGAJAR HARI INI (Sesuai Desain Wireframe Gambar) -->
    <div class="bg-white dark:bg-[#141412] border border-slate-300 dark:border-[#252525] rounded-3xl shadow-xs overflow-hidden">
        <!-- Header Card: Judul, Badge Prioritas, Nav Panah < > -->
        <div class="px-4 sm:px-5 py-3.5 border-b border-slate-200 dark:border-[#252525] flex items-center justify-between bg-white dark:bg-[#1B1B18]">
            <div class="flex items-center space-x-2">
                <span class="text-slate-900 dark:text-white text-lg font-black leading-none">•</span>
                <h3 class="font-black text-slate-900 dark:text-slate-100 text-xs sm:text-sm tracking-tight">
                    Jadwal Mengajar Hari Ini ({{ $namaHariIni }})
                </h3>
            </div>
            
            <div class="flex items-center space-x-2 sm:space-x-3">
                <span class="text-[11px] font-bold text-rose-700 dark:text-rose-300 bg-[#FECDD3] dark:bg-rose-950/60 border border-[#FDA4AF] dark:border-rose-900/50 px-2.5 py-1 rounded-xl whitespace-nowrap">
                    Prioritas Belum Isi
                </span>
                <div class="inline-flex rounded-xl border border-slate-300 dark:border-slate-700 p-0.5 bg-white dark:bg-[#202020] shadow-2xs">
                    <button type="button" id="prevBtn" onclick="geserSlide(-1)" class="p-1 sm:p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-white/10 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white disabled:opacity-30 transition-colors cursor-pointer" disabled>
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <button type="button" id="nextBtn" onclick="geserSlide(1)" class="p-1 sm:p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-white/10 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white disabled:opacity-30 transition-colors cursor-pointer">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-hidden max-h-[520px] overflow-y-auto">
            <table class="w-full table-fixed text-left border-collapse text-xs">
                <!-- Header Cyan Sesuai Wireframe Gambar (5 Kolom Pas 1 Layar HP) -->
                <thead class="sticky top-0 bg-[#67D5F2] dark:bg-[#0E4A5C] border-b-2 border-[#38BDF8] dark:border-[#166876] z-10 shadow-xs">
                    <tr class="text-[#0C4A6E] dark:text-[#7DD3FC] font-black text-[11px] sm:text-xs">
                        <th class="py-2.5 px-1 sm:px-2 text-center w-[13%]">Jam Ke-</th>
                        <th class="py-2.5 px-1 sm:px-2 w-[18%]">Kelas</th>
                        <th class="py-2.5 px-1.5 sm:px-2.5 w-[28%]">Guru</th>
                        <th class="py-2.5 px-1.5 sm:px-2.5 w-[23%]">Mapel</th>
                        <th class="py-2.5 px-1 sm:px-2 text-center w-[18%]">status</th>
                    </tr>
                </thead>
                <tbody id="jadwalTbody" class="divide-y divide-slate-200 dark:divide-[#222220] text-slate-700 dark:text-slate-300">
                    @forelse ($jadwalHariIni as $index => $j)
                        <tr class="jadwal-row hover:bg-slate-50/80 transition-colors {{ $j->status_jurnal === 'Belum Mengisi Jurnal' ? 'bg-rose-50/20' : '' }}" data-index="{{ $index }}" style="{{ $index >= 10 ? 'display: none;' : '' }}">
                            <!-- Jam Ke- -->
                            <td class="py-2.5 px-1 sm:px-2 text-center font-black text-slate-900 dark:text-white text-[11px] sm:text-xs leading-tight">
                                {{ $j->jam_mulai }}{{ $j->jam_mulai != $j->jam_selesai ? ' - ' . $j->jam_selesai : '' }}
                            </td>

                            <!-- Kelas (Pill Cyan Sesuai Gambar: [XI TKJ 1] di atas, keterangan di bawah) -->
                            <td class="py-2.5 px-1 sm:px-2 align-top">
                                @php
                                    $rawKelas = $j->kelas ? $j->kelas->nama_kelas : '-';
                                    $namaUtama = $rawKelas;
                                    $keterangan = null;
                                    if (preg_match('/^(.*?)\s*\((.*?)\)$/i', $rawKelas, $matches)) {
                                        $namaUtama = trim($matches[1]);
                                        $keterangan = trim($matches[2]);
                                    }
                                @endphp
                                <div class="flex flex-col items-start leading-tight">
                                    <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-[#BCEBF5] dark:bg-[#16384C] border border-[#7DD3FC] dark:border-[#0284C7] rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-black text-[#0369A1] dark:text-[#7DD3FC] inline-block shadow-2xs">
                                        {{ $namaUtama }}
                                    </span>
                                    @if($keterangan)
                                        <span class="text-[9px] sm:text-[10px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5 lowercase first-letter:uppercase tracking-tight leading-tight">
                                            {{ $keterangan }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Guru -->
                            <td class="py-2.5 px-1.5 sm:px-2.5 font-bold text-slate-800 dark:text-slate-200 text-[10.5px] sm:text-xs leading-snug break-words align-top">
                                {{ $j->guru ? $j->guru->nama_guru : '-' }}
                            </td>

                            <!-- Mapel -->
                            <td class="py-2.5 px-1.5 sm:px-2.5 font-bold text-slate-900 dark:text-white text-[10px] sm:text-xs uppercase leading-tight break-words align-top">
                                {{ $j->mapel ? $j->mapel->nama_mapel : '-' }}
                            </td>

                            <!-- Status (Badge Terisi / Belum Mengisi Sesuai Gambar) -->
                            <td class="py-2.5 px-1 sm:px-2 text-center align-top">
                                @if ($j->status_jurnal === 'Selesai')
                                    <span class="inline-block px-1.5 sm:px-2.5 py-1 bg-[#D1FAE5] dark:bg-[#132924] text-[#065F46] dark:text-[#6EE7B7] border border-[#34D399] dark:border-[#059669] text-[9.5px] sm:text-[11px] font-black rounded-lg sm:rounded-xl leading-none">
                                        Terisi
                                    </span>
                                @elseif ($j->status_jurnal === 'Belum Mengisi Jurnal')
                                    <span class="inline-block px-1 sm:px-2 py-1 bg-[#FEE2E2] dark:bg-[#3D141A] text-[#991B1B] dark:text-[#F87171] border border-[#F87171] dark:border-[#B91C1C] text-[9px] sm:text-[10.5px] font-black rounded-lg sm:rounded-xl leading-none">
                                        Belum Mengisi
                                    </span>
                                @elseif (str_contains($j->status_jurnal, 'Event') || str_contains($j->status_jurnal, 'Pulang Pagi'))
                                    <span class="inline-block px-1 sm:px-2 py-1 bg-violet-100 dark:bg-violet-950/60 text-violet-800 dark:text-violet-300 border border-violet-300 dark:border-violet-800 text-[9px] sm:text-[10.5px] font-bold rounded-lg sm:rounded-xl leading-none">
                                        {{ $j->status_jurnal }}
                                    </span>
                                @elseif (str_contains($j->status_jurnal, 'Sah'))
                                    <span class="inline-block px-1 sm:px-2 py-1 bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border border-blue-300 dark:border-blue-800 text-[9px] sm:text-[10.5px] font-bold rounded-lg sm:rounded-xl leading-none">
                                        {{ $j->status_jurnal }}
                                    </span>
                                @else
                                    <span class="inline-block px-1 sm:px-2 py-1 bg-slate-100 dark:bg-[#252525] text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700 text-[9px] sm:text-[10.5px] font-bold rounded-lg sm:rounded-xl leading-none">
                                        {{ $j->status_jurnal }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400 italic text-xs">
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
<div id="modalAlpa" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-xl max-w-2xl w-full shadow-xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-4 py-2.5 border-b border-slate-200 bg-rose-50/70 flex items-center justify-between">
            <div class="flex items-center space-x-1.5">
                <i data-lucide="user-x" class="w-4 h-4 text-rose-600"></i>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Daftar Guru Alpa / Belum Isi Jurnal Hari Ini</h3>
            </div>
            <button onclick="closeModalAlpa()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="p-3.5 overflow-y-auto space-y-2.5">
            <p class="text-[11px] text-slate-500">Berikut daftar sesi mengajar hari ini yang jam mengajarnya telah berakhir namun belum ada catatan jurnal mengajar:</p>
            <div class="overflow-x-auto border border-slate-200 rounded-lg">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-white text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="py-2 px-3 text-center w-10">No</th>
                            <th class="py-2 px-3">Nama Guru</th>
                            <th class="py-2 px-3">Kelas & Mapel</th>
                            <th class="py-2 px-3 text-center">Jam Ke-</th>
                            <th class="py-2 px-3">Ruangan</th>
                            <th class="py-2 px-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($listGuruAlpaHariIni as $idx => $g)
                            <tr class="hover:bg-rose-50/20 transition-colors">
                                <td class="py-2 px-3 text-center font-medium text-slate-400 tabular-nums">{{ $idx + 1 }}</td>
                                <td class="py-2 px-3 font-semibold text-slate-900">{{ $g->guru ? $g->guru->nama_guru : '-' }}</td>
                                <td class="py-2 px-3">
                                    <div class="font-semibold text-slate-800">{{ $g->kelas ? $g->kelas->nama_kelas : '-' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $g->mapel ? $g->mapel->nama_mapel : '-' }}</div>
                                </td>
                                <td class="py-2 px-3 text-center font-semibold text-rose-700">Jam {{ $g->jam_mulai }}{{ $g->jam_mulai != $g->jam_selesai ? ' - ' . $g->jam_selesai : '' }}</td>
                                <td class="py-2 px-3 text-slate-600">{{ $g->ruangan ? $g->ruangan->nama_ruangan : '-' }}</td>
                                <td class="py-2 px-3 text-center">
                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 font-bold rounded text-[10px]">ALPA</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-slate-400 italic text-xs">
                                    Tidak ada guru yang terdeteksi Alpa hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="px-5 py-3 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="closeModalAlpa()" class="h-10 px-6 min-w-[100px] bg-[#1E2538] hover:bg-[#121724] text-white text-xs sm:text-sm font-bold rounded-xl transition-all cursor-pointer shadow-xs hover:shadow-md flex items-center justify-center">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL 2: LIST GURU BELUM ISI KEMARIN -->
<div id="modalKemarin" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-xl max-w-2xl w-full shadow-xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-4 py-2.5 border-b border-slate-200 bg-amber-50/70 flex items-center justify-between">
            <div class="flex items-center space-x-1.5">
                <i data-lucide="book-x" class="w-4 h-4 text-amber-600"></i>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Daftar Sesi Mengajar Belum Diisi (Kemarin)</h3>
            </div>
            <button onclick="closeModalKemarin()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="p-3.5 overflow-y-auto space-y-2.5">
            <p class="text-[11px] text-slate-500">Berikut daftar sesi mengajar kemarin yang belum diisi oleh guru pengampu:</p>
            <div class="overflow-x-auto border border-slate-200 rounded-lg">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-white text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="py-2 px-3 text-center w-10">No</th>
                            <th class="py-2 px-3">Nama Guru</th>
                            <th class="py-2 px-3">Kelas & Mapel</th>
                            <th class="py-2 px-3 text-center">Jam Ke-</th>
                            <th class="py-2 px-3">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($listGuruBelumIsiKemarin as $idx => $g)
                            <tr class="hover:bg-amber-50/20 transition-colors">
                                <td class="py-2 px-3 text-center font-medium text-slate-400 tabular-nums">{{ $idx + 1 }}</td>
                                <td class="py-2 px-3 font-semibold text-slate-900">{{ $g->guru ? $g->guru->nama_guru : '-' }}</td>
                                <td class="py-2 px-3">
                                    <div class="font-semibold text-slate-800">{{ $g->kelas ? $g->kelas->nama_kelas : '-' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $g->mapel ? $g->mapel->nama_mapel : '-' }}</div>
                                </td>
                                <td class="py-2 px-3 text-center font-semibold text-amber-700">Jam {{ $g->jam_mulai }}{{ $g->jam_mulai != $g->jam_selesai ? ' - ' . $g->jam_selesai : '' }}</td>
                                <td class="py-2 px-3 text-slate-600">{{ $g->ruangan ? $g->ruangan->nama_ruangan : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-slate-400 italic text-xs">
                                    Semua jurnal mengajar kemarin telah diisi lengkap.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="px-5 py-3 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="closeModalKemarin()" class="h-10 px-6 min-w-[100px] bg-[#1E2538] hover:bg-[#121724] text-white text-xs sm:text-sm font-bold rounded-xl transition-all cursor-pointer shadow-xs hover:shadow-md flex items-center justify-center">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL 3: LIST SISWA SAKIT HARI INI -->
<div id="modalSiswaSakit" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-xl max-w-2xl w-full shadow-xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-4 py-2.5 border-b border-slate-200 bg-amber-50/70 flex items-center justify-between">
            <div class="flex items-center space-x-1.5">
                <i data-lucide="stethoscope" class="w-4 h-4 text-amber-600"></i>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Daftar Siswa Sakit Hari Ini</h3>
                <span class="text-[11px] font-bold text-amber-700 bg-amber-100 border border-amber-200 px-2 py-0.5 rounded-full">{{ $siswaSakitHariIni }} siswa</span>
            </div>
            <button onclick="closeModalSiswaSakit()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="p-3.5 overflow-y-auto space-y-2.5">
            <p class="text-[11px] text-slate-500">Berikut daftar siswa yang tercatat sakit pada jurnal mengajar hari ini:</p>
            <div class="overflow-x-auto border border-slate-200 rounded-lg">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-white text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="py-2 px-3 text-center w-10">No</th>
                            <th class="py-2 px-3">NIS</th>
                            <th class="py-2 px-3">Nama Siswa</th>
                            <th class="py-2 px-3">Kelas</th>
                            <th class="py-2 px-3">Pada Mata Pelajaran</th>
                            <th class="py-2 px-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($listSiswaSakitHariIni as $idx => $d)
                            <tr class="hover:bg-amber-50/20 transition-colors">
                                <td class="py-2 px-3 text-center font-medium text-slate-400 tabular-nums">{{ $idx + 1 }}</td>
                                <td class="py-2 px-3 font-mono text-slate-500">{{ $d->id_siswa }}</td>
                                <td class="py-2 px-3 font-semibold text-slate-900">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</td>
                                <td class="py-2 px-3">
                                    <span class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-[10px] font-semibold text-slate-700">{{ $d->siswa && $d->siswa->kelas ? $d->siswa->kelas->nama_kelas : '-' }}</span>
                                </td>
                                <td class="py-2 px-3 text-slate-600">
                                    {{ $d->mapel_text ?? ($d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->mapel ? $d->jurnal->jadwal->mapel->nama_mapel : '-') }}
                                    @if(empty($d->mapel_text) && $d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->kelas)
                                        <span class="text-[10px] text-slate-400">({{ $d->jurnal->jadwal->kelas->nama_kelas }})</span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 font-bold rounded text-[10px]">SAKIT</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-slate-400 italic text-xs">
                                    Tidak ada siswa yang sakit hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="px-5 py-3 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="closeModalSiswaSakit()" class="h-10 px-6 min-w-[100px] bg-[#1E2538] hover:bg-[#121724] text-white text-xs sm:text-sm font-bold rounded-xl transition-all cursor-pointer shadow-xs hover:shadow-md flex items-center justify-center">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL 4: LIST SISWA IZIN / DISPEN HARI INI -->
<div id="modalSiswaIzin" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-xl max-w-2xl w-full shadow-xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-4 py-2.5 border-b border-slate-200 bg-blue-50/70 flex items-center justify-between">
            <div class="flex items-center space-x-1.5">
                <i data-lucide="file-badge" class="w-4 h-4 text-blue-600"></i>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Daftar Siswa Izin / Dispen Hari Ini</h3>
                <span class="text-[11px] font-bold text-blue-700 bg-blue-100 border border-blue-200 px-2 py-0.5 rounded-full">{{ $siswaIzinTotal }} siswa</span>
            </div>
            <button onclick="closeModalSiswaIzin()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="p-3.5 overflow-y-auto space-y-3.5">
            {{-- Bagian 1: Izin dari Jurnal --}}
            @if($listSiswaIzinHariIni->count() > 0)
            <div>
                <p class="text-[11px] text-slate-500 mb-1.5 font-semibold">Izin (tercatat di jurnal mengajar):</p>
                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-white text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="py-2 px-3 text-center w-10">No</th>
                                <th class="py-2 px-3">NIS</th>
                                <th class="py-2 px-3">Nama Siswa</th>
                                <th class="py-2 px-3">Kelas</th>
                                <th class="py-2 px-3">Pada Mata Pelajaran</th>
                                <th class="py-2 px-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($listSiswaIzinHariIni as $idx => $d)
                                <tr class="hover:bg-blue-50/20 transition-colors">
                                    <td class="py-2 px-3 text-center font-medium text-slate-400 tabular-nums">{{ $idx + 1 }}</td>
                                    <td class="py-2 px-3 font-mono text-slate-500">{{ $d->id_siswa }}</td>
                                    <td class="py-2 px-3 font-semibold text-slate-900">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</td>
                                    <td class="py-2 px-3">
                                        <span class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-[10px] font-semibold text-slate-700">{{ $d->siswa && $d->siswa->kelas ? $d->siswa->kelas->nama_kelas : '-' }}</span>
                                    </td>
                                    <td class="py-2 px-3 text-slate-600">
                                        {{ $d->mapel_text ?? ($d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->mapel ? $d->jurnal->jadwal->mapel->nama_mapel : '-') }}
                                        @if(empty($d->mapel_text) && $d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->kelas)
                                            <span class="text-[10px] text-slate-400">({{ $d->jurnal->jadwal->kelas->nama_kelas }})</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 font-bold rounded text-[10px]">IZIN</span>
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
                <p class="text-[11px] text-slate-500 mb-1.5 font-semibold">Dispensasi (surat dispen sah):</p>
                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-white text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="py-2 px-3 text-center w-10">No</th>
                                <th class="py-2 px-3">NIS</th>
                                <th class="py-2 px-3">Nama Siswa</th>
                                <th class="py-2 px-3">Kelas</th>
                                <th class="py-2 px-3">Keperluan</th>
                                <th class="py-2 px-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($listDispenActive as $idx => $dp)
                                <tr class="hover:bg-blue-50/20 transition-colors">
                                    <td class="py-2 px-3 text-center font-medium text-slate-400 tabular-nums">{{ $idx + 1 }}</td>
                                    <td class="py-2 px-3 font-mono text-slate-500">{{ $dp->nis }}</td>
                                    <td class="py-2 px-3 font-semibold text-slate-900">{{ $dp->siswa ? $dp->siswa->nama_siswa : '-' }}</td>
                                    <td class="py-2 px-3">
                                        <span class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-[10px] font-semibold text-slate-700">{{ $dp->siswa && $dp->siswa->kelas ? $dp->siswa->kelas->nama_kelas : '-' }}</span>
                                    </td>
                                    <td class="py-2 px-3 text-slate-600">{{ $dp->keperluan ?? '-' }}</td>
                                    <td class="py-2 px-3 text-center">
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200 font-bold rounded text-[10px]">DISPEN</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($listSiswaIzinHariIni->count() === 0 && $listDispenActive->count() === 0)
                <div class="py-5 text-center text-slate-400 italic text-xs">
                    <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                    Tidak ada siswa yang izin atau dispen hari ini.
                </div>
            @endif
        </div>
        <div class="px-5 py-3 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="closeModalSiswaIzin()" class="h-10 px-6 min-w-[100px] bg-[#1E2538] hover:bg-[#121724] text-white text-xs sm:text-sm font-bold rounded-xl transition-all cursor-pointer shadow-xs hover:shadow-md flex items-center justify-center">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL 5: LIST SISWA ALPA HARI INI -->
<div id="modalSiswaAlpa" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-xl max-w-2xl w-full shadow-xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="px-4 py-2.5 border-b border-slate-200 bg-rose-50/70 flex items-center justify-between">
            <div class="flex items-center space-x-1.5">
                <i data-lucide="user-x" class="w-4 h-4 text-rose-600"></i>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Daftar Siswa Alpa (Tanpa Keterangan) Hari Ini</h3>
                <span class="text-[11px] font-bold text-rose-700 bg-rose-100 border border-rose-200 px-2 py-0.5 rounded-full">{{ $siswaAlpaHariIni }} siswa</span>
            </div>
            <button onclick="closeModalSiswaAlpa()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="p-3.5 overflow-y-auto space-y-2.5">
            <p class="text-[11px] text-slate-500">Berikut daftar siswa yang tercatat Alpa (tanpa keterangan) pada jurnal mengajar hari ini:</p>
            <div class="overflow-x-auto border border-slate-200 rounded-lg">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-white text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="py-2 px-3 text-center w-10">No</th>
                            <th class="py-2 px-3">NIS</th>
                            <th class="py-2 px-3">Nama Siswa</th>
                            <th class="py-2 px-3">Kelas</th>
                            <th class="py-2 px-3">Pada Mata Pelajaran</th>
                            <th class="py-2 px-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($listSiswaAlpaHariIni as $idx => $d)
                            <tr class="hover:bg-rose-50/20 transition-colors">
                                <td class="py-2 px-3 text-center font-medium text-slate-400 tabular-nums">{{ $idx + 1 }}</td>
                                <td class="py-2 px-3 font-mono text-slate-500">{{ $d->id_siswa }}</td>
                                <td class="py-2 px-3 font-semibold text-slate-900">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</td>
                                <td class="py-2 px-3">
                                    <span class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-[10px] font-semibold text-slate-700">{{ $d->siswa && $d->siswa->kelas ? $d->siswa->kelas->nama_kelas : '-' }}</span>
                                </td>
                                <td class="py-2 px-3 text-slate-600">
                                    {{ $d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->mapel ? $d->jurnal->jadwal->mapel->nama_mapel : '-' }}
                                    @if($d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->kelas)
                                        <span class="text-[10px] text-slate-400">({{ $d->jurnal->jadwal->kelas->nama_kelas }})</span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 font-bold rounded text-[10px]">ALPA</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-slate-400 italic text-xs">
                                    Tidak ada siswa yang Alpa hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="px-5 py-3 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="closeModalSiswaAlpa()" class="h-10 px-6 min-w-[100px] bg-[#1E2538] hover:bg-[#121724] text-white text-xs sm:text-sm font-bold rounded-xl transition-all cursor-pointer shadow-xs hover:shadow-md flex items-center justify-center">
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