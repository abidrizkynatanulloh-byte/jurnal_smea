@extends('layouts.app')

@section('title', 'Kesiswaan Dashboard - Jurnal Esemkita')

@section('content')
<div class="space-y-5">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Dashboard Kesiswaan (Dispensasi Siswa)</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Proses perizinan dan dispensasi keluar lingkungan sekolah siswa
            </p>
        </div>
    </div>

    <!-- BAGIAN 1: DAFTAR PENDING (MENUNGGU ACC) -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 bg-amber-50/40 flex items-center justify-between">
            <h3 class="font-bold text-amber-900 text-xs flex items-center space-x-1.5">
                <i data-lucide="bell" class="w-4 h-4 text-amber-600 animate-bounce"></i>
                <span>Menunggu Persetujuan ({{ count($pendingDispen) }} Pengajuan)</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-white text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                        <th class="py-2 px-3.5 w-48">Siswa</th>
                        <th class="py-2 px-3.5 w-44">Rencana Waktu</th>
                        <th class="py-2 px-3.5">Keperluan / Alasan</th>
                        <th class="py-2 px-3.5 text-center w-72">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($pendingDispen as $d)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2 px-3.5">
                                <div class="font-bold text-slate-900">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</div>
                                <div class="text-[11px] text-slate-500">NISN: {{ $d->siswa ? $d->siswa->nisn ?? '-' : '-' }} | NIS: {{ $d->nis }}</div>
                                <div class="text-[11px] text-indigo-600 font-semibold">{{ $d->siswa && $d->siswa->kelas ? $d->siswa->kelas->nama_kelas : '-' }}</div>
                            </td>
                            <td class="py-2 px-3.5 text-xs leading-normal">
                                @if($d->jam_ke)
                                    <div class="font-bold text-slate-900 mb-0.5">{{ $d->jam_ke }}</div>
                                @endif
                                <div class="flex items-center space-x-1.5 text-[11px]">
                                    <span class="font-semibold text-slate-600">Keluar:</span>
                                    <span class="text-slate-800 font-medium">{{ substr($d->jam_keluar_rencana, 0, 5) ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-1.5 text-[11px]">
                                    <span class="font-semibold text-slate-600">Kembali:</span>
                                    <span class="text-slate-800 font-medium">{{ $d->jam_kembali_rencana ? substr($d->jam_kembali_rencana, 0, 5) : 'Tidak kembali' }}</span>
                                </div>
                            </td>
                            <td class="py-2 px-3.5 text-slate-700 font-medium leading-relaxed">{{ $d->keperluan }}</td>
                            <td class="py-2 px-3.5 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Approve Form -->
                                    <form action="{{ route('wakasis.siswa.dispen.approve', $d->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="h-7 px-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-semibold shadow-2xs flex items-center space-x-1 cursor-pointer transition-colors">
                                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                            <span>Setujui</span>
                                        </button>
                                    </form>

                                    <!-- Reject Form -->
                                    <form action="{{ route('wakasis.siswa.dispen.reject', $d->id) }}" method="POST" class="flex items-center space-x-1.5">
                                        @csrf
                                        <input type="text" name="catatan_wakasis" placeholder="Alasan tolak..." required
                                            class="h-7 px-2.5 bg-white border border-slate-200 rounded-md text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-all">
                                        <button type="submit" class="h-7 px-2.5 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 rounded-md text-xs font-semibold transition-colors flex items-center space-x-1 cursor-pointer">
                                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                            <span>Tolak</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400 italic text-xs">
                                <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1 text-emerald-500"></i>
                                Bersih! Tidak ada pengajuan dispensasi yang menunggu persetujuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BAGIAN 2: RIWAYAT DISPENSASI SISWA (HISTORY) -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5">
                <i data-lucide="history" class="w-4 h-4 text-slate-500"></i>
                <span>Riwayat & Arsip Keputusan Dispensasi</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-white text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                        <th class="py-2 px-3.5 w-28">Tanggal</th>
                        <th class="py-2 px-3.5 w-44">Siswa</th>
                        <th class="py-2 px-3.5 w-44">Jam Keluar/Kembali</th>
                        <th class="py-2 px-3.5">Keperluan</th>
                        <th class="py-2 px-3.5 w-28 text-center">Status</th>
                        <th class="py-2 px-3.5">Catatan Wakasis / Diproses</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($historyDispen as $d)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2 px-3.5 font-semibold text-slate-600">{{ $d->tanggal }}</td>
                            <td class="py-2 px-3.5">
                                <div class="font-bold text-slate-900">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</div>
                                <div class="text-[11px] text-slate-400">NIS: {{ $d->nis }}</div>
                            </td>
                            <td class="py-2 px-3.5 text-xs leading-normal">
                                <div class="flex items-center space-x-1 text-[11px]">
                                    <span class="font-semibold text-slate-600">Keluar:</span>
                                    <span class="text-slate-800">{{ substr($d->jam_keluar_rencana, 0, 5) ?? '-' }}</span>
                                </div>
                                <div class="flex items-center space-x-1 text-[11px]">
                                    <span class="font-semibold text-slate-600">Kembali:</span>
                                    <span class="text-slate-800">{{ $d->jam_kembali_rencana ? substr($d->jam_kembali_rencana, 0, 5) : 'Tidak kembali' }}</span>
                                </div>
                            </td>
                            <td class="py-2 px-3.5 text-xs text-slate-700 max-w-xs truncate" title="{{ $d->keperluan }}">{{ $d->keperluan }}</td>
                            <td class="py-2 px-3.5 text-center">
                                @if ($d->status === 'Disetujui')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-[11px] font-semibold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        <span>Disetujui</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200/60 text-[11px] font-semibold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        <span>Ditolak</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 px-3.5 text-xs text-slate-600">
                                @if($d->catatan_wakasis)
                                    <div class="italic text-rose-600 font-medium">"{{ $d->catatan_wakasis }}"</div>
                                @endif
                                <div class="text-[10px] text-slate-400 mt-0.5 flex items-center space-x-1">
                                    <i data-lucide="user-check" class="w-3 h-3 text-slate-400"></i>
                                    <span>Diproses oleh: {{ $d->disetujuiOleh ? $d->disetujuiOleh->username : '-' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 italic text-xs">
                                <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                                Belum ada riwayat keputusan dispensasi siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION FOOTER -->
        <x-pagination-bar :paginator="$historyDispen" />
    </div>
</div>
@endsection
