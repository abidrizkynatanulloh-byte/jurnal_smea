@extends('layouts.app')

@section('title', 'Piket Dashboard - Jurnal Esemkita')

@section('content')
<div class="space-y-5">
    <!-- Page Header & Mode Switcher -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-white dark:bg-[#242A35] p-4.5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight flex items-center space-x-2">
                <i data-lucide="shield-alert" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                <span>Dashboard Guru Piket</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Selamat datang kembali, <span class="font-semibold text-slate-800 dark:text-slate-200">{{ Auth::user()->username }}</span> • Kelola pengajuan dispensasi, pendataan siswa terlambat, & izin siswa.
            </p>
        </div>

        @if(Auth::user()->role === 'guru' || Auth::user()->id_guru !== null)
            <div class="flex items-center space-x-2 shrink-0">
                <a href="{{ route('guru.dashboard') }}" class="h-9 px-3.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold transition-all flex items-center space-x-1.5 shadow-2xs">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                    <span>Mode Mengajar</span>
                </a>
                <a href="{{ route('piket.monitoring-kelas') }}" class="h-9 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-2xs flex items-center space-x-1.5 transition-all">
                    <i data-lucide="monitor" class="w-4 h-4"></i>
                    <span>Monitoring Kelas</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl text-xs font-semibold flex items-center space-x-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-3.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-xl text-xs font-semibold flex items-center space-x-2">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- NAVIGATION TAB BAR -->
    <div class="flex border-b border-slate-200 dark:border-slate-800 space-x-2 overflow-x-auto pb-0.5">
        <button id="tabBtnDispen" onclick="switchTab('dispen')" class="py-2.5 px-4 font-bold text-xs border-b-2 border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400 transition-all flex items-center space-x-2 cursor-pointer shrink-0">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            <span>1. Pengajuan Dispensasi Siswa</span>
        </button>
        <button id="tabBtnTelat" onclick="switchTab('telat')" class="py-2.5 px-4 font-semibold text-xs border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-all flex items-center space-x-2 cursor-pointer shrink-0">
            <i data-lucide="clock-alert" class="w-4 h-4"></i>
            <span>2. Pendataan Siswa Terlambat</span>
        </button>
        <button id="tabBtnIzinSiswa" onclick="switchTab('izinSiswa')" class="py-2.5 px-4 font-semibold text-xs border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-all flex items-center space-x-2 cursor-pointer shrink-0">
            <i data-lucide="shield-check" class="w-4 h-4"></i>
            <span>3. Persetujuan Izin Siswa / Ortu ({{ count($izinSiswaPending) }})</span>
        </button>
    </div>

    <!-- TAB 1: DISPENSASI SISWA -->
    <div id="tabDispen" class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        <!-- FORM INPUT DISPEN -->
        <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-4 lg:sticky lg:top-8 space-y-3">
            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center space-x-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                <i data-lucide="file-plus-2" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                <span>Input Pengajuan Dispensasi</span>
            </h3>
            
            <form action="{{ route('piket.dispen.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="nis" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Pilih Siswa (Bisa Pilih >1 Siswa)</label>
                    <select name="nis[]" id="nis" multiple required 
                        placeholder="Cari / pilih satu atau beberapa siswa..."
                        class="block w-full bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-600 transition-all cursor-pointer">
                        @foreach ($daftarSiswa as $s)
                            <option value="{{ $s->nis }}">
                                [NISN: {{ $s->nisn ?? '-' }}] {{ $s->nis }} - {{ $s->nama_siswa }} ({{ $s->kelas ? $s->kelas->nama_kelas : '-' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 dark:text-slate-400 mt-1">Pilih beberapa siswa sekaligus jika memiliki keperluan dispen yang sama.</p>
                </div>

                <div>
                    <label for="jenis_dispen" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Kategori Dispensasi</label>
                    <select name="jenis_dispen" id="jenis_dispen" 
                        class="block w-full h-8 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-600 transition-all cursor-pointer">
                        <option value="sekolah" selected>🏆 Keperluan Sekolah / Lomba (Otomatis Disetujui)</option>
                        <option value="pribadi">👤 Keperluan Pribadi / Lainnya (Memerlukan ACC Waka)</option>
                    </select>
                </div>

                <div>
                    <label for="jam_ke" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Jam Ke-Berapa s/d Jam Ke-Berapa</label>
                    <input type="text" name="jam_ke" id="jam_ke" placeholder="Contoh: Jam ke-2 s/d Jam ke-4" value="{{ old('jam_ke') }}"
                        class="block w-full h-8 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-600 transition-all">
                </div>

                <div>
                    <label for="keperluan" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Alasan / Keperluan Dispensasi</label>
                    <textarea name="keperluan" id="keperluan" rows="2" placeholder="Contoh: Mengikuti lomba perwakilan sekolah..." required
                        class="block w-full px-2.5 py-1.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-600 transition-all resize-none">{{ old('keperluan') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label for="jam_keluar_rencana" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Estimasi Jam Keluar</label>
                        <input type="time" name="jam_keluar_rencana" id="jam_keluar_rencana" required
                            class="block w-full h-8 px-2 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-600 transition-all cursor-pointer">
                    </div>
                    <div>
                        <label for="jam_kembali_rencana" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Estimasi Jam Kembali</label>
                        <input type="time" name="jam_kembali_rencana" id="jam_kembali_rencana"
                            class="block w-full h-8 px-2 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-600 transition-all cursor-pointer">
                    </div>
                </div>

                <div class="pt-1">
                    <button type="submit" class="w-full min-h-[44px] py-3 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold tracking-wide transition-all flex items-center justify-center space-x-2 shadow-xs cursor-pointer shrink-0">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Simpan Pengajuan Dispensasi</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- TABEL REKAP DISPEN HARI INI -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center space-x-2">
                        <i data-lucide="clipboard-list" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                        <span>Pengajuan Dispensasi Hari Ini</span>
                    </h3>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ count($dispenHariIni) }} Pengajuan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-[#1A2836] border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-blue-300 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-2.5 px-3.5 text-center w-12">No</th>
                                <th class="py-2.5 px-3.5 w-40">Siswa (NISN/NIS)</th>
                                <th class="py-2.5 px-3.5 w-32">Jam Pelajaran</th>
                                <th class="py-2.5 px-3.5">Alasan Keperluan</th>
                                <th class="py-2.5 px-3.5 w-36 text-center">Status Dispensasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            @forelse ($dispenHariIni as $index => $d)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-2.5 px-3.5 text-center font-medium text-slate-400 dark:text-slate-400 text-xs">{{ $index + 1 }}</td>
                                    <td class="py-2.5 px-3.5">
                                        <div class="font-bold text-slate-800 dark:text-slate-100 text-xs">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-400">NISN: {{ $d->siswa ? $d->siswa->nisn ?? '-' : '-' }} | NIS: {{ $d->nis }}</div>
                                        <div class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold">{{ $d->siswa && $d->siswa->kelas ? $d->siswa->kelas->nama_kelas : '-' }}</div>
                                    </td>
                                    <td class="py-2.5 px-3.5 text-xs leading-normal">
                                        @if($d->jam_ke)
                                            <div class="font-bold text-slate-800 dark:text-slate-200">{{ $d->jam_ke }}</div>
                                        @endif
                                        <div class="text-slate-500 dark:text-slate-400 text-[11px]">Keluar: {{ substr($d->jam_keluar_rencana, 0, 5) ?? '-' }}</div>
                                        <div class="text-slate-500 dark:text-slate-400 text-[11px]">Kembali: {{ $d->jam_kembali_rencana ? substr($d->jam_kembali_rencana, 0, 5) : 'Selesai KBM' }}</div>
                                    </td>
                                    <td class="py-2.5 px-3.5 text-xs text-slate-600 dark:text-slate-300 max-w-xs leading-relaxed">{{ $d->keperluan }}</td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        @if ($d->status === 'Menunggu')
                                            <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 text-[10px] font-semibold rounded-md border border-amber-200/60 dark:border-amber-800 space-x-1">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                                <span>Menunggu ACC</span>
                                            </span>
                                        @elseif ($d->status === 'Disetujui')
                                            <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-[10px] font-semibold rounded-md border border-emerald-200/60 dark:border-emerald-800 space-x-1">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                                <span>Disetujui</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 text-[10px] font-semibold rounded-md border border-rose-200/60 dark:border-rose-800 space-x-1">
                                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                                <span>Ditolak</span>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400 dark:text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300 dark:text-slate-600"></i>
                                        Belum ada pengajuan dispensasi untuk hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: PENDATAAN SISWA TERLAMBAT -->
    <div id="tabTelat" class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start hidden">
        <!-- FORM INPUT SISWA TELAT -->
        <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-4 lg:sticky lg:top-8 space-y-3">
            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center space-x-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                <i data-lucide="user-minus" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
                <span>Catat Siswa Terlambat</span>
            </h3>
            
            <form action="{{ route('piket.siswa-telat.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="nis_telat" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Pilih Siswa Terlambat</label>
                    <select name="nis" id="nis_telat" required 
                        class="block w-full h-8 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-amber-600 transition-all cursor-pointer">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($daftarSiswa as $s)
                            <option value="{{ $s->nis }}" {{ old('nis') == $s->nis ? 'selected' : '' }}>
                                [NISN: {{ $s->nisn ?? '-' }}] {{ $s->nis }} - {{ $s->nama_siswa }} ({{ $s->kelas ? $s->kelas->nama_kelas : '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="jam_terlambat" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Jam Datang Terlambat</label>
                    <input type="time" name="jam_terlambat" id="jam_terlambat" value="{{ date('H:i') }}" required
                        class="block w-full h-8 px-2 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-amber-600 transition-all cursor-pointer">
                </div>

                <div>
                    <label for="alasan_telat" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Alasan Terlambat</label>
                    <textarea name="alasan" id="alasan_telat" rows="2" placeholder="Contoh: Ban motor bocor di jalan..."
                        class="block w-full px-2.5 py-1.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-600 transition-all resize-none">{{ old('alasan') }}</textarea>
                </div>

                <div>
                    <label for="tindakan" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Tindakan / Sanksi Piket (Opsional)</label>
                    <input type="text" name="tindakan" id="tindakan" placeholder="Contoh: Pembinaan & kebersihan halaman" value="{{ old('tindakan') }}"
                        class="block w-full h-8 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-600 transition-all">
                </div>

                <div class="pt-1">
                    <button type="submit" class="w-full min-h-[44px] py-3 px-5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold tracking-wide transition-all flex items-center justify-center space-x-2 shadow-xs cursor-pointer shrink-0">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Simpan Data Keterlambatan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- TABEL REKAP SISWA TELAT HARI INI -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs flex items-center space-x-2">
                        <i data-lucide="clock" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
                        <span>Siswa Terlambat Hari Ini</span>
                    </h3>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ count($siswaTelatHariIni) }} Siswa</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-[#1A2836] border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-amber-300 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-2.5 px-3.5 text-center w-12">No</th>
                                <th class="py-2.5 px-3.5 w-40">Siswa (NISN/NIS)</th>
                                <th class="py-2.5 px-3.5 w-24">Jam Datang</th>
                                <th class="py-2.5 px-3.5">Alasan</th>
                                <th class="py-2.5 px-3.5">Tindakan / Sanksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            @forelse ($siswaTelatHariIni as $index => $t)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-2.5 px-3.5 text-center font-medium text-slate-400 dark:text-slate-400 text-xs">{{ $index + 1 }}</td>
                                    <td class="py-2.5 px-3.5">
                                        <div class="font-bold text-slate-800 dark:text-slate-100 text-xs">{{ $t->siswa ? $t->siswa->nama_siswa : '-' }}</div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-400">NISN: {{ $t->siswa ? $t->siswa->nisn ?? '-' : '-' }} | NIS: {{ $t->nis }}</div>
                                        <div class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold">{{ $t->siswa && $t->siswa->kelas ? $t->siswa->kelas->nama_kelas : '-' }}</div>
                                    </td>
                                    <td class="py-2.5 px-3.5 font-bold text-amber-700 dark:text-amber-400 text-xs">
                                        {{ substr($t->jam_terlambat, 0, 5) }}
                                    </td>
                                    <td class="py-2.5 px-3.5 text-xs text-slate-600 dark:text-slate-300 max-w-xs">{{ $t->alasan ?? '-' }}</td>
                                    <td class="py-2.5 px-3.5 text-xs font-medium text-slate-700 dark:text-slate-300">{{ $t->tindakan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400 dark:text-slate-400 italic text-xs">
                                        <i data-lucide="check-circle-2" class="w-6 h-6 mx-auto mb-1 text-slate-300 dark:text-slate-600"></i>
                                        Tidak ada siswa yang tercatat terlambat hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: PERSETUJUAN IZIN SISWA DARI ORANG TUA -->
    <div id="tabIzinSiswa" class="hidden bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
                <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs">Persetujuan Permohonan Izin Siswa dari Orang Tua / Wali Murid</h3>
            </div>
            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">{{ count($izinSiswaPending) }} Menunggu ACC</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-[#1A2836] border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-emerald-300 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-2.5 px-3 text-center w-10">NO</th>
                        <th class="py-2.5 px-3">SISWA & KELAS</th>
                        <th class="py-2.5 px-3 w-36">KATEGORI & TANGGAL</th>
                        <th class="py-2.5 px-3">ALASAN DETAIL</th>
                        <th class="py-2.5 px-3 text-center w-28">BUKTI FOTO</th>
                        <th class="py-2.5 px-3 text-center w-48">AKSI PERSETUJUAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse ($izinSiswaPending as $idx => $is)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-2 px-3 text-center font-mono text-slate-400 dark:text-slate-400 text-[11px]">{{ $idx + 1 }}</td>
                            <td class="py-2 px-3">
                                <p class="font-bold text-slate-900 dark:text-slate-100 leading-tight">{{ $is->siswa ? $is->siswa->nama_siswa : $is->nis }}</p>
                                <div class="flex items-center space-x-2 mt-0.5">
                                    <span class="text-[10.5px] text-slate-500 dark:text-slate-400 font-mono">NIS: {{ $is->nis }}</span>
                                    <span class="px-1.5 py-0.2 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded text-[10px] font-semibold">
                                        {{ $is->siswa && $is->siswa->kelas ? $is->siswa->kelas->nama_kelas : '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <span class="inline-block px-2 py-0.5 rounded text-[10.5px] font-bold 
                                    @if($is->kategori === 'Sakit') bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800
                                    @elseif($is->kategori === 'Izin') bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800
                                    @else bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 @endif mb-1">
                                    {{ $is->kategori }}
                                </span>
                                <p class="text-[11px] text-slate-600 dark:text-slate-400 font-mono">
                                    {{ \Carbon\Carbon::parse($is->tanggal_mulai)->translatedFormat('d M Y') }}
                                    @if($is->tanggal_mulai !== $is->tanggal_selesai)
                                        s/d {{ \Carbon\Carbon::parse($is->tanggal_selesai)->translatedFormat('d M Y') }}
                                    @endif
                                </p>
                            </td>
                            <td class="py-2 px-3">
                                <p class="text-xs text-slate-800 dark:text-slate-200 leading-relaxed font-medium">{{ $is->alasan }}</p>
                            </td>
                            <td class="py-2 px-3 text-center">
                                @if($is->bukti_foto)
                                    <button type="button" onclick="openDetailApprovalModal({
                                        title: 'Detail Permohonan Izin Siswa',
                                        applicantName: '{{ addslashes($is->siswa ? $is->siswa->nama_siswa : $is->nis) }}',
                                        applicantMeta: 'NIS: {{ $is->nis }} • Kelas: {{ addslashes($is->siswa && $is->siswa->kelas ? $is->siswa->kelas->nama_kelas : "-") }}',
                                        category: '{{ $is->kategori }}',
                                        period: '{{ \Carbon\Carbon::parse($is->tanggal_mulai)->translatedFormat("d M Y") }} s/d {{ \Carbon\Carbon::parse($is->tanggal_selesai)->translatedFormat("d M Y") }}',
                                        reason: '{{ addslashes($is->alasan) }}',
                                        proofUrl: '{{ $is->bukti_foto ? (\Illuminate\Support\Str::startsWith($is->bukti_foto, ["http", "uploads/", "storage/"]) ? asset($is->bukti_foto) : asset("storage/" . $is->bukti_foto)) : "" }}',
                                        approveUrl: '{{ route("piket.izin-siswa.approve", $is->id) }}',
                                        rejectUrl: '{{ route("piket.izin-siswa.reject", $is->id) }}',
                                        rejectInputName: 'catatan_penolakan'
                                    })" class="inline-flex items-center space-x-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-950/50 hover:bg-blue-100 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 rounded-lg border border-blue-200 dark:border-blue-800 text-[11px] font-bold transition-colors cursor-pointer">
                                        <i data-lucide="eye" class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400"></i>
                                        <span>Lihat Detail & Foto</span>
                                    </button>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 italic text-[11px]">Tanpa Foto</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <form action="{{ route('piket.izin-siswa.approve', $is->id) }}" method="POST" data-confirm="ACC / Setujui izin siswa ini?">
                                        @csrf
                                        <button type="submit" class="h-7 px-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-colors cursor-pointer inline-flex items-center space-x-1 shadow-2xs">
                                            <i data-lucide="check" class="w-3 h-3"></i>
                                            <span>ACC</span>
                                        </button>
                                    </form>

                                    <form action="{{ route('piket.izin-siswa.reject', $is->id) }}" method="POST" class="flex items-center space-x-1">
                                        @csrf
                                        <input type="text" name="catatan_penolakan" placeholder="Catatan tolak..." required
                                            class="h-7 px-2 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 w-24 focus:outline-none focus:border-rose-500">
                                        <button type="submit" class="h-7 px-2 border border-rose-200 dark:border-rose-900 bg-white dark:bg-rose-950/40 hover:bg-rose-50 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-300 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-400 italic text-xs">
                                <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1.5 text-emerald-500 dark:text-emerald-400"></i>
                                Tidak ada permohonan izin siswa dari orang tua yang menunggu persetujuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        const tabDispen = document.getElementById('tabDispen');
        const tabTelat = document.getElementById('tabTelat');
        const tabIzinSiswa = document.getElementById('tabIzinSiswa');
        
        const btnDispen = document.getElementById('tabBtnDispen');
        const btnTelat = document.getElementById('tabBtnTelat');
        const btnIzinSiswa = document.getElementById('tabBtnIzinSiswa');

        if (tabDispen) tabDispen.classList.add('hidden');
        if (tabTelat) tabTelat.classList.add('hidden');
        if (tabIzinSiswa) tabIzinSiswa.classList.add('hidden');

        const inactiveClass = "py-2.5 px-4 font-semibold text-xs border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-all flex items-center space-x-2 cursor-pointer shrink-0";

        if (btnDispen) btnDispen.className = inactiveClass;
        if (btnTelat) btnTelat.className = inactiveClass;
        if (btnIzinSiswa) btnIzinSiswa.className = inactiveClass;

        if (tabName === 'dispen' && tabDispen && btnDispen) {
            tabDispen.classList.remove('hidden');
            btnDispen.className = "py-2.5 px-4 font-bold text-xs border-b-2 border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400 transition-all flex items-center space-x-2 cursor-pointer shrink-0";
        } else if (tabName === 'telat' && tabTelat && btnTelat) {
            tabTelat.classList.remove('hidden');
            btnTelat.className = "py-2.5 px-4 font-bold text-xs border-b-2 border-amber-600 text-amber-600 dark:border-amber-400 dark:text-amber-400 transition-all flex items-center space-x-2 cursor-pointer shrink-0";
        } else if (tabName === 'izinSiswa' && tabIzinSiswa && btnIzinSiswa) {
            tabIzinSiswa.classList.remove('hidden');
            btnIzinSiswa.className = "py-2.5 px-4 font-bold text-xs border-b-2 border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400 transition-all flex items-center space-x-2 cursor-pointer shrink-0";
        }
    }
</script>
@endsection