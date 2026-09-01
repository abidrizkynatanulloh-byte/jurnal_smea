@extends('layouts.app')

@section('title', 'Piket Dashboard - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header & Mode Switcher -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Dashboard Guru Piket</h1>
            <p class="text-xs text-gray-500 mt-1">
                Selamat datang kembali, <span class="font-semibold text-[#405078]">{{ Auth::user()->username }}</span> • Kelola dispensasi & pendataan siswa terlambat hari ini.
            </p>
        </div>

        @if(Auth::user()->role === 'guru' || Auth::user()->id_guru !== null)
            <div class="flex items-center space-x-2">
                <a href="{{ route('guru.dashboard') }}" class="px-4 py-2 bg-white border border-[#D1D9EB] hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-all flex items-center space-x-1.5 shadow-xs">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-[#405078]"></i>
                    <span>Mode Mengajar</span>
                </a>
                <a href="{{ route('piket.monitoring-kelas') }}" class="px-4 py-2 bg-[#405078] text-white rounded-xl text-xs font-semibold shadow-xs flex items-center space-x-1.5">
                    <i data-lucide="monitor" class="w-4 h-4"></i>
                    <span>Monitoring Kelas</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center space-x-2">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold flex items-center space-x-2">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- NAVIGATION TAB BAR -->
    <div class="flex border-b border-[#D1D9EB] space-x-4 overflow-x-auto">
        <button id="tabBtnDispen" onclick="switchTab('dispen')" class="py-3 px-4 font-bold text-sm border-b-2 border-[#405078] text-[#405078] transition-all flex items-center space-x-2 cursor-pointer shrink-0">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            <span>1. Pengajuan Dispensasi Siswa</span>
        </button>
        <button id="tabBtnTelat" onclick="switchTab('telat')" class="py-3 px-4 font-bold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all flex items-center space-x-2 cursor-pointer shrink-0">
            <i data-lucide="clock-alert" class="w-4 h-4"></i>
            <span>2. Pendataan Siswa Terlambat</span>
        </button>
        <button id="tabBtnIzinGuru" onclick="switchTab('izinGuru')" class="py-3 px-4 font-bold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all flex items-center space-x-2 cursor-pointer shrink-0">
            <i data-lucide="user-check" class="w-4 h-4"></i>
            <span>3. Persetujuan Izin Guru ({{ count($izinGuruPending) }})</span>
        </button>
    </div>

    <!-- TAB 1: DISPENSASI SISWA -->
    <div id="tabDispen" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- FORM INPUT DISPEN -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-8">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="file-plus-2" class="w-5 h-5 text-[#405078]"></i>
                <span>Input Pengajuan Dispensasi</span>
            </h3>
            
            <form action="{{ route('piket.dispen.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="nis" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Siswa (NISN / NIS / Nama)</label>
                    <select name="nis" id="nis" required 
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($daftarSiswa as $s)
                            <option value="{{ $s->nis }}" {{ old('nis') == $s->nis ? 'selected' : '' }}>
                                [NISN: {{ $s->nisn ?? '-' }}] {{ $s->nis }} - {{ $s->nama_siswa }} ({{ $s->kelas ? $s->kelas->nama_kelas : '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="jam_ke" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Jam Ke-Berapa s/d Jam Ke-Berapa</label>
                    <input type="text" name="jam_ke" id="jam_ke" placeholder="Contoh: Jam ke-2 s/d Jam ke-4" value="{{ old('jam_ke') }}"
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="keperluan" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Alasan / Keperluan Dispensasi</label>
                    <textarea name="keperluan" id="keperluan" rows="3" placeholder="Contoh: Mengikuti lomba perwakilan sekolah..." required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all resize-none">{{ old('keperluan') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jam_keluar_rencana" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Estimasi Jam Keluar</label>
                        <input type="time" name="jam_keluar_rencana" id="jam_keluar_rencana" required
                            class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                    </div>
                    <div>
                        <label for="jam_kembali_rencana" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Estimasi Jam Kembali</label>
                        <input type="time" name="jam_kembali_rencana" id="jam_kembali_rencana"
                            class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Pengajuan Kesiswaan</span>
                </button>
            </form>
        </div>

        <!-- TABEL REKAP DISPEN HARI INI -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
                    <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                        <i data-lucide="clipboard-list" class="w-5 h-5 text-[#405078]"></i>
                        <span>Pengajuan Dispensasi Hari Ini</span>
                    </h3>
                    <span class="text-xs font-semibold text-gray-500">{{ count($dispenHariIni) }} Pengajuan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-6 w-44">Siswa (NISN/NIS)</th>
                                <th class="py-4 px-6 w-36">Jam Pelajaran</th>
                                <th class="py-4 px-6">Alasan Keperluan</th>
                                <th class="py-4 px-6 w-44 text-center">Status Wakasis</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($dispenHariIni as $index => $d)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-6 text-center font-medium text-gray-400">{{ $index + 1 }}</td>
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-gray-800">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</div>
                                        <div class="text-[11px] text-gray-400">NISN: {{ $d->siswa ? $d->siswa->nisn ?? '-' : '-' }} | NIS: {{ $d->nis }}</div>
                                        <div class="text-[11px] text-[#405078] font-semibold">{{ $d->siswa && $d->siswa->kelas ? $d->siswa->kelas->nama_kelas : '-' }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-xs leading-normal">
                                        @if($d->jam_ke)
                                            <div class="font-bold text-gray-800 mb-1">{{ $d->jam_ke }}</div>
                                        @endif
                                        <div class="text-gray-500">Keluar: {{ substr($d->jam_keluar_rencana, 0, 5) ?? '-' }}</div>
                                        <div class="text-gray-500">Kembali: {{ $d->jam_kembali_rencana ? substr($d->jam_kembali_rencana, 0, 5) : 'Selesai KBM' }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-600 max-w-xs leading-relaxed">{{ $d->keperluan }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if ($d->status === 'Menunggu')
                                            <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full space-x-1 animate-pulse">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                                <span>Menunggu ACC Wakasis</span>
                                            </span>
                                        @elseif ($d->status === 'Disetujui')
                                            <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full space-x-1">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                                <span>Disetujui Wakasis (Di Pos Satpam)</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-700 text-xs font-semibold rounded-full space-x-1">
                                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                                <span>Ditolak Wakasis</span>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 italic">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
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
    <div id="tabTelat" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start hidden">
        <!-- FORM INPUT SISWA TELAT -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-8">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="user-minus" class="w-5 h-5 text-amber-600"></i>
                <span>Catat Siswa Terlambat</span>
            </h3>
            
            <form action="{{ route('piket.siswa-telat.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="nis_telat" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Siswa Terlambat</label>
                    <select name="nis" id="nis_telat" required 
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($daftarSiswa as $s)
                            <option value="{{ $s->nis }}" {{ old('nis') == $s->nis ? 'selected' : '' }}>
                                [NISN: {{ $s->nisn ?? '-' }}] {{ $s->nis }} - {{ $s->nama_siswa }} ({{ $s->kelas ? $s->kelas->nama_kelas : '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="jam_terlambat" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Jam Datang Terlambat</label>
                    <input type="time" name="jam_terlambat" id="jam_terlambat" value="{{ date('H:i') }}" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                </div>

                <div>
                    <label for="alasan_telat" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Alasan Terlambat</label>
                    <textarea name="alasan" id="alasan_telat" rows="2" placeholder="Contoh: Ban motor bocor di jalan..."
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all resize-none">{{ old('alasan') }}</textarea>
                </div>

                <div>
                    <label for="tindakan" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tindakan / Sanksi Piket (Opsional)</label>
                    <input type="text" name="tindakan" id="tindakan" placeholder="Contoh: Pembinaan & kebersihan halaman" value="{{ old('tindakan') }}"
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Data Keterlambatan</span>
                </button>
            </form>
        </div>

        <!-- TABEL REKAP SISWA TELAT HARI INI -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
                    <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                        <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                        <span>Siswa Terlambat Hari Ini</span>
                    </h3>
                    <span class="text-xs font-semibold text-gray-500">{{ count($siswaTelatHariIni) }} Siswa</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-6 w-44">Siswa (NISN/NIS)</th>
                                <th class="py-4 px-6 w-28">Jam Datang</th>
                                <th class="py-4 px-6">Alasan</th>
                                <th class="py-4 px-6">Tindakan / Sanksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($siswaTelatHariIni as $index => $t)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-6 text-center font-medium text-gray-400">{{ $index + 1 }}</td>
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-gray-800">{{ $t->siswa ? $t->siswa->nama_siswa : '-' }}</div>
                                        <div class="text-[11px] text-gray-400">NISN: {{ $t->siswa ? $t->siswa->nisn ?? '-' : '-' }} | NIS: {{ $t->nis }}</div>
                                        <div class="text-[11px] text-[#405078] font-semibold">{{ $t->siswa && $t->siswa->kelas ? $t->siswa->kelas->nama_kelas : '-' }}</div>
                                    </td>
                                    <td class="py-4 px-6 font-bold text-amber-700">
                                        {{ substr($t->jam_terlambat, 0, 5) }}
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-600 max-w-xs">{{ $t->alasan ?? '-' }}</td>
                                    <td class="py-4 px-6 text-xs font-medium text-gray-700">{{ $t->tindakan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 italic">
                                        <i data-lucide="check-circle-2" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
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
</div>

    <!-- TAB 3: PERSETUJUAN IZIN GURU (MENUNGGU PIKET) -->
    <div id="tabIzinGuru" class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
            <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                <i data-lucide="user-check" class="w-5 h-5 text-[#405078]"></i>
                <span>Verifikasi Izin Guru ({{ count($izinGuruPending) }} Mengajukan)</span>
            </h3>
            <span class="text-xs text-gray-500 font-semibold">Tugas Piket Hari Ini</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6 w-16 text-center">No</th>
                        <th class="py-4 px-6 w-48">Nama Guru</th>
                        <th class="py-4 px-6 w-40">Tanggal & Alasan</th>
                        <th class="py-4 px-6">Keterangan / Terdampak</th>
                        <th class="py-4 px-6 w-32 text-center">Bukti Pendukung</th>
                        <th class="py-4 px-6 text-center w-64">Aksi Verifikasi Piket</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse ($izinGuruPending as $index => $ig)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-center font-medium text-gray-400">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-[#1E2538]">{{ $ig->guru ? $ig->guru->nama_guru : '-' }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">NIP: {{ $ig->guru ? $ig->guru->nip ?? '-' : '-' }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs">
                                <div class="font-bold text-gray-800">{{ $ig->alasan }}</div>
                                <div class="text-gray-500 mt-0.5">{{ $ig->tanggal_mulai }} s/d {{ $ig->tanggal_selesai }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs leading-relaxed">
                                <div class="text-gray-700 font-medium">{{ $ig->keterangan ?? '-' }}</div>
                                @if($ig->kelas_terdampak)
                                    <div class="text-[11px] text-[#405078] mt-0.5">Terdampak: {{ $ig->kelas_terdampak }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($ig->bukti_foto)
                                    <a href="{{ asset('storage/' . $ig->bukti_foto) }}" target="_blank" class="inline-flex items-center space-x-1 text-xs text-[#405078] hover:underline font-bold bg-[#405078]/10 px-2.5 py-1 rounded-lg">
                                        <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                        <span>Lihat Bukti</span>
                                    </a>
                                @else
                                    <span class="text-xs text-rose-500 font-bold">Tanpa Foto</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <form action="{{ route('piket.izin-guru.approve', $ig->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center space-x-1 cursor-pointer">
                                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                            <span>Setujui</span>
                                        </button>
                                    </form>

                                    <form action="{{ route('piket.izin-guru.reject', $ig->id) }}" method="POST" class="flex items-center space-x-1">
                                        @csrf
                                        <input type="text" name="catatan" placeholder="Catatan tolak..." required
                                            class="px-2 py-1 bg-[#F8FAFC] border border-[#D1D9EB] rounded-lg text-xs text-[#1E2538] w-28 focus:outline-none focus:border-[#405078]">
                                        <button type="submit" class="px-2.5 py-1.5 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 rounded-xl text-xs font-bold transition-colors cursor-pointer">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 italic text-xs">
                                <i data-lucide="check-circle" class="w-7 h-7 mx-auto mb-1.5 text-emerald-500"></i>
                                Tidak ada pengajuan izin guru yang menunggu verifikasi piket saat ini.
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
        const tabIzinGuru = document.getElementById('tabIzinGuru');
        
        const btnDispen = document.getElementById('tabBtnDispen');
        const btnTelat = document.getElementById('tabBtnTelat');
        const btnIzinGuru = document.getElementById('tabBtnIzinGuru');

        tabDispen.classList.add('hidden');
        tabTelat.classList.add('hidden');
        tabIzinGuru.classList.add('hidden');

        btnDispen.className = "py-3 px-4 font-bold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all flex items-center space-x-2 cursor-pointer shrink-0";
        btnTelat.className = "py-3 px-4 font-bold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all flex items-center space-x-2 cursor-pointer shrink-0";
        btnIzinGuru.className = "py-3 px-4 font-bold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all flex items-center space-x-2 cursor-pointer shrink-0";

        if (tabName === 'dispen') {
            tabDispen.classList.remove('hidden');
            btnDispen.className = "py-3 px-4 font-bold text-sm border-b-2 border-[#405078] text-[#405078] transition-all flex items-center space-x-2 cursor-pointer shrink-0";
        } else if (tabName === 'telat') {
            tabTelat.classList.remove('hidden');
            btnTelat.className = "py-3 px-4 font-bold text-sm border-b-2 border-amber-600 text-amber-600 transition-all flex items-center space-x-2 cursor-pointer shrink-0";
        } else if (tabName === 'izinGuru') {
            tabIzinGuru.classList.remove('hidden');
            btnIzinGuru.className = "py-3 px-4 font-bold text-sm border-b-2 border-[#405078] text-[#405078] transition-all flex items-center space-x-2 cursor-pointer shrink-0";
        }
    }
</script>
@endsection