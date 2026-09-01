<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Isi Jurnal Mengajar</title>
</head>
<body>

    <a href="{{ route('guru.dashboard') }}">← Kembali ke Dashboard</a>
    <h1>✏️ Input Jurnal Mengajar</h1>
    <hr>

    <!-- Info Sesi -->
    <fieldset style="margin-bottom: 15px;">
        <legend><b>📋 Info Sesi Mengajar</b></legend>
        <ul>
            <li><b>Kelas:</b> {{ $jadwal->kelas ? $jadwal->kelas->nama_kelas : '-' }}</li>
            <li><b>Mata Pelajaran:</b> {{ $jadwal->mapel ? $jadwal->mapel->nama_mapel : '-' }}</li>
            <li><b>Ruangan:</b> {{ $jadwal->ruangan ? $jadwal->ruangan->nama_ruangan : '-' }}</li>
            <li><b>Jam Ke-:</b> {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</li>
            <li><b>Tanggal:</b> {{ \Carbon\Carbon::parse($tanggalHariIni)->locale('id')->isoFormat('dddd, D MMMM Y') }}</li>
        </ul>
    </fieldset>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.jurnal.store') }}" method="POST">
        @csrf

        {{-- Field tersembunyi --}}
        <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
        <input type="hidden" name="tanggal" value="{{ $tanggalHariIni }}">

        <!-- ============================================================ -->
        <!-- BAGIAN 1: FORM JURNAL MENGAJAR                               -->
        <!-- ============================================================ -->
        <fieldset style="margin-bottom: 20px;">
            <legend><h3>📝 Jurnal Mengajar</h3></legend>

            <div>
                <label for="materi"><b>Materi / Topik yang Diajarkan:</b></label><br>
                <textarea name="materi" id="materi" rows="4" cols="60"
                    placeholder="Contoh: Pengenalan algoritma sorting, Bubble Sort dan implementasinya..."
                    required>{{ old('materi') }}</textarea>
            </div>
            <br>

            <div>
                <label for="status_kehadiran_guru"><b>Status Kehadiran Guru:</b></label><br>
                <select name="status_kehadiran_guru" id="status_kehadiran_guru" required>
                    <option value="Hadir" {{ old('status_kehadiran_guru', 'Hadir') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="Izin" {{ old('status_kehadiran_guru') == 'Izin' ? 'selected' : '' }}>Izin</option>
                    <option value="Sakit" {{ old('status_kehadiran_guru') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="Tanpa Keterangan" {{ old('status_kehadiran_guru') == 'Tanpa Keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                </select>
            </div>
            <br>

            <div>
                <label for="catatan"><b>Catatan Tambahan:</b> <small>(opsional)</small></label><br>
                <textarea name="catatan" id="catatan" rows="2" cols="60"
                    placeholder="Catatan untuk admin / kepala sekolah...">{{ old('catatan') }}</textarea>
            </div>
        </fieldset>

        <!-- ============================================================ -->
        <!-- BAGIAN 2: ABSENSI SISWA                                      -->
        <!-- ============================================================ -->
        <fieldset style="margin-bottom: 20px;">
            <legend><h3>🎓 Absensi Siswa (Tandai yang TIDAK HADIR saja)</h3></legend>
            <p><i>Siswa yang tidak ditandai dianggap <b>HADIR</b>.</i></p>

            @if ($siswaDiKelas->isEmpty())
                <p style="color: orange;"><i>Belum ada data siswa di kelas ini.</i></p>
            @else
                <table border="1" cellpadding="6" cellspacing="0" width="100%">
                    <thead>
                        <tr bgcolor="#f0f0f0">
                            <th width="5%">No</th>
                            <th width="40%">Nama Siswa</th>
                            <th width="15%">NIS</th>
                            <th width="20%">Info Izin</th>
                            <th width="20%">Keterangan Tidak Hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswaDiKelas as $idx => $s)
                            <tr>
                                <td align="center">{{ $idx + 1 }}</td>
                                <td><b>{{ $s->nama_siswa }}</b></td>
                                <td>{{ $s->nis }}</td>
                                <td>
                                    {{-- Jika siswa punya izin yang sudah disetujui hari ini --}}
                                    @if ($s->izin_hari_ini)
                                        <span style="color: blue; font-weight: bold;">
                                            📋 Ada Izin: {{ $s->izin_hari_ini->jenis_izin }}
                                        </span>
                                    @else
                                        <span style="color: gray;"><i>-</i></span>
                                    @endif
                                </td>
                                <td>
                                    <select name="ketidakhadiran[{{ $s->nis }}]">
                                        {{-- Opsi kosong = Hadir (tidak akan disimpan) --}}
                                        <option value="">✅ Hadir</option>
                                        <option value="Sakit"
                                            {{ $s->izin_hari_ini && $s->izin_hari_ini->jenis_izin == 'Sakit' ? 'selected' : '' }}>
                                            🤒 Sakit
                                        </option>
                                        <option value="Izin"
                                            {{ $s->izin_hari_ini && $s->izin_hari_ini->jenis_izin == 'Izin' ? 'selected' : '' }}>
                                            📄 Izin
                                        </option>
                                        <option value="Alpa">❌ Alpa</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </fieldset>

                    <!-- ============================================================ -->
            <!-- BAGIAN FITUR KAMERA LIVE ANTI-KECURANGAN                     -->
            <!-- ============================================================ -->
            <div>
                <label><b>📷 Ambil Foto Kegiatan (Wajib Kamera Langsung):</b></label><br>
                
                <!-- Area Kamera -->
                <div id="camera-area" style="margin-bottom: 10px;">
                    <video id="kamera-video" width="100%" style="max-width: 400px; border: 1px solid #ccc; background: #000;" autoplay playsinline></video>
                    <br>
                    <button type="button" id="btn-capture" style="background: blue; color: white; padding: 8px 15px; margin-top: 5px; cursor:pointer;">
                        📸 Jepret Foto
                    </button>
                </div>

                <!-- Area Hasil Foto (Awalnya Disembunyikan) -->
                <div id="result-area" style="display: none; margin-bottom: 10px;">
                    <img id="hasil-foto" width="100%" style="max-width: 400px; border: 1px solid #ccc;" />
                    <br>
                    <button type="button" id="btn-retake" style="background: orange; color: white; padding: 8px 15px; margin-top: 5px; cursor:pointer;">
                        🔄 Ulangi Foto
                    </button>
                </div>

                <!-- Input tersembunyi untuk mengirim gambar ke server -->
                <input type="hidden" name="foto_base64" id="foto_base64">
                
                <!-- Canvas tersembunyi untuk memproses tangkapan gambar -->
                <canvas id="kamera-canvas" style="display:none;"></canvas>
            </div>

        <button type="submit"
            style="background: green; color: white; padding: 10px 30px; font-size: 16px; cursor: pointer;">
            💾 Simpan Jurnal
        </button>
        <a href="{{ route('guru.dashboard') }}" style="margin-left: 15px;">Batal</a>
    </form>

        <!-- Script untuk menyalakan kamera -->
    <script>
        const video = document.getElementById('kamera-video');
        const canvas = document.getElementById('kamera-canvas');
        const imgResult = document.getElementById('hasil-foto');
        const hiddenInput = document.getElementById('foto_base64');
        const btnCapture = document.getElementById('btn-capture');
        const btnRetake = document.getElementById('btn-retake');
        const cameraArea = document.getElementById('camera-area');
        const resultArea = document.getElementById('result-area');

        // Nyalakan kamera (Prioritaskan kamera belakang HP: 'environment')
        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
                video.srcObject = stream;
            } catch (err) {
                alert("Gagal mengakses kamera! Pastikan kamu memberi izin (allow) akses kamera di browser.");
            }
        }

        // Jalankan kamera saat halaman dimuat
        startCamera();

        // Saat tombol 'Jepret Foto' ditekan
        btnCapture.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Gambar frame video ke canvas
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Ubah gambar menjadi format Teks (Base64)
            const dataUrl = canvas.toDataURL('image/png');
            
            // Tampilkan foto hasil jepretan & masukkan datanya ke form tersembunyi
            imgResult.src = dataUrl;
            hiddenInput.value = dataUrl;
            
            // Matikan tampilan video, munculkan hasil
            cameraArea.style.display = 'none';
            resultArea.style.display = 'block';
        });

        // Saat tombol 'Ulangi Foto' ditekan
        btnRetake.addEventListener('click', () => {
            hiddenInput.value = '';
            imgResult.src = '';
            
            resultArea.style.display = 'none';
            cameraArea.style.display = 'block';
        });
    </script>
</body>
</html>