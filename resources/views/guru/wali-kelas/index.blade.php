@extends('layouts.app')

@section('title', 'Rekap Presensi Wali Kelas - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <div class="flex items-center space-x-1.5 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Rekapitulasi Presensi Kelas Binaan</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Monitoring ketidakhadiran siswa dan deteksi dini siswa yang membutuhkan atensi wali kelas
            </p>
        </div>

        <!-- Filter / Info Kelas Binaan -->
        <div>
            @if($daftarKelas->count() > 1)
                <form action="{{ route('guru.wali-kelas') }}" method="GET" class="flex items-center space-x-2">
                    <label class="text-xs font-bold text-slate-500">Pilih Kelas:</label>
                    <select name="kelas_id" onchange="this.form.submit()"
                        class="h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#1E2538] shadow-2xs cursor-pointer">
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ $kelasAktif && $kelasAktif->id_kelas == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @elseif($kelasAktif)
                <div class="h-8 px-3 bg-white border border-slate-200 rounded-lg shadow-2xs text-xs font-bold text-slate-800 flex items-center space-x-1.5">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-slate-600"></i>
                    <span>Kelas Binaan: {{ $kelasAktif->nama_kelas }}</span>
                </div>
            @endif
        </div>
    </div>

    @php
        $siswaPengawasan = $rekapSiswa->where('perlu_pengawasan', true);
        $siswaTindak     = $rekapSiswa->where('perlu_tindak', true)->where('perlu_pengawasan', false);
        $siswaAtensi     = $rekapSiswa->where('perlu_atensi', true)->where('perlu_tindak', false)->where('perlu_pengawasan', false);
    @endphp

    {{-- Banner 1: SISWA TERAWASI (Alpa 5 Hari Berturut-turut) --}}
    @if($siswaPengawasan->isNotEmpty())
        <div class="p-3.5 bg-red-100 border border-red-300 rounded-xl text-red-900 shadow-2xs space-y-1.5 mb-3">
            <div class="flex items-center space-x-2">
                <i data-lucide="shield-alert" class="w-4 h-4 text-red-600"></i>
                <h3 class="font-bold text-xs text-red-800">PERINGATAN DARURAT: {{ $siswaPengawasan->count() }} Siswa Terawasi (Alpa 5 Hari Berturut-turut)</h3>
            </div>
            <p class="text-[11px] text-red-700">Siswa di bawah ini Alpa 5 hari berturut-turut. Segera hubungi Orang Tua & Guru BK:</p>
            <div class="flex flex-wrap gap-1.5 pt-0.5">
                @foreach($siswaPengawasan as $sp)
                    <span class="px-2 py-0.5 bg-red-600 text-white rounded-md text-[11px] font-bold">
                        {{ $sp['nama_siswa'] }} ({{ $sp['max_berturut_alpa'] }} Hari Berturut-turut)
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Banner 2: PERLU DITINDAK (Total Alpa > 5 Hari Acak Tanggal) --}}
    @if($siswaTindak->isNotEmpty())
        <div class="p-3.5 bg-purple-100 border border-purple-300 rounded-xl text-purple-900 shadow-2xs space-y-1.5 mb-3">
            <div class="flex items-center space-x-2">
                <i data-lucide="alert-octagon" class="w-4 h-4 text-purple-600"></i>
                <h3 class="font-bold text-xs text-purple-800">PERINGATAN DITINDAK: {{ $siswaTindak->count() }} Siswa Total Alpa > 5 Hari (Acak Tanggal)</h3>
            </div>
            <p class="text-[11px] text-purple-700">Siswa di bawah ini memiliki akumulasi Alpa lebih dari 5 hari dalam semester ini:</p>
            <div class="flex flex-wrap gap-1.5 pt-0.5">
                @foreach($siswaTindak as $st)
                    <span class="px-2 py-0.5 bg-purple-600 text-white rounded-md text-[11px] font-bold">
                        {{ $st['nama_siswa'] }} (Total {{ $st['alpa'] }}x Alpa)
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Banner 3: PERLU ATENSI (Total Alpa >= 3 Hari) --}}
    @if($siswaAtensi->isNotEmpty())
        <div class="p-3.5 bg-rose-50/80 border border-rose-200 rounded-xl text-rose-900 shadow-2xs space-y-1.5 mb-3">
            <div class="flex items-center space-x-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                <h3 class="font-bold text-xs">Peringatan Wali Kelas: {{ $siswaAtensi->count() }} Siswa Perlu Atensi (Alpa ≥ 3 Kali)</h3>
            </div>
            <p class="text-[11px] text-rose-700">Siswa di bawah ini memiliki riwayat alpa tinggi. Disarankan berkoordinasi dengan Guru BK atau Orang Tua.</p>
            <div class="flex flex-wrap gap-1.5 pt-0.5">
                @foreach($siswaAtensi as $sa)
                    <span class="px-2 py-0.5 bg-white border border-rose-200 rounded-md text-[11px] font-bold text-rose-800 shadow-2xs">
                        {{ $sa['nama_siswa'] }} ({{ $sa['alpa'] }}x Alpa)
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- TABEL REKAPITULASI PRESENSI KELAS -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i data-lucide="users" class="w-4 h-4 text-slate-500"></i>
                <h3 class="font-bold text-slate-900 text-xs">
                    Daftar Siswa {{ $kelasAktif ? $kelasAktif->nama_kelas : '' }} ({{ $rekapSiswa->count() }} Siswa)
                </h3>
            </div>
            <span class="text-[11px] text-slate-400 font-semibold">Semester Berjalan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-2 px-3.5 w-12 text-center">No</th>
                        <th class="py-2 px-3.5 w-28">NIS</th>
                        <th class="py-2 px-3.5">Nama Lengkap Siswa</th>
                        <th class="py-2 px-3.5 text-center w-20">Sakit</th>
                        <th class="py-2 px-3.5 text-center w-20">Izin</th>
                        <th class="py-2 px-3.5 text-center w-20">Alpa</th>
                        <th class="py-2 px-3.5 text-center w-20">Dispen</th>
                        <th class="py-2 px-3.5 text-center w-32">Status & Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($rekapSiswa as $idx => $s)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $s['perlu_pengawasan'] ? 'bg-red-50/30' : ($s['perlu_tindak'] ? 'bg-purple-50/30' : ($s['perlu_atensi'] ? 'bg-rose-50/20' : '')) }}">
                            <td class="py-2 px-3.5 text-center text-slate-400 text-xs">{{ $idx + 1 }}</td>
                            <td class="py-2 px-3.5 font-semibold text-slate-600 text-xs">{{ $s['nis'] }}</td>
                            <td class="py-2 px-3.5 font-bold text-slate-800 text-xs">{{ $s['nama_siswa'] }}</td>
                            <td class="py-2 px-3.5 text-center font-semibold text-blue-600 text-xs">{{ $s['sakit'] }}</td>
                            <td class="py-2 px-3.5 text-center font-semibold text-amber-600 text-xs">{{ $s['izin'] }}</td>
                            <td class="py-2 px-3.5 text-center font-bold text-xs {{ $s['alpa'] > 0 ? 'text-rose-600' : 'text-slate-400' }}">{{ $s['alpa'] }}</td>
                            <td class="py-2 px-3.5 text-center font-semibold text-slate-600 text-xs">{{ $s['dispen'] }}</td>
                            <td class="py-2 px-3.5 text-center" x-data="{ modalTerbuka: false }">
                                <div class="flex items-center justify-center space-x-1.5">
                                    @if($s['perlu_pengawasan'])
                                        <span class="inline-flex items-center px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-md border border-red-300 animate-pulse">
                                            Terawasi (5x Berturut)
                                        </span>
                                    @elseif($s['perlu_tindak'])
                                        <span class="inline-flex items-center px-2 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-bold rounded-md border border-purple-300">
                                            Perlu Ditindak (>5x Alpa)
                                        </span>
                                    @elseif($s['perlu_atensi'])
                                        <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-bold rounded-md border border-rose-200/60">
                                            Perlu Atensi (≥3x Alpa)
                                        </span>
                                    @elseif($s['total_absen'] == 0)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md border border-emerald-200/60">
                                            Rajin (100%)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-medium rounded-md border border-slate-200">
                                            Normal
                                        </span>
                                    @endif
                                    
                                    <button @click="modalTerbuka = true" class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded border border-slate-200 transition-colors" title="Lihat Detail Riwayat">
                                        <i data-lucide="info" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>

                                <!-- Modal Riwayat Ketidakhadiran & Dispensasi -->
                                <div x-show="modalTerbuka" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-center justify-center min-h-screen p-4 text-center">
                                        <div x-show="modalTerbuka" @click="modalTerbuka = false" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-xs transition-opacity" aria-hidden="true"></div>
                                        <div x-show="modalTerbuka" x-transition x-data="{ activeTab: 'absen' }" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all max-w-md w-full z-10 border border-slate-200">
                                            <div class="bg-white p-4">
                                                <div class="w-full">
                                                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2" id="modal-title">
                                                        Detail Riwayat - {{ $s['nama_siswa'] }}
                                                    </h3>

                                                    <!-- Tab Buttons -->
                                                    <div class="flex mt-3 bg-slate-100 rounded-lg p-0.5 space-x-1">
                                                        <button @click="activeTab = 'absen'" :class="activeTab === 'absen' ? 'bg-white shadow-2xs text-slate-900 font-bold' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-1.5 px-2 rounded-md text-xs font-semibold transition-all">
                                                            <span>Ketidakhadiran</span>
                                                            <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px]" :class="activeTab === 'absen' ? 'bg-rose-100 text-rose-700' : 'bg-slate-200 text-slate-500'">{{ count($s['riwayat_absen']) }}</span>
                                                        </button>
                                                        <button @click="activeTab = 'dispen'" :class="activeTab === 'dispen' ? 'bg-white shadow-2xs text-slate-900 font-bold' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-1.5 px-2 rounded-md text-xs font-semibold transition-all">
                                                            <span>Dispensasi</span>
                                                            <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px]" :class="activeTab === 'dispen' ? 'bg-purple-100 text-purple-700' : 'bg-slate-200 text-slate-500'">{{ count($s['riwayat_dispen']) }}</span>
                                                        </button>
                                                    </div>

                                                    <!-- Tab: Ketidakhadiran -->
                                                    <div x-show="activeTab === 'absen'" class="mt-3 space-y-2 max-h-60 overflow-y-auto pr-1">
                                                        @forelse($s['riwayat_absen'] as $riwayat)
                                                            <div class="flex justify-between items-center p-2.5 bg-slate-50 rounded-lg border border-slate-200/60">
                                                                <div class="text-left">
                                                                    <div class="font-bold text-xs text-slate-800">{{ \Carbon\Carbon::parse($riwayat['tanggal'])->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
                                                                    <div class="text-[11px] text-slate-500 mt-0.5">Waktu: <span class="font-semibold">{{ $riwayat['detail_jam'] }}</span></div>
                                                                </div>
                                                                <div>
                                                                    @php
                                                                        $badge = match ($riwayat['keterangan']) {
                                                                            'Sakit' => 'bg-blue-50 text-blue-700 border border-blue-200/60',
                                                                            'Izin'  => 'bg-amber-50 text-amber-700 border border-amber-200/60',
                                                                            default => 'bg-rose-50 text-rose-700 border border-rose-200/60',
                                                                        };
                                                                    @endphp
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $badge }}">
                                                                        {{ $riwayat['keterangan'] }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-center text-slate-400 text-xs italic py-4">Belum ada riwayat ketidakhadiran.</div>
                                                        @endforelse
                                                    </div>

                                                    <!-- Tab: Dispensasi -->
                                                    <div x-show="activeTab === 'dispen'" style="display: none;" class="mt-3 space-y-2 max-h-60 overflow-y-auto pr-1">
                                                        @forelse($s['riwayat_dispen'] as $dispen)
                                                            <div class="p-2.5 bg-purple-50/50 rounded-lg border border-purple-100">
                                                                <div class="flex justify-between items-start">
                                                                    <div class="text-left flex-1">
                                                                        <div class="font-bold text-xs text-slate-800">{{ \Carbon\Carbon::parse($dispen['tanggal'])->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
                                                                        <div class="text-[11px] text-slate-600 mt-0.5">
                                                                            <span class="font-semibold">Keperluan:</span> {{ $dispen['keperluan'] }}
                                                                        </div>
                                                                        @if($dispen['jam_ke'])
                                                                            <div class="text-[11px] text-slate-500 mt-0.5">Jam ke: {{ $dispen['jam_ke'] }}</div>
                                                                        @endif
                                                                        @if($dispen['jam_keluar'] || $dispen['jam_kembali'])
                                                                            <div class="text-[10px] text-slate-500 mt-0.5">
                                                                                @if($dispen['jam_keluar'])
                                                                                    Keluar: <span class="font-semibold">{{ $dispen['jam_keluar'] }}</span>
                                                                                @endif
                                                                                @if($dispen['jam_kembali'])
                                                                                    · Kembali: <span class="font-semibold">{{ $dispen['jam_kembali'] }}</span>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="ml-2">
                                                                        @php
                                                                            $statusBadge = match ($dispen['status']) {
                                                                                'Disetujui'      => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
                                                                                'Sedang di Luar' => 'bg-amber-50 text-amber-700 border border-amber-200/60',
                                                                                'Sudah Kembali'  => 'bg-blue-50 text-blue-700 border border-blue-200/60',
                                                                                'Ditolak'        => 'bg-rose-50 text-rose-700 border border-rose-200/60',
                                                                                default          => 'bg-slate-100 text-slate-600 border border-slate-200',
                                                                            };
                                                                        @endphp
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $statusBadge }}">
                                                                            {{ $dispen['status'] }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-center text-slate-400 text-xs italic py-4">Belum ada riwayat dispensasi.</div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-slate-50 px-4 py-2.5 sm:flex sm:flex-row-reverse border-t border-slate-100">
                                                <button 
                                                    type="button" 
                                                    @click="modalTerbuka = false" 
                                                    class="h-10 px-6 min-w-[90px] bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer shadow-xs hover:shadow-md"
                                                >
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400 italic text-xs">
                                Tidak ada data siswa di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
