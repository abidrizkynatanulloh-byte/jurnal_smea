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
            <a href="{{ route('admin.rekap.index') }}" class="px-3.5 py-2 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-xs">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                <span>Lihat Rekapitulasi Jurnal</span>
            </a>
        </div>
    </div>

    <!-- 1. Statistik Grid Utama (Klik untuk membuka halaman detail) -->
    <!-- 1. Statistik Grid Utama (Klik untuk membuka halaman detail - Sesuai Gambar 2 & 3) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        <!-- Card 1: Siswa (Pastel Hijau Mint) -->
        <a href="{{ route('admin.siswa.index') }}" class="bg-[#E8F8F5] dark:bg-[#132924] border border-[#B2E6DC] dark:border-[#1D423A] rounded-xl p-3.5 shadow-xs hover:shadow-sm hover:border-[#83D6C6] dark:hover:border-[#2D665A] transition-all flex items-start space-x-3 group cursor-pointer">
            <div class="w-8 h-8 rounded-lg bg-[#C6EFE6] dark:bg-[#1B3B34] text-[#147A64] dark:text-[#5EEAD4] border border-[#9EE2D4] dark:border-[#265349] flex items-center justify-center flex-shrink-0">
                <i data-lucide="graduation-cap" class="w-4 h-4"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold text-[#16705D] dark:text-[#6EE7B7] uppercase tracking-wider">Total Siswa</p>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-[#288470] dark:text-[#A7F3D0] group-hover:translate-x-0.5 transition-all"></i>
                </div>
                <h3 class="text-xl font-extrabold text-[#083E33] dark:text-[#F0FDF4] mt-0.5 tracking-tight tabular-nums">{{ number_format($totalSiswa, 0, ',', '.') }}</h3>
                <p class="text-[11px] font-medium text-[#288470] dark:text-[#A7F3D0] mt-0.5 truncate">Data siswa & rombel</p>
            </div>
        </a>

        <!-- Card 2: Guru & Staf (Pastel Biru Muda) -->
        <a href="{{ route('admin.guru.index') }}" class="bg-[#EBF4FC] dark:bg-[#132337] border border-[#BDE0FA] dark:border-[#1B3654] rounded-xl p-3.5 shadow-xs hover:shadow-sm hover:border-[#8FC6F5] dark:hover:border-[#2B5480] transition-all flex items-start space-x-3 group cursor-pointer">
            <div class="w-8 h-8 rounded-lg bg-[#CFE5FA] dark:bg-[#19334F] text-[#1D63A8] dark:text-[#60A5FA] border border-[#A8D3F7] dark:border-[#23456B] flex items-center justify-center flex-shrink-0">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold text-[#1B5D9D] dark:text-[#93C5FD] uppercase tracking-wider">Guru & Pegawai</p>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-[#2B6EA8] dark:text-[#BFDBFE] group-hover:translate-x-0.5 transition-all"></i>
                </div>
                <h3 class="text-xl font-extrabold text-[#0B3054] dark:text-[#EFF6FF] mt-0.5 tracking-tight tabular-nums">{{ $totalPegawai }}</h3>
                <p class="text-[11px] font-medium text-[#2B6EA8] dark:text-[#BFDBFE] mt-0.5 truncate">{{ $totalGuru }} Guru • {{ $totalStaf }} Staf TU</p>
            </div>
        </a>

        <!-- Card 3: Jadwal (Pastel Amber / Kuning Hangat) -->
        <a href="{{ route('admin.jadwal.index') }}" class="bg-[#FEF6EC] dark:bg-[#2C1F0E] border border-[#FCE1BD] dark:border-[#473216] rounded-xl p-3.5 shadow-xs hover:shadow-sm hover:border-[#F9CA88] dark:hover:border-[#694A20] transition-all flex items-start space-x-3 group cursor-pointer">
            <div class="w-8 h-8 rounded-lg bg-[#FDE9CD] dark:bg-[#3D2C13] text-[#B87114] dark:text-[#FBBF24] border border-[#FBD79F] dark:border-[#573F1C] flex items-center justify-center flex-shrink-0">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold text-[#9B5B08] dark:text-[#FCD34D] uppercase tracking-wider">Total Jadwal</p>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-[#9B621B] dark:text-[#FDE68A] group-hover:translate-x-0.5 transition-all"></i>
                </div>
                <h3 class="text-xl font-extrabold text-[#583303] dark:text-[#FFFBEB] mt-0.5 tracking-tight tabular-nums">{{ $totalJadwal }}</h3>
                <p class="text-[11px] font-medium text-[#9B621B] dark:text-[#FDE68A] mt-0.5 truncate">Jadwal KBM mengajar</p>
            </div>
        </a>

        <!-- Card 4: Kepatuhan (Pastel Rose / Merah Lembut) -->
        <a href="{{ route('admin.rekap.kepatuhan') }}" class="bg-[#FDEEF0] dark:bg-[#2F1318] border border-[#F9C6CE] dark:border-[#4B1E26] rounded-xl p-3.5 shadow-xs hover:shadow-sm hover:border-[#F49BA9] dark:hover:border-[#6B2B36] transition-all flex items-start space-x-3 group cursor-pointer">
            <div class="w-8 h-8 rounded-lg bg-[#FBD6DC] dark:bg-[#431A21] text-[#C42B46] dark:text-[#F87171] border border-[#F7B6C0] dark:border-[#5E242F] flex items-center justify-center flex-shrink-0">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold text-[#A81E38] dark:text-[#FDA4AF] uppercase tracking-wider">Kepatuhan Jurnal</p>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-[#AA2A43] dark:text-[#FECDD3] group-hover:translate-x-0.5 transition-all"></i>
                </div>
                <h3 class="text-xl font-extrabold text-[#5E0B1A] dark:text-[#FFF1F2] mt-0.5 tracking-tight tabular-nums">{{ $persentaseKepatuhan }}%</h3>
                <p class="text-[11px] font-medium text-[#AA2A43] dark:text-[#FECDD3] mt-0.5 truncate">Kepatuhan pengisian</p>
            </div>
        </a>
    </div>

    <!-- 2. REKAP ABSENSI SISWA HARI INI (Sesuai Gambar 2 & 3) -->
    <div class="bg-white dark:bg-[#141412] border border-slate-200 dark:border-[#252525] rounded-xl p-4 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-tight flex items-center space-x-2">
                <i data-lucide="user-check" class="w-3.5 h-3.5 text-slate-700 dark:text-slate-300"></i>
                <span>Data Absensi Siswa Hari Ini</span>
            </h3>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ $tanggalHariIniTeks }}</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <!-- Hadir (Pastel Mint) -->
            <div class="p-3 bg-[#E8F8F5] dark:bg-[#132924] border border-[#B2E6DC] dark:border-[#1D423A] rounded-lg flex items-center space-x-2.5">
                <div class="w-7 h-7 rounded-md bg-[#C6EFE6] dark:bg-[#1B3B34] text-[#147A64] dark:text-[#5EEAD4] flex items-center justify-center flex-shrink-0 font-bold text-xs">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <p class="text-[10.5px] font-bold uppercase tracking-wider text-[#16705D] dark:text-[#6EE7B7]">Hadir</p>
                    <h4 class="text-lg font-extrabold text-[#083E33] dark:text-[#F0FDF4] leading-tight tabular-nums">{{ $siswaHadirHariIni }}</h4>
                </div>
            </div>

            <!-- Sakit (Pastel Kuning) -->
            <div onclick="openModalSiswaSakit()" class="p-3 bg-[#FEF6EC] dark:bg-[#2C1F0E] border border-[#FCE1BD] dark:border-[#473216] hover:border-[#F9CA88] dark:hover:border-[#694A20] rounded-lg flex items-center space-x-2.5 transition-colors cursor-pointer group">
                <div class="w-7 h-7 rounded-md bg-[#FDE9CD] dark:bg-[#3D2C13] text-[#B87114] dark:text-[#FBBF24] flex items-center justify-center flex-shrink-0 font-bold text-xs">
                    <i data-lucide="thermometer" class="w-3.5 h-3.5"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10.5px] font-bold uppercase tracking-wider text-[#9B5B08] dark:text-[#FCD34D]">Sakit</p>
                    <h4 class="text-lg font-extrabold text-[#583303] dark:text-[#FFFBEB] leading-tight tabular-nums">{{ $siswaSakitHariIni }}</h4>
                </div>
            </div>

            <!-- Izin / Dispen (Pastel Ungu) -->
            <div onclick="openModalSiswaIzin()" class="p-3 bg-[#F3EEFC] dark:bg-[#221633] border border-[#DCCBF7] dark:border-[#382453] hover:border-[#C4A5F4] dark:hover:border-[#523577] rounded-lg flex items-center space-x-2.5 transition-colors cursor-pointer group">
                <div class="w-7 h-7 rounded-md bg-[#E6DBFA] dark:bg-[#311F49] text-[#743DC4] dark:text-[#C084FC] flex items-center justify-center flex-shrink-0 font-bold text-xs">
                    <i data-lucide="file-badge" class="w-3.5 h-3.5"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10.5px] font-bold uppercase tracking-wider text-[#672EB8] dark:text-[#D8B4FE]">Izin / Dispen</p>
                    <h4 class="text-lg font-extrabold text-[#3F1678] dark:text-[#FAF5FF] leading-tight tabular-nums">{{ $siswaIzinTotal }}</h4>
                </div>
            </div>

            <!-- Alpa (Pastel Rose) -->
            <div onclick="openModalSiswaAlpa()" class="p-3 bg-[#FDEEF0] dark:bg-[#2F1318] border border-[#F9C6CE] dark:border-[#4B1E26] hover:border-[#F49BA9] dark:hover:border-[#6B2B36] rounded-lg flex items-center space-x-2.5 transition-colors cursor-pointer group">
                <div class="w-7 h-7 rounded-md bg-[#FBD6DC] dark:bg-[#431A21] text-[#C42B46] dark:text-[#F87171] flex items-center justify-center flex-shrink-0 font-bold text-xs">
                    <i data-lucide="user-x" class="w-3.5 h-3.5"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10.5px] font-bold uppercase tracking-wider text-[#A81E38] dark:text-[#FDA4AF]">Alpa</p>
                    <h4 class="text-lg font-extrabold text-[#5E0B1A] dark:text-[#FFF1F2] leading-tight tabular-nums">{{ $siswaAlpaHariIni }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Perhatian Operasional (Klik untuk membuka daftar detail - Sesuai Gambar 2 & 3) -->
    <div class="bg-white dark:bg-[#141412] border border-slate-200 dark:border-[#252525] rounded-xl p-5 shadow-xs">
        <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider mb-3 flex items-center space-x-2">
            <i data-lucide="alert-triangle" class="w-4 h-4 text-slate-700 dark:text-slate-300"></i>
            <span>Perhatian Operasional (Klik Kartu untuk Melihat List Guru)</span>
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <!-- Warning 1: Jurnal Kemarin (Amber Sesuai Gambar) -->
            <div onclick="openModalKemarin()" class="p-3.5 bg-[#FEF3E2] dark:bg-[#271B0B] border border-[#FADBAA] dark:border-[#453014] rounded-xl flex items-start space-x-3 hover:border-[#F8C882] dark:hover:border-[#6B4B20] transition-all cursor-pointer group">
                <div class="p-1.5 bg-[#FDE7C4] dark:bg-[#3B2910] text-[#B45309] dark:text-[#F59E0B] rounded-lg flex-shrink-0">
                    <i data-lucide="book-x" class="w-4 h-4"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-[#92400E] dark:text-[#FBBF24]">Jurnal Mengajar Kemarin</h4>
                        <span class="text-[10.5px] text-[#B45309] dark:text-[#FCD34D] font-bold underline flex items-center space-x-0.5">
                            <span>Lihat List</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </span>
                    </div>
                    <p class="text-xs text-[#78350F] dark:text-[#FEF3C7] mt-0.5"><span class="font-bold text-[#451A03] dark:text-white">{{ $guruBelumIsiKemarin }} sesi mengajar</span> terdeteksi belum mengisi jurnal kemarin.</p>
                </div>
            </div>

            <!-- Warning 2: Guru Alpa Hari Ini (Rose Sesuai Gambar) -->
            <div onclick="openModalAlpa()" class="p-3.5 bg-[#FEECEB] dark:bg-[#2C1014] border border-[#F9C4C0] dark:border-[#4A1A22] rounded-xl flex items-start space-x-3 hover:border-[#F59E98] dark:hover:border-[#6D2732] transition-all cursor-pointer group">
                <div class="p-1.5 bg-[#FDCFCD] dark:bg-[#3F161C] text-[#C53030] dark:text-[#F87171] rounded-lg flex-shrink-0">
                    <i data-lucide="user-x" class="w-4 h-4"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-[#991B1B] dark:text-[#FCA5A5]">Guru Alpa Hari Ini</h4>
                        <span class="text-[10.5px] text-[#DC2626] dark:text-[#F87171] font-bold underline flex items-center space-x-0.5">
                            <span>Lihat List Alpa</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </span>
                    </div>
                    <p class="text-xs text-[#7F1D1D] dark:text-[#FEE2E2] mt-0.5"><span class="font-bold text-[#450A0A] dark:text-white">{{ $guruAlpaHariIni }} guru / {{ count($listGuruAlpaHariIni) }} sesi</span> terdeteksi Alpa (jam lewat).</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Tabel Jadwal Hari Ini (Urutan: ALPA lebih dulu - Sesuai Gambar 2 & 3) -->
    <div class="bg-white dark:bg-[#141412] border border-slate-200 dark:border-[#252525] rounded-xl shadow-xs overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-200 dark:border-[#252525] flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2.5 sm:space-y-0 bg-[#F4F7FA] dark:bg-[#1B1B18]">
            <div class="flex items-center space-x-2.5">
                <div class="w-2 h-2 bg-[#166876] dark:bg-[#84C4C9] rounded-full"></div>
                <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider">Jadwal Mengajar Hari Ini ({{ $namaHariIni }})</h3>
                <span class="text-[10.5px] font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 px-2 py-0.5 rounded">Prioritas Alpa</span>
            </div>
            
            <div class="flex items-center space-x-3">
                <span id="slideInfo" class="text-xs font-medium text-slate-500 dark:text-slate-400">Menampilkan 1-10 dari {{ count($jadwalHariIni) }} sesi</span>
                <div class="inline-flex rounded border border-slate-300 dark:border-slate-700 p-0.5 bg-white dark:bg-[#202020] shadow-2xs">
                    <button type="button" id="prevBtn" onclick="geserSlide(-1)" class="p-1 rounded hover:bg-slate-100 dark:hover:bg-white/10 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white disabled:opacity-40 transition-colors cursor-pointer" disabled>
                        <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                    </button>
                    <button type="button" id="nextBtn" onclick="geserSlide(1)" class="p-1 rounded hover:bg-slate-100 dark:hover:bg-white/10 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white disabled:opacity-40 transition-colors cursor-pointer">
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto max-h-[480px] overflow-y-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="sticky top-0 bg-[#DDF0F7] dark:bg-[#192634] border-b border-[#BCE1EE] dark:border-[#273B4E] z-10 shadow-xs">
                    <tr class="text-[#0F4E5A] dark:text-[#7DD3FC] font-extrabold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3.5 text-center w-12">No</th>
                        <th class="py-3 px-3.5 w-28">Jam Ke-</th>
                        <th class="py-3 px-3.5 w-24">Kelas</th>
                        <th class="py-3 px-3.5">Guru</th>
                        <th class="py-3 px-3.5">Mata Pelajaran</th>
                        <th class="py-3 px-3.5 w-28">Ruangan</th>
                        <th class="py-3 px-3.5 text-center w-36">Status Jurnal</th>
                    </tr>
                </thead>
                <tbody id="jadwalTbody" class="divide-y divide-slate-100 dark:divide-[#222220] text-slate-700 dark:text-slate-300">
                    @forelse ($jadwalHariIni as $index => $j)
                        <tr class="jadwal-row hover:bg-slate-50/80 transition-colors {{ $j->status_jurnal === 'Alpa' ? 'bg-rose-50/20' : '' }}" data-index="{{ $index }}" style="{{ $index >= 10 ? 'display: none;' : '' }}">
                            <td class="py-2.5 px-3.5 text-center font-medium text-slate-400 text-xs tabular-nums">{{ $index + 1 }}</td>
                            <td class="py-2.5 px-3.5 font-semibold text-slate-900">Jam {{ $j->jam_mulai }}–{{ $j->jam_selesai }}</td>
                            <td class="py-2.5 px-3.5"><span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[11px] font-bold text-slate-700">{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</span></td>
                            <td class="py-2.5 px-3.5 font-medium text-slate-900 leading-tight">{{ $j->guru ? $j->guru->nama_guru : '-' }}</td>
                            <td class="py-2.5 px-3.5 font-medium text-slate-700">{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</td>
                            <td class="py-2.5 px-3.5 text-slate-500"><span class="inline-flex items-center space-x-1"><i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i><span>{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</span></span></td>
                            <td class="py-2.5 px-3.5 text-center">
                                @if ($j->status_jurnal === 'Selesai')
                                    <span class="inline-block px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold rounded">
                                        Selesai
                                    </span>
                                @elseif ($j->status_jurnal === 'Alpa')
                                    <span class="inline-block px-2.5 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-bold rounded">
                                        Alpa (Belum Diisi)
                                    </span>
                                @elseif (str_contains($j->status_jurnal, 'Sah'))
                                    <span class="inline-block px-2.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-bold rounded">
                                        {{ $j->status_jurnal }}
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 text-[11px] font-semibold rounded">
                                        Terjadwal
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400 italic text-xs">
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
                                <td class="py-2 px-3 text-center font-semibold text-rose-700">Jam {{ $g->jam_mulai }} - {{ $j->jam_selesai ?? $g->jam_selesai }}</td>
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
        <div class="px-4 py-2 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="closeModalAlpa()" class="h-8 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white text-xs font-semibold rounded-lg cursor-pointer">
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
                                <td class="py-2 px-3 text-center font-semibold text-amber-700">Jam {{ $g->jam_mulai }} - {{ $g->jam_selesai }}</td>
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
        <div class="px-4 py-2 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="closeModalKemarin()" class="h-8 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white text-xs font-semibold rounded-lg cursor-pointer">
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
                                    {{ $d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->mapel ? $d->jurnal->jadwal->mapel->nama_mapel : '-' }}
                                    @if($d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->kelas)
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
        <div class="px-4 py-2 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="closeModalSiswaSakit()" class="h-8 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white text-xs font-semibold rounded-lg cursor-pointer">
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
                                        {{ $d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->mapel ? $d->jurnal->jadwal->mapel->nama_mapel : '-' }}
                                        @if($d->jurnal && $d->jurnal->jadwal && $d->jurnal->jadwal->kelas)
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
        <div class="px-4 py-2 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="closeModalSiswaIzin()" class="h-8 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white text-xs font-semibold rounded-lg cursor-pointer">
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
        <div class="px-4 py-2 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="closeModalSiswaAlpa()" class="h-8 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white text-xs font-semibold rounded-lg cursor-pointer">
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