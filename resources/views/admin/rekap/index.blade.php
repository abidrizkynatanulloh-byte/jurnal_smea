@extends('layouts.app')

@section('title', 'Rekap Jurnal & Kehadiran - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Rekapitulasi Jurnal & Kehadiran</h1>
            <p class="text-xs text-gray-500 mt-1">
                Pantau laporan pengisian jurnal mengajar harian guru
            </p>
        </div>
    </div>

    <!-- FILTER TANGGAL -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-5">
        <form action="{{ route('admin.rekap.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label for="tanggal" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Tanggal Rekap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                        class="block w-full pl-11 pr-4 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                </div>
            </div>

            <div class="flex space-x-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-initial px-5 py-2.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer shadow-sm">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('admin.rekap.index') }}" class="px-5 py-2.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                    <span>Hari Ini</span>
                </a>
            </div>
        </form>
    </div>

    <!-- TABEL 1: LAPORAN GURU ALPA (TIDAK MENGISI JURNAL) -->
    <div class="bg-rose-50/20 border border-rose-200/60 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 bg-rose-50/50 border-b border-rose-200/50 flex items-center justify-between">
            <h3 class="font-bold text-rose-900 text-base flex items-center space-x-2">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600"></i>
                <span>Laporan Guru Belum Isi Jurnal (Alpa)</span>
            </h3>
            <span class="px-2.5 py-1 bg-rose-100 text-rose-800 text-xs font-extrabold rounded-full">
                {{ $guruAlpaList->count() }} Jadwal Terlewat
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-rose-50/30 text-rose-700 text-xs font-semibold uppercase tracking-wider border-b border-rose-200/30">
                        <th class="py-4 px-6 w-32">Jam Ke-</th>
                        <th class="py-4 px-6">Guru Pengajar</th>
                        <th class="py-4 px-6">Mata Pelajaran</th>
                        <th class="py-4 px-6 w-32">Kelas</th>
                        <th class="py-4 px-6 w-56 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rose-200/20 text-sm text-rose-900/80">
                    @forelse ($guruAlpaList as $ga)
                        <tr class="hover:bg-rose-50/10 transition-colors">
                            <td class="py-4 px-6 font-semibold">Jam {{ $ga->jam_mulai }} - {{ $ga->jam_selesai }}</td>
                            <td class="py-4 px-6 font-bold">{{ $ga->guru ? $ga->guru->nama_guru : '-' }}</td>
                            <td class="py-4 px-6 text-rose-900/60">{{ $ga->mapel ? $ga->mapel->nama_mapel : '-' }}</td>
                            <td class="py-4 px-6 font-bold text-rose-800">{{ $ga->kelas ? $ga->kelas->nama_kelas : '-' }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-block px-3 py-1 bg-rose-100/80 text-rose-800 text-xs font-bold rounded-full">
                                    BELUM ISI JURNAL
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-emerald-700 font-bold bg-emerald-50/20">
                                <div class="flex flex-col items-center justify-center space-y-1.5">
                                    <i data-lucide="check-circle-2" class="w-7 h-7 text-emerald-600"></i>
                                    <span>Luar Biasa! Semua guru telah mengisi jurnal pada tanggal {{ $tanggal }}.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL 2: JURNAL TERSIMPAN -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
            <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                <i data-lucide="book-check" class="w-5 h-5 text-emerald-600"></i>
                <span>Jurnal Tersimpan ({{ $jurnalTersimpan->count() }} Terisi)</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6">Guru & Mapel</th>
                        <th class="py-4 px-6 w-32">Kelas</th>
                        <th class="py-4 px-6 w-32">Kehadiran Guru</th>
                        <th class="py-4 px-6">Materi Pembelajaran</th>
                        <th class="py-4 px-6 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse ($jurnalTersimpan as $jt)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-[#1E2538]">{{ $jt->jadwal && $jt->jadwal->guru ? $jt->jadwal->guru->nama_guru : '-' }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $jt->jadwal && $jt->jadwal->mapel ? $jt->jadwal->mapel->nama_mapel : '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 bg-gray-100 rounded-md text-xs font-bold text-gray-700">
                                    {{ $jt->jadwal && $jt->jadwal->kelas ? $jt->jadwal->kelas->nama_kelas : '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full space-x-1">
                                    <span class="w-1 h-1 bg-emerald-500 rounded-full"></span>
                                    <span>{{ $jt->status_kehadiran_guru ?? 'Hadir' }}</span>
                                </span>
                            </td>
                            <td class="py-4 px-6 font-medium text-[#1E2538] max-w-sm truncate" title="{{ $jt->materi }}">{{ $jt->materi ?? '-' }}</td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('admin.rekap.show', $jt->id_jurnal) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-[#405078] bg-[#405078]/10 hover:bg-[#405078]/20 transition-colors">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 italic">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                Belum ada jurnal yang disimpan pada tanggal {{ $tanggal }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
