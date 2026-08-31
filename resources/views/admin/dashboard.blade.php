<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Tata Usaha</title>
</head>
<body>

    <h1>Dashboard Tata Usaha</h1>
    <p><b>{{ $tanggalHariIniTeks }}</b> | Ringkasan operasional sekolah hari ini</p>
    
<div style="margin-bottom: 15px;">
    <b>Menu Navigasi:</b>
    <a href="{{ route('admin.guru.index') }}">👨‍🏫 Data Guru</a> | 
    <a href="{{ route('admin.siswa.index') }}">🎓 Data Siswa</a> | 
    <a href="{{ route('admin.mapel.index') }}">📚 Mata Pelajaran</a> | 
    <a href="{{ route('admin.jam.index') }}">⏰ Master Jam</a> | 
    <a href="{{ route('admin.jadwal.index') }}">📅 Jadwal Mengajar</a> | 
    <a href="{{ route('admin.rekap.index') }}">📋 Rekap Jurnal</a> | 
    
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Keluar (Logout)</button>
    </form>
</div>
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Keluar (Logout)</button>
    </form>
    <hr>

    <!-- ========================================================== -->
    <!-- 1. EMPAT KARTU STATISTIK (DATA MENTAH)                      -->
    <!-- ========================================================== -->
    <h3>📊 Ringkasan Statistik:</h3>
    <ul>
        <li><b>Total Siswa Aktif:</b> {{ $totalSiswa }} Siswa</li>
        <li><b>Guru & Pegawai:</b> {{ $totalPegawai }} Orang ({{ $totalGuru }} Guru, {{ $totalStaf }} Staf TU)</li>
        <li><b>Total Jadwal Mengajar:</b> {{ $totalJadwal }} Jadwal Aktif</li>
        <li><b>Kepatuhan Jurnal Hari Ini:</b> {{ $persentaseKepatuhan }}% ({{ $jurnalTerisiHariIni }} dari {{ $totalJadwalHariIni }} sesi hari ini)</li>
    </ul>
    <hr>

    <!-- ========================================================== -->
    <!-- 2. TABEL JADWAL HARI INI (HARI: {{ $namaHariIni }})         -->
    <!-- ========================================================== -->
        <!-- ========================================================== -->
    <!-- 2. TABEL JADWAL HARI INI (SLIDE 10 DATA PER TAMPILAN)      -->
    <!-- ========================================================== -->
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3>📅 Jadwal Mengajar Hari Ini ({{ $namaHariIni }}):</h3>
        
        {{-- Info Slide Sesi --}}
        <div>
            <span id="slideInfo" style="font-weight: bold;">Menampilkan 1-10 dari {{ count($jadwalHariIni) }} sesi</span>
            &nbsp;
            <button type="button" id="prevBtn" onclick="geserSlide(-1)" style="cursor: pointer;" disabled>« Sebelumnya</button>
            <button type="button" id="nextBtn" onclick="geserSlide(1)" style="cursor: pointer;">Selanjutnya »</button>
        </div>
    </div>

    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <thead>
            <tr bgcolor="#f0f0f0">
                <th width="5%">No</th>
                <th width="15%">Jam Ke-</th>
                <th width="15%">Kelas</th>
                <th width="25%">Guru</th>
                <th width="20%">Mata Pelajaran</th>
                <th width="10%">Ruangan</th>
                <th width="10%">Status Jurnal</th>
            </tr>
        </thead>
        <tbody id="jadwalTbody">
            @forelse ($jadwalHariIni as $index => $j)
                {{-- Data row jadwal dengan penanda index baris --}}
                <tr class="jadwal-row" data-index="{{ $index }}" style="{{ $index >= 10 ? 'display: none;' : '' }}">
                    <td align="center">{{ $index + 1 }}</td>
                    <td>Jam ke {{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                    <td><b>{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</b></td>
                    <td>{{ $j->guru ? $j->guru->nama_guru : '-' }}</td>
                    <td>{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</td>
                    <td>{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</td>
                    <td>
                        @if ($j->status_jurnal === 'Selesai')
                            <b style="color: green;">✓ Selesai</b>
                        @else
                            <b style="color: orange;">⏳ Terjadwal</b>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" align="center"><i>Tidak ada jadwal mengajar pada hari {{ $namaHariIni }}.</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ========================================================== -->
    <!-- SCRIPT JAVASCRIPT SLIDE INSTAN (10 DATA PER SLIDE)         -->
    <!-- ========================================================== -->
    <script>
        let currentSlide = 0;
        const perSlide = 10;
        const rows = document.querySelectorAll('.jadwal-row');
        const totalRows = rows.length;
        const totalSlides = Math.ceil(totalRows / perSlide);

        function updateSlideView() {
            const start = currentSlide * perSlide;
            const end = start + perSlide;

            // Sembunyikan/tampilkan baris sesuai slide yang aktif
            rows.forEach((row, idx) => {
                if (idx >= start && idx < end) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Update teks keterangan info (contoh: Menampilkan 11-20 dari 166 sesi)
            const tampilSampai = Math.min(end, totalRows);
            const tampilMulai = totalRows > 0 ? start + 1 : 0;
            document.getElementById('slideInfo').innerText = `Menampilkan ${tampilMulai}-${tampilSampai} dari ${totalRows} sesi`;

            // Atur tombol aktif/nonaktif
            document.getElementById('prevBtn').disabled = (currentSlide === 0);
            document.getElementById('nextBtn').disabled = (currentSlide >= totalSlides - 1);
        }

        function geserSlide(arah) {
            currentSlide += arah;
            if (currentSlide < 0) currentSlide = 0;
            if (currentSlide >= totalSlides) currentSlide = totalSlides - 1;
            updateSlideView();
        }
    </script>
    <hr>

    <!-- ========================================================== -->
    <!-- 3. WIDGET PERLU TINDAKAN                                  -->
    <!-- ========================================================== -->
    <h3>⚠️ Perlu Tindakan:</h3>
    <ul>
        <li><b>{{ $guruBelumIsiKemarin }} sesi mengajar</b> belum diisi jurnal kemarin.</li>
        <li><b>{{ $siswaAlpaHariIni }} siswa</b> tercatat tanpa keterangan (Alpa) hari ini.</li>
    </ul>

</body>
</html>