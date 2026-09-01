@extends('layouts.app')

@section('title', 'Persetujuan Izin Guru - Waka Kurikulum & SDM')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Verifikasi Izin Guru (Waka Kurikulum & SDM)</h1>
            <p class="text-xs text-gray-500 mt-1">
                Alur verifikasi berjenjang pengajuan izin ketidakhadiran tenaga pengajar
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-blue-50 text-[#405078] text-xs font-bold border border-[#D1D9EB] shadow-xs space-x-1.5">
                <i data-lucide="check-square" class="w-3.5 h-3.5"></i>
                <span>Panel Pimpinan Bidang</span>
            </span>
        </div>
    </div>

    <!-- 2 KOLOM APPROVAL: TAHAP 1 (WAKA KURIKULUM) & TAHAP 2 (SDM) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- KOLOM 1: TAHAP 1 - WAKA KURIKULUM -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#D1D9EB] bg-[#405078]/5 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="book-check" class="w-5 h-5 text-[#405078]"></i>
                    <h3 class="font-bold text-[#1E2538] text-base">Tahap 1: Waka Kurikulum ({{ $menungguWaka->count() }})</h3>
                </div>
                <span class="text-xs font-bold text-[#405078] bg-[#405078]/10 px-2.5 py-0.5 rounded-full">KBM</span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($menungguWaka as $mw)
                    <div class="p-5 space-y-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-[#1E2538]">{{ $mw->guru ? $mw->guru->nama_guru : '-' }}</h4>
                                <p class="text-xs text-gray-500">NIP: {{ $mw->guru ? $mw->guru->nip : '-' }}</p>
                            </div>
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-lg">
                                Menunggu Verifikasi
                            </span>
                        </div>

                        <div class="bg-[#F8FAFC] border border-[#D1D9EB] p-3 rounded-xl text-xs space-y-1">
                            <p><span class="font-semibold text-gray-500">Periode:</span> {{ \Carbon\Carbon::parse($mw->tanggal_mulai)->locale('id')->isoFormat('D MMM') }} s/d {{ \Carbon\Carbon::parse($mw->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</p>
                            <p><span class="font-semibold text-gray-500">Alasan:</span> <span class="font-bold text-[#1E2538]">{{ $mw->alasan }}</span></p>
                            @if($mw->kelas_terdampak)
                                <p><span class="font-semibold text-gray-500">Kelas Terdampak:</span> {{ $mw->kelas_terdampak }}</p>
                            @endif
                            <div class="pt-1">
                                @if($mw->bukti_foto)
                                    <a href="{{ asset('storage/' . $mw->bukti_foto) }}" target="_blank" class="inline-flex items-center space-x-1 text-xs text-[#405078] hover:underline font-bold bg-[#405078]/10 px-2.5 py-1 rounded-lg">
                                        <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                        <span>Lihat Bukti Foto (Surat)</span>
                                    </a>
                                @else
                                    <span class="text-xs text-rose-500 font-bold">Tanpa Bukti Foto</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 pt-1">
                            <form action="{{ route('wakasis.guru.approve.waka', $mw->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    <span>Setujui (Lanjut ke SDM)</span>
                                </button>
                            </form>

                            <form action="{{ route('wakasis.guru.reject.waka', $mw->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-colors flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    <span>Tolak Izin</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 italic text-xs">
                        Tidak ada pengajuan izin yang menunggu persetujuan Waka Kurikulum.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KOLOM 2: TAHAP 2 - BAGIAN SDM / KEPEGAWAIAN -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#D1D9EB] bg-blue-50/40 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                    <h3 class="font-bold text-[#1E2538] text-base">Tahap 2: Bagian SDM ({{ $menungguSdm->count() }})</h3>
                </div>
                <span class="text-xs font-bold text-blue-700 bg-blue-100 px-2.5 py-0.5 rounded-full">Kepegawaian</span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($menungguSdm as $ms)
                    <div class="p-5 space-y-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-[#1E2538]">{{ $ms->guru ? $ms->guru->nama_guru : '-' }}</h4>
                                <p class="text-xs text-gray-500">NIP: {{ $ms->guru ? $ms->guru->nip : '-' }}</p>
                            </div>
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg">
                                Disetujui Waka Kurikulum
                            </span>
                        </div>

                        <div class="bg-[#F8FAFC] border border-[#D1D9EB] p-3 rounded-xl text-xs space-y-1">
                            <p><span class="font-semibold text-gray-500">Periode:</span> {{ \Carbon\Carbon::parse($ms->tanggal_mulai)->locale('id')->isoFormat('D MMM') }} s/d {{ \Carbon\Carbon::parse($ms->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</p>
                            <p><span class="font-semibold text-gray-500">Alasan:</span> <span class="font-bold text-[#1E2538]">{{ $ms->alasan }}</span></p>
                        </div>

                        <div class="flex items-center space-x-2 pt-1">
                            <form action="{{ route('wakasis.guru.approve.sdm', $ms->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    <span>Setujui (Teruskan ke Kepsek)</span>
                                </button>
                            </form>

                            <form action="{{ route('wakasis.guru.reject.sdm', $ms->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-colors flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    <span>Tolak Izin</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 italic text-xs">
                        Tidak ada pengajuan izin yang menunggu verifikasi SDM.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- TABEL RIWAYAT PERSETUJUAN KESELURUHAN -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
            <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                <i data-lucide="history" class="w-5 h-5 text-[#405078]"></i>
                <span>Riwayat Keseluruhan Pengajuan Izin Tenaga Pendidik</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6">Nama Guru</th>
                        <th class="py-4 px-6">Periode & Alasan</th>
                        <th class="py-4 px-6 text-center w-36">Waka Kurikulum</th>
                        <th class="py-4 px-6 text-center w-36">SDM</th>
                        <th class="py-4 px-6 text-center w-36">Kepala Sekolah</th>
                        <th class="py-4 px-6 text-center w-32">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse($riwayatIzin as $r)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 font-bold text-[#1E2538] text-xs">
                                {{ $r->guru ? $r->guru->nama_guru : '-' }}
                                <span class="block font-normal text-gray-400">NIP: {{ $r->guru ? $r->guru->nip : '-' }}</span>
                            </td>
                            <td class="py-4 px-6 text-xs">
                                <span class="font-semibold text-[#1E2538]">{{ $r->alasan }}</span>
                                <span class="block text-gray-400">{{ \Carbon\Carbon::parse($r->tanggal_mulai)->locale('id')->isoFormat('D MMM') }} - {{ \Carbon\Carbon::parse($r->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</span>
                            </td>
                            <td class="py-4 px-6 text-center text-xs">
                                <span class="font-bold {{ $r->status_waka === 'Disetujui' ? 'text-emerald-600' : ($r->status_waka === 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">{{ $r->status_waka }}</span>
                            </td>
                            <td class="py-4 px-6 text-center text-xs">
                                <span class="font-bold {{ $r->status_sdm === 'Disetujui' ? 'text-emerald-600' : ($r->status_sdm === 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">{{ $r->status_sdm }}</span>
                            </td>
                            <td class="py-4 px-6 text-center text-xs">
                                <span class="font-bold {{ $r->status_kepsek === 'Disetujui' ? 'text-emerald-600' : ($r->status_kepsek === 'Ditolak' ? 'text-rose-600' : 'text-amber-600') }}">{{ $r->status_kepsek }}</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($r->status_akhir === 'Disetujui')
                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-black rounded-full">Disetujui</span>
                                @elseif($r->status_akhir === 'Ditolak')
                                    <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 text-xs font-black rounded-full">Ditolak</span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-full">Proses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 italic text-xs">
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
