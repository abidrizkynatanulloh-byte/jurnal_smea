@extends('layouts.app')

@section('title', 'Rekap Jurnal & Kehadiran - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-dark tracking-tight">Rekapitulasi Jurnal & Kehadiran</h1>
            <p class="text-sm text-gray-500 mt-1">
                Pantau laporan pengisian jurnal mengajar harian guru dan kehadiran siswa
            </p>
        </div>
    </div>

    <!-- FILTER TANGGAL & TABS -->
    <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm p-5 space-y-4">
        <form action="{{ route('admin.rekap.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <input type="hidden" name="tab" value="{{ $tab }}">
            
            <div class="flex-1 w-full">
                <label for="tanggal" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Tanggal Rekap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                        class="block w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all cursor-pointer">
                </div>
            </div>

            <div class="flex space-x-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-initial px-5 py-2.5 bg-brand hover:bg-brand-hover text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer shadow-sm">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('admin.rekap.index', ['tab' => $tab]) }}" class="px-5 py-2.5 border border-gray-250 bg-white hover:bg-gray-50 text-gray-600 rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                    <span>Hari Ini</span>
                </a>
            </div>
        </form>

        <!-- TAB NAVIGATION -->
        <div class="border-t border-gray-100 pt-4 flex flex-wrap gap-2">
            <a href="{{ route('admin.rekap.index', ['tanggal' => $tanggal, 'tab' => 'terisi']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $tab == 'terisi' ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <i data-lucide="book-check" class="w-4 h-4"></i>
                <span>1. Jurnal Terisi & Foto ({{ $jurnalTersimpan->count() }})</span>
            </a>

            <a href="{{ route('admin.rekap.index', ['tanggal' => $tanggal, 'tab' => 'siswa_absen']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $tab == 'siswa_absen' ? 'bg-amber-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <i data-lucide="users-2" class="w-4 h-4"></i>
                <span>2. Ketidakhadiran Siswa ({{ $siswaAbsenList->count() }})</span>
            </a>

            <a href="{{ route('admin.rekap.index', ['tanggal' => $tanggal, 'tab' => 'guru_alpa']) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 {{ $tab == 'guru_alpa' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                <span>3. Guru Belum Isi Jurnal ({{ $guruAlpaList->count() }})</span>
            </a>
        </div>
    </div>

    <!-- TAB 1: JURNAL TERISI & FOTO KEGIATAN -->
    @if ($tab == 'terisi')
        <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#19140015] flex items-center justify-between">
                <h3 class="font-bold text-dark text-base flex items-center space-x-2">
                    <i data-lucide="book-check" class="w-5 h-5 text-green-600"></i>
                    <span>Daftar Jurnal Terisi - {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#19140015]">
                            <th class="py-4 px-6 w-16 text-center">Foto</th>
                            <th class="py-4 px-6">Guru & Mapel</th>
                            <th class="py-4 px-6 w-28">Kelas</th>
                            <th class="py-4 px-6 w-28">Jam Ke-</th>
                            <th class="py-4 px-6">Materi Pembelajaran</th>
                            <th class="py-4 px-6 text-center w-28">Siswa Absen</th>
                            <th class="py-4 px-6 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#19140010] text-sm text-gray-600">
                        @forelse ($jurnalTersimpan as $jt)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6 text-center">
                                    @if ($jt->foto)
                                        <img src="{{ asset('storage/' . $jt->foto->foto_path) }}" alt="Foto" class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm mx-auto">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center mx-auto text-gray-400">
                                            <i data-lucide="image-off" class="w-5 h-5"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-dark">{{ $jt->jadwal && $jt->jadwal->guru ? $jt->jadwal->guru->nama_guru : '-' }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $jt->jadwal && $jt->jadwal->mapel ? $jt->jadwal->mapel->nama_mapel : '-' }}</div>
                                </td>
                                <td class="py-4 px-6 font-bold text-dark">
                                    {{ $jt->jadwal && $jt->jadwal->kelas ? $jt->jadwal->kelas->nama_kelas : '-' }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-gray-700">
                                    Jam {{ $jt->jadwal->jam_mulai ?? '-' }} - {{ $jt->jadwal->jam_selesai ?? '-' }}
                                </td>
                                <td class="py-4 px-6 font-medium text-dark max-w-xs truncate" title="{{ $jt->materi }}">
                                    {{ $jt->materi ?? '-' }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if ($jt->detailKetidakhadiran->count() > 0)
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">
                                            {{ $jt->detailKetidakhadiran->count() }} Siswa
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                                            Nihil
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="{{ route('admin.rekap.show', $jt->id_jurnal) }}"
                                       class="inline-flex items-center space-x-1 px-3 py-1.5 bg-brand hover:bg-brand-hover text-white rounded-lg text-xs font-bold transition-colors shadow-sm">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>Detail</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-400 italic">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                    Belum ada jurnal yang diisi pada tanggal {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- TAB 2: LAPORAN KETIDAKHADIRAN SISWA -->
    @if ($tab == 'siswa_absen')
        <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#19140015] flex items-center justify-between">
                <h3 class="font-bold text-dark text-base flex items-center space-x-2">
                    <i data-lucide="users-2" class="w-5 h-5 text-amber-600"></i>
                    <span>Daftar Ketidakhadiran Siswa (Sakit / Izin / Alpa)</span>
                </h3>
                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">
                    Total: {{ $siswaAbsenList->count() }} Siswa
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#19140015]">
                            <th class="py-4 px-6">NIS</th>
                            <th class="py-4 px-6">Nama Siswa</th>
                            <th class="py-4 px-6">Kelas</th>
                            <th class="py-4 px-6 text-center">Keterangan</th>
                            <th class="py-4 px-6">Guru Pelapor</th>
                            <th class="py-4 px-6">Mata Pelajaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#19140010] text-sm text-gray-600">
                        @forelse ($siswaAbsenList as $sa)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6 font-mono text-xs font-bold text-gray-700">{{ $sa->id_siswa }}</td>
                                <td class="py-4 px-6 font-bold text-dark">{{ $sa->siswa ? $sa->siswa->nama_siswa : '-' }}</td>
                                <td class="py-4 px-6 font-semibold text-gray-700">
                                    {{ $sa->siswa && $sa->siswa->kelas ? $sa->siswa->kelas->nama_kelas : '-' }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if ($sa->keterangan == 'Sakit')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">🤒 Sakit</span>
                                    @elseif ($sa->keterangan == 'Izin')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">📄 Izin</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">❌ Alpa</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-gray-700">
                                    {{ $sa->jurnal && $sa->jurnal->jadwal && $sa->jurnal->jadwal->guru ? $sa->jurnal->jadwal->guru->nama_guru : '-' }}
                                </td>
                                <td class="py-4 px-6 text-gray-500">
                                    {{ $sa->jurnal && $sa->jurnal->jadwal && $sa->jurnal->jadwal->mapel ? $sa->jurnal->jadwal->mapel->nama_mapel : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400 italic">
                                    <i data-lucide="check-circle" class="w-8 h-8 mx-auto mb-2 text-green-500"></i>
                                    Tidak ada catatan ketidakhadiran siswa pada tanggal {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- TAB 3: LAPORAN GURU BELUM ISI JURNAL (GURU ALPA) -->
    @if ($tab == 'guru_alpa')
        <div class="bg-red-50/20 border border-red-200/60 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 bg-red-50/50 border-b border-red-200/50 flex items-center justify-between">
                <h3 class="font-bold text-red-900 text-base flex items-center space-x-2">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                    <span>Laporan Guru Belum Isi Jurnal (Alpa Mengajar)</span>
                </h3>
                <span class="px-2.5 py-1 bg-red-100 text-red-800 text-xs font-extrabold rounded-full">
                    {{ $guruAlpaList->count() }} Jadwal Terlewat
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-red-50/30 text-red-700 text-xs font-semibold uppercase tracking-wider border-b border-red-200/30">
                            <th class="py-4 px-6 w-32">Jam Ke-</th>
                            <th class="py-4 px-6">Guru Pengajar</th>
                            <th class="py-4 px-6">Mata Pelajaran</th>
                            <th class="py-4 px-6 w-32">Kelas</th>
                            <th class="py-4 px-6 w-56 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-200/20 text-sm text-red-900/80">
                        @forelse ($guruAlpaList as $ga)
                            <tr class="hover:bg-red-50/10 transition-colors">
                                <td class="py-4 px-6 font-semibold">Jam {{ $ga->jam_mulai }} - {{ $ga->jam_selesai }}</td>
                                <td class="py-4 px-6 font-bold">{{ $ga->guru ? $ga->guru->nama_guru : '-' }}</td>
                                <td class="py-4 px-6 text-red-900/60">{{ $ga->mapel ? $ga->mapel->nama_mapel : '-' }}</td>
                                <td class="py-4 px-6 font-bold text-red-800">{{ $ga->kelas ? $ga->kelas->nama_kelas : '-' }}</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block px-3 py-1 bg-red-100/80 text-red-800 text-xs font-bold rounded-full">
                                        BELUM ISI JURNAL
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-green-700 font-bold bg-green-50/20">
                                    <div class="flex flex-col items-center justify-center space-y-1.5">
                                        <i data-lucide="check-circle-2" class="w-7 h-7 text-green-600"></i>
                                        <span>Luar Biasa! Semua guru telah mengisi jurnal pada tanggal {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
