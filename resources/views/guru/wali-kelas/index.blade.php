@extends('layouts.app')

@section('title', 'Rekap Presensi Wali Kelas - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-[#405078] hover:underline flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Rekapitulasi Presensi Kelas Binaan</h1>
            <p class="text-xs text-gray-500 mt-0.5">
                Monitoring ketidakhadiran siswa dan deteksi dini siswa yang membutuhkan atensi wali kelas
            </p>
        </div>

        <!-- Filter / Info Kelas Binaan -->
        <div>
            @if($daftarKelas->count() > 1)
                <form action="{{ route('guru.wali-kelas') }}" method="GET" class="flex items-center space-x-2">
                    <label class="text-xs font-bold text-gray-500">Pilih Kelas Binaan:</label>
                    <select name="kelas_id" onchange="this.form.submit()"
                        class="px-3.5 py-2 bg-white border border-[#D1D9EB] rounded-xl text-xs font-bold text-[#1E2538] focus:outline-none focus:border-[#405078] shadow-xs cursor-pointer">
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ $kelasAktif && $kelasAktif->id_kelas == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @elseif($kelasAktif)
                <div class="px-4 py-2 bg-white border border-[#D1D9EB] rounded-xl shadow-xs text-xs font-bold text-[#1E2538] flex items-center space-x-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-[#405078]"></i>
                    <span>Kelas Binaan: {{ $kelasAktif->nama_kelas }}</span>
                </div>
            @endif
        </div>
    </div>

    @php
        $siswaBermasalah = $rekapSiswa->where('perlu_atensi', true);
    @endphp

    <!-- PERINGATAN SISWA PERLU ATENSI KHUSUS -->
    @if($siswaBermasalah->isNotEmpty())
        <div class="p-5 bg-rose-50/80 border border-rose-200 rounded-2xl text-rose-900 shadow-sm space-y-2">
            <div class="flex items-center space-x-2">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600"></i>
                <h3 class="font-bold text-sm">Peringatan Wali Kelas: {{ $siswaBermasalah->count() }} Siswa Perlu Atensi (Alpa ≥ 3 Kali)</h3>
            </div>
            <p class="text-xs text-rose-700">Siswa di bawah ini memiliki riwayat alpa/tanpa keterangan yang tinggi. Disarankan untuk berkoordinasi dengan Guru BK atau Orang Tua.</p>
            <div class="flex flex-wrap gap-2 pt-1">
                @foreach($siswaBermasalah as $sb)
                    <span class="px-3 py-1 bg-white border border-rose-200 rounded-xl text-xs font-bold text-rose-800 shadow-xs">
                        {{ $sb['nama_siswa'] }} ({{ $sb['alpa'] }}x Alpa)
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- TABEL REKAPITULASI PRESENSI KELAS -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i data-lucide="users" class="w-5 h-5 text-[#405078]"></i>
                <h3 class="font-bold text-[#1E2538] text-base">
                    Daftar Siswa {{ $kelasAktif ? $kelasAktif->nama_kelas : '' }} ({{ $rekapSiswa->count() }} Siswa)
                </h3>
            </div>
            <span class="text-xs text-gray-400 font-semibold">Semester Berjalan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6 w-16 text-center">No</th>
                        <th class="py-4 px-6 w-32">NIS</th>
                        <th class="py-4 px-6">Nama Lengkap Siswa</th>
                        <th class="py-4 px-6 text-center w-24">Sakit</th>
                        <th class="py-4 px-6 text-center w-24">Izin</th>
                        <th class="py-4 px-6 text-center w-24">Alpa</th>
                        <th class="py-4 px-6 text-center w-24">Dispen</th>
                        <th class="py-4 px-6 text-center w-36">Status Atensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse($rekapSiswa as $idx => $s)
                        <tr class="hover:bg-gray-50/50 transition-colors {{ $s['perlu_atensi'] ? 'bg-rose-50/20' : '' }}">
                            <td class="py-4 px-6 text-center text-xs text-gray-400">{{ $idx + 1 }}</td>
                            <td class="py-4 px-6 text-xs font-semibold text-gray-700">{{ $s['nis'] }}</td>
                            <td class="py-4 px-6 font-bold text-[#1E2538]">{{ $s['nama_siswa'] }}</td>
                            <td class="py-4 px-6 text-center text-xs font-medium text-blue-600">{{ $s['sakit'] }}</td>
                            <td class="py-4 px-6 text-center text-xs font-medium text-amber-600">{{ $s['izin'] }}</td>
                            <td class="py-4 px-6 text-center text-xs font-bold {{ $s['alpa'] > 0 ? 'text-rose-600' : 'text-gray-400' }}">{{ $s['alpa'] }}</td>
                            <td class="py-4 px-6 text-center text-xs font-medium text-[#405078]">{{ $s['dispen'] }}</td>
                            <td class="py-4 px-6 text-center">
                                @if($s['perlu_atensi'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-rose-100 text-rose-800 text-[11px] font-bold rounded-full">
                                        Perlu Tindak Lanjut
                                    </span>
                                @elseif($s['total_absen'] == 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded-full">
                                        Rajin (100%)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-gray-100 text-gray-600 text-[11px] font-medium rounded-full">
                                        Normal
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400 italic text-xs">
                                Tidak ada data siswa di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
