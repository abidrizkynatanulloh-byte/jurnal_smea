@extends('layouts.app')

@section('title', 'Rekap Jurnal Saya - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-[#405078] hover:underline flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Riwayat Jurnal Mengajar</h1>
            <p class="text-xs text-gray-500 mt-0.5">
                Rekapitulasi riwayat pengisian jurnal pembelajaran oleh <span class="font-bold text-[#405078]">{{ $guru->nama_guru }}</span>
            </p>
        </div>
    </div>

    <!-- Filter Tanggal Card -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-5">
        <form action="{{ route('guru.jurnal.rekap') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1 w-full sm:w-auto">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari') }}"
                    class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
            </div>

            <div class="flex-1 w-full sm:w-auto">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}"
                    class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
            </div>

            <div class="flex space-x-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-initial px-5 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer shadow-sm">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Terapkan Filter</span>
                </button>
                @if (request('dari') || request('sampai'))
                    <a href="{{ route('guru.jurnal.rekap') }}" class="px-4 py-2 border border-[#D1D9EB] hover:bg-gray-50 text-gray-500 hover:text-[#1E2538] rounded-xl text-sm font-semibold transition-colors flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Rekap Card -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
            <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                <i data-lucide="clipboard-check" class="w-5 h-5 text-[#405078]"></i>
                <span>Daftar Jurnal Tersimpan ({{ $rekapList->total() }} Sesi)</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6 w-32">Tanggal</th>
                        <th class="py-4 px-6 w-32">Kelas</th>
                        <th class="py-4 px-6">Mata Pelajaran</th>
                        <th class="py-4 px-6 w-32">Jam Ke-</th>
                        <th class="py-4 px-6 w-32">Kehadiran</th>
                        <th class="py-4 px-6">Materi</th>
                        <th class="py-4 px-6 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse ($rekapList as $r)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 font-semibold text-[#1E2538]">{{ \Carbon\Carbon::parse($r->tanggal)->locale('id')->isoFormat('D MMM Y') }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 bg-gray-100 rounded-md text-xs font-bold text-gray-700">
                                    {{ $r->jadwal && $r->jadwal->kelas ? $r->jadwal->kelas->nama_kelas : '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-medium text-[#1E2538]">{{ $r->jadwal && $r->jadwal->mapel ? $r->jadwal->mapel->nama_mapel : '-' }}</td>
                            <td class="py-4 px-6 text-gray-500">Jam {{ $r->jadwal->jam_mulai ?? '-' }} - {{ $r->jadwal->jam_selesai ?? '-' }}</td>
                            <td class="py-4 px-6">
                                @if ($r->status_kehadiran_guru == 'Hadir')
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        <span>Hadir</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-rose-50 text-rose-700 text-xs font-bold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        <span>{{ $r->status_kehadiran_guru }}</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-gray-600 text-xs max-w-sm truncate" title="{{ $r->materi }}">{{ \Illuminate\Support\Str::limit($r->materi, 50) }}</td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('guru.jurnal.show', $r->id_jurnal) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-[#405078] bg-[#405078]/10 hover:bg-[#405078]/20 transition-colors">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 italic">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                Belum ada jurnal yang tersimpan pada rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION FOOTER -->
        @if ($rekapList->hasPages())
            <div class="px-6 py-4 bg-[#F8FAFC] border-t border-[#D1D9EB] flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Menampilkan halaman <span class="font-semibold text-[#1E2538]">{{ $rekapList->currentPage() }}</span> dari <span class="font-semibold text-[#1E2538]">{{ $rekapList->lastPage() }}</span>
                </div>
                <div class="inline-flex space-x-1.5">
                    @if ($rekapList->previousPageUrl())
                        <a href="{{ $rekapList->previousPageUrl() }}" class="px-3.5 py-1.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
                            <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                            <span>Sebelumnya</span>
                        </a>
                    @endif
                    @if ($rekapList->nextPageUrl())
                        <a href="{{ $rekapList->nextPageUrl() }}" class="px-3.5 py-1.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
                            <span>Selanjutnya</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection