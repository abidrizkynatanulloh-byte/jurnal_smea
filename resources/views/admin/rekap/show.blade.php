@extends('layouts.app')

@section('title', 'Detail Jurnal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.rekap.index', ['tanggal' => $jurnal->tanggal]) }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-gray-500 hover:text-brand transition-colors mb-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Rekapitulasi</span>
            </a>
            <h1 class="text-2xl font-extrabold text-dark tracking-tight">Detail Jurnal Mengajar</h1>
            <p class="text-sm text-gray-500 mt-0.5">
                Dicatat pada: {{ \Carbon\Carbon::parse($jurnal->dicatat_pada)->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Rincian Informati & Foto -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card Detail -->
            <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm p-6 space-y-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pb-6 border-b border-gray-100">
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Guru Pengajar</span>
                        <span class="text-sm font-bold text-dark">{{ $jurnal->jadwal && $jurnal->jadwal->guru ? $jurnal->jadwal->guru->nama_guru : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Mata Pelajaran</span>
                        <span class="text-sm font-semibold text-dark">{{ $jurnal->jadwal && $jurnal->jadwal->mapel ? $jurnal->jadwal->mapel->nama_mapel : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kelas & Ruangan</span>
                        <span class="text-sm font-semibold text-dark">
                            {{ $jurnal->jadwal && $jurnal->jadwal->kelas ? $jurnal->jadwal->kelas->nama_kelas : '-' }}
                            ({{ $jurnal->jadwal && $jurnal->jadwal->ruangan ? $jurnal->jadwal->ruangan->nama_ruangan : '-' }})
                        </span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Sesi Jam</span>
                        <span class="text-sm font-semibold text-dark">Jam {{ $jurnal->jadwal->jam_mulai ?? '-' }} - {{ $jurnal->jadwal->jam_selesai ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tanggal</span>
                        <span class="text-sm font-semibold text-dark">{{ \Carbon\Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kehadiran Guru</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 bg-green-50 text-green-700 text-xs font-bold rounded-full mt-1">
                            ✓ {{ $jurnal->status_kehadiran_guru }}
                        </span>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Materi Pembelajaran</h3>
                    <div class="p-4 bg-gray-50 border border-gray-200/60 rounded-xl text-sm text-dark font-medium leading-relaxed">
                        {{ $jurnal->materi ?? 'Tidak ada materi dicatat.' }}
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Catatan Guru</h3>
                    <div class="p-4 bg-gray-50 border border-gray-200/60 rounded-xl text-sm text-gray-600 leading-relaxed italic">
                        {{ $jurnal->catatan ?? 'Tidak ada catatan tambahan.' }}
                    </div>
                </div>
            </div>

            <!-- Card Absensi Siswa -->
            <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-[#19140015] bg-gray-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-dark text-base flex items-center space-x-2">
                        <i data-lucide="users-2" class="w-5 h-5 text-brand"></i>
                        <span>Siswa Tidak Hadir pada Sesi Ini ({{ $jurnal->detailKetidakhadiran->count() }})</span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#19140015]">
                                <th class="py-3 px-6">No</th>
                                <th class="py-3 px-6">NIS</th>
                                <th class="py-3 px-6">Nama Siswa</th>
                                <th class="py-3 px-6 text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#19140010] text-sm text-gray-600">
                            @forelse ($jurnal->detailKetidakhadiran as $idx => $dk)
                                <tr>
                                    <td class="py-3 px-6 font-semibold text-center w-12">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-6 font-mono text-xs">{{ $dk->id_siswa }}</td>
                                    <td class="py-3 px-6 font-bold text-dark">{{ $dk->siswa ? $dk->siswa->nama_siswa : '-' }}</td>
                                    <td class="py-3 px-6 text-center">
                                        @if ($dk->keterangan == 'Sakit')
                                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">🤒 Sakit</span>
                                        @elseif ($dk->keterangan == 'Izin')
                                            <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">📄 Izin</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">❌ Alpa</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-green-600 font-medium">
                                        ✓ Semua siswa hadir pada sesi pembelajaran ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bukti Foto Side Card -->
        <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm p-6 space-y-4 lg:sticky lg:top-8">
            <h3 class="font-bold text-dark text-base flex items-center space-x-2">
                <i data-lucide="camera" class="w-5 h-5 text-brand"></i>
                <span>Bukti Foto Realtime</span>
            </h3>

            @if ($jurnal->foto)
                <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-900">
                    <img src="{{ asset('storage/' . $jurnal->foto->foto_path) }}" alt="Bukti Mengajar" class="w-full object-cover max-h-96">
                </div>
                <div class="text-xs text-gray-500 flex items-center justify-between pt-1">
                    <span>Diambil pada:</span>
                    <span class="font-semibold text-dark">{{ \Carbon\Carbon::parse($jurnal->foto->diambil_pada)->format('H:i:s') }} WIB</span>
                </div>
            @else
                <div class="p-8 text-center bg-gray-50 border border-dashed border-gray-300 rounded-xl text-gray-400">
                    <i data-lucide="image-off" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                    <p class="text-xs">Tidak ada foto bukti yang tersimpan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
