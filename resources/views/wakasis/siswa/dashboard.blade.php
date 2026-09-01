@extends('layouts.app')

@section('title', 'Kesiswaan Dashboard - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Dashboard Wakil Kesiswaan (Siswa)</h1>
            <p class="text-xs text-gray-500 mt-1">
                Proses perizinan dan dispensasi keluar lingkungan sekolah siswa
            </p>
        </div>
    </div>

    <!-- BAGIAN 1: DAFTAR PENDING (MENUNGGU ACC) -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] bg-amber-50/30 flex items-center justify-between">
            <h3 class="font-bold text-amber-900 text-base flex items-center space-x-2">
                <i data-lucide="bell" class="w-5 h-5 text-amber-600 animate-bounce"></i>
                <span>Menunggu Persetujuan ({{ count($pendingDispen) }} Pengajuan)</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6 w-48">Siswa</th>
                        <th class="py-4 px-6 w-48">Rencana Waktu</th>
                        <th class="py-4 px-6">Keperluan / Alasan</th>
                        <th class="py-4 px-6 text-center w-80">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse ($pendingDispen as $d)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-[#1E2538]">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">NISN: {{ $d->siswa ? $d->siswa->nisn ?? '-' : '-' }} | NIS: {{ $d->nis }}</div>
                                <div class="text-xs text-[#405078] font-semibold">{{ $d->siswa && $d->siswa->kelas ? $d->siswa->kelas->nama_kelas : '-' }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs leading-normal">
                                @if($d->jam_ke)
                                    <div class="font-bold text-[#1E2538] mb-0.5">{{ $d->jam_ke }}</div>
                                @endif
                                <div class="flex items-center space-x-1.5">
                                    <span class="font-semibold text-gray-700">Keluar:</span>
                                    <span class="text-gray-500 font-medium">{{ substr($d->jam_keluar_rencana, 0, 5) ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-1.5 mt-0.5">
                                    <span class="font-semibold text-gray-700">Kembali:</span>
                                    <span class="text-gray-500 font-medium">{{ $d->jam_kembali_rencana ? substr($d->jam_kembali_rencana, 0, 5) : 'Tidak kembali' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-gray-700 font-medium leading-relaxed">{{ $d->keperluan }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <!-- Approve Form -->
                                    <form action="{{ route('wakasis.siswa.dispen.approve', $d->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center space-x-1 cursor-pointer">
                                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                            <span>Setujui</span>
                                        </button>
                                    </form>

                                    <!-- Reject Form -->
                                    <form action="{{ route('wakasis.siswa.dispen.reject', $d->id) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        <input type="text" name="catatan_wakasis" placeholder="Alasan tolak..." required
                                            class="px-2.5 py-1.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-lg text-xs text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                                        <button type="submit" class="px-3.5 py-2 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 rounded-xl text-xs font-bold transition-colors flex items-center space-x-1 cursor-pointer">
                                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                            <span>Tolak</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400 italic">
                                <i data-lucide="check-circle" class="w-8 h-8 mx-auto mb-2 text-emerald-500"></i>
                                Bersih! Tidak ada pengajuan dispensasi yang menunggu persetujuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BAGIAN 2: RIWAYAT DISPENSASI SISWA (HISTORY) -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB]">
            <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                <i data-lucide="history" class="w-5 h-5 text-[#405078]"></i>
                <span>Riwayat & Arsip Keputusan Dispensasi</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6 w-32">Tanggal</th>
                        <th class="py-4 px-6 w-48">Siswa</th>
                        <th class="py-4 px-6 w-48">Jam Keluar/Kembali</th>
                        <th class="py-4 px-6">Keperluan</th>
                        <th class="py-4 px-6 w-32 text-center">Status</th>
                        <th class="py-4 px-6">Catatan Wakasis / Diproses</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse ($historyDispen as $d)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 font-semibold text-gray-500">{{ $d->tanggal }}</td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-[#1E2538]">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">NIS: {{ $d->nis }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs leading-normal">
                                <div class="flex items-center space-x-1.5">
                                    <span class="font-semibold text-gray-700">Keluar:</span>
                                    <span class="text-gray-500">{{ substr($d->jam_keluar_rencana, 0, 5) ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-1.5 mt-0.5">
                                    <span class="font-semibold text-gray-700">Kembali:</span>
                                    <span class="text-gray-500">{{ $d->jam_kembali_rencana ? substr($d->jam_kembali_rencana, 0, 5) : 'Tidak kembali' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500 max-w-xs truncate" title="{{ $d->keperluan }}">{{ $d->keperluan }}</td>
                            <td class="py-4 px-6 text-center">
                                @if ($d->status === 'Disetujui')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        <span>Disetujui</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-700 text-xs font-semibold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        <span>Ditolak</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500">
                                @if($d->catatan_wakasis)
                                    <div class="italic text-rose-600 font-medium">"{{ $d->catatan_wakasis }}"</div>
                                @endif
                                <div class="text-[10px] text-gray-400 mt-1 flex items-center space-x-1">
                                    <i data-lucide="user-check" class="w-3 h-3 text-gray-400"></i>
                                    <span>Diproses oleh: {{ $d->disetujuiOleh ? $d->disetujuiOleh->username : '-' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 italic">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                Belum ada riwayat keputusan dispensasi siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION FOOTER -->
        @if ($historyDispen->hasPages())
            <div class="px-6 py-4 bg-[#F8FAFC] border-t border-[#D1D9EB] flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Menampilkan halaman <span class="font-semibold text-[#1E2538]">{{ $historyDispen->currentPage() }}</span> dari <span class="font-semibold text-[#1E2538]">{{ $historyDispen->lastPage() }}</span>
                </div>
                <div class="inline-flex space-x-1.5">
                    @if ($historyDispen->previousPageUrl())
                        <a href="{{ $historyDispen->previousPageUrl() }}" class="px-3.5 py-1.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
                            <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                            <span>Sebelumnya</span>
                        </a>
                    @endif
                    @if ($historyDispen->nextPageUrl())
                        <a href="{{ $historyDispen->nextPageUrl() }}" class="px-3.5 py-1.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
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
