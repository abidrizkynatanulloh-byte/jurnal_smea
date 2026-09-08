@extends('layouts.app')

@section('title', 'Input Jurnal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-4 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Input Jurnal Mengajar</h1>
            <p class="text-xs text-slate-500 mt-0.5">Lengkapi materi pembelajaran, kehadiran siswa, dan dokumentasi sesi mengajar</p>
        </div>
    </div>

    <!-- Info Sesi Mengajar Header Card -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-3.5">
        <div class="flex items-center space-x-2 pb-2.5 border-b border-slate-100 mb-2.5">
            <i data-lucide="info" class="w-4 h-4 text-slate-700"></i>
            <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Informasi Sesi Mengajar</h3>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kelas</span>
                <span class="text-xs font-bold text-slate-900">{{ $jadwal->kelas ? $jadwal->kelas->nama_kelas : '-' }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mata Pelajaran</span>
                <span class="text-xs font-semibold text-slate-800">{{ $jadwal->mapel ? $jadwal->mapel->nama_mapel : '-' }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ruangan & Jam</span>
                <span class="text-xs font-semibold text-slate-800">{{ $jadwal->ruangan ? $jadwal->ruangan->nama_ruangan : '-' }} (Jam {{ $jadwal->jam_mulai }}-{{ $jadwal->jam_selesai }})</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</span>
                <span class="text-xs font-semibold text-slate-800">{{ \Carbon\Carbon::parse($tanggalHariIni)->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Form Jurnal -->
    <form action="{{ route('guru.jurnal.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
        <input type="hidden" name="tanggal" value="{{ $tanggalHariIni }}">

        <!-- BAGIAN 1: FORM MATERI -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-3.5 space-y-3">
            <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider flex items-center space-x-1.5 pb-2 border-b border-slate-100">
                <i data-lucide="book-open" class="w-3.5 h-3.5 text-slate-700"></i>
                <span>Detail Pembelajaran</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="md:col-span-2">
                    <label for="materi" class="block text-xs font-semibold text-slate-700 mb-1">Materi / Topik yang Diajarkan *</label>
                    <textarea name="materi" id="materi" rows="3" placeholder="Contoh: Pengenalan dasar akuntansi, siklus jurnal umum dan penyesuaian..." required
                        class="block w-full p-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538] transition-all">{{ old('materi') }}</textarea>
                </div>

                <div class="space-y-2.5">
                    <div>
                        <label for="status_kehadiran_guru" class="block text-xs font-semibold text-slate-700 mb-1">Kehadiran Guru *</label>
                        <select name="status_kehadiran_guru" id="status_kehadiran_guru" required
                            class="block w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538] transition-all cursor-pointer">
                            <option value="Hadir" {{ old('status_kehadiran_guru', 'Hadir') == 'Hadir' ? 'selected' : '' }}>Hadir di Kelas</option>
                            <option value="Izin" {{ old('status_kehadiran_guru') == 'Izin' ? 'selected' : '' }}>Izin Terencana</option>
                            <option value="Sakit" {{ old('status_kehadiran_guru') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Tanpa Keterangan" {{ old('status_kehadiran_guru') == 'Tanpa Keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                        </select>
                    </div>

                    <div>
                        <label for="catatan" class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" id="catatan" rows="1" placeholder="Catatan kelas..."
                            class="block w-full p-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538] transition-all">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: ABSENSI SISWA (MENGGUNAKAN RADIO BUTTON & AUTO-STATUS) -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-3.5 py-2.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                        <i data-lucide="users-check" class="w-3.5 h-3.5 text-slate-700"></i>
                        <span>Presensi Siswa di Jam Mapel Ini</span>
                    </h3>
                </div>
                <span class="px-2 py-0.5 bg-slate-200 text-slate-700 rounded-full text-[11px] font-bold">
                    {{ count($siswaDiKelas) }} Siswa
                </span>
            </div>

            @if ($siswaDiKelas->isEmpty())
                <div class="p-8 text-center text-gray-400 italic">
                    Belum ada data siswa yang terdaftar di kelas ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-2 px-3 text-center w-10">No</th>
                                <th class="py-2 px-3">Nama Lengkap & NIS</th>
                                <th class="py-2 px-3">Status Sebelumnya</th>
                                <th class="py-2 px-3 text-center w-80">Pilihan Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($siswaDiKelas as $idx => $s)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2 px-3 text-center font-medium text-slate-400 text-xs tabular-nums">{{ $idx + 1 }}</td>
                                    <td class="py-2 px-3">
                                        <p class="font-semibold text-slate-900 text-xs">{{ $s->nama_siswa }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono">NIS: {{ $s->nis }}</p>
                                    </td>
                                    <td class="py-2 px-3">
                                        @if ($s->info_status)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-bold rounded space-x-1">
                                                <i data-lucide="info" class="w-3 h-3"></i>
                                                <span>{{ $s->info_status }}</span>
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-[11px] font-medium">Siap KBM</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <!-- RADIO BUTTON GROUP PRESENSI -->
                                        <div class="inline-flex items-center p-0.5 bg-slate-100 rounded-lg space-x-0.5 border border-slate-200">
                                            <!-- HADIR -->
                                            <label class="cursor-pointer select-none">
                                                <input type="radio" name="ketidakhadiran[{{ $s->nis }}]" value="Hadir" {{ $s->auto_status === 'Hadir' ? 'checked' : '' }} class="peer sr-only">
                                                <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold text-slate-500 peer-checked:bg-emerald-600 peer-checked:text-white transition-all inline-flex items-center shadow-2xs">
                                                    <span>Hadir</span>
                                                </span>
                                            </label>

                                            <!-- SAKIT -->
                                            <label class="cursor-pointer select-none">
                                                <input type="radio" name="ketidakhadiran[{{ $s->nis }}]" value="Sakit" {{ $s->auto_status === 'Sakit' ? 'checked' : '' }} class="peer sr-only">
                                                <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white transition-all inline-flex items-center shadow-2xs">
                                                    <span>Sakit</span>
                                                </span>
                                            </label>

                                            <!-- IZIN -->
                                            <label class="cursor-pointer select-none">
                                                <input type="radio" name="ketidakhadiran[{{ $s->nis }}]" value="Izin" {{ $s->auto_status === 'Izin' ? 'checked' : '' }} class="peer sr-only">
                                                <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold text-slate-500 peer-checked:bg-amber-500 peer-checked:text-white transition-all inline-flex items-center shadow-2xs">
                                                    <span>Izin</span>
                                                </span>
                                            </label>

                                            <!-- ALPA -->
                                            <label class="cursor-pointer select-none">
                                                <input type="radio" name="ketidakhadiran[{{ $s->nis }}]" value="Alpa" class="peer sr-only">
                                                <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold text-slate-500 peer-checked:bg-rose-600 peer-checked:text-white transition-all inline-flex items-center shadow-2xs">
                                                    <span>Alpa</span>
                                                </span>
                                            </label>

                                            <!-- DISPEN -->
                                            <label class="cursor-pointer select-none">
                                                <input type="radio" name="ketidakhadiran[{{ $s->nis }}]" value="Dispen" {{ $s->auto_status === 'Dispen' ? 'checked' : '' }} class="peer sr-only">
                                                <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold text-slate-500 peer-checked:bg-indigo-600 peer-checked:text-white transition-all inline-flex items-center shadow-2xs">
                                                    <span>Dispen</span>
                                                </span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- BAGIAN 3: DOKUMENTASI LIVE KAMERA -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-3.5 space-y-3">
            <div class="flex items-center space-x-2 pb-2 border-b border-slate-100">
                <i data-lucide="camera" class="w-4 h-4 text-slate-700"></i>
                <div>
                    <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Dokumentasi Pembelajaran (Kamera Langsung)</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Ambil foto dokumentasi aktivitas belajar mengajar secara langsung</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 items-start">
                <!-- Area Kamera Live -->
                <div id="camera-area" class="w-full max-w-xs space-y-2">
                    <div class="rounded-lg overflow-hidden bg-black border border-slate-200 aspect-video relative flex items-center justify-center">
                        <video id="kamera-video" class="w-full h-full object-cover" autoplay playsinline></video>
                    </div>
                    <button type="button" id="btn-capture" class="w-full h-8.5 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors shadow-2xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                        <span>Jepret Foto Sekarang</span>
                    </button>
                </div>

                <!-- Area Hasil Foto Preview -->
                <div id="result-area" class="w-full max-w-xs space-y-2" style="display: none;">
                    <div class="rounded-lg overflow-hidden bg-black border border-slate-200 aspect-video relative flex items-center justify-center">
                        <img id="hasil-foto" class="w-full h-full object-cover" />
                    </div>
                    <button type="button" id="btn-retake" class="w-full h-8.5 px-3.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-semibold transition-colors shadow-2xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Ulangi Pengambilan Foto</span>
                    </button>
                </div>

                <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-500 space-y-1 flex-1">
                    <p class="font-bold text-slate-900">Petunjuk Dokumentasi:</p>
                    <p>• Pastikan siswa dan ruang kelas terlihat jelas pada bidikan foto.</p>
                    <p>• Tombol jepret akan mengunci gambar secara otomatis ke formulir jurnal.</p>
                    <p>• Jika hasil foto kurang jelas, Anda dapat menekan tombol ulangi foto.</p>
                </div>

                <!-- Hidden inputs & canvas -->
                <input type="hidden" name="foto_base64" id="foto_base64">
                <canvas id="kamera-canvas" style="display:none;"></canvas>
            </div>
        </div>

        <!-- Tombol Simpan & Batal -->
        <div class="flex items-center space-x-2.5 pt-2">
            <button type="submit" class="h-10.5 px-5 bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl font-bold text-xs sm:text-sm transition-all shadow-xs flex items-center space-x-2 cursor-pointer">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Simpan Jurnal & Presensi</span>
            </button>
            <a href="{{ route('guru.dashboard') }}" class="h-10.5 px-4.5 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-xs sm:text-sm font-semibold transition-colors flex items-center justify-center">
                Batal
            </a>
        </div>
    </form>
</div>

<!-- Kamera Script -->
<script>
    const video = document.getElementById('kamera-video');
    const canvas = document.getElementById('kamera-canvas');
    const imgResult = document.getElementById('hasil-foto');
    const hiddenInput = document.getElementById('foto_base64');
    const btnCapture = document.getElementById('btn-capture');
    const btnRetake = document.getElementById('btn-retake');
    const cameraArea = document.getElementById('camera-area');
    const resultArea = document.getElementById('result-area');

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
            video.srcObject = stream;
        } catch (err) {
            console.warn("Kamera tidak tersedia atau tidak diizinkan.", err);
        }
    }

    startCamera();

    if (btnCapture) {
        btnCapture.addEventListener('click', () => {
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/png');
            imgResult.src = dataUrl;
            hiddenInput.value = dataUrl;
            cameraArea.style.display = 'none';
            resultArea.style.display = 'block';
        });
    }

    if (btnRetake) {
        btnRetake.addEventListener('click', () => {
            hiddenInput.value = '';
            imgResult.src = '';
            resultArea.style.display = 'none';
            cameraArea.style.display = 'block';
        });
    }
</script>
@endsection