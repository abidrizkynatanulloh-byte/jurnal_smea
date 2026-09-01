@extends('layouts.app')

@section('title', 'Pos Satpam - Validasi Gerbang Sekolah')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Pos Keamanan (Satpam)</h1>
            <p class="text-xs text-gray-500 mt-1">
                Validasi izin siswa keluar-masuk gerbang sekolah real-time • <span class="font-bold text-[#405078]">{{ \Carbon\Carbon::parse($hariIni)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 shadow-xs space-x-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span>Gerbang Utama Online</span>
            </span>
        </div>
    </div>

    <!-- Quick Search Bar at Gate -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl p-4 shadow-sm">
        <form action="{{ route('satpam.dashboard') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4 text-[#8697C3]"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS / Nama Siswa di Gerbang..." 
                    class="block w-full pl-10 pr-4 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="px-5 py-2.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-colors flex items-center space-x-1.5 shadow-sm">
                    <i data-lucide="scan" class="w-4 h-4"></i>
                    <span>Cari Izin</span>
                </button>
                @if(request('search'))
                    <a href="{{ route('satpam.dashboard') }}" class="px-4 py-2.5 border border-[#D1D9EB] hover:bg-gray-50 text-gray-600 rounded-xl text-xs font-semibold flex items-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Peringatan Siswa Terlambat Kembali (Overdue Alert) -->
    @if($terlambatKembali->isNotEmpty())
        <div class="p-5 bg-rose-50/70 border-l-4 border-rose-600 rounded-r-2xl text-rose-900 shadow-sm space-y-2">
            <div class="flex items-center space-x-2">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600 animate-bounce"></i>
                <h3 class="font-bold text-sm">Peringatan: {{ $terlambatKembali->count() }} Siswa Belum Kembali Melewati Batas Waktu!</h3>
            </div>
            <p class="text-xs text-rose-700">Siswa di bawah ini telah melewati jam estimasi kembali. Segera hubungi guru piket atau wali kelas jika diperlukan.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-2">
                @foreach($terlambatKembali as $tk)
                    <div class="p-3 bg-white border border-rose-200 rounded-xl text-xs flex items-center justify-between shadow-xs">
                        <div>
                            <p class="font-bold text-[#1E2538]">{{ $tk->siswa ? $tk->siswa->nama_siswa : '-' }}</p>
                            <p class="text-[11px] text-gray-500">Kelas: {{ $tk->siswa && $tk->siswa->kelas ? $tk->siswa->kelas->nama_kelas : '-' }}</p>
                            <p class="text-[10px] text-rose-600 font-semibold mt-0.5">Batas: {{ substr($tk->jam_kembali_rencana, 0, 5) }} WIB</p>
                        </div>
                        <form action="{{ route('satpam.dispen.kembali', $tk->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[11px] transition-colors cursor-pointer">
                                Sudah Kembali
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- GRID 2 KOLOM: SIAP KELUAR vs SEDANG DI LUAR -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- KOLOM 1: SISWA DENGAN IZIN AKTIF SIAP KELUAR -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#D1D9EB] bg-[#405078]/5 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="shield-check" class="w-5 h-5 text-[#405078]"></i>
                    <h3 class="font-bold text-[#1E2538] text-base">Izin Sah Siap Keluar ({{ $siapKeluar->count() }})</h3>
                </div>
                <span class="text-[11px] text-gray-400 font-semibold">Telah Disetujui Wakasis</span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($siapKeluar as $item)
                    <div class="p-5 hover:bg-gray-50/50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-sm text-[#1E2538]">{{ $item->siswa ? $item->siswa->nama_siswa : '-' }}</span>
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-[10px] font-bold rounded">
                                    {{ $item->siswa && $item->siswa->kelas ? $item->siswa->kelas->nama_kelas : '-' }}
                                </span>
                            </div>
                            <p class="text-[11px] text-gray-500">NISN: <span class="font-semibold text-gray-700">{{ $item->siswa ? $item->siswa->nisn ?? '-' : '-' }}</span> | NIS: <span class="font-semibold text-gray-700">{{ $item->nis }}</span></p>
                            <p class="text-xs text-gray-500 leading-snug"><span class="font-semibold text-gray-700">Keperluan:</span> {{ $item->keperluan }}</p>
                            <p class="text-[11px] text-[#405078] font-semibold">
                                @if($item->jam_ke) <span class="bg-[#405078]/10 text-[#405078] px-2 py-0.5 rounded font-bold mr-1">{{ $item->jam_ke }}</span> @endif
                                Estimasi: {{ substr($item->jam_keluar_rencana, 0, 5) }} s/d {{ $item->jam_kembali_rencana ? substr($item->jam_kembali_rencana, 0, 5) : 'Selesai' }}
                            </p>
                        </div>
                        <div>
                            <form action="{{ route('satpam.dispen.keluar', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center justify-center space-x-1.5 cursor-pointer">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                    <span>Konfirmasi Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 italic text-xs">
                        <i data-lucide="check-circle" class="w-7 h-7 mx-auto mb-1.5 text-gray-300"></i>
                        Tidak ada siswa dengan status izin siap keluar saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KOLOM 2: SISWA YANG SEDANG DI LUAR LINGKUNGAN SEKOLAH -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#D1D9EB] bg-amber-50/40 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                    <h3 class="font-bold text-[#1E2538] text-base">Sedang di Luar Sekolah ({{ $sedangDiLuar->count() }})</h3>
                </div>
                <span class="text-[11px] text-amber-700 font-semibold">Menunggu Kembali</span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($sedangDiLuar as $item)
                    <div class="p-5 hover:bg-gray-50/50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-sm text-[#1E2538]">{{ $item->siswa ? $item->siswa->nama_siswa : '-' }}</span>
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-[10px] font-bold rounded">
                                    {{ $item->siswa && $item->siswa->kelas ? $item->siswa->kelas->nama_kelas : '-' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500"><span class="font-semibold text-gray-700">Keperluan:</span> {{ $item->keperluan }}</p>
                            <div class="flex items-center space-x-3 text-[11px] text-gray-500">
                                <span>Keluar: <b class="text-[#1E2538]">{{ substr($item->jam_keluar_aktual, 0, 5) ?? '-' }}</b></span>
                                <span>•</span>
                                <span>Batas: <b class="text-[#1E2538]">{{ $item->jam_kembali_rencana ? substr($item->jam_kembali_rencana, 0, 5) : 'Tidak kembali' }}</b></span>
                            </div>
                        </div>
                        <div>
                            <form action="{{ route('satpam.dispen.kembali', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center justify-center space-x-1.5 cursor-pointer">
                                    <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                                    <span>Konfirmasi Kembali</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 italic text-xs">
                        <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-gray-300"></i>
                        Tidak ada siswa yang sedang berada di luar lingkungan sekolah.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- TABEL RIWAYAT SISWA SUDAH KEMBALI HARI INI -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
            <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                <i data-lucide="history" class="w-5 h-5 text-[#405078]"></i>
                <span>Riwayat Siswa Selesai Izin Hari Ini ({{ $sudahKembali->count() }})</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6 w-32">NIS</th>
                        <th class="py-4 px-6">Nama Siswa</th>
                        <th class="py-4 px-6 w-32">Kelas</th>
                        <th class="py-4 px-6">Keperluan</th>
                        <th class="py-4 px-6 w-48">Waktu Keluar / Kembali</th>
                        <th class="py-4 px-6 text-center w-32">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse($sudahKembali as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 font-semibold text-[#1E2538]">{{ $item->nis }}</td>
                            <td class="py-4 px-6 font-bold text-[#1E2538]">{{ $item->siswa ? $item->siswa->nama_siswa : '-' }}</td>
                            <td class="py-4 px-6"><span class="px-2 py-1 bg-gray-100 rounded text-xs font-semibold">{{ $item->siswa && $item->siswa->kelas ? $item->siswa->kelas->nama_kelas : '-' }}</span></td>
                            <td class="py-4 px-6 text-xs text-gray-600">{{ $item->keperluan }}</td>
                            <td class="py-4 px-6 text-xs">
                                <div>Keluar: <span class="font-bold text-[#1E2538]">{{ substr($item->jam_keluar_aktual, 0, 5) ?? '-' }}</span></div>
                                <div class="mt-0.5">Kembali: <span class="font-bold text-emerald-700">{{ substr($item->jam_kembali_aktual, 0, 5) ?? '-' }}</span></div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full">
                                    ✓ Sudah Kembali
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 italic text-xs">
                                Belum ada riwayat siswa yang kembali hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
