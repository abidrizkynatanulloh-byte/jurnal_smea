@extends('layouts.app')

@section('title', 'Detail Jurnal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-4 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-1.5 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Detail Jurnal Mengajar</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Dicatat pada: {{ $jurnal->dicatat_pada ? \Carbon\Carbon::parse($jurnal->dicatat_pada)->locale('id')->isoFormat('D MMMM Y, HH:mm') . ' WIB' : '-' }}
            </p>
        </div>
        <div>
            <a href="{{ route('guru.jurnal.rekap') }}" class="h-8 px-3 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="list" class="w-3.5 h-3.5 text-slate-500"></i>
                <span>Semua Riwayat</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        <!-- Rincian Informasi & Foto -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Card Detail -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-4 space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pb-3.5 border-b border-slate-100">
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kelas</span>
                        <span class="text-xs font-bold text-slate-800">{{ $jurnal->jadwal && $jurnal->jadwal->kelas ? $jurnal->jadwal->kelas->nama_kelas : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mata Pelajaran</span>
                        <span class="text-xs font-semibold text-slate-800">{{ $jurnal->jadwal && $jurnal->jadwal->mapel ? $jurnal->jadwal->mapel->nama_mapel : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ruangan & Jam</span>
                        <span class="text-xs font-semibold text-slate-800">
                            {{ $jurnal->jadwal && $jurnal->jadwal->ruangan ? $jurnal->jadwal->ruangan->nama_ruangan : '-' }}
                            (Jam {{ $jurnal->jadwal->jam_mulai ?? '-' }}-{{ $jurnal->jadwal->jam_selesai ?? '-' }})
                        </span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</span>
                        <span class="text-xs font-semibold text-slate-800">{{ \Carbon\Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kehadiran Guru</span>
                        <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-md mt-0.5 border border-emerald-200/60">
                            ✓ {{ $jurnal->status_kehadiran_guru }}
                        </span>
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Materi Pembelajaran</h3>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 font-medium leading-relaxed">
                        {{ $jurnal->materi }}
                    </div>
                </div>

                @if ($jurnal->catatan)
                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Catatan Pembelajaran</h3>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-600 italic leading-relaxed">
                        "{{ $jurnal->catatan }}"
                    </div>
                </div>
                @endif

                <!-- Foto Bukti Pembelajaran -->
                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Dokumentasi Sesi Mengajar</h3>
                    @php 
                        $fotoPath = $jurnal->foto ? $jurnal->foto->foto_path : ($jurnal->foto_kegiatan ?? null); 
                    @endphp
                    @if ($fotoPath)
                        <div class="rounded-lg overflow-hidden border border-slate-200 max-w-sm shadow-2xs">
                            <img src="{{ asset('storage/' . $fotoPath) }}" alt="Bukti Mengajar" class="w-full h-auto object-cover">
                        </div>
                    @else
                        <div class="p-4 bg-slate-50 border border-dashed border-slate-200 rounded-lg text-center text-xs text-slate-400">
                            <i data-lucide="image-off" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                            Tidak ada dokumentasi foto yang tersimpan.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Daftar Ketidakhadiran Siswa -->
        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5">
                            <i data-lucide="user-x" class="w-3.5 h-3.5 text-slate-500"></i>
                            <span>Siswa Tidak Hadir</span>
                        </h3>
                        <p class="text-[10px] text-slate-400">Presensi pada sesi ini</p>
                    </div>
                    <span class="text-xs text-rose-600 font-bold px-2 py-0.5 bg-rose-50 rounded-md border border-rose-200/60">{{ count($jurnal->detailKetidakhadiran ?? []) }} Siswa</span>
                </div>

                @if (!isset($jurnal->detailKetidakhadiran) || $jurnal->detailKetidakhadiran->isEmpty())
                    <div class="p-5 text-center text-emerald-700 bg-emerald-50/40 font-semibold text-xs flex flex-col items-center space-y-1">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                        <span>Seluruh siswa hadir lengkap pada sesi ini.</span>
                    </div>
                @else
                    <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-white border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-bold">
                                    <th class="py-2 px-3.5">Nama Siswa</th>
                                    <th class="py-2 px-3.5 text-center w-32">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($jurnal->detailKetidakhadiran as $k)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-2 px-3.5">
                                            <p class="font-bold text-slate-800 text-xs">{{ $k->siswa ? $k->siswa->nama_siswa : '-' }}</p>
                                            <p class="text-[10px] text-slate-400">NIS: {{ $k->id_siswa }}</p>
                                        </td>
                                        <td class="py-2 px-3.5 text-center">
                                            @if ($k->keterangan === 'Sakit')
                                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 font-bold rounded-md inline-block text-[10px] border border-blue-200/60">Sakit</span>
                                            @elseif ($k->keterangan === 'Izin')
                                                <span class="px-2 py-0.5 bg-amber-50 text-amber-700 font-bold rounded-md inline-block text-[10px] border border-amber-200/60">Izin</span>
                                            @elseif ($k->keterangan === 'Alpa')
                                                <span class="px-2 py-0.5 bg-rose-50 text-rose-700 font-bold rounded-md inline-block text-[10px] border border-rose-200/60">Alpa Mapel</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-bold rounded-md inline-block text-[10px] border border-slate-200">{{ $k->keterangan }}</span>
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