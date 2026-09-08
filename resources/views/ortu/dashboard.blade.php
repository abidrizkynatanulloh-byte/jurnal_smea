@extends('layouts.app')

@section('title', 'Portal Orang Tua - Monitoring Kehadiran & Pengajuan Izin')

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
        <div class="flex items-center space-x-2">
            @if($siswa)
                <button type="button" onclick="openModalAjukanIzin()" class="h-8.5 px-3 bg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition-all flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                    <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                    <span>Ajukan Izin / Sakit Siswa</span>
                </button>
            @endif
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200 shadow-2xs space-x-1.5">
                <i data-lucide="user" class="w-3.5 h-3.5 text-slate-500"></i>
                <span>Akun Wali Murid</span>
            </span>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-medium flex items-center space-x-2 shadow-2xs">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs font-medium flex items-center space-x-2 shadow-2xs">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

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

        <!-- STATISTIK BULANAN (KLIK UNTUK POP-UP DETAIL) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- SAKIT -->
            <div onclick="openDetailModalAbsen('Sakit')" class="bg-white border border-slate-200 hover:border-blue-400 rounded-xl p-3.5 shadow-xs hover:shadow-md flex items-center justify-between cursor-pointer transition-all group">
                <div class="flex items-center space-x-3">
                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i data-lucide="thermometer" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sakit Bulan Ini</p>
                        <h3 class="text-lg font-bold text-slate-900 mt-0.5">{{ $rekapBulanIni['sakit'] }} Sesi</h3>
                    </div>
                </div>
                <div class="text-slate-300 group-hover:text-blue-500 transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- IZIN -->
            <div onclick="openDetailModalAbsen('Izin')" class="bg-white border border-slate-200 hover:border-amber-400 rounded-xl p-3.5 shadow-xs hover:shadow-md flex items-center justify-between cursor-pointer transition-all group">
                <div class="flex items-center space-x-3">
                    <div class="p-2.5 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-600 group-hover:text-white transition-colors">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Izin Bulan Ini</p>
                        <h3 class="text-lg font-bold text-slate-900 mt-0.5">{{ $rekapBulanIni['izin'] }} Sesi</h3>
                    </div>
                </div>
                <div class="text-slate-300 group-hover:text-amber-500 transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- ALPA -->
            <div onclick="openDetailModalAbsen('Alpa')" class="bg-white border border-slate-200 hover:border-rose-400 rounded-xl p-3.5 shadow-xs hover:shadow-md flex items-center justify-between cursor-pointer transition-all group">
                <div class="flex items-center space-x-3">
                    <div class="p-2.5 bg-rose-50 text-rose-600 rounded-lg group-hover:bg-rose-600 group-hover:text-white transition-colors">
                        <i data-lucide="user-x" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Alpa / Tanpa Ket.</p>
                        <h3 class="text-lg font-bold text-rose-600 mt-0.5">{{ $rekapBulanIni['alpa'] }} Sesi</h3>
                    </div>
                </div>
                <div class="text-slate-300 group-hover:text-rose-500 transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
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
                            <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-[11px] font-bold font-mono rounded-md">
                                    {{ $p['jam_ke'] }}<span class="text-slate-500 dark:text-slate-400 font-medium">{{ $p['waktu_jam'] }}</span>
                                </span>
                                <h4 class="font-bold text-xs text-slate-900 dark:text-slate-100">{{ $p['mapel'] }}</h4>
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

        <!-- RIWAYAT PENGAJUAN IZIN SISWA DARI ORANG TUA -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="file-plus-2" class="w-4 h-4 text-slate-600"></i>
                    <h3 class="font-bold text-slate-900 text-xs">Riwayat Permohonan Izin / Sakit (Pengajuan Orang Tua)</h3>
                </div>
                <button type="button" onclick="openModalAjukanIzin()" class="text-xs font-semibold text-rose-600 hover:text-rose-700 inline-flex items-center space-x-1 cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Buat Pengajuan Baru</span>
                </button>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @forelse($riwayatIzin as $iz)
                    <div class="p-3.5 hover:bg-slate-50/80 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded text-[10.5px] font-bold 
                                    @if($iz->kategori === 'Sakit') bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif($iz->kategori === 'Izin') bg-amber-50 text-amber-700 border border-amber-200
                                    @else bg-purple-50 text-purple-700 border border-purple-200 @endif">
                                    {{ $iz->kategori }}
                                </span>
                                <span class="font-bold text-slate-900 text-xs font-mono">
                                    {{ \Carbon\Carbon::parse($iz->tanggal_mulai)->translatedFormat('d M Y') }}
                                    @if($iz->tanggal_mulai !== $iz->tanggal_selesai)
                                        s/d {{ \Carbon\Carbon::parse($iz->tanggal_selesai)->translatedFormat('d M Y') }}
                                    @endif
                                </span>
                            </div>
                            <p class="text-xs text-slate-700 font-medium leading-relaxed">{{ $iz->alasan }}</p>
                            @if($iz->bukti_foto)
                                <a href="{{ asset($iz->bukti_foto) }}" target="_blank" class="inline-flex items-center space-x-1 text-[11px] text-blue-600 hover:underline font-semibold">
                                    <i data-lucide="paperclip" class="w-3 h-3"></i>
                                    <span>Lihat Lampiran Surat Dokter / Bukti Foto</span>
                                </a>
                            @endif
                        </div>
                        <div class="shrink-0 text-left sm:text-right">
                            @if($iz->status === 'Disetujui')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 space-x-1">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    <span>Disetujui (ACC)</span>
                                </span>
                            @elseif($iz->status === 'Ditolak')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 space-x-1" title="{{ $iz->catatan_penolakan }}">
                                    <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i>
                                    <span>Ditolak</span>
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 space-x-1">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i>
                                    <span>Menunggu ACC Piket/Wali Kelas</span>
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 italic text-xs">
                        Belum ada permohonan izin/sakit yang diajukan.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIWAYAT KETIDAKHADIRAN SEKOLAH -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-1.5">
                    <i data-lucide="history" class="w-4 h-4 text-slate-500"></i>
                    <h3 class="font-bold text-slate-900 text-xs">Riwayat Ketidakhadiran di Kelas (Rekapitulasi Jurnal)</h3>
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
                        Anak Anda memiliki catatan kehadiran di kelas yang sempurna.
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- MODAL POP-UP DETAIL STATISTIK KETIDAKHADIRAN -->
<div id="modalDetailStatistik" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-4 max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 shrink-0">
            <div class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0">
                    <i data-lucide="list-checks" class="w-4.5 h-4.5 text-slate-700 dark:text-slate-200"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm tracking-tight" id="modalDetailKategoriTitle">Detail Presensi</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Rincian tanggal dan sesi ketidakhadiran</p>
                </div>
            </div>
            <button type="button" onclick="closeDetailModalAbsen()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="overflow-y-auto flex-1 space-y-3 pr-1" id="modalDetailAbsenList">
            <!-- Dynamic Content -->
        </div>

        <div class="flex justify-end pt-3 border-t border-slate-100 dark:border-slate-800 shrink-0">
            <button type="button" onclick="closeDetailModalAbsen()" class="h-10 px-6 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL FORM PENGAJUAN IZIN SISWA (WAJIB FOTO/SURAT DOKTER) -->
