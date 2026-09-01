<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Guru - Jurnal Esemkita</title>
</head>
<body>

    <h1>Dashboard Guru</h1>
    <p><b>Selamat datang, {{ $guru->nama_guru ?? 'Guru' }}</b> | {{ $tanggalTeks ?? '' }}</p>

    <a href="{{ route('guru.jurnal.rekap') }}">📋 Rekap Jurnal Saya</a> |
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Keluar (Logout)</button>
    </form>
    <hr>

    @if (session('success'))
        <p style="color: green;"><b>✅ {{ session('success') }}</b></p>
    @endif
    @if (session('info'))
        <p style="color: blue;"><b>ℹ️ {{ session('info') }}</b></p>
    @endif

    <!-- ========================================================== -->
    <!-- STATISTIK RINGKASAN                                         -->
    <!-- ========================================================== -->
    <h3>📊 Statistik Minggu Ini:</h3>
    <ul>
        <li><b>Total Jadwal Semua:</b> {{ $totalJadwalSemua ?? 0 }} Sesi</li>
        <li><b>Sesi Hari Ini ({{ $namaHariIni ?? '' }}):</b> {{ $totalSesiHariIni ?? 0 }} Sesi</li>
        <li><b>Sudah Diisi Hari Ini:</b> {{ $sudahDiisiHariIni ?? 0 }} / {{ $totalSesiHariIni ?? 0 }} Sesi</li>
        <li>
            <b>Belum Diisi Minggu Ini:</b>
            @if (($belumIsiMingguIni ?? 0) > 0)
                <span style="color: red;"><b>{{ $belumIsiMingguIni }} Sesi BELUM diisi!</b></span>
            @else
                <span style="color: green;"><b>Semua sudah diisi 🎉</b></span>
            @endif
        </li>
    </ul>
    <hr>

    <!-- ========================================================== -->
    <!-- TABEL JADWAL HARI INI                                       -->
    <!-- ========================================================== -->
    <h3>📅 Jadwal Mengajar Hari Ini ({{ $namaHariIni ?? '' }}):</h3>

    @if (!isset($jadwalHariIni) || $jadwalHariIni->isEmpty())
        <p><i>Tidak ada jadwal mengajar hari ini. Selamat istirahat! 😊</i></p>
    @else
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr bgcolor="#f0f0f0">
                    <th>Jam Ke-</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Ruangan</th>
                    <th>Status Jurnal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                                @foreach ($jadwalHariIni as $j)
                    @php $statusWaktu = $j->statusWaktuMengajar(); @endphp
                    <tr>
                        <td align="center">Jam {{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                        <td><b>{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</b></td>
                        <td>{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</td>
                        <td>{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</td>

                        {{-- Kolom 5: STATUS JURNAL --}}
                        <td align="center">
                            @if ($j->sudah_diisi)
                                <b style="color: green;">✓ Sudah Diisi</b>
                            @elseif ($statusWaktu === 'sekarang')
                                <b style="color: orange;">⏳ Belum Diisi</b>
                            @elseif ($statusWaktu === 'belum')
                                <b style="color: gray;">🕐 Belum Mulai</b>
                            @elseif ($statusWaktu === 'telat')
                                <b style="color: red;">❌ Tidak Hadir (Alpa)</b>
                            @else
                                <b style="color: gray;">-</b>
                            @endif
                        </td>

                        {{-- Kolom 6: AKSI --}}
                        <td align="center">
                            @if ($j->sudah_diisi)
                                <a href="{{ route('guru.jurnal.show', $j->jurnal->id_jurnal) }}" style="color: blue;">
                                    👁️ Lihat Jurnal
                                </a>
                            @elseif ($statusWaktu === 'sekarang')
                                <a href="{{ route('guru.jurnal.create', $j->id_jadwal) }}"
                                   style="background: green; color: white; padding: 4px 10px; text-decoration: none; border-radius: 4px;">
                                    ✏️ Isi Jurnal
                                </a>
                            @elseif ($statusWaktu === 'belum')
                                <span style="background: orange; color: white; padding: 4px 10px; border-radius: 4px; cursor: not-allowed;">
                                    ⏳ Belum Mulai
                                </span>
                            @elseif ($statusWaktu === 'telat')
                                <span style="background: red; color: white; padding: 4px 10px; border-radius: 4px; cursor: not-allowed;">
                                    🔒 Waktu Habis
                                </span>
                            @else
                                <span style="color: gray;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>