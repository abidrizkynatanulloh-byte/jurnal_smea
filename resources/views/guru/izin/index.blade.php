@extends('layouts.app')

@section('title', 'Riwayat Izin Guru - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-[#405078] hover:underline flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Pengajuan Izin Mengajar</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola pengajuan perizinan dan pantau proses verifikasi berjenjang</p>
        </div>
        <div>
            <a href="{{ route('guru.izin.create') }}" class="px-4 py-2.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-colors flex items-center space-x-1.5 shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Ajukan Izin Baru</span>
            </a>
        </div>
    </div>

    <!-- TABEL RIWAYAT IZIN GURU -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
            <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                <i data-lucide="calendar" class="w-5 h-5 text-[#405078]"></i>
                <span>Daftar Pengajuan Izin Saya ({{ $daftarIzin->count() }})</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6">Periode Izin</th>
                        <th class="py-4 px-6">Alasan & Keterangan</th>
                        <th class="py-4 px-6 text-center w-36">Waka Kurikulum</th>
                        <th class="py-4 px-6 text-center w-36">Bagian SDM</th>
                        <th class="py-4 px-6 text-center w-36">Kepala Sekolah</th>
                        <th class="py-4 px-6 text-center w-32">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse($daftarIzin as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-xs font-semibold text-[#1E2538]">
                                <div>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->locale('id')->isoFormat('D MMM Y') }}</div>
                                <div class="text-gray-400 font-normal">s/d {{ \Carbon\Carbon::parse($item->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs">
                                <span class="font-bold text-[#1E2538] block">{{ $item->alasan }}</span>
                                <span class="text-gray-500">{{ $item->keterangan ?? '-' }}</span>
                                @if($item->catatan_penolakan)
                                    <span class="text-rose-600 block mt-1">Catatan: {{ $item->catatan_penolakan }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $item->status_waka === 'Disetujui' ? 'bg-emerald-50 text-emerald-700' : ($item->status_waka === 'Ditolak' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">
                                    {{ $item->status_waka }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $item->status_sdm === 'Disetujui' ? 'bg-emerald-50 text-emerald-700' : ($item->status_sdm === 'Ditolak' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">
                                    {{ $item->status_sdm }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $item->status_kepsek === 'Disetujui' ? 'bg-emerald-50 text-emerald-700' : ($item->status_kepsek === 'Ditolak' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">
                                    {{ $item->status_kepsek }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($item->status_akhir === 'Disetujui')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-black rounded-full">
                                        ✓ Disetujui
                                    </span>
                                @elseif($item->status_akhir === 'Ditolak')
                                    <span class="px-3 py-1 bg-rose-100 text-rose-800 text-xs font-black rounded-full">
                                        ✕ Ditolak
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-blue-50 text-blue-800 text-xs font-bold rounded-full">
                                        ⏳ Berproses
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 italic text-xs">
                                Anda belum memiliki riwayat pengajuan izin mengajar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
