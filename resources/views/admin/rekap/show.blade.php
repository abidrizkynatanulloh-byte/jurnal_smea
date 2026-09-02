@extends('layouts.app')

@section('title', 'Detail Jurnal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.rekap.index', ['tanggal' => $jurnal->tanggal]) }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-gray-500 hover:text-[#405078] transition-colors mb-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Rekapitulasi</span>
            </a>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Detail Jurnal Mengajar</h1>
            <p class="text-xs text-gray-500 mt-0.5">
                Dicatat pada: {{ \Carbon\Carbon::parse($jurnal->dicatat_pada)->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Rincian Informasi & Foto -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card Detail -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 space-y-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pb-6 border-b border-gray-100">
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Guru Pengajar</span>
                        <span class="text-sm font-bold text-[#1E2538]">{{ $jurnal->jadwal && $jurnal->jadwal->guru ? $jurnal->jadwal->guru->nama_guru : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Mata Pelajaran</span>
                        <span class="text-sm font-semibold text-[#1E2538]">{{ $jurnal->jadwal && $jurnal->jadwal->mapel ? $jurnal->jadwal->mapel->nama_mapel : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kelas & Ruangan</span>
                        <span class="text-sm font-semibold text-[#1E2538]">
                            {{ $jurnal->jadwal && $jurnal->jadwal->kelas ? $jurnal->jadwal->kelas->nama_kelas : '-' }}
                            ({{ $jurnal->jadwal && $jurnal->jadwal->ruangan ? $jurnal->jadwal->ruangan->nama_ruangan : '-' }})
                        </span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Sesi Jam</span>
                        <span class="text-sm font-semibold text-[#1E2538]">Jam {{ $jurnal->jadwal->jam_mulai ?? '-' }} - {{ $jurnal->jadwal->jam_selesai ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tanggal</span>
                        <span class="text-sm font-semibold text-[#1E2538]">{{ \Carbon\Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kehadiran Guru</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full mt-1">
                            ✓ {{ $jurnal->status_kehadiran_guru }}
                        </span>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Materi Pembelajaran</h3>
                    <div class="p-4 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] font-medium leading-relaxed">
                        {{ $jurnal->materi }}
                    </div>
                </div>

                @if ($jurnal->catatan)
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Catatan Khusus Guru</h3>
                    <div class="p-4 bg-gray-50 border border-gray-200/60 rounded-xl text-sm text-gray-600 italic leading-relaxed">
                        "{{ $jurnal->catatan }}"
                    </div>
                </div>
                @endif

                <!-- Foto Bukti Pembelajaran -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Dokumentasi Foto Kamera Kelas</h3>
                    @if ($jurnal->foto && $jurnal->foto->foto_path)
                        <div class="rounded-xl overflow-hidden border border-[#D1D9EB] max-w-md shadow-sm">
                            <img src="{{ asset('storage/' . $jurnal->foto->foto_path) }}" alt="Foto Pembelajaran Live" class="w-full h-auto object-cover">
                        </div>
                    @elseif ($jurnal->foto_kegiatan)
                        <div class="rounded-xl overflow-hidden border border-[#D1D9EB] max-w-md shadow-sm">
                            <img src="{{ asset('storage/' . $jurnal->foto_kegiatan) }}" alt="Foto Kegiatan" class="w-full h-auto object-cover">
                        </div>
                    @else
                        <div class="p-6 bg-gray-50 border border-dashed border-gray-200 rounded-xl text-center text-xs text-gray-400">
                            <i data-lucide="image-off" class="w-8 h-8 mx-auto mb-1 text-gray-300"></i>
                            Tidak ada dokumentasi foto yang diunggah.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rekap Ketidakhadiran Siswa di Jam Ini -->
        <div class="space-y-6">
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-[#D1D9EB] flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-[#1E2538] text-sm flex items-center space-x-2">
                            <i data-lucide="users" class="w-4 h-4 text-[#405078]"></i>
                            <span>Ketidakhadiran Siswa Sesi Ini</span>
                        </h3>
                        <p class="text-[10px] text-gray-400">Siswa yang tidak hadir di jam mapel ini</p>
                    </div>
                    <span class="text-xs text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-full">
                        {{ count($jurnal->detailKetidakhadiran ?? []) }} Siswa
                    </span>
                </div>

                <div class="max-h-[500px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-400 uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-3 px-4">Nama Siswa</th>
                                <th class="py-3 px-4 text-center w-36">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($jurnal->detailKetidakhadiran as $det)
                                <tr>
                                    <td class="py-3 px-4">
                                        <p class="font-bold text-[#1E2538]">{{ $det->siswa ? $det->siswa->nama_siswa : '-' }}</p>
                                        <p class="text-[10px] text-gray-400">NIS: {{ $det->id_siswa }}</p>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if ($det->keterangan === 'Sakit')
                                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg inline-block text-[11px]">Sakit</span>
                                        @elseif ($det->keterangan === 'Izin')
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 font-bold rounded-lg inline-block text-[11px]">Izin</span>
                                        @elseif ($det->keterangan === 'Alpa')
                                            <span class="px-2.5 py-1 bg-rose-50 text-rose-700 font-bold rounded-lg inline-block text-[11px]" title="Alpa pada jam mata pelajaran ini">
                                                Alpa Jam Mapel
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 font-bold rounded-lg inline-block text-[11px]">{{ $det->keterangan }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-8 text-center text-gray-400 italic">
                                        <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1 text-emerald-500"></i>
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
