@extends('layouts.app')

@section('title', 'Rincian Jurnal Tertunggak - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Rincian Sesi Jurnal Belum Diisi / Tertunggak</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Daftar rincian jadwal mengajar Anda yang belum dilengkapi pengisian jurnal pembelajarannya
            </p>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('guru.jurnal.rekap') }}" class="h-8.5 px-3.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="history" class="w-3.5 h-3.5 text-slate-500"></i>
                <span>Riwayat Jurnal Terisi</span>
            </a>
        </div>
    </div>

    <!-- SUMMARY BANNER -->
    <div class="p-3 {{ count($daftarTertunggak) > 0 ? 'bg-rose-50/80 border-rose-200' : 'bg-emerald-50/80 border-emerald-200' }} border rounded-xl flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="p-2 {{ count($daftarTertunggak) > 0 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }} rounded-lg">
                <i data-lucide="{{ count($daftarTertunggak) > 0 ? 'alert-triangle' : 'check-circle-2' }}" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold {{ count($daftarTertunggak) > 0 ? 'text-rose-900' : 'text-emerald-900' }}">
                    {{ count($daftarTertunggak) > 0 ? 'Terdapat ' . count($daftarTertunggak) . ' Sesi Mengajar Belum Diisi' : 'Luar Biasa! Seluruh Jurnal Mengajar Lengkap' }}
                </h3>
                <p class="text-[11px] {{ count($daftarTertunggak) > 0 ? 'text-rose-700' : 'text-emerald-700' }} mt-0.5">
                    {{ count($daftarTertunggak) > 0 ? 'Pastikan seluruh sesi pembelajaran dilengkapi jurnal dan bukti foto untuk kepatuhan administrasi KBM.' : 'Tidak ada sesi mengajar yang tertunggak minggu ini.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- TABEL DETAIL SESI TERTUNGGAK -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-3.5 py-2.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-1.5">
                <i data-lucide="calendar-x" class="w-4 h-4 text-rose-600"></i>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Rincian Tanggal, Jam, dan Kelas</h3>
            </div>
            <span class="text-[11px] font-semibold text-slate-400">Minggu Berjalan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-white text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-2 px-3 text-center w-10">No</th>
                        <th class="py-2 px-3 w-36">Hari & Tanggal</th>
                        <th class="py-2 px-3 w-32">Jam Pelajaran</th>
                        <th class="py-2 px-3 w-32">Kelas & Ruang</th>
                        <th class="py-2 px-3">Mata Pelajaran</th>
                        <th class="py-2 px-3 text-center w-32">Status</th>
                        <th class="py-2 px-3 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($daftarTertunggak as $idx => $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2 px-3 text-center font-medium text-slate-400 text-xs tabular-nums">{{ $idx + 1 }}</td>
                            <td class="py-2 px-3">
                                <p class="font-semibold text-slate-900 text-xs">{{ $item['hari'] }}</p>
                                <p class="text-[10px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($item['tanggal'])->locale('id')->isoFormat('D MMMM Y') }}</p>
                            </td>
                            <td class="py-2 px-3">
                                <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[11px] font-bold text-slate-700">
                                    {{ $item['jam_ke'] }}
                                </span>
                            </td>
                            <td class="py-2 px-3">
                                <span class="font-semibold text-slate-900 text-xs block">{{ $item['kelas'] }}</span>
                                <span class="text-[10px] text-slate-400">{{ $item['ruangan'] }}</span>
                            </td>
                            <td class="py-2 px-3">
                                <p class="font-medium text-slate-800 text-xs">{{ $item['mapel'] }}</p>
                            </td>
                            <td class="py-2 px-3 text-center">
                                @if(str_contains($item['keterangan'], 'Sah'))
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold rounded">
                                        {{ $item['keterangan'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold rounded space-x-1">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></span>
                                        <span>Belum Diisi</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 px-3 text-center">
                                @if($item['is_today'])
                                    <a href="{{ route('guru.jurnal.create', $item['id_jadwal']) }}"
                                        class="h-6.5 px-2.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-md text-xs font-semibold transition-colors shadow-2xs inline-flex items-center space-x-1">
                                        <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        <span>Isi Sekarang</span>
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Lewat Hari</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400 italic text-xs">
                                <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1 text-emerald-500"></i>
                                Tidak ada jurnal yang tertunggak. Semua sesi KBM telah dilengkapi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
