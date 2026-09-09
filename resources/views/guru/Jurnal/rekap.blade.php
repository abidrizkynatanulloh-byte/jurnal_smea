@extends('layouts.app')

@section('title', 'Rekap Jurnal Saya - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Riwayat Jurnal Mengajar</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Rekapitulasi riwayat pengisian jurnal pembelajaran oleh <span class="font-bold text-slate-800">{{ $guru->nama_guru ?? Auth::user()->nama_display ?? Auth::user()->username }}</span>
            </p>
        </div>
    </div>

    <!-- Filter Tanggal Card -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-3">
        <form action="{{ route('guru.jurnal.rekap') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5 items-end">
            <div class="flex-1 w-full sm:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari') }}"
                    class="block w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538] transition-all cursor-pointer">
            </div>

            <div class="flex-1 w-full sm:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}"
                    class="block w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538] transition-all cursor-pointer">
            </div>

            <div class="flex space-x-1.5 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-initial h-8 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer shadow-2xs">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan Filter</span>
                </button>
                @if (request('dari') || request('sampai'))
                    <a href="{{ route('guru.jurnal.rekap') }}" class="h-8 px-3 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Rekap Card -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-3.5 py-2.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                <i data-lucide="clipboard-check" class="w-3.5 h-3.5 text-slate-700"></i>
                <span>Daftar Jurnal Tersimpan ({{ $rekapList->total() }} Sesi)</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-white text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-2 px-3 w-28">Tanggal</th>
                        <th class="py-2 px-3 w-24">Kelas</th>
                        <th class="py-2 px-3">Mata Pelajaran</th>
                        <th class="py-2 px-3 w-28">Jam Ke-</th>
                        <th class="py-2 px-3 w-24">Kehadiran</th>
                        <th class="py-2 px-3">Materi</th>
                        <th class="py-2 px-3 text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($rekapList as $r)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2 px-3 font-semibold text-slate-900">{{ \Carbon\Carbon::parse($r->tanggal)->locale('id')->isoFormat('D MMM Y') }}</td>
                            <td class="py-2 px-3">
                                <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[11px] font-bold text-slate-700">
                                    {{ $r->jadwal && $r->jadwal->kelas ? $r->jadwal->kelas->nama_kelas : '-' }}
                                </span>
                            </td>
                            <td class="py-2 px-3 font-medium text-slate-800">{{ $r->jadwal && $r->jadwal->mapel ? $r->jadwal->mapel->nama_mapel : '-' }}</td>
                            <td class="py-2 px-3 text-slate-500">Jam {{ $r->jadwal->jam_mulai ?? '-' }}–{{ $r->jadwal->jam_selesai ?? '-' }}</td>
                            <td class="py-2 px-3">
                                @if ($r->status_kehadiran_guru == 'Hadir')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold rounded space-x-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        <span>Hadir</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold rounded space-x-1">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        <span>{{ $r->status_kehadiran_guru }}</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 px-3 text-slate-600 text-xs max-w-sm truncate" title="{{ $r->materi }}">{{ \Illuminate\Support\Str::limit($r->materi, 50) }}</td>
                            <td class="py-2 px-3 text-center">
                                <a href="{{ route('guru.jurnal.show', $r->id_jurnal) }}" class="inline-flex items-center space-x-1 h-6.5 px-2 rounded-md text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors">
                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400 italic text-xs">
                                <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                                Belum ada jurnal yang tersimpan pada rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION FOOTER -->
        @if ($rekapList->hasPages())
            <div class="px-3.5 py-2.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs">
                <div class="text-slate-500">
                    Menampilkan halaman <span class="font-semibold text-slate-900">{{ $rekapList->currentPage() }}</span> dari <span class="font-semibold text-slate-900">{{ $rekapList->lastPage() }}</span>
                </div>
                <div class="inline-flex space-x-1">
                    @if ($rekapList->previousPageUrl())
                        <a href="{{ $rekapList->previousPageUrl() }}" class="h-7 px-2.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-md text-xs font-semibold transition-colors flex items-center space-x-1">
                            <i data-lucide="chevron-left" class="w-3 h-3"></i>
                            <span>Sebelumnya</span>
                        </a>
                    @endif
                    @if ($rekapList->nextPageUrl())
                        <a href="{{ $rekapList->nextPageUrl() }}" class="h-7 px-2.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-md text-xs font-semibold transition-colors flex items-center space-x-1">
                            <span>Selanjutnya</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection