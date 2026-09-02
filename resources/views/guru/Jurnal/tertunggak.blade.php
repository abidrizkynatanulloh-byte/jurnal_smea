@extends('layouts.app')

@section('title', 'Rincian Jurnal Tertunggak - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-[#405078] hover:underline flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Rincian Sesi Jurnal Belum Diisi / Tertunggak</h1>
            <p class="text-xs text-gray-500 mt-0.5">
                Daftar rincian jadwal mengajar Anda yang belum dilengkapi pengisian jurnal pembelajarannya
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('guru.jurnal.rekap') }}" class="px-4 py-2 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-xs">
                <i data-lucide="history" class="w-4 h-4 text-[#405078]"></i>
                <span>Riwayat Jurnal Terisi</span>
            </a>
        </div>
    </div>

    <!-- SUMMARY BANNER -->
    <div class="p-5 {{ count($daftarTertunggak) > 0 ? 'bg-rose-50/80 border-rose-200' : 'bg-emerald-50/80 border-emerald-200' }} border rounded-2xl flex items-center justify-between">
        <div class="flex items-center space-x-3.5">
            <div class="p-2.5 {{ count($daftarTertunggak) > 0 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }} rounded-xl">
                <i data-lucide="{{ count($daftarTertunggak) > 0 ? 'alert-triangle' : 'check-circle-2' }}" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold {{ count($daftarTertunggak) > 0 ? 'text-rose-900' : 'text-emerald-900' }}">
                    {{ count($daftarTertunggak) > 0 ? 'Terdapat ' . count($daftarTertunggak) . ' Sesi Mengajar Belum Diisi' : 'Luar Biasa! Seluruh Jurnal Mengajar Lengkap' }}
                </h3>
                <p class="text-xs {{ count($daftarTertunggak) > 0 ? 'text-rose-700' : 'text-emerald-700' }} mt-0.5">
                    {{ count($daftarTertunggak) > 0 ? 'Pastikan seluruh sesi pembelajaran dilengkapi jurnal dan bukti foto untuk kepatuhan administrasi KBM.' : 'Tidak ada sesi mengajar yang tertunggak minggu ini.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- TABEL DETAIL SESI TERTUNGGAK -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i data-lucide="calendar-x" class="w-5 h-5 text-rose-600"></i>
                <h3 class="font-bold text-[#1E2538] text-base">Rincian Tanggal, Jam, dan Kelas</h3>
            </div>
            <span class="text-xs font-semibold text-gray-400">Minggu Berjalan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-3.5 px-6 text-center w-14">No</th>
                        <th class="py-3.5 px-6 w-44">Hari & Tanggal</th>
                        <th class="py-3.5 px-6 w-40">Jam Pelajaran</th>
                        <th class="py-3.5 px-6 w-36">Kelas & Ruang</th>
                        <th class="py-3.5 px-6">Mata Pelajaran</th>
                        <th class="py-3.5 px-6 text-center w-36">Status</th>
                        <th class="py-3.5 px-6 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse($daftarTertunggak as $idx => $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-center font-medium text-gray-400 text-xs">{{ $idx + 1 }}</td>
                            <td class="py-4 px-6">
                                <p class="font-bold text-[#1E2538] text-xs">{{ $item['hari'] }}</p>
                                <p class="text-[11px] text-gray-400">{{ \Carbon\Carbon::parse($item['tanggal'])->locale('id')->isoFormat('D MMMM Y') }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 bg-gray-100 rounded-lg text-xs font-bold text-gray-700">
                                    {{ $item['jam_ke'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-[#1E2538] text-xs block">{{ $item['kelas'] }}</span>
                                <span class="text-[11px] text-gray-400">{{ $item['ruangan'] }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-semibold text-[#405078] text-xs">{{ $item['mapel'] }}</p>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if(str_contains($item['keterangan'], 'Sah'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-full">
                                        {{ $item['keterangan'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-rose-50 text-rose-700 text-xs font-bold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></span>
                                        <span>Belum Diisi</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($item['is_today'])
                                    <a href="{{ route('guru.jurnal.create', $item['id_jadwal']) }}"
                                        class="px-3 py-1.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-colors shadow-2xs inline-flex items-center space-x-1">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        <span>Isi Sekarang</span>
                                    </a>
                                @else
                                    <span class="text-[11px] text-gray-400 italic">Lewat Hari</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-400 italic text-xs">
                                <i data-lucide="check-circle" class="w-8 h-8 mx-auto mb-2 text-emerald-500"></i>
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
