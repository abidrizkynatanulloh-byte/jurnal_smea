@extends('layouts.app')

@section('title', 'Riwayat Izin Guru - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Pengajuan Izin Mengajar</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola pengajuan perizinan dan pantau proses verifikasi berjenjang</p>
        </div>
        <div>
            <a href="{{ route('guru.izin.create') }}" class="h-11 px-6 bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-sm font-bold transition-all flex items-center space-x-2 shadow-xs hover:shadow-md cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Ajukan Izin Baru</span>
            </a>
        </div>
    </div>

    <!-- TABEL RIWAYAT IZIN GURU -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-3.5 py-2.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-700"></i>
                <span>Daftar Pengajuan Izin Saya ({{ $daftarIzin->count() }})</span>
            </h3>
        </div>

        <!-- DESKTOP TABLE VIEW (TETAP PERSIS SEPERTI SEBELUMNYA) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-white text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-2 px-3 w-40">Tanggal Izin</th>
                        <th class="py-2 px-3">Alasan & Keterangan</th>
                        <th class="py-2 px-3 text-center w-24">Waka Kurikulum</th>
                        <th class="py-2 px-3 text-center w-24">Waka SDM</th>
                        <th class="py-2 px-3 text-center w-24">Kepala Sekolah</th>
                        <th class="py-2 px-3 text-center w-28">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($daftarIzin as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2 px-3 text-xs font-semibold text-slate-900">
                                <div>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->locale('id')->isoFormat('D MMM Y') }}</div>
                                <div class="text-slate-400 font-normal text-[10px]">s/d {{ \Carbon\Carbon::parse($item->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</div>
                            </td>
                            <td class="py-2 px-3 text-xs">
                                <span class="font-bold text-slate-900 block">{{ $item->alasan }}</span>
                                <span class="text-slate-500">{{ $item->keterangan ?? '-' }}</span>
                                @if($item->catatan_penolakan)
                                    <span class="text-rose-600 block mt-0.5 text-[10px]">Catatan: {{ $item->catatan_penolakan }}</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 text-center">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $item->status_waka === 'Disetujui' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($item->status_waka === 'Ditolak' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    {{ $item->status_waka }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-center">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $item->status_sdm === 'Disetujui' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($item->status_sdm === 'Ditolak' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    {{ $item->status_sdm }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-center">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $item->status_kepsek === 'Disetujui' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($item->status_kepsek === 'Ditolak' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    {{ $item->status_kepsek }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-center">
                                @if($item->status_akhir === 'Disetujui')
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold rounded">
                                        ✓ Disetujui
                                    </span>
                                @elseif($item->status_akhir === 'Ditolak')
                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold rounded">
                                        ✕ Ditolak
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold rounded">
                                        ⏳ Berproses
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 italic text-xs">
                                Anda belum memiliki riwayat pengajuan izin mengajar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- MOBILE ADAPTIVE CARD VIEW (KHUSUS MOBILE) -->
        <div class="block md:hidden divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($daftarIzin as $item)
                <div class="p-3.5 space-y-2.5 bg-white dark:bg-[#1E2538]">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-xs font-bold text-slate-900 dark:text-white flex items-center space-x-1.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-indigo-500"></i>
                                <span>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->locale('id')->isoFormat('D MMM Y') }}</span>
                            </span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">s/d {{ \Carbon\Carbon::parse($item->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</span>
                        </div>
                        <div>
                            @if($item->status_akhir === 'Disetujui')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-[10px] font-bold rounded-lg">
                                    ✓ Disetujui
                                </span>
                            @elseif($item->status_akhir === 'Ditolak')
                                <span class="px-2 py-0.5 bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-[10px] font-bold rounded-lg">
                                    ✕ Ditolak
                                </span>
                            @else
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-[10px] font-bold rounded-lg">
                                    ⏳ Berproses
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-sm text-slate-900 dark:text-white leading-snug">{{ $item->alasan }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">{{ $item->keterangan ?? '-' }}</p>
                        @if($item->catatan_penolakan)
                            <div class="mt-2 p-2 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-lg text-xs text-rose-700 dark:text-rose-300">
                                <span class="font-bold">Catatan Penolakan:</span> {{ $item->catatan_penolakan }}
                            </div>
                        @endif
                    </div>

                    <!-- Approval Tracker Mini Grid -->
                    <div class="pt-1">
                        <div class="bg-slate-50 dark:bg-slate-800/60 p-2 rounded-xl border border-slate-100 dark:border-slate-800 grid grid-cols-3 gap-1 text-center">
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase block">Kurikulum</span>
                                <span class="text-[10px] font-bold {{ $item->status_waka === 'Disetujui' ? 'text-emerald-600' : ($item->status_waka === 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">
                                    {{ $item->status_waka }}
                                </span>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase block">SDM</span>
                                <span class="text-[10px] font-bold {{ $item->status_sdm === 'Disetujui' ? 'text-emerald-600' : ($item->status_sdm === 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">
                                    {{ $item->status_sdm }}
                                </span>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase block">Kepsek</span>
                                <span class="text-[10px] font-bold {{ $item->status_kepsek === 'Disetujui' ? 'text-emerald-600' : ($item->status_kepsek === 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">
                                    {{ $item->status_kepsek }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-400 italic text-xs">
                    Anda belum memiliki riwayat pengajuan izin mengajar.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
