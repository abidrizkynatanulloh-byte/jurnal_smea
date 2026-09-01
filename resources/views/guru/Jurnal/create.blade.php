@extends('layouts.app')

@section('title', 'Input Jurnal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-[#405078] hover:underline flex items-center space-x-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Dashboard Guru</span>
                </a>
            </div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Input Jurnal Mengajar</h1>
            <p class="text-xs text-gray-500 mt-0.5">Lengkapi materi pembelajaran, kehadiran siswa, dan dokumentasi sesi mengajar</p>
        </div>
    </div>

    <!-- Info Sesi Mengajar Header Card -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6">
        <div class="flex items-center space-x-2.5 mb-4 pb-3 border-b border-gray-100">
            <i data-lucide="info" class="w-5 h-5 text-[#405078]"></i>
            <h3 class="font-bold text-[#1E2538] text-sm">Informasi Sesi Mengajar</h3>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kelas</span>
                <span class="text-sm font-bold text-[#1E2538]">{{ $jadwal->kelas ? $jadwal->kelas->nama_kelas : '-' }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Mata Pelajaran</span>
                <span class="text-sm font-semibold text-[#1E2538]">{{ $jadwal->mapel ? $jadwal->mapel->nama_mapel : '-' }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Ruangan & Jam</span>
                <span class="text-sm font-semibold text-[#1E2538]">{{ $jadwal->ruangan ? $jadwal->ruangan->nama_ruangan : '-' }} (Jam {{ $jadwal->jam_mulai }}-{{ $jadwal->jam_selesai }})</span>
            </div>
            <div>
                <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tanggal</span>
                <span class="text-sm font-semibold text-[#1E2538]">{{ \Carbon\Carbon::parse($tanggalHariIni)->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Form Jurnal -->
    <form action="{{ route('guru.jurnal.store') }}" method="POST" class="space-y-8">
        @csrf
        <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
        <input type="hidden" name="tanggal" value="{{ $tanggalHariIni }}">

        <!-- BAGIAN 1: FORM MATERI -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 space-y-5">
            <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2 pb-3 border-b border-gray-100">
                <i data-lucide="book-open" class="w-5 h-5 text-[#405078]"></i>
                <span>Detail Pembelajaran</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <label for="materi" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Materi / Topik yang Diajarkan *</label>
                    <textarea name="materi" id="materi" rows="4" placeholder="Contoh: Pengenalan dasar akuntansi, siklus jurnal umum dan penyesuaian..." required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">{{ old('materi') }}</textarea>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="status_kehadiran_guru" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kehadiran Guru *</label>
                        <select name="status_kehadiran_guru" id="status_kehadiran_guru" required
                            class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                            <option value="Hadir" {{ old('status_kehadiran_guru', 'Hadir') == 'Hadir' ? 'selected' : '' }}>Hadir di Kelas</option>
                            <option value="Izin" {{ old('status_kehadiran_guru') == 'Izin' ? 'selected' : '' }}>Izin Terencana</option>
                            <option value="Sakit" {{ old('status_kehadiran_guru') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Tanpa Keterangan" {{ old('status_kehadiran_guru') == 'Tanpa Keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                        </select>
                    </div>

                    <div>
                        <label for="catatan" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" id="catatan" rows="2" placeholder="Catatan kelas..."
                            class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: ABSENSI SISWA -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                        <i data-lucide="users-check" class="w-5 h-5 text-[#405078]"></i>
                        <span>Presensi Siswa di Jam Ini</span>
                    </h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Tandai siswa yang TIDAK HADIR saja. Siswa yang tidak ditandai otomatis dihitung <b>Hadir</b>.</p>
                </div>
                <span class="px-3 py-1 bg-[#405078]/10 text-[#405078] rounded-full text-xs font-bold">
                    {{ count($siswaDiKelas) }} Siswa
                </span>
            </div>

            @if ($siswaDiKelas->isEmpty())
                <div class="p-8 text-center text-gray-400 italic">
                    Belum ada data siswa yang terdaftar di kelas ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-3.5 px-6 text-center w-16">No</th>
                                <th class="py-3.5 px-6">Nama Siswa</th>
                                <th class="py-3.5 px-6 w-32">NIS</th>
                                <th class="py-3.5 px-6 w-48">Info Izin Hari Ini</th>
                                <th class="py-3.5 px-6 w-48 text-center">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @foreach ($siswaDiKelas as $idx => $s)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3.5 px-6 text-center font-medium text-gray-400">{{ $idx + 1 }}</td>
                                    <td class="py-3.5 px-6 font-bold text-[#1E2538]">{{ $s->nama_siswa }}</td>
                                    <td class="py-3.5 px-6 text-gray-500 text-xs">{{ $s->nis }}</td>
                                    <td class="py-3.5 px-6">
                                        @if ($s->izin_hari_ini)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-full space-x-1">
                                                <span>📋 Izin: {{ $s->izin_hari_ini->jenis_izin }}</span>
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-6 text-center">
                                        <select name="ketidakhadiran[{{ $s->nis }}]" 
                                            class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-lg text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078] cursor-pointer">
                                            <option value="">✅ Hadir</option>
                                            <option value="Sakit" {{ $s->izin_hari_ini && $s->izin_hari_ini->jenis_izin == 'Sakit' ? 'selected' : '' }}>🤒 Sakit</option>
                                            <option value="Izin" {{ $s->izin_hari_ini && $s->izin_hari_ini->jenis_izin == 'Izin' ? 'selected' : '' }}>📄 Izin</option>
                                            <option value="Alpa">❌ Alpa</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- BAGIAN 3: DOKUMENTASI LIVE KAMERA -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 space-y-4">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-gray-100">
                <i data-lucide="camera" class="w-5 h-5 text-[#405078]"></i>
                <div>
                    <h3 class="font-bold text-[#1E2538] text-base">Dokumentasi Pembelajaran (Kamera Langsung)</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Ambil foto dokumentasi aktivitas belajar mengajar secara langsung</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-6 items-start">
                <!-- Area Kamera Live -->
                <div id="camera-area" class="w-full max-w-sm space-y-3">
                    <div class="rounded-xl overflow-hidden bg-black border border-[#D1D9EB] aspect-video relative flex items-center justify-center">
                        <video id="kamera-video" class="w-full h-full object-cover" autoplay playsinline></video>
                    </div>
                    <button type="button" id="btn-capture" class="w-full py-2.5 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                        <i data-lucide="camera" class="w-4 h-4"></i>
                        <span>Jepret Foto Sekarang</span>
                    </button>
                </div>

                <!-- Area Hasil Foto Preview -->
                <div id="result-area" class="w-full max-w-sm space-y-3" style="display: none;">
                    <div class="rounded-xl overflow-hidden bg-black border border-[#D1D9EB] aspect-video relative flex items-center justify-center">
                        <img id="hasil-foto" class="w-full h-full object-cover" />
                    </div>
                    <button type="button" id="btn-retake" class="w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        <span>Ulangi Pengambilan Foto</span>
                    </button>
                </div>

                <div class="p-4 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-gray-500 space-y-1.5 flex-1">
                    <p class="font-bold text-[#1E2538]">Petunjuk Dokumentasi:</p>
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
        <div class="flex items-center space-x-4 pt-2">
            <button type="submit" class="py-3 px-8 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-md shadow-[#405078]/20 flex items-center space-x-2 cursor-pointer">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Simpan Jurnal & Presensi</span>
            </button>
            <a href="{{ route('guru.dashboard') }}" class="px-6 py-3 border border-[#D1D9EB] hover:bg-white text-gray-500 hover:text-[#1E2538] rounded-xl text-sm font-semibold transition-colors">
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