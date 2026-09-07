@extends('layouts.app')

@section('title', 'Persetujuan Izin Guru - Waka Kurikulum & SDM')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Verifikasi Izin Guru (Waka Kurikulum & SDM)</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Alur verifikasi berjenjang pengajuan izin ketidakhadiran tenaga pengajar
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-[#1E2538] text-xs font-bold border border-blue-200/60 shadow-2xs space-x-1.5">
                <i data-lucide="check-square" class="w-3.5 h-3.5 text-blue-600"></i>
                <span>Panel Pimpinan Bidang</span>
            </span>
        </div>
    </div>

    <!-- 2 KOLOM APPROVAL: TAHAP 1 (WAKA KURIKULUM) & TAHAP 2 (SDM) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        
        <!-- KOLOM 1: TAHAP 1 - WAKA KURIKULUM -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center space-x-1.5">
                    <i data-lucide="book-check" class="w-4 h-4 text-slate-600"></i>
                    <h3 class="font-bold text-slate-900 text-xs">Tahap 1: Waka Kurikulum ({{ $menungguWaka->count() }})</h3>
                </div>
                <span class="text-[10px] font-bold text-slate-700 bg-slate-200/80 px-2 py-0.5 rounded-full">KBM</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($menungguWaka as $mw)
                    <div class="p-3.5 space-y-2.5">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-xs text-slate-900">{{ $mw->guru ? $mw->guru->nama_guru : '-' }}</h4>
                                <p class="text-[10px] text-slate-400">NIP: {{ $mw->guru ? $mw->guru->nip : '-' }}</p>
                            </div>
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-semibold rounded-md border border-amber-200/60">
                                Menunggu Verifikasi
                            </span>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 p-2.5 rounded-lg text-xs space-y-0.5">
                            <p><span class="font-semibold text-slate-500">Periode:</span> {{ \Carbon\Carbon::parse($mw->tanggal_mulai)->locale('id')->isoFormat('D MMM') }} s/d {{ \Carbon\Carbon::parse($mw->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</p>
                            <p><span class="font-semibold text-slate-500">Alasan:</span> <span class="font-bold text-slate-800">{{ $mw->alasan }}</span></p>
                            @if($mw->kelas_terdampak)
                                <p><span class="font-semibold text-slate-500">Kelas Terdampak:</span> {{ $mw->kelas_terdampak }}</p>
                            @endif
                            <div class="pt-0.5">
                                @if($mw->bukti_foto)
                                    <a href="{{ asset('storage/' . $mw->bukti_foto) }}" target="_blank" class="inline-flex items-center space-x-1 text-[11px] text-slate-700 hover:text-slate-900 font-bold bg-slate-100 hover:bg-slate-200 border border-slate-200 px-2 py-0.5 rounded-md transition-colors">
                                        <i data-lucide="image" class="w-3 h-3 text-slate-500"></i>
                                        <span>Lihat Bukti Surat</span>
                                    </a>
                                @else
                                    <span class="text-[10px] text-rose-500 font-bold">Tanpa Bukti Foto</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 pt-0.5">
                            <form action="{{ route('wakasis.guru.approve.waka', $mw->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full h-7 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors shadow-2xs flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="check" class="w-3 h-3"></i>
                                    <span>Setujui (ke SDM)</span>
                                </button>
                            </form>

                            <form action="{{ route('wakasis.guru.reject.waka', $mw->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full h-7 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                    <span>Tolak Izin</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 italic text-xs">
                        Tidak ada pengajuan izin yang menunggu persetujuan Waka Kurikulum.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KOLOM 2: TAHAP 2 - BAGIAN SDM / KEPEGAWAIAN -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 bg-blue-50/40 flex items-center justify-between">
                <div class="flex items-center space-x-1.5">
                    <i data-lucide="users" class="w-4 h-4 text-blue-600"></i>
                    <h3 class="font-bold text-slate-900 text-xs">Tahap 2: Bagian SDM ({{ $menungguSdm->count() }})</h3>
                </div>
                <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">Kepegawaian</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($menungguSdm as $ms)
                    <div class="p-3.5 space-y-2.5">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-xs text-slate-900">{{ $ms->guru ? $ms->guru->nama_guru : '-' }}</h4>
                                <p class="text-[10px] text-slate-400">NIP: {{ $ms->guru ? $ms->guru->nip : '-' }}</p>
                            </div>
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-semibold rounded-md border border-emerald-200/60">
                                Disetujui Waka Kurikulum
                            </span>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 p-2.5 rounded-lg text-xs space-y-0.5">
                            <p><span class="font-semibold text-slate-500">Periode:</span> {{ \Carbon\Carbon::parse($ms->tanggal_mulai)->locale('id')->isoFormat('D MMM') }} s/d {{ \Carbon\Carbon::parse($ms->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</p>
                            <p><span class="font-semibold text-slate-500">Alasan:</span> <span class="font-bold text-slate-800">{{ $ms->alasan }}</span></p>
                        </div>

                        <div class="flex items-center space-x-2 pt-0.5">
                            <form action="{{ route('wakasis.guru.approve.sdm', $ms->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full h-7 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-colors shadow-2xs flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="check" class="w-3 h-3"></i>
                                    <span>Setujui (ke Kepsek)</span>
                                </button>
                            </form>

                            <form action="{{ route('wakasis.guru.reject.sdm', $ms->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full h-7 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                    <span>Tolak Izin</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 italic text-xs">
                        Tidak ada pengajuan izin yang menunggu verifikasi SDM.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- TABEL RIWAYAT PERSETUJUAN KESELURUHAN -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5">
                <i data-lucide="history" class="w-4 h-4 text-slate-500"></i>
                <span>Riwayat Keseluruhan Pengajuan Izin Tenaga Pendidik</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-2 px-3.5">Nama Guru</th>
                        <th class="py-2 px-3.5">Periode & Alasan</th>
                        <th class="py-2 px-3.5 text-center w-32">Waka Kurikulum</th>
                        <th class="py-2 px-3.5 text-center w-32">SDM</th>
                        <th class="py-2 px-3.5 text-center w-32">Kepala Sekolah</th>
                        <th class="py-2 px-3.5 text-center w-28">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($riwayatIzin as $r)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2 px-3.5 font-bold text-slate-800 text-xs">
                                {{ $r->guru ? $r->guru->nama_guru : '-' }}
                                <span class="block font-normal text-slate-400 text-[10px]">NIP: {{ $r->guru ? $r->guru->nip : '-' }}</span>
                            </td>
                            <td class="py-2 px-3.5 text-xs">
                                <span class="font-semibold text-slate-800">{{ $r->alasan }}</span>
                                <span class="block text-slate-400 text-[10px]">{{ \Carbon\Carbon::parse($r->tanggal_mulai)->locale('id')->isoFormat('D MMM') }} - {{ \Carbon\Carbon::parse($r->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</span>
                            </td>
                            <td class="py-2 px-3.5 text-center text-xs">
                                <span class="font-bold {{ $r->status_waka === 'Disetujui' ? 'text-emerald-600' : ($r->status_waka === 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">{{ $r->status_waka }}</span>
                            </td>
                            <td class="py-2 px-3.5 text-center text-xs">
                                <span class="font-bold {{ $r->status_sdm === 'Disetujui' ? 'text-emerald-600' : ($r->status_sdm === 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">{{ $r->status_sdm }}</span>
                            </td>
                            <td class="py-2 px-3.5 text-center text-xs">
                                <span class="font-bold {{ $r->status_kepsek === 'Disetujui' ? 'text-emerald-600' : ($r->status_kepsek === 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">{{ $r->status_kepsek }}</span>
                            </td>
                            <td class="py-2 px-3.5 text-center">
                                @if($r->status_akhir === 'Disetujui')
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md border border-emerald-200/60">Disetujui</span>
                                @elseif($r->status_akhir === 'Ditolak')
                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-bold rounded-md border border-rose-200/60">Ditolak</span>
                                @else
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-md border border-blue-200/60">Proses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 italic text-xs">
                                Belum ada riwayat perizinan guru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
