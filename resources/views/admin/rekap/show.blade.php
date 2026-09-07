@extends('layouts.app')

@section('title', 'Detail Jurnal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.rekap.index', ['tanggal' => $jurnal->tanggal]) }}" class="inline-flex items-center space-x-1 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors mb-1.5">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Rekapitulasi</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Detail Jurnal Mengajar</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Dicatat pada: {{ \Carbon\Carbon::parse($jurnal->dicatat_pada)->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">
        <!-- Rincian Informasi & Foto -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Card Detail -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-4 space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pb-3.5 border-b border-slate-100">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Guru Pengajar</span>
                        <span class="text-xs font-bold text-slate-900">{{ $jurnal->jadwal && $jurnal->jadwal->guru ? $jurnal->jadwal->guru->nama_guru : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mata Pelajaran</span>
                        <span class="text-xs font-semibold text-slate-800">{{ $jurnal->jadwal && $jurnal->jadwal->mapel ? $jurnal->jadwal->mapel->nama_mapel : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kelas & Ruangan</span>
                        <span class="text-xs font-semibold text-slate-800">
                            {{ $jurnal->jadwal && $jurnal->jadwal->kelas ? $jurnal->jadwal->kelas->nama_kelas : '-' }}
                            ({{ $jurnal->jadwal && $jurnal->jadwal->ruangan ? $jurnal->jadwal->ruangan->nama_ruangan : '-' }})
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sesi Jam</span>
                        <span class="text-xs font-semibold text-slate-800">Jam {{ $jurnal->jadwal->jam_mulai ?? '-' }} - {{ $jurnal->jadwal->jam_selesai ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</span>
                        <span class="text-xs font-semibold text-slate-800">{{ \Carbon\Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kehadiran Guru</span>
                        <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded mt-0.5">
                            ✓ {{ $jurnal->status_kehadiran_guru }}
                        </span>
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Materi Pembelajaran</h3>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 leading-relaxed">
                        {{ $jurnal->materi }}
                    </div>
                </div>

                @if ($jurnal->catatan)
                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Catatan Khusus Guru</h3>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-600 italic leading-relaxed">
                        "{{ $jurnal->catatan }}"
                    </div>
                </div>
                @endif

                <!-- Foto Bukti Pembelajaran -->
                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Dokumentasi Foto Kamera Kelas</h3>
                    @if ($jurnal->foto && $jurnal->foto->foto_path)
                        <div class="rounded-lg overflow-hidden border border-slate-200 max-w-sm shadow-2xs">
                            <img src="{{ asset('storage/' . $jurnal->foto->foto_path) }}" alt="Foto Pembelajaran Live" class="w-full h-auto object-cover">
                        </div>
                    @elseif ($jurnal->foto_kegiatan)
                        <div class="rounded-lg overflow-hidden border border-slate-200 max-w-sm shadow-2xs">
                            <img src="{{ asset('storage/' . $jurnal->foto_kegiatan) }}" alt="Foto Kegiatan" class="w-full h-auto object-cover">
                        </div>
                    @else
                        <div class="p-5 bg-slate-50 border border-dashed border-slate-200 rounded-lg text-center text-xs text-slate-400">
                            <i data-lucide="image-off" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                            Tidak ada dokumentasi foto yang diunggah.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rekap Ketidakhadiran Siswa di Jam Ini -->
        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-3.5 py-2.5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <div>
                        <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                            <i data-lucide="users" class="w-3.5 h-3.5 text-slate-700"></i>
                            <span>Ketidakhadiran Siswa Sesi Ini</span>
                        </h3>
                    </div>
                    <span class="text-[11px] text-rose-700 font-bold bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full">
                        {{ count($jurnal->detailKetidakhadiran ?? []) }} Siswa
                    </span>
                </div>

                <div class="max-h-[500px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-200">
                                <th class="py-2 px-3.5">Nama Siswa</th>
                                <th class="py-2 px-3.5 text-center w-28">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($jurnal->detailKetidakhadiran as $det)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2 px-3.5">
                                        <p class="font-semibold text-slate-900">{{ $det->siswa ? $det->siswa->nama_siswa : '-' }}</p>
                                        <p class="text-[10px] text-slate-400">NIS: {{ $det->id_siswa }}</p>
                                    </td>
                                    <td class="py-2 px-3.5 text-center">
                                        @if ($det->keterangan === 'Sakit')
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 font-semibold rounded text-[10px]">Sakit</span>
                                        @elseif ($det->keterangan === 'Izin')
                                            <span class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 font-semibold rounded text-[10px]">Izin</span>
                                        @elseif ($det->keterangan === 'Alpa')
                                            <span class="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 font-semibold rounded text-[10px]" title="Alpa pada jam mata pelajaran ini">
                                                Alpa Jam Mapel
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 border border-slate-200 font-semibold rounded text-[10px]">{{ $det->keterangan }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-6 text-center text-slate-400 italic">
                                        <i data-lucide="check-circle" class="w-5 h-5 mx-auto mb-1 text-emerald-500"></i>
                                        Semua siswa hadir di jam pelajaran ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
