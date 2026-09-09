@extends('layouts.app')

@section('title', 'Piket Dashboard - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Page Header & Mode Switcher -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Dashboard Guru Piket</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Selamat datang kembali, <span class="font-semibold text-slate-800">{{ Auth::user()->username }}</span> • Kelola dispensasi & pendataan siswa terlambat hari ini.
            </p>
        </div>

        @if(Auth::user()->role === 'guru' || Auth::user()->id_guru !== null)
            <div class="flex items-center space-x-2">
                <a href="{{ route('guru.dashboard') }}" class="h-8.5 px-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center space-x-1.5 shadow-2xs">
                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>Mode Mengajar</span>
                </a>
                <a href="{{ route('piket.monitoring-kelas') }}" class="h-8.5 px-3 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold shadow-2xs flex items-center space-x-1.5">
                    <i data-lucide="monitor" class="w-3.5 h-3.5"></i>
                    <span>Monitoring Kelas</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs font-semibold flex items-center space-x-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-xs font-semibold flex items-center space-x-2">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- NAVIGATION TAB BAR -->
    <div class="flex border-b border-slate-200 space-x-3 overflow-x-auto">
        <button id="tabBtnDispen" onclick="switchTab('dispen')" class="py-2 px-3 font-bold text-xs border-b-2 border-[#1E2538] text-slate-900 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0">
            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
            <span>1. Pengajuan Dispensasi Siswa</span>
        </button>
        <button id="tabBtnTelat" onclick="switchTab('telat')" class="py-2 px-3 font-semibold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0">
            <i data-lucide="clock-alert" class="w-3.5 h-3.5"></i>
            <span>2. Pendataan Siswa Terlambat</span>
        </button>
        <button id="tabBtnIzinGuru" onclick="switchTab('izinGuru')" class="py-2 px-3 font-semibold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0">
            <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
            <span>3. Persetujuan Izin Guru ({{ count($izinGuruPending) }})</span>
        </button>
        <button id="tabBtnIzinSiswa" onclick="switchTab('izinSiswa')" class="py-2 px-3 font-semibold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0">
            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
            <span>4. Izin Siswa / Ortu ({{ count($izinSiswaPending) }})</span>
        </button>
    </div>

    <!-- TAB 1: DISPENSASI SISWA -->
    <div id="tabDispen" class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        ...
    </div>

    ... (rest of content)

    <!-- TAB 4: PERSETUJUAN IZIN SISWA DARI ORANG TUA -->
    <div id="tabIzinSiswa" class="hidden bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                <h3 class="font-bold text-slate-900 text-xs">Persetujuan Permohonan Izin Siswa dari Orang Tua / Wali Murid</h3>
            </div>
            <span class="text-[11px] text-slate-400 font-mono">{{ count($izinSiswaPending) }} Menunggu ACC</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-2.5 px-3 text-center w-10">NO</th>
                        <th class="py-2.5 px-3">SISWA & KELAS</th>
                        <th class="py-2.5 px-3 w-36">KATEGORI & TANGGAL</th>
                        <th class="py-2.5 px-3">ALASAN DETAIL</th>
                        <th class="py-2.5 px-3 text-center w-24">BUKTI FOTO</th>
                        <th class="py-2.5 px-3 text-center w-48">AKSI PERSETUJUAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($izinSiswaPending as $idx => $is)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2 px-3 text-center font-mono text-slate-400 text-[11px]">{{ $idx + 1 }}</td>
                            <td class="py-2 px-3">
                                <p class="font-bold text-slate-900 leading-tight">{{ $is->siswa ? $is->siswa->nama_siswa : $is->nis }}</p>
                                <div class="flex items-center space-x-2 mt-0.5">
                                    <span class="text-[10.5px] text-slate-400 font-mono">NIS: {{ $is->nis }}</span>
                                    <span class="px-1.5 py-0.2 bg-slate-100 border border-slate-200 text-slate-700 rounded text-[10px] font-semibold">
                                        {{ $is->siswa && $is->siswa->kelas ? $is->siswa->kelas->nama_kelas : '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <span class="inline-block px-2 py-0.5 rounded text-[10.5px] font-bold 
                                    @if($is->kategori === 'Sakit') bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif($is->kategori === 'Izin') bg-amber-50 text-amber-700 border border-amber-200
                                    @else bg-purple-50 text-purple-700 border border-purple-200 @endif mb-1">
                                    {{ $is->kategori }}
                                </span>
                                <p class="text-[11px] text-slate-600 font-mono">
                                    {{ \Carbon\Carbon::parse($is->tanggal_mulai)->translatedFormat('d M Y') }}
                                    @if($is->tanggal_mulai !== $is->tanggal_selesai)
                                        s/d {{ \Carbon\Carbon::parse($is->tanggal_selesai)->translatedFormat('d M Y') }}
                                    @endif
                                </p>
                            </td>
                            <td class="py-2 px-3">
                                <p class="text-xs text-slate-800 leading-relaxed font-medium">{{ $is->alasan }}</p>
                            </td>
                            <td class="py-2 px-3 text-center">
                                @if($is->bukti_foto)
                                    <a href="{{ asset($is->bukti_foto) }}" target="_blank" class="inline-flex items-center space-x-1 px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded border border-slate-200 text-[11px] font-semibold transition-colors">
                                        <i data-lucide="image" class="w-3 h-3 text-slate-500"></i>
                                        <span>Lihat Foto</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Tanpa Foto</span>
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
                                            class="h-7 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 w-24 focus:outline-none focus:border-[#1E2538]">
                                        <button type="submit" class="h-7 px-2 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 italic text-xs">
                                <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1.5 text-emerald-500"></i>
                                Tidak ada permohonan izin siswa dari orang tua yang menunggu persetujuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<script>
    function switchTab(tabName) {
        const tabDispen = document.getElementById('tabDispen');
        const tabTelat = document.getElementById('tabTelat');
        const tabIzinGuru = document.getElementById('tabIzinGuru');
        const tabIzinSiswa = document.getElementById('tabIzinSiswa');
        
        const btnDispen = document.getElementById('tabBtnDispen');
        const btnTelat = document.getElementById('tabBtnTelat');
        const btnIzinGuru = document.getElementById('tabBtnIzinGuru');
        const btnIzinSiswa = document.getElementById('tabBtnIzinSiswa');

        tabDispen.classList.add('hidden');
        tabTelat.classList.add('hidden');
        tabIzinGuru.classList.add('hidden');
        if (tabIzinSiswa) tabIzinSiswa.classList.add('hidden');

        btnDispen.className = "py-2 px-3 font-semibold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        btnTelat.className = "py-2 px-3 font-semibold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        btnIzinGuru.className = "py-2 px-3 font-semibold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        if (btnIzinSiswa) btnIzinSiswa.className = "py-2 px-3 font-semibold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";

        if (tabName === 'dispen') {
            tabDispen.classList.remove('hidden');
            btnDispen.className = "py-2 px-3 font-bold text-xs border-b-2 border-[#1E2538] text-slate-900 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        } else if (tabName === 'telat') {
            tabTelat.classList.remove('hidden');
            btnTelat.className = "py-2 px-3 font-bold text-xs border-b-2 border-amber-600 text-amber-700 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        } else if (tabName === 'izinGuru') {
            tabIzinGuru.classList.remove('hidden');
            btnIzinGuru.className = "py-2 px-3 font-bold text-xs border-b-2 border-[#1E2538] text-slate-900 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        } else if (tabName === 'izinSiswa') {
            if (tabIzinSiswa) tabIzinSiswa.classList.remove('hidden');
            if (btnIzinSiswa) btnIzinSiswa.className = "py-2 px-3 font-bold text-xs border-b-2 border-emerald-600 text-emerald-800 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        }
    }
</script>
        <!-- FORM INPUT DISPEN -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-4 lg:sticky lg:top-8">
            <h3 class="font-bold text-slate-900 text-xs mb-3 flex items-center space-x-1.5">
                <i data-lucide="file-plus-2" class="w-4 h-4 text-slate-600"></i>
                <span>Input Pengajuan Dispensasi</span>
            </h3>
            
            <form action="{{ route('piket.dispen.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="nis" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Siswa (NISN / NIS / Nama)</label>
                    <select name="nis" id="nis" required 
                        class="block w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538] transition-all cursor-pointer">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($daftarSiswa as $s)
                            <option value="{{ $s->nis }}" {{ old('nis') == $s->nis ? 'selected' : '' }}>
                                [NISN: {{ $s->nisn ?? '-' }}] {{ $s->nis }} - {{ $s->nama_siswa }} ({{ $s->kelas ? $s->kelas->nama_kelas : '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="jam_ke" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Jam Ke-Berapa s/d Jam Ke-Berapa</label>
                    <input type="text" name="jam_ke" id="jam_ke" placeholder="Contoh: Jam ke-2 s/d Jam ke-4" value="{{ old('jam_ke') }}"
                        class="block w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538] transition-all">
                </div>

                <div>
                    <label for="keperluan" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Alasan / Keperluan Dispensasi</label>
                    <textarea name="keperluan" id="keperluan" rows="2" placeholder="Contoh: Mengikuti lomba perwakilan sekolah..." required
                        class="block w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538] transition-all resize-none">{{ old('keperluan') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label for="jam_keluar_rencana" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Estimasi Jam Keluar</label>
                        <input type="time" name="jam_keluar_rencana" id="jam_keluar_rencana" required
                            class="block w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538] transition-all cursor-pointer">
                    </div>
                    <div>
                        <label for="jam_kembali_rencana" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Estimasi Jam Kembali</label>
                        <input type="time" name="jam_kembali_rencana" id="jam_kembali_rencana"
                            class="block w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538] transition-all cursor-pointer">
                    </div>
                </div>

                <button type="submit" class="w-full mt-1.5 h-8.5 px-3 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg font-semibold text-xs transition-colors shadow-2xs flex items-center justify-center space-x-1.5 cursor-pointer">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    <span>Kirim Pengajuan Kesiswaan</span>
                </button>
            </form>
        </div>

        <!-- TABEL REKAP DISPEN HARI INI -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5">
                        <i data-lucide="clipboard-list" class="w-4 h-4 text-slate-500"></i>
                        <span>Pengajuan Dispensasi Hari Ini</span>
                    </h3>
                    <span class="text-xs font-semibold text-gray-500">{{ count($dispenHariIni) }} Pengajuan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-2 px-3.5 text-center w-12">No</th>
                                <th class="py-2 px-3.5 w-40">Siswa (NISN/NIS)</th>
                                <th class="py-2 px-3.5 w-32">Jam Pelajaran</th>
                                <th class="py-2 px-3.5">Alasan Keperluan</th>
                                <th class="py-2 px-3.5 w-36 text-center">Status Wakasis</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($dispenHariIni as $index => $d)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2 px-3.5 text-center font-medium text-slate-400 text-xs">{{ $index + 1 }}</td>
                                    <td class="py-2 px-3.5">
                                        <div class="font-bold text-slate-800 text-xs">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</div>
                                        <div class="text-[10px] text-slate-400">NISN: {{ $d->siswa ? $d->siswa->nisn ?? '-' : '-' }} | NIS: {{ $d->nis }}</div>
                                        <div class="text-[10px] text-slate-500 font-semibold">{{ $d->siswa && $d->siswa->kelas ? $d->siswa->kelas->nama_kelas : '-' }}</div>
                                    </td>
                                    <td class="py-2 px-3.5 text-xs leading-normal">
                                        @if($d->jam_ke)
                                            <div class="font-bold text-slate-800">{{ $d->jam_ke }}</div>
                                        @endif
                                        <div class="text-slate-500 text-[11px]">Keluar: {{ substr($d->jam_keluar_rencana, 0, 5) ?? '-' }}</div>
                                        <div class="text-slate-500 text-[11px]">Kembali: {{ $d->jam_kembali_rencana ? substr($d->jam_kembali_rencana, 0, 5) : 'Selesai KBM' }}</div>
                                    </td>
                                    <td class="py-2 px-3.5 text-xs text-slate-600 max-w-xs leading-relaxed">{{ $d->keperluan }}</td>
                                    <td class="py-2 px-3.5 text-center">
                                        @if ($d->status === 'Menunggu')
                                            <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-semibold rounded-md border border-amber-200/60 space-x-1">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                                <span>Menunggu ACC</span>
                                            </span>
                                        @elseif ($d->status === 'Disetujui')
                                            <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-semibold rounded-md border border-emerald-200/60 space-x-1">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                                <span>Disetujui Wakasis</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-semibold rounded-md border border-rose-200/60 space-x-1">
                                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                                <span>Ditolak Wakasis</span>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
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
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-4 lg:sticky lg:top-8">
            <h3 class="font-bold text-slate-900 text-xs mb-3 flex items-center space-x-1.5">
                <i data-lucide="user-minus" class="w-4 h-4 text-amber-600"></i>
                <span>Catat Siswa Terlambat</span>
            </h3>
            
            <form action="{{ route('piket.siswa-telat.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="nis_telat" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Siswa Terlambat</label>
                    <select name="nis" id="nis_telat" required 
                        class="block w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538] transition-all cursor-pointer">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($daftarSiswa as $s)
                            <option value="{{ $s->nis }}" {{ old('nis') == $s->nis ? 'selected' : '' }}>
                                [NISN: {{ $s->nisn ?? '-' }}] {{ $s->nis }} - {{ $s->nama_siswa }} ({{ $s->kelas ? $s->kelas->nama_kelas : '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="jam_terlambat" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Jam Datang Terlambat</label>
                    <input type="time" name="jam_terlambat" id="jam_terlambat" value="{{ date('H:i') }}" required
                        class="block w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538] transition-all cursor-pointer">
                </div>

                <div>
                    <label for="alasan_telat" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Alasan Terlambat</label>
                    <textarea name="alasan" id="alasan_telat" rows="2" placeholder="Contoh: Ban motor bocor di jalan..."
                        class="block w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538] transition-all resize-none">{{ old('alasan') }}</textarea>
                </div>

                <div>
                    <label for="tindakan" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tindakan / Sanksi Piket (Opsional)</label>
                    <input type="text" name="tindakan" id="tindakan" placeholder="Contoh: Pembinaan & kebersihan halaman" value="{{ old('tindakan') }}"
                        class="block w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538] transition-all">
                </div>

                <button type="submit" class="w-full mt-1.5 h-8.5 px-3 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-semibold text-xs transition-colors shadow-2xs flex items-center justify-center space-x-1.5 cursor-pointer">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    <span>Simpan Data Keterlambatan</span>
                </button>
            </form>
        </div>

        <!-- TABEL REKAP SISWA TELAT HARI INI -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5">
                        <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                        <span>Siswa Terlambat Hari Ini</span>
                    </h3>
                    <span class="text-xs font-semibold text-slate-500">{{ count($siswaTelatHariIni) }} Siswa</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-2 px-3.5 text-center w-12">No</th>
                                <th class="py-2 px-3.5 w-40">Siswa (NISN/NIS)</th>
                                <th class="py-2 px-3.5 w-24">Jam Datang</th>
                                <th class="py-2 px-3.5">Alasan</th>
                                <th class="py-2 px-3.5">Tindakan / Sanksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($siswaTelatHariIni as $index => $t)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2 px-3.5 text-center font-medium text-slate-400 text-xs">{{ $index + 1 }}</td>
                                    <td class="py-2 px-3.5">
                                        <div class="font-bold text-slate-800 text-xs">{{ $t->siswa ? $t->siswa->nama_siswa : '-' }}</div>
                                        <div class="text-[10px] text-slate-400">NISN: {{ $t->siswa ? $t->siswa->nisn ?? '-' : '-' }} | NIS: {{ $t->nis }}</div>
                                        <div class="text-[10px] text-slate-500 font-semibold">{{ $t->siswa && $t->siswa->kelas ? $t->siswa->kelas->nama_kelas : '-' }}</div>
                                    </td>
                                    <td class="py-2 px-3.5 font-bold text-amber-700 text-xs">
                                        {{ substr($t->jam_terlambat, 0, 5) }}
                                    </td>
                                    <td class="py-2 px-3.5 text-xs text-slate-600 max-w-xs">{{ $t->alasan ?? '-' }}</td>
                                    <td class="py-2 px-3.5 text-xs font-medium text-slate-700">{{ $t->tindakan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="check-circle-2" class="w-6 h-6 mx-auto mb-1 text-slate-300"></i>
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
    <div id="tabIzinGuru" class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden hidden">
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5">
                <i data-lucide="user-check" class="w-4 h-4 text-slate-600"></i>
                <span>Verifikasi Izin Guru ({{ count($izinGuruPending) }} Mengajukan)</span>
            </h3>
            <span class="text-xs text-slate-500 font-semibold">Tugas Piket Hari Ini</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-2 px-3.5 w-12 text-center">No</th>
                        <th class="py-2 px-3.5 w-44">Nama Guru</th>
                        <th class="py-2 px-3.5 w-36">Tanggal & Alasan</th>
                        <th class="py-2 px-3.5">Keterangan / Terdampak</th>
                        <th class="py-2 px-3.5 w-28 text-center">Bukti</th>
                        <th class="py-2 px-3.5 text-center w-56">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($izinGuruPending as $index => $ig)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2 px-3.5 text-center font-medium text-slate-400 text-xs">{{ $index + 1 }}</td>
                            <td class="py-2 px-3.5">
                                <div class="font-bold text-slate-800 text-xs">{{ $ig->guru ? $ig->guru->nama_guru : '-' }}</div>
                                <div class="text-[10px] text-slate-400">NIP: {{ $ig->guru ? $ig->guru->nip ?? '-' : '-' }}</div>
                            </td>
                            <td class="py-2 px-3.5 text-xs">
                                <div class="font-bold text-slate-800">{{ $ig->alasan }}</div>
                                <div class="text-slate-500 text-[11px]">{{ $ig->tanggal_mulai }} s/d {{ $ig->tanggal_selesai }}</div>
                            </td>
                            <td class="py-2 px-3.5 text-xs leading-relaxed">
                                <div class="text-slate-700 font-medium">{{ $ig->keterangan ?? '-' }}</div>
                                @if($ig->kelas_terdampak)
                                    <div class="text-[10px] text-slate-500 mt-0.5">Terdampak: {{ $ig->kelas_terdampak }}</div>
                                @endif
                            </td>
                            <td class="py-2 px-3.5 text-center">
                                @if($ig->bukti_foto)
                                    <a href="{{ asset('storage/' . $ig->bukti_foto) }}" target="_blank" class="inline-flex items-center space-x-1 text-[11px] text-slate-700 hover:text-slate-900 font-bold bg-slate-100 hover:bg-slate-200 border border-slate-200 px-2 py-0.5 rounded-md transition-colors">
                                        <i data-lucide="image" class="w-3 h-3 text-slate-500"></i>
                                        <span>Bukti</span>
                                    </a>
                                @else
                                    <span class="text-[10px] text-rose-500 font-bold">Tanpa Foto</span>
                                @endif
                            </td>
                            <td class="py-2 px-3.5 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <form action="{{ route('piket.izin-guru.approve', $ig->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="h-6.5 px-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-semibold transition-colors shadow-2xs flex items-center space-x-1 cursor-pointer">
                                            <i data-lucide="check" class="w-3 h-3"></i>
                                            <span>Setujui</span>
                                        </button>
                                    </form>

                                    <form action="{{ route('piket.izin-guru.reject', $ig->id) }}" method="POST" class="flex items-center space-x-1">
                                        @csrf
                                        <input type="text" name="catatan" placeholder="Catatan tolak..." required
                                            class="h-6.5 px-2 bg-white border border-slate-200 rounded-md text-xs text-slate-800 w-24 focus:outline-none focus:border-[#1E2538]">
                                        <button type="submit" class="h-6.5 px-2 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 rounded-md text-xs font-semibold transition-colors cursor-pointer">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 italic text-xs">
                                <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1 text-emerald-500"></i>
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

        btnDispen.className = "py-2 px-3 font-semibold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        btnTelat.className = "py-2 px-3 font-semibold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        btnIzinGuru.className = "py-2 px-3 font-semibold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";

        if (tabName === 'dispen') {
            tabDispen.classList.remove('hidden');
            btnDispen.className = "py-2 px-3 font-bold text-xs border-b-2 border-[#1E2538] text-slate-900 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        } else if (tabName === 'telat') {
            tabTelat.classList.remove('hidden');
            btnTelat.className = "py-2 px-3 font-bold text-xs border-b-2 border-amber-600 text-amber-700 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        } else if (tabName === 'izinGuru') {
            tabIzinGuru.classList.remove('hidden');
            btnIzinGuru.className = "py-2 px-3 font-bold text-xs border-b-2 border-[#1E2538] text-slate-900 transition-all flex items-center space-x-1.5 cursor-pointer shrink-0";
        }
    }
</script>
@endsection