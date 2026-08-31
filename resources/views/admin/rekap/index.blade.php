<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Jurnal & Kehadiran - Admin</title>
</head>
<body>

    <a href="{{ route('admin.dashboard') }}">← Kembali ke Dashboard</a>
    <h1>Rekapitulasi Jurnal & Kehadiran</h1>
    <hr>

    <!-- FILTER TANGGAL -->
    <fieldset style="margin-bottom: 20px;">
        <legend><b>📅 Filter Tanggal Rekap</b></legend>
        <form action="{{ route('admin.rekap.index') }}" method="GET">
            <label for="tanggal">Pilih Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}">
            <button type="submit">Filter</button>
            <a href="{{ route('admin.rekap.index') }}"><button type="button">Hari Ini</button></a>
        </form>
    </fieldset>

    <!-- ========================================================== -->
    <!-- TABEL 1: LAPORAN GURU ALPA (TIDAK MENGISI JURNAL)          -->
    <!-- ========================================================== -->
    <h2 style="color: red;">⚠️ Laporan Guru Alpa - {{ $namaHari }}, {{ $tanggal }} ({{ $guruAlpaList->count() }})</h2>

    <table border="1" cellpadding="8" cellspacing="0" width="100%" bgcolor="#fff5f5">
        <thead>
            <tr bgcolor="#ffe0e0">
                <th>Jam Ke-</th>
                <th>Guru & Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($guruAlpaList as $ga)
                <tr>
                    <td>Jam {{ $ga->jam_mulai }} - {{ $ga->jam_selesai }}</td>
                    <td>
                        <b>{{ $ga->guru ? $ga->guru->nama_guru : '-' }}</b> <br>
                        <small>{{ $ga->mapel ? $ga->mapel->nama_mapel : '-' }}</small>
                    </td>
                    <td>{{ $ga->kelas ? $ga->kelas->nama_kelas : '-' }}</td>
                    <td>
                        <span style="background-color: #ffcccc; color: red; padding: 3px 8px; font-weight: bold; border-radius: 4px;">
                            TIDAK MENGISI JURNAL (ALPA)
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" align="center" style="color: green;"><b>✓ Hebat! Semua guru mengisi jurnal pada tanggal ini.</b></td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br><hr>

    <!-- ========================================================== -->
    <!-- TABEL 2: JURNAL TERSIMPAN                                  -->
    <!-- ========================================================== -->
    <h2 style="color: green;">📋 Jurnal Tersimpan - {{ $namaHari }}, {{ $tanggal }} ({{ $jurnalTersimpan->count() }})</h2>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr bgcolor="#f0f0f0">
                <th>Tanggal</th>
                <th>Guru & Mapel</th>
                <th>Kelas</th>
                <th>Status Guru</th>
                <th>Materi Pembelajaran</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jurnalTersimpan as $jt)
                <tr>
                    <td>{{ $jt->tanggal }}</td>
                    <td>
                        <b>{{ $jt->jadwal && $jt->jadwal->guru ? $jt->jadwal->guru->nama_guru : '-' }}</b> <br>
                        <small>{{ $jt->jadwal && $jt->jadwal->mapel ? $jt->jadwal->mapel->nama_mapel : '-' }}</small>
                    </td>
                    <td>{{ $jt->jadwal && $jt->jadwal->kelas ? $jt->jadwal->kelas->nama_kelas : '-' }}</td>
                    <td>
                        <span style="color: green; font-weight: bold;">{{ $jt->status_kehadiran_guru ?? 'Hadir' }}</span>
                    </td>
                    <td>{{ $jt->materi ?? '-' }}</td>
                    <td>{{ $jt->catatan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" align="center"><i>Belum ada jurnal yang disimpan pada tanggal ini.</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