<div id="modalAjukanIzin" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 flex items-center justify-center shrink-0">
                    <i data-lucide="file-plus-2" class="w-4.5 h-4.5 text-rose-600 dark:text-rose-400"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm tracking-tight">Form Permohonan Izin Siswa</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Kirim surat/alasan tidak masuk ke Guru Piket & Wali Kelas</p>
                </div>
            </div>
            <button type="button" onclick="closeModalAjukanIzin()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('ortu.izin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Permohonan Izin *</label>
                <select name="kategori" required class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-rose-500 cursor-pointer">
                    <option value="Sakit">Sakit (Melampirkan Surat Dokter / Bukti Foto)</option>
                    <option value="Izin">Izin (Acara Keluarga / Kepentingan Sah)</option>
                    <option value="Dispen">Dispensasi (Lomba / Kegiatan Sekolah)</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Mulai *</label>
                    <input type="date" name="tanggal_mulai" value="{{ date('Y-m-d') }}" required
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Selesai *</label>
                    <input type="date" name="tanggal_selesai" value="{{ date('Y-m-d') }}" required
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-rose-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Alasan Ketidakhadiran *</label>
                <textarea name="alasan" rows="3" required placeholder="Jelaskan alasan detail ketidakhadiran siswa..."
                    class="w-full px-3 py-2 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-rose-500 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-rose-700 dark:text-rose-400 mb-1">Upload Bukti Foto / Surat Dokter * (Wajib)</label>
                <input type="file" name="bukti_foto" accept="image/*" required
                    class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 dark:file:bg-rose-950/60 file:text-rose-700 dark:file:text-rose-300 hover:file:bg-rose-100 dark:hover:file:bg-rose-900/60 cursor-pointer border border-rose-200 dark:border-rose-900/60 rounded-xl p-1 bg-white dark:bg-[#1A2230]">
                <p class="text-[10.5px] text-rose-500 font-medium mt-1">* Wajib melampirkan foto surat dokter atau bukti keterangan izin resmi (Maksimal 5 MB)</p>
            </div>

            <div class="flex items-center justify-end space-x-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeModalAjukanIzin()" class="h-10 px-5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-10 px-7 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-colors cursor-pointer shadow-sm whitespace-nowrap shrink-0">
                    Kirim Permohonan Izin
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const riwayatAbsenData = @json($riwayatAbsen ?? []);
    const riwayatIzinData = @json($riwayatIzin ?? []);

    function openModalAjukanIzin() {
        document.getElementById('modalAjukanIzin').classList.remove('hidden');
    }
    function closeModalAjukanIzin() {
        document.getElementById('modalAjukanIzin').classList.add('hidden');
    }

    function openDetailModalAbsen(kategori) {
        document.getElementById('modalDetailKategoriTitle').innerText = `Detail Presensi - ${kategori}`;
        const listContainer = document.getElementById('modalDetailAbsenList');

        const filteredAbsen = riwayatAbsenData.filter(item => item.keterangan === kategori);
        const filteredIzin = riwayatIzinData.filter(item => item.kategori === kategori);

        let html = '';

        if (filteredIzin.length > 0) {
            html += `<div class="mb-4"><span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Permohonan Izin / Surat Dokter (Orang Tua):</span>`;
            filteredIzin.forEach(iz => {
                let statusBadge = '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Menunggu ACC</span>';
                if (iz.status === 'Disetujui') {
                    statusBadge = '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui (ACC)</span>';
                } else if (iz.status === 'Ditolak') {
                    statusBadge = '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>';
                }

                let fotoLink = '';
                if (iz.bukti_foto) {
                    fotoLink = `<a href="/${iz.bukti_foto}" target="_blank" class="inline-flex items-center space-x-1 text-blue-600 hover:underline text-[11px] font-semibold mt-1">
                        <i data-lucide="image" class="w-3 h-3"></i>
                        <span>Lihat Bukti Foto / Surat Dokter</span>
                    </a>`;
                }

                html += `
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl space-y-1.5 text-xs mb-2">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-slate-900 dark:text-slate-100 font-mono">${iz.tanggal_mulai} s/d ${iz.tanggal_selesai}</span>
                            ${statusBadge}
                        </div>
                        <p class="text-slate-700 dark:text-slate-300 font-medium">${iz.alasan}</p>
                        ${fotoLink}
                    </div>
                `;
            });
            html += `</div>`;
        }

        if (filteredAbsen.length > 0) {
            html += `<div><span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Catatan Ketidakhadiran di Kelas:</span><div class="divide-y divide-slate-100 border border-slate-200 rounded-xl overflow-hidden">`;
            filteredAbsen.forEach(ab => {
                html += `
                    <div class="p-3 bg-white dark:bg-[#1A2230] hover:bg-slate-50 transition-colors flex justify-between items-center text-xs">
                        <div>
                            <p class="font-bold text-slate-900 dark:text-slate-100">${ab.tanggal}</p>
                            <p class="text-slate-500 text-[11px]">${ab.detail_jam}</p>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">${ab.keterangan}</span>
                    </div>
                `;
            });
            html += `</div></div>`;
        }

        if (filteredAbsen.length === 0 && filteredIzin.length === 0) {
            html = `
                <div class="py-8 text-center text-slate-400 italic text-xs">
                    <i data-lucide="check-circle" class="w-7 h-7 mx-auto mb-1.5 text-emerald-500"></i>
                    Tidak ada riwayat catatan ${kategori} untuk siswa ini.
                </div>
            `;
        }

        listContainer.innerHTML = html;
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        document.getElementById('modalDetailStatistik').classList.remove('hidden');
    }

    function closeDetailModalAbsen() {
        document.getElementById('modalDetailStatistik').classList.add('hidden');
    }
</script>
@endsection
