<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Jam Pelajaran - Admin</title>
</head>
<body>

    <a href="{{ route('admin.dashboard') }}">← Kembali ke Dashboard</a>
    <h1>Master Jam Pelajaran</h1>
    <hr>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <p style="color: green;"><b>{{ session('success') }}</b></p>
    @endif

    <!-- FORM TAMBAH SESI JAM BARU -->
    <fieldset style="margin-bottom: 25px;">
        <legend><h3>➕ Tambah Jam Baru</h3></legend>
        <form action="{{ route('admin.jam.store') }}" method="POST">
            @csrf

            <label for="jam_ke">Jam Ke-:</label>
            <input type="number" name="jam_ke" id="jam_ke" placeholder="Misal: 11" min="1" required style="width: 80px;">

            &nbsp;&nbsp;
            <label for="waktu_mulai">Waktu Mulai:</label>
            <input type="time" name="waktu_mulai" id="waktu_mulai" required>

            &nbsp;&nbsp;
            <label for="waktu_selesai">Waktu Selesai:</label>
            <input type="time" name="waktu_selesai" id="waktu_selesai" required>

            &nbsp;&nbsp;
            <button type="submit">➕ Tambah</button>
        </form>
    </fieldset>

    <!-- TABEL DAFTAR SESI JAM -->
    <h3>📋 Daftar Sesi Jam Pelajaran</h3>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr bgcolor="#f0f0f0">
                <th>Jam Ke-</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jamList as $j)
                <tr>
                    <td align="center"><b>{{ $j->jam_ke }}</b></td>
                    
                    {{-- Form Update Waktu Per Baris --}}
                    <form action="{{ route('admin.jam.update', $j->id_jam) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <td align="center">
                            <input type="time" name="waktu_mulai" value="{{ substr($j->waktu_mulai, 0, 5) }}" required>
                        </td>
                        <td align="center">
                            <input type="time" name="waktu_selesai" value="{{ substr($j->waktu_selesai, 0, 5) }}" required>
                        </td>
                        <td align="center">
                            <button type="submit" style="background-color: blue; color: white; cursor: pointer;">
                                SIMPAN PERUBAHAN
                            </button>
                    </form>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('admin.jam.destroy', $j->id_jam) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus jam ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background-color: red; color: white; cursor: pointer;">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" align="center"><i>Belum ada sesi jam pelajaran. Silakan tambah di atas.</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>