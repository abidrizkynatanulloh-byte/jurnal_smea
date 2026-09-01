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

                @if ($jurnal->kegiatan)
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kegiatan Pembelajaran</h3>
                    <div class="p-4 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] font-medium leading-relaxed">
                        {{ $jurnal->kegiatan }}
                    </div>
                </div>
                @endif

                @if ($jurnal->tugas)
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tugas yang Diberikan</h3>
                    <div class="p-4 bg-amber-50/50 border border-amber-200/60 rounded-xl text-sm text-amber-900 font-medium leading-relaxed">
                        {{ $jurnal->tugas }}
                    </div>
                </div>
                @endif

                @if ($jurnal->catatan)
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Catatan Khusus</h3>
                    <div class="p-4 bg-gray-50 border border-gray-200/60 rounded-xl text-sm text-gray-600 italic leading-relaxed">
                        "{{ $jurnal->catatan }}"
                    </div>
                </div>
                @endif

                <!-- Foto Bukti Pembelajaran -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Dokumentasi Foto Kelas</h3>
                    @if ($jurnal->foto_kegiatan)
                        <div class="rounded-xl overflow-hidden border border-[#D1D9EB] max-w-md">
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

        <!-- Rekap Absensi Siswa di Jam Ini -->
        <div class="space-y-6">
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-[#D1D9EB] flex items-center justify-between">
                    <h3 class="font-bold text-[#1E2538] text-sm flex items-center space-x-2">
                        <i data-lucide="users" class="w-4 h-4 text-[#405078]"></i>
                        <span>Presensi Siswa</span>
                    </h3>
                    <span class="text-xs text-gray-400 font-medium">{{ count($jurnal->absensi ?? []) }} Siswa</span>
                </div>

                <div class="max-h-[500px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-400 uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-3 px-4">Nama Siswa</th>
                                <th class="py-3 px-4 text-center w-24">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($jurnal->absensi as $abs)
                                <tr>
                                    <td class="py-2.5 px-4">
                                        <p class="font-bold text-[#1E2538]">{{ $abs->siswa ? $abs->siswa->nama_siswa : '-' }}</p>
                                        <p class="text-[10px] text-gray-400">NIS: {{ $abs->nis }}</p>
                                    </td>
                                    <td class="py-2.5 px-4 text-center">
                                        @if ($abs->status === 'Hadir')
                                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold rounded">H</span>
                                        @elseif ($abs->status === 'Sakit')
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 font-bold rounded">S</span>
                                        @elseif ($abs->status === 'Izin')
                                            <span class="px-2 py-0.5 bg-amber-50 text-amber-700 font-bold rounded">I</span>
                                        @elseif ($abs->status === 'Dispen')
                                            <span class="px-2 py-0.5 bg-purple-50 text-purple-700 font-bold rounded">D</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-rose-50 text-rose-700 font-bold rounded">A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-6 text-center text-gray-400 italic">
                                        Tidak ada catatan absensi siswa.
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
