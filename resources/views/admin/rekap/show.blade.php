@extends('layouts.app')

@section('title', 'Detail Jurnal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-4 max-w-6xl mx-auto pb-12">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-[#1C2433] p-4 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xs">
        <div>
            <a href="{{ route('admin.rekap.index', ['tanggal' => $jurnal->tanggal]) }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline mb-1.5 transition-colors">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Rekapitulasi</span>
            </a>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight flex items-center space-x-2.5">
                <div class="p-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 shrink-0">
                    <i data-lucide="book-open-check" class="w-5 h-5"></i>
                </div>
                <span>Detail Jurnal Mengajar</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Dicatat pada: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $jurnal->dicatat_pada ? \Carbon\Carbon::parse($jurnal->dicatat_pada)->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB' : '-' }}</span>
            </p>
        </div>

        <div class="flex items-center space-x-2 shrink-0">
            <span class="px-3 py-1.5 bg-slate-100 dark:bg-[#141C29] border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl flex items-center space-x-1.5">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                <span>{{ \Carbon\Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">
        <!-- Kolom Kiri: Rincian Sesi KBM & Bukti Foto -->
        <div class="lg:col-span-2 space-y-4">
            
            <!-- Card Ringkasan Informasi Sesi -->
            <div class="bg-white dark:bg-[#1C2433] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xs p-4 sm:p-5 space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <span class="block text-[10.5px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Guru Pengajar</span>
                        <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5 block leading-tight">
                            {{ $jurnal->jadwal && $jurnal->jadwal->guru ? $jurnal->jadwal->guru->nama_guru : '-' }}
                        </span>
                        <span class="text-[10.5px] text-slate-400 font-mono">NIP: {{ $jurnal->jadwal && $jurnal->jadwal->guru ? ($jurnal->jadwal->guru->nip ?? '-') : '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10.5px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Mata Pelajaran</span>
                        <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 mt-0.5 block leading-tight">
                            {{ $jurnal->jadwal && $jurnal->jadwal->mapel ? $jurnal->jadwal->mapel->nama_mapel : '-' }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-[10.5px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kelas & Ruangan</span>
                        <span class="text-xs sm:text-sm font-bold text-blue-600 dark:text-blue-400 mt-0.5 block leading-tight">
                            {{ $jurnal->jadwal && $jurnal->jadwal->kelas ? $jurnal->jadwal->kelas->nama_kelas : '-' }}
                        </span>
                        <span class="text-[10.5px] text-slate-500 dark:text-slate-400">
                            {{ $jurnal->jadwal && $jurnal->jadwal->ruangan ? $jurnal->jadwal->ruangan->nama_ruangan : 'Default Ruangan' }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-[10.5px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sesi Jam Pelajaran</span>
                        <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-slate-100 font-mono mt-0.5 block">
                            Jam ke-{{ $jurnal->jadwal->jam_mulai ?? '-' }} s/d {{ $jurnal->jadwal->jam_selesai ?? '-' }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-[10.5px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status Kehadiran Guru</span>
                        <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/80 text-xs font-bold rounded-lg mt-1 whitespace-nowrap">
                            <i data-lucide="check" class="w-3.5 h-3.5 mr-1 stroke-[3]"></i>
                            <span>{{ $jurnal->status_kehadiran_guru ?? 'Hadir' }}</span>
                        </span>
                    </div>
                </div>

                <!-- Materi Pembelajaran -->
                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5 flex items-center space-x-1.5">
                        <i data-lucide="file-text" class="w-3.5 h-3.5 text-blue-500"></i>
                        <span>Materi Pembelajaran yang Disampaikan</span>
                    </h3>
                    <div class="p-3.5 bg-slate-50 dark:bg-[#141C29] border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 font-medium leading-relaxed">
                        {{ $jurnal->materi ?? 'Belum ada materi yang dicatat.' }}
                    </div>
                </div>

                <!-- Catatan Khusus Guru (Jika ada) -->
                @if ($jurnal->catatan)
                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5 flex items-center space-x-1.5">
                        <i data-lucide="message-square" class="w-3.5 h-3.5 text-amber-500"></i>
                        <span>Catatan Khusus Guru</span>
                    </h3>
                    <div class="p-3.5 bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-900/60 rounded-xl text-xs text-amber-900 dark:text-amber-200 italic leading-relaxed">
                        "{{ $jurnal->catatan }}"
                    </div>
                </div>
                @endif

                <!-- Foto Bukti Kamera Kelas -->
                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 flex items-center space-x-1.5">
                        <i data-lucide="camera" class="w-3.5 h-3.5 text-emerald-500"></i>
                        <span>Dokumentasi Foto Kamera Kelas</span>
                    </h3>
                    @php 
                        $fotoPath = $jurnal->foto ? $jurnal->foto->foto_path : ($jurnal->foto_kegiatan ?? null); 
                    @endphp
                    @if ($fotoPath)
                        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 max-w-md shadow-xs group relative">
                            <img src="{{ asset('storage/' . $fotoPath) }}" alt="Foto Pembelajaran Live" class="w-full h-auto object-cover group-hover:scale-[1.02] transition-transform duration-300">
                        </div>
                    @else
                        <div class="p-6 bg-slate-50 dark:bg-[#141C29] border border-dashed border-slate-200 dark:border-slate-800 rounded-xl text-center text-xs text-slate-400 dark:text-slate-500 space-y-1">
                            <i data-lucide="image-off" class="w-7 h-7 mx-auto text-slate-300 dark:text-slate-600"></i>
                            <p>Tidak ada dokumentasi foto yang diunggah pada sesi ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Rekap Ketidakhadiran Siswa Sesi Ini -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-[#1C2433] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xs overflow-hidden flex flex-col">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/60 dark:bg-[#141C29]">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-lg">
                            <i data-lucide="user-x" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider">
                                Ketidakhadiran Siswa
                            </h3>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500">Sesi Jam Pelajaran Ini</p>
                        </div>
                    </div>
                    <span class="text-xs text-rose-700 dark:text-rose-300 font-bold bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800/80 px-2.5 py-0.5 rounded-full font-mono">
                        {{ count($jurnal->detailKetidakhadiran ?? []) }} Siswa
                    </span>
                </div>

                @if (!isset($jurnal->detailKetidakhadiran) || $jurnal->detailKetidakhadiran->isEmpty())
                    <div class="p-8 text-center bg-white dark:bg-[#1C2433] text-xs space-y-2">
                        <div class="w-10 h-10 mx-auto rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-6 h-6"></i>
                        </div>
                        <p class="font-bold text-slate-800 dark:text-slate-200">Semua Siswa Hadir</p>
                        <p class="text-slate-400 dark:text-slate-500 text-[11px]">Seluruh siswa tercatat hadir lengkap di jam ini.</p>
                    </div>
                @else
                    <div class="max-h-[500px] overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($jurnal->detailKetidakhadiran as $det)
                            <div class="p-3.5 flex items-center justify-between gap-3 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <div class="min-w-0">
                                    <p class="font-bold text-xs text-slate-900 dark:text-slate-100 truncate">
                                        {{ $det->siswa ? $det->siswa->nama_siswa : '-' }}
                                    </p>
                                    <p class="text-[10.5px] font-mono text-slate-400 dark:text-slate-500 mt-0.5">
                                        NIS: {{ $det->id_siswa }}
                                    </p>
                                </div>

                                <div class="shrink-0">
                                    @if ($det->keterangan === 'Sakit')
                                        <span class="inline-flex items-center px-2.5 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-[11px] font-bold rounded-lg whitespace-nowrap shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                                            Sakit
                                        </span>
                                    @elseif ($det->keterangan === 'Izin')
                                        <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-[11px] font-bold rounded-lg whitespace-nowrap shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                            Izin
                                        </span>
                                    @elseif ($det->keterangan === 'Alpa')
                                        <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-[11px] font-bold rounded-lg whitespace-nowrap shadow-2xs" title="Alpa pada jam mata pelajaran ini">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                            Alpa Jam Mapel
                                        </span>
                                    @elseif ($det->keterangan === 'Dispen')
                                        <span class="inline-flex items-center px-2.5 py-1 bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 text-[11px] font-bold rounded-lg whitespace-nowrap shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500 mr-1.5"></span>
                                            Dispen
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 text-[11px] font-bold rounded-lg whitespace-nowrap shadow-2xs">
                                            {{ $det->keterangan }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
