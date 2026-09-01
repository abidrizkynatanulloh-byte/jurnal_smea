<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Jurnal Mengajar</title>
</head>
<body>

    <a href="{{ route('guru.dashboard') }}">← Kembali ke Dashboard</a>
    <h1>📋 Detail Jurnal Mengajar</h1>
    <hr>

    @if (session('success'))
        <p style="color: green;"><b>{{ session('success') }}</b></p>
    @endif

    <!-- Info Jurnal -->
    <fieldset style="margin-bottom: 20px;">
        <legend><b>📝 Data Jurnal</b></legend>
        <table>
            <tr>
                <td><b>Tanggal</b></td>
                <td>: {{ \Carbon\Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</td>
            </tr>
            <tr>
                <td><b>Kelas</b></td>
                <td>: {{ $jurnal->jadwal->kelas ? $jurnal->jadwal->kelas->nama_kelas : '-' }}</td>
            </tr>
            <tr>
                <td><b>Mata Pelajaran</b></td>
                <td>: {{ $jurnal->jadwal->mapel ? $jurnal->jadwal->mapel->nama_mapel : '-' }}</td>
            </tr>
            <tr>
                <td><b>Ruangan</b></td>
                <td>: {{ $jurnal->jadwal->ruangan ? $jurnal->jadwal->ruangan->nama_ruangan : '-' }}</td>
            </tr>
            <tr>
                <td><b>Jam Ke-</b></td>
                <td>: {{ $jurnal->jadwal->jam_mulai }} - {{ $jurnal->jadwal->jam_selesai }}</td>
            </tr>
            <tr>
                <td><b>Status Kehadiran Guru</b></td>
                <td>:
                    @if ($jurnal->status_kehadiran_guru == 'Hadir')
                        <b style="color: green;">✓ Hadir</b>
                    @else
                        <b style="color: red;">{{ $jurnal->status_kehadiran_guru }}</b>
                    @endif
                </td>
            </tr>
            <tr>
                <td><b>Materi</b></td>
                <td>: {{ $jurnal->materi }}</td>
            </tr>
            <tr>
                <td><b>Catatan</b></td>
                <td>: {{ $jurnal->catatan ?? '-' }}</td>
            </tr>
            <tr>
                <td><b>Dicatat Pada</b></td>
                <td>: {{ $jurnal->dicatat_pada }}</td>
            </tr>
                        <tr>
                <td><b>Bukti Foto Mengajar</b></td>
                <td>: 
                    <br>
                    @if($jurnal->foto)
                        <img src="{{ asset('storage/' . $jurnal->foto->foto_path) }}" alt="Bukti Mengajar" width="300" style="border: 2px solid #ccc; border-radius: 8px; margin-top: 5px;">
                    @else
                        <i style="color: red;">Tidak ada foto bukti</i>
                    @endif
                </td>
            </tr>
        </table>
    </fieldset>

    <!-- Daftar Ketidakhadiran Siswa -->
    <fieldset>
        <legend><b>🎓 Siswa Tidak Hadir ({{ $ketidakhadiran->count() }} Siswa)</b></legend>

        @if ($ketidakhadiran->isEmpty())
            <p style="color: green;"><b>✅ Semua siswa hadir pada sesi ini.</b></p>
        @else
            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead>
                    <tr bgcolor="#ffe0e0">
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ketidakhadiran as $idx => $k)
                        <tr>
                            <td align="center">{{ $idx + 1 }}</td>
                            <td>{{ $k->id_siswa }}</td>
                            <td><b>{{ $k->siswa ? $k->siswa->nama_siswa : '-' }}</b></td>
                            <td>
                                @if ($k->keterangan == 'Sakit')
                                    <span style="color: orange;">🤒 Sakit</span>
                                @elseif ($k->keterangan == 'Izin')
                                    <span style="color: blue;">📄 Izin</span>
                                @else
                                    <span style="color: red; font-weight: bold;">❌ Alpa</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </fieldset>

</body>
</html>