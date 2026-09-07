@extends('layouts.app')

@section('title', 'Pos Satpam - Validasi Gerbang Sekolah')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Pos Keamanan (Satpam)</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Validasi izin siswa keluar-masuk gerbang sekolah real-time • <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($hariIni)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200/60 shadow-2xs space-x-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                <span>Gerbang Utama Online</span>
            </span>
        </div>
    </div>

    <!-- Quick Search Bar at Gate -->
    <div class="bg-white border border-slate-200 rounded-xl p-3 shadow-xs">
        <form action="{{ route('satpam.dashboard') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS / Nama Siswa di Gerbang..." 
                    class="block w-full pl-8 pr-3 h-8 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538] transition-all">
            </div>
            <div class="flex space-x-1.5">
                <button type="submit" class="h-8.5 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                    <i data-lucide="scan" class="w-3.5 h-3.5"></i>
                    <span>Cari Izin</span>
                </button>
                @if(request('search'))
                    <a href="{{ route('satpam.dashboard') }}" class="h-8.5 px-3 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold flex items-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Peringatan Siswa Terlambat Kembali (Overdue Alert) -->
    @if($terlambatKembali->isNotEmpty())
        <div class="p-3.5 bg-rose-50/70 border-l-4 border-rose-600 rounded-r-xl text-rose-900 shadow-2xs space-y-1.5">
            <div class="flex items-center space-x-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 animate-bounce"></i>
                <h3 class="font-bold text-xs">Peringatan: {{ $terlambatKembali->count() }} Siswa Belum Kembali Melewati Batas Waktu!</h3>
            </div>
            <p class="text-[11px] text-rose-700">Siswa di bawah ini telah melewati jam estimasi kembali. Segera hubungi guru piket atau wali kelas jika diperlukan.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 pt-1">
                @foreach($terlambatKembali as $tk)
                    <div class="p-2.5 bg-white border border-rose-200 rounded-lg text-xs flex items-center justify-between shadow-2xs">
                        <div>
                            <p class="font-bold text-slate-800 text-xs">{{ $tk->siswa ? $tk->siswa->nama_siswa : '-' }}</p>
                            <p class="text-[10px] text-slate-500">Kelas: {{ $tk->siswa && $tk->siswa->kelas ? $tk->siswa->kelas->nama_kelas : '-' }}</p>
                            <p class="text-[10px] text-rose-600 font-semibold mt-0.5">Batas: {{ substr($tk->jam_kembali_rencana, 0, 5) }} WIB</p>
                        </div>
                        <form action="{{ route('satpam.dispen.kembali', $tk->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="h-6.5 px-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md font-semibold text-[11px] transition-colors cursor-pointer">
                                Kembali
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- GRID 2 KOLOM: SIAP KELUAR vs SEDANG DI LUAR -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        
        <!-- KOLOM 1: SISWA DENGAN IZIN AKTIF SIAP KELUAR -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center space-x-1.5">
                    <i data-lucide="shield-check" class="w-4 h-4 text-slate-600"></i>
                    <h3 class="font-bold text-slate-900 text-xs">Izin Sah Siap Keluar ({{ $siapKeluar->count() }})</h3>
                </div>
                <span class="text-[10px] text-slate-400 font-semibold">Telah Disetujui</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($siapKeluar as $item)
                    <div class="p-3 hover:bg-slate-50/80 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-0.5">
                            <div class="flex items-center space-x-1.5">
                                <span class="font-bold text-xs text-slate-900">{{ $item->siswa ? $item->siswa->nama_siswa : '-' }}</span>
                                <span class="px-1.5 py-0.5 bg-slate-100 text-slate-700 text-[10px] font-bold rounded">
                                    {{ $item->siswa && $item->siswa->kelas ? $item->siswa->kelas->nama_kelas : '-' }}
                                </span>
                            </div>
                            <p class="text-[10px] text-slate-400">NIS: <span class="font-semibold text-slate-600">{{ $item->nis }}</span></p>
                            <p class="text-xs text-slate-600"><span class="font-semibold text-slate-700">Keperluan:</span> {{ $item->keperluan }}</p>
                            <p class="text-[11px] text-slate-500 font-medium">
                                @if($item->jam_ke) <span class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded font-bold mr-1">{{ $item->jam_ke }}</span> @endif
                                Estimasi: {{ substr($item->jam_keluar_rencana, 0, 5) }} s/d {{ $item->jam_kembali_rencana ? substr($item->jam_kembali_rencana, 0, 5) : 'Selesai' }}
                            </p>
                        </div>
                        <div>
                            <form action="{{ route('satpam.dispen.keluar', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto h-7 px-3 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors shadow-2xs flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="log-out" class="w-3 h-3"></i>
                                    <span>Konfirmasi Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 italic text-xs">
                        <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                        Tidak ada siswa dengan status izin siap keluar saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KOLOM 2: SISWA YANG SEDANG DI LUAR LINGKUNGAN SEKOLAH -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-200 bg-amber-50/40 flex items-center justify-between">
                <div class="flex items-center space-x-1.5">
                    <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                    <h3 class="font-bold text-slate-900 text-xs">Sedang di Luar Sekolah ({{ $sedangDiLuar->count() }})</h3>
                </div>
                <span class="text-[10px] text-amber-700 font-semibold">Menunggu Kembali</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($sedangDiLuar as $item)
                    <div class="p-3 hover:bg-slate-50/80 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-0.5">
                            <div class="flex items-center space-x-1.5">
                                <span class="font-bold text-xs text-slate-900">{{ $item->siswa ? $item->siswa->nama_siswa : '-' }}</span>
                                <span class="px-1.5 py-0.5 bg-slate-100 text-slate-700 text-[10px] font-bold rounded">
                                    {{ $item->siswa && $item->siswa->kelas ? $item->siswa->kelas->nama_kelas : '-' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-600"><span class="font-semibold text-slate-700">Keperluan:</span> {{ $item->keperluan }}</p>
                            <div class="flex items-center space-x-2 text-[11px] text-slate-500">
                                <span>Keluar: <b class="text-slate-800">{{ substr($item->jam_keluar_aktual, 0, 5) ?? '-' }}</b></span>
                                <span>•</span>
                                <span>Batas: <b class="text-slate-800">{{ $item->jam_kembali_rencana ? substr($item->jam_kembali_rencana, 0, 5) : 'Tidak kembali' }}</b></span>
                            </div>
                        </div>
                        <div>
                            <form action="{{ route('satpam.dispen.kembali', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto h-7 px-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors shadow-2xs flex items-center justify-center space-x-1 cursor-pointer">
                                    <i data-lucide="log-in" class="w-3 h-3"></i>
                                    <span>Konfirmasi Kembali</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 italic text-xs">
                        <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
                        Tidak ada siswa yang sedang berada di luar lingkungan sekolah.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- TABEL RIWAYAT SISWA SUDAH KEMBALI HARI INI -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5">
                <i data-lucide="history" class="w-4 h-4 text-slate-500"></i>
                <span>Riwayat Siswa Selesai Izin Hari Ini ({{ $sudahKembali->count() }})</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-2 px-3.5 w-28">NIS</th>
                        <th class="py-2 px-3.5">Nama Siswa</th>
                        <th class="py-2 px-3.5 w-28">Kelas</th>
                        <th class="py-2 px-3.5">Keperluan</th>
                        <th class="py-2 px-3.5 w-44">Waktu Keluar / Kembali</th>
                        <th class="py-2 px-3.5 text-center w-28">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($sudahKembali as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2 px-3.5 font-semibold text-slate-600 text-xs">{{ $item->nis }}</td>
                            <td class="py-2 px-3.5 font-bold text-slate-800 text-xs">{{ $item->siswa ? $item->siswa->nama_siswa : '-' }}</td>
                            <td class="py-2 px-3.5"><span class="px-2 py-0.5 bg-slate-100 rounded text-xs font-semibold border border-slate-200/60">{{ $item->siswa && $item->siswa->kelas ? $item->siswa->kelas->nama_kelas : '-' }}</span></td>
                            <td class="py-2 px-3.5 text-xs text-slate-600">{{ $item->keperluan }}</td>
                            <td class="py-2 px-3.5 text-xs">
                                <div>Keluar: <span class="font-bold text-slate-800">{{ substr($item->jam_keluar_aktual, 0, 5) ?? '-' }}</span></div>
                                <div class="mt-0.5">Kembali: <span class="font-bold text-emerald-700">{{ substr($item->jam_kembali_aktual, 0, 5) ?? '-' }}</span></div>
                            </td>
                            <td class="py-2 px-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md border border-emerald-200/60">
                                    ✓ Sudah Kembali
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 italic text-xs">
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
