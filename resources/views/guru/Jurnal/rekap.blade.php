<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Jurnal - {{ $guru->nama_guru }}</title>
</head>
<body>

    <a href="{{ route('guru.dashboard') }}">← Kembali ke Dashboard</a>
    <h1>📋 Rekap Jurnal Mengajar</h1>
    <p><b>Guru:</b> {{ $guru->nama_guru }}</p>
    <hr>

    <!-- Filter Tanggal -->
    <fieldset style="margin-bottom: 20px;">
        <legend><b>📅 Filter Tanggal</b></legend>
        <form action="{{ route('guru.jurnal.rekap') }}" method="GET">
            <label>Dari:</label>
            <input type="date" name="dari" value="{{ request('dari') }}">

            &nbsp;&nbsp;
            <label>Sampai:</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}">

            &nbsp;&nbsp;
            <button type="submit">Filter</button>
            @if (request('dari') || request('sampai'))
                <a href="{{ route('guru.jurnal.rekap') }}">Reset</a>
            @endif
        </form>
    </fieldset>

    <!-- Tabel Rekap -->
    <h3>Total: {{ $rekapList->total() }} Jurnal</h3>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr bgcolor="#f0f0f0">
                <th>Tanggal</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Jam Ke-</th>
                <th>Status Guru</th>
                <th>Materi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekapList as $r)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($r->tanggal)->locale('id')->isoFormat('ddd, D MMM Y') }}</td>
                    <td><b>{{ $r->jadwal && $r->jadwal->kelas ? $r->jadwal->kelas->nama_kelas : '-' }}</b></td>
                    <td>{{ $r->jadwal && $r->jadwal->mapel ? $r->jadwal->mapel->nama_mapel : '-' }}</td>
                    <td>Jam {{ $r->jadwal->jam_mulai ?? '-' }} - {{ $r->jadwal->jam_selesai ?? '-' }}</td>
                    <td>
                        @if ($r->status_kehadiran_guru == 'Hadir')
                            <span style="color: green;">✓ Hadir</span>
                        @else
                            <span style="color: red;">{{ $r->status_kehadiran_guru }}</span>
                        @endif
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($r->materi, 60) }}</td>
                    <td>
                        <a href="{{ route('guru.jurnal.show', $r->id_jurnal) }}">👁️ Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" align="center"><i>Belum ada jurnal yang tersimpan.</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Navigasi Halaman -->
    <div style="margin-top: 15px;">
        @if ($rekapList->previousPageUrl())
            <a href="{{ $rekapList->previousPageUrl() }}">« Sebelumnya</a>
        @endif
        <span> | Halaman {{ $rekapList->currentPage() }} dari {{ $rekapList->lastPage() }} | </span>
        @if ($rekapList->nextPageUrl())
            <a href="{{ $rekapList->nextPageUrl() }}">Selanjutnya »</a>
        @endif
    </div>

</body>
</html>