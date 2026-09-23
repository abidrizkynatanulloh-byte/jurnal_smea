@extends('layouts.app')

@section('title', 'Input Jurnal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-4 max-w-5xl mx-auto pb-32 md:pb-12">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-[#1E2538] p-4 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white flex items-center space-x-1 transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">Input Jurnal Mengajar</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Lengkapi materi pembelajaran, presensi siswa, & dokumentasi mengajar</p>
        </div>
    </div>

    <!-- Info Sesi Mengajar Header Card -->
    <div class="bg-white dark:bg-[#1E2538] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs p-4 sm:p-5">
        <div class="flex items-center space-x-2 pb-3 border-b border-slate-100 dark:border-slate-800 mb-3">
            <i data-lucide="info" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider">Informasi Sesi Mengajar</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <div>
                <span class="block text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kelas</span>
                <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-slate-100 block mt-0.5">{{ $jadwal->kelas ? $jadwal->kelas->nama_kelas : '-' }}</span>
            </div>
            <div>
                <span class="block text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Mata Pelajaran</span>
                <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 block mt-0.5">{{ $jadwal->mapel ? $jadwal->mapel->nama_mapel : '-' }}</span>
            </div>
            <div>
                <span class="block text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Ruangan & Jam</span>
                <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 block mt-0.5">{{ $jadwal->ruangan ? $jadwal->ruangan->nama_ruangan : '-' }} (Jam {{ $jadwal->jam_mulai }}-{{ $jadwal->jam_selesai }})</span>
            </div>
            <div>
                <span class="block text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tanggal</span>
                <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 block mt-0.5">{{ \Carbon\Carbon::parse($tanggalHariIni)->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Form Jurnal Utama -->
    <form action="{{ route('guru.jurnal.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
        <input type="hidden" name="tanggal" value="{{ $tanggalHariIni }}">

        <!-- BAGIAN 1: FORM MATERI -->
        <div class="bg-white dark:bg-[#1E2538] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs p-4 sm:p-5 space-y-4">
            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider flex items-center space-x-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                <i data-lucide="book-open" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
                <span>Detail Pembelajaran</span>
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2">
                    <label for="materi" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Materi / Topik yang Diajarkan *</label>
                    <textarea name="materi" id="materi" rows="3" placeholder="Contoh: Pengenalan dasar akuntansi, siklus jurnal umum dan penyesuaian..." required
                        class="block w-full p-3 bg-white dark:bg-slate-900/70 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">{{ old('materi') }}</textarea>
                </div>
                <div class="space-y-3">
                    <div>
                        <label for="status_kehadiran_guru" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Kehadiran Guru *</label>
                        <select name="status_kehadiran_guru" id="status_kehadiran_guru" required
                            class="block w-full h-10 px-3 bg-white dark:bg-slate-900/70 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                            <option value="Hadir" {{ old('status_kehadiran_guru', 'Hadir') == 'Hadir' ? 'selected' : '' }}>Hadir di Kelas</option>
                            <option value="Izin" {{ old('status_kehadiran_guru') == 'Izin' ? 'selected' : '' }}>Izin Terencana</option>
                            <option value="Sakit" {{ old('status_kehadiran_guru') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Tanpa Keterangan" {{ old('status_kehadiran_guru') == 'Tanpa Keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                        </select>
                    </div>
                    <div>
                        <label for="catatan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" id="catatan" rows="1" placeholder="Catatan kelas..."
                            class="block w-full p-2.5 bg-white dark:bg-slate-900/70 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: ABSENSI SISWA -->
        <div class="bg-white dark:bg-[#1E2538] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                        <i data-lucide="users-check" class="w-4 h-4 text-slate-700 dark:text-slate-300"></i>
                        <span>Presensi Siswa di Jam Mapel Ini</span>
                    </h3>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <button type="button" id="btn-semua-hadir" class="px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 rounded-xl text-xs font-bold hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-all flex items-center space-x-1.5 cursor-pointer shadow-2xs" title="Tandai semua siswa hadir">
                        <i data-lucide="check-check" class="w-3.5 h-3.5"></i>
                        <span>Semua Hadir</span>
                    </button>
                    <span class="px-3 py-1 bg-slate-900 text-white dark:bg-slate-800 dark:text-slate-100 border border-slate-800 dark:border-slate-700 rounded-xl text-xs font-bold font-mono">
                        {{ count($siswaDiKelas) }} Siswa
                    </span>
                </div>
            </div>

            @if ($siswaDiKelas->isEmpty())
                <div class="p-8 text-center text-slate-400 dark:text-slate-500 italic text-xs">
                    Belum ada data siswa yang terdaftar di kelas ini.
                </div>
            @else
                <!-- DESKTOP TABLE VIEW -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                                <th class="py-3 px-4 text-center w-12">No</th>
                                <th class="py-3 px-4">Nama Lengkap & NIS</th>
                                <th class="py-3 px-4">Status Sebelumnya</th>
                                <th class="py-3 px-4 text-center min-w-[340px]">Pilihan Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-slate-700 dark:text-slate-300">
                            @foreach ($siswaDiKelas as $idx => $s)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-2.5 px-4 text-center font-medium text-slate-400 dark:text-slate-500 text-xs tabular-nums">{{ $idx + 1 }}</td>
                                    <td class="py-2.5 px-4">
                                        <p class="font-bold text-slate-900 dark:text-slate-100 text-xs sm:text-sm">{{ $s->nama_siswa }}</p>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">NIS: {{ $s->nis }}</p>
                                    </td>
                                    <td class="py-2.5 px-4">
                                        @if ($s->info_status)
                                            <span class="inline-flex items-center px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-[10px] font-bold rounded-lg space-x-1">
                                                <i data-lucide="info" class="w-3 h-3 shrink-0"></i>
                                                <span>{{ $s->info_status }}</span>
                                            </span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500 text-[11px] font-medium">Siap KBM</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-4 text-center">
                                        <div class="inline-flex items-center p-0.5 rounded-xl space-x-0.5" style="background-color: rgba(100,116,139,0.15); border: 1px solid rgba(100,116,139,0.25);">
                                            @foreach (['Hadir', 'Terlambat', 'Sakit', 'Izin', 'Alpa', 'Dispen'] as $stVal)
                                                <label class="cursor-pointer select-none" onclick="handlePillClick(this)">
                                                    <input type="radio" name="ketidakhadiran[{{ $s->nis }}]" value="{{ $stVal }}" {{ ($s->auto_status ?? 'Hadir') === $stVal ? 'checked' : '' }} class="presensi-radio hidden">
                                                    <span data-pill="{{ $stVal }}" class="att-pill px-2.5 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center transition-all" style="cursor:pointer;">
                                                        {{ $stVal }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE & TABLET ATTENDANCE CARDS -->
                <div class="block md:hidden divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($siswaDiKelas as $idx => $s)
                        <div class="p-3.5 space-y-2.5 bg-white dark:bg-[#1E2538]">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center space-x-2.5">
                                    <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs flex items-center justify-center shrink-0 tabular-nums">
                                        {{ $idx + 1 }}
                                    </span>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white text-xs leading-snug">{{ $s->nama_siswa }}</p>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">NIS: {{ $s->nis }}</p>
                                    </div>
                                </div>
                                @if ($s->info_status)
                                    <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-[10px] font-bold rounded-lg space-x-1 shrink-0">
                                        <i data-lucide="info" class="w-3 h-3"></i>
                                        <span>{{ $s->info_status }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="grid grid-cols-3 sm:grid-cols-6 gap-1.5 pt-0.5">
                                @foreach (['Hadir', 'Terlambat', 'Sakit', 'Izin', 'Alpa', 'Dispen'] as $stVal)
                                    <label class="cursor-pointer select-none" onclick="handlePillClick(this)">
                                        <input type="radio" name="ketidakhadiran_mob[{{ $s->nis }}]" value="{{ $stVal }}" {{ ($s->auto_status ?? 'Hadir') === $stVal ? 'checked' : '' }} class="presensi-radio hidden">
                                        <span data-pill="{{ $stVal }}" class="att-pill min-h-[36px] w-full rounded-xl text-xs font-bold flex items-center justify-center transition-all" style="cursor:pointer;">
                                            {{ $stVal }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- BAGIAN 3: DOKUMENTASI LIVE KAMERA -->
        <div class="bg-white dark:bg-[#1E2538] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs p-4 sm:p-5 space-y-4">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                <i data-lucide="camera" class="w-4 h-4 text-purple-600 dark:text-purple-400"></i>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider">Dokumentasi Pembelajaran (Kamera Langsung)</h3>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Ambil foto dokumentasi aktivitas belajar mengajar secara langsung</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-stretch sm:items-start">
                <div id="camera-area" class="w-full max-w-sm space-y-2.5">
                    <div class="rounded-2xl overflow-hidden bg-black border border-slate-200 dark:border-slate-700 aspect-video relative flex items-center justify-center shadow-xs">
                        <video id="kamera-video" class="w-full h-full object-cover" autoplay playsinline></video>
                    </div>
                    <button type="button" id="btn-capture" class="w-full min-h-[44px] py-2.5 px-4 bg-[#1E2538] dark:bg-blue-600 hover:bg-[#121724] dark:hover:bg-blue-700 text-white rounded-xl text-xs sm:text-sm font-bold tracking-wide transition-all shadow-xs hover:shadow-md flex items-center justify-center space-x-2 cursor-pointer">
                        <i data-lucide="camera" class="w-4 h-4"></i>
                        <span>Jepret Foto Sekarang</span>
                    </button>
                </div>
                <div id="result-area" class="w-full max-w-sm space-y-2.5" style="display: none;">
                    <div class="rounded-2xl overflow-hidden bg-black border border-slate-200 dark:border-slate-700 aspect-video relative flex items-center justify-center shadow-xs">
                        <img id="hasil-foto" class="w-full h-full object-cover" />
                    </div>
                    <button type="button" id="btn-retake" class="w-full min-h-[44px] py-2.5 px-4 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs sm:text-sm font-bold tracking-wide transition-all shadow-xs hover:shadow-md flex items-center justify-center space-x-2 cursor-pointer">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        <span>Ulangi Pengambilan Foto</span>
                    </button>
                </div>
                <div class="p-4 bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs text-slate-600 dark:text-slate-300 space-y-1.5 flex-1">
                    <p class="font-bold text-slate-900 dark:text-slate-100">Petunjuk Dokumentasi:</p>
                    <p>• Pastikan siswa dan ruang kelas terlihat jelas pada bidikan foto.</p>
                    <p>• Tombol jepret akan mengunci gambar secara otomatis ke formulir jurnal.</p>
                    <p>• Jika hasil foto kurang jelas, Anda dapat menekan tombol ulangi foto.</p>
                </div>
                <input type="hidden" name="foto_base64" id="foto_base64">
                <canvas id="kamera-canvas" style="display:none;"></canvas>
            </div>
        </div>

        <!-- Tombol Simpan & Batal (Desktop) -->
        <div class="hidden md:flex items-center space-x-3 pt-3">
            <button type="submit" class="min-h-[48px] py-3.5 px-8 bg-[#1E2538] dark:bg-blue-600 hover:bg-[#121724] dark:hover:bg-blue-700 text-white rounded-xl font-bold text-sm tracking-wide transition-all shadow-sm hover:shadow-md flex items-center space-x-2.5 cursor-pointer">
                <i data-lucide="save" class="w-4.5 h-4.5"></i>
                <span>Simpan Jurnal & Presensi</span>
            </button>
            <a href="{{ route('guru.dashboard') }}" class="min-h-[48px] px-6 py-3.5 min-w-[100px] border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-semibold transition-colors flex items-center justify-center cursor-pointer shadow-2xs">
                Batal
            </a>
        </div>

        <!-- Sticky Bottom Bar Mobile -->
        <div class="block md:hidden fixed bottom-14 inset-x-0 bg-white/95 dark:bg-[#1C1F26]/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 p-3 z-30 shadow-2xl">
            <div class="flex items-center space-x-2 max-w-lg mx-auto">
                <button type="submit" class="flex-1 min-h-[46px] py-2.5 px-4 bg-[#1E2538] dark:bg-blue-600 active:bg-[#121724] text-white rounded-xl font-bold text-xs sm:text-sm tracking-wide transition-all shadow-md flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Jurnal & Presensi</span>
                </button>
                <a href="{{ route('guru.dashboard') }}" class="min-h-[46px] px-4 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold flex items-center justify-center cursor-pointer">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ============================================================ --}}
{{-- JAVASCRIPT: 100% inline-style approach — immune to CSS wars  --}}
{{-- ============================================================ --}}
<script>
(function() {
    'use strict';

    // Peta warna per status presensi
    var PILL_COLORS = {
        'Hadir':     '#10b981',
        'Terlambat': '#f59e0b',
        'Sakit':     '#2563eb',
        'Izin':      '#0284c7',
        'Alpa':      '#e11d48',
        'Dispen':    '#9333ea'
    };

    var isDark = document.documentElement.classList.contains('dark');

    // ── Terapkan style "aktif" pada pill ──
    function activatePill(span, value) {
        var color = PILL_COLORS[value];
        if (!color) return;
        span.style.setProperty('background-color', color, 'important');
        span.style.setProperty('color', '#ffffff', 'important');
        span.style.setProperty('border-color', color, 'important');
        span.style.setProperty('font-weight', '700', 'important');
        span.style.setProperty('box-shadow', '0 1px 3px 0 rgba(0,0,0,0.2)', 'important');
    }

    // ── Terapkan style "non-aktif" pada pill ──
    function deactivatePill(span) {
        span.style.setProperty('background-color', isDark ? '#1e293b' : '#f1f5f9', 'important');
        span.style.setProperty('color', isDark ? '#cbd5e1' : '#475569', 'important');
        span.style.setProperty('border-color', isDark ? '#334155' : '#e2e8f0', 'important');
        span.style.setProperty('font-weight', '600', 'important');
        span.style.setProperty('box-shadow', 'none', 'important');
        // Tambahkan border agar pill terlihat
        span.style.setProperty('border-width', '1px', 'important');
        span.style.setProperty('border-style', 'solid', 'important');
    }

    // ── Render ulang semua pill di satu grup radio ──
    function refreshGroup(groupName) {
        var radios = document.querySelectorAll('input[name="' + groupName + '"]');
        radios.forEach(function(radio) {
            var span = radio.parentElement ? radio.parentElement.querySelector('.att-pill') : null;
            if (!span) return;
            if (radio.checked) {
                activatePill(span, radio.value);
            } else {
                deactivatePill(span);
            }
        });
    }

    // ── Render ulang SEMUA pill di halaman ──
    function refreshAllPills() {
        isDark = document.documentElement.classList.contains('dark');
        var processedGroups = {};
        document.querySelectorAll('.presensi-radio').forEach(function(radio) {
            if (!processedGroups[radio.name]) {
                processedGroups[radio.name] = true;
                refreshGroup(radio.name);
            }
        });
    }

    // ── Handler klik pada label pill ──
    window.handlePillClick = function(labelEl) {
        var radio = labelEl.querySelector('.presensi-radio');
        if (!radio) return;

        // Pastikan radio terpilih
        radio.checked = true;

        // Refresh visual grup ini
        refreshGroup(radio.name);

        // Sinkronisasi desktop <-> mobile
        var matches = radio.name.match(/\[(.*?)\]/);
        if (!matches || !matches[1]) return;
        var nis = matches[1];

        if (radio.name.indexOf('ketidakhadiran_mob[') === 0) {
            // Mobile -> sync ke desktop
            var deskRadio = document.querySelector('input[name="ketidakhadiran[' + nis + ']"][value="' + radio.value + '"]');
            if (deskRadio && !deskRadio.checked) {
                deskRadio.checked = true;
                refreshGroup(deskRadio.name);
            }
        } else if (radio.name.indexOf('ketidakhadiran[') === 0) {
            // Desktop -> sync ke mobile
            var mobRadio = document.querySelector('input[name="ketidakhadiran_mob[' + nis + ']"][value="' + radio.value + '"]');
            if (mobRadio && !mobRadio.checked) {
                mobRadio.checked = true;
                refreshGroup(mobRadio.name);
            }
        }
    };

    // ── Tombol "Semua Hadir" ──
    document.addEventListener('DOMContentLoaded', function() {
        var btnSemua = document.getElementById('btn-semua-hadir');
        if (btnSemua) {
            btnSemua.addEventListener('click', function() {
                document.querySelectorAll('.presensi-radio').forEach(function(radio) {
                    radio.checked = (radio.value === 'Hadir');
                });
                refreshAllPills();
            });
        }

        // Render pill saat halaman selesai dimuat
        refreshAllPills();
    });

    // ── Deteksi perubahan tema (dark/light toggle) ──
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(m) {
            if (m.attributeName === 'class') {
                refreshAllPills();
            }
        });
    });
    observer.observe(document.documentElement, { attributes: true });

    // ── Kamera ──
    var video = document.getElementById('kamera-video');
    var canvas = document.getElementById('kamera-canvas');
    var imgResult = document.getElementById('hasil-foto');
    var hiddenInput = document.getElementById('foto_base64');
    var btnCapture = document.getElementById('btn-capture');
    var btnRetake = document.getElementById('btn-retake');
    var cameraArea = document.getElementById('camera-area');
    var resultArea = document.getElementById('result-area');

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(function(stream) { if (video) video.srcObject = stream; })
            .catch(function(err) { console.warn('Kamera tidak tersedia:', err); });
    }

    if (btnCapture) {
        btnCapture.addEventListener('click', function() {
            if (!video || !canvas) return;
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            var dataUrl = canvas.toDataURL('image/png');
            if (imgResult) imgResult.src = dataUrl;
            if (hiddenInput) hiddenInput.value = dataUrl;
            if (cameraArea) cameraArea.style.display = 'none';
            if (resultArea) resultArea.style.display = 'block';
        });
    }

    if (btnRetake) {
        btnRetake.addEventListener('click', function() {
            if (hiddenInput) hiddenInput.value = '';
            if (imgResult) imgResult.src = '';
            if (resultArea) resultArea.style.display = 'none';
            if (cameraArea) cameraArea.style.display = 'block';
        });
    }

})();
</script>
@endsection