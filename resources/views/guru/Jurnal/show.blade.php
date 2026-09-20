@extends('layouts.app')

@section('title', 'Detail Jurnal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-4 max-w-5xl mx-auto pb-24 md:pb-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-[#1E2538] p-4 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <div>
            <div class="flex items-center space-x-1.5 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white flex items-center space-x-1 transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">Detail Jurnal Mengajar</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Dicatat pada: <span class="font-medium text-slate-700 dark:text-slate-300">{{ $jurnal->dicatat_pada ? \Carbon\Carbon::parse($jurnal->dicatat_pada)->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB' : '-' }}</span>
            </p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('guru.jurnal.rekap') }}" class="h-9 px-3.5 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition-all flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="list" class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400"></i>
                <span>Semua Riwayat</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 items-start">
        <!-- Rincian Informasi & Foto -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Card Detail Sesi Mengajar -->
            <div class="bg-white dark:bg-[#1E2538] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs p-4 sm:p-5 space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <span class="block text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kelas</span>
                        <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-slate-100">{{ $jurnal->jadwal && $jurnal->jadwal->kelas ? $jurnal->jadwal->kelas->nama_kelas : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Mata Pelajaran</span>
                        <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $jurnal->jadwal && $jurnal->jadwal->mapel ? $jurnal->jadwal->mapel->nama_mapel : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Ruangan & Jam</span>
                        <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $jurnal->jadwal && $jurnal->jadwal->ruangan ? $jurnal->jadwal->ruangan->nama_ruangan : '-' }}
                            (Jam {{ $jurnal->jadwal->jam_mulai ?? '-' }}-{{ $jurnal->jadwal->jam_selesai ?? '-' }})
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tanggal</span>
                        <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kehadiran Guru</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold rounded-md mt-0.5 border border-emerald-200/60 dark:border-emerald-800">
                            ✓ {{ $jurnal->status_kehadiran_guru }}
                        </span>
                    </div>
                </div>

                <div>
                    <h3 class="text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Materi Pembelajaran</h3>
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-200 font-medium leading-relaxed">
                        {{ $jurnal->materi }}
                    </div>
                </div>

                @if ($jurnal->catatan)
                <div>
                    <h3 class="text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Catatan Pembelajaran</h3>
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-600 dark:text-slate-300 italic leading-relaxed">
                        "{{ $jurnal->catatan }}"
                    </div>
                </div>
                @endif

                <!-- Foto Bukti Pembelajaran -->
                <div>
                    <h3 class="text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Foto Dokumentasi Sesi Mengajar</h3>
                    @php 
                        $fotoPath = $jurnal->foto ? $jurnal->foto->foto_path : ($jurnal->foto_kegiatan ?? null); 
                    @endphp
                    @if ($fotoPath)
                        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 max-w-sm shadow-2xs">
                            <img src="{{ asset('storage/' . $fotoPath) }}" alt="Bukti Mengajar" class="w-full h-auto object-cover">
                        </div>
                    @else
                        <div class="p-4 bg-slate-50 dark:bg-slate-900/40 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl text-center text-xs text-slate-400 dark:text-slate-500">
                            <i data-lucide="image-off" class="w-6 h-6 mx-auto mb-1 text-slate-300 dark:text-slate-600"></i>
                            Tidak ada dokumentasi foto yang tersimpan.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Daftar Ketidakhadiran Siswa -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-[#1E2538] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
                <div class="px-4 py-3.5 bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                            <i data-lucide="user-x" class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400"></i>
                            <span>Siswa Tidak Hadir</span>
                        </h3>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Presensi pada sesi ini</p>
                    </div>
                    <span class="text-xs text-rose-600 dark:text-rose-400 font-bold px-2.5 py-0.5 bg-rose-50 dark:bg-rose-950/60 rounded-lg border border-rose-200/60 dark:border-rose-900">{{ count($jurnal->detailKetidakhadiran ?? []) }} Siswa</span>
                </div>

                @if (!isset($jurnal->detailKetidakhadiran) || $jurnal->detailKetidakhadiran->isEmpty())
                    <div class="p-6 text-center text-emerald-700 dark:text-emerald-400 bg-emerald-50/40 dark:bg-emerald-950/20 font-semibold text-xs flex flex-col items-center space-y-1.5">
                        <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                        <span>Seluruh siswa hadir lengkap pada sesi ini.</span>
                    </div>
                @else
                    <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-white dark:bg-[#1E2538] border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[11px] font-bold">
                                    <th class="py-2.5 px-4">Nama Siswa</th>
                                    <th class="py-2.5 px-4 text-center w-32">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                @foreach ($jurnal->detailKetidakhadiran as $k)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="py-2.5 px-4">
                                            <p class="font-bold text-slate-800 dark:text-slate-200 text-xs">{{ $k->siswa ? $k->siswa->nama_siswa : '-' }}</p>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">NIS: {{ $k->id_siswa }}</p>
                                        </td>
                                        <td class="py-2.5 px-4 text-center">
                                            @if ($k->keterangan === 'Sakit')
                                                <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 font-bold rounded-md inline-block text-[10px] border border-blue-200/60 dark:border-blue-900">Sakit</span>
                                            @elseif ($k->keterangan === 'Izin')
                                                <span class="px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-bold rounded-md inline-block text-[10px] border border-amber-200/60 dark:border-amber-900">Izin</span>
                                            @elseif ($k->keterangan === 'Alpa')
                                                <span class="px-2.5 py-0.5 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 font-bold rounded-md inline-block text-[10px] border border-rose-200/60 dark:border-rose-900">Alpa Mapel</span>
                                            @else
                                                <span class="px-2.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-md inline-block text-[10px] border border-slate-200 dark:border-slate-700">{{ $k->keterangan }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection