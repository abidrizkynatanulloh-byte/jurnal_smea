<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa - Admin</title>
</head>
<body>

    <a href="{{ route('admin.dashboard') }}">← Kembali ke Dashboard</a>
    <h1>Data Siswa (Total: {{ $totalSiswa }})</h1>
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

    <!-- FORM TAMBAH SISWA -->
    <fieldset style="margin-bottom: 25px;">
        <legend><h3>➕ Tambah Siswa Baru</h3></legend>
        <form action="{{ route('admin.siswa.store') }}" method="POST">
            @csrf

            <div>
                <label for="nis">NIS:</label><br>
                <input type="text" name="nis" id="nis" value="{{ old('nis') }}" placeholder="Nomor Induk Siswa" required>
            </div>
            <br>

            <div>
                <label for="nisn">NISN:</label><br>
                <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}" placeholder="Nomor Induk Siswa Nasional" required>
            </div>
            <br>

            <div>
                <label for="nama_siswa">Nama Lengkap Siswa:</label><br>
                <input type="text" name="nama_siswa" id="nama_siswa" value="{{ old('nama_siswa') }}" placeholder="Nama Siswa" required>
            </div>
            <br>

            <div>
                <label for="id_kelas">Kelas:</label><br>
                <select name="id_kelas" id="id_kelas" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <br>

            <div>
                <label for="kota_lahir">Kota Lahir:</label><br>
                <input type="text" name="kota_lahir" id="kota_lahir" value="{{ old('kota_lahir') }}" placeholder="Tempat Lahir">
            </div>
            <br>

            <div>
                <label for="tanggal_lahir">Tanggal Lahir:</label><br>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
            </div>
            <br>

            <div>
                <label for="alamat">Alamat Lengkap:</label><br>
                <textarea name="alamat" id="alamat" rows="2" cols="40" placeholder="Alamat Siswa">{{ old('alamat') }}</textarea>
            </div>
            <br>

            <button type="submit">💾 Simpan Siswa</button>
        </form>
    </fieldset>

    <!-- PENCARIAN SISWA -->
    <fieldset style="margin-bottom: 20px;">
        <legend><b>🔍 Cari Siswa</b></legend>
        <form action="{{ route('admin.siswa.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIS / NISN..." size="35">
            <button type="submit">Cari</button>
            @if (request('search'))
                <a href="{{ route('admin.siswa.index') }}">Reset</a>
            @endif
        </form>
    </fieldset>

    <!-- TABEL DAFTAR SISWA -->
    <h3>📋 Daftar Siswa</h3>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr bgcolor="#f0f0f0">
                <th>NIS</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Kota Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswaList as $s)
                <tr>
                    <td><b>{{ $s->nis }}</b></td>
                    <td>{{ $s->nisn }}</td>
                    <td>{{ $s->nama_siswa }}</td>
                    <td>{{ $s->kelas ? $s->kelas->nama_kelas : '-' }}</td>
                    <td>{{ $s->kota_lahir ?? '-' }}</td>
                    <td>{{ $s->tanggal_lahir ?? '-' }}</td>
                    <td>{{ $s->alamat ?? '-' }}</td>
                    <td>
                        <form action="{{ route('admin.siswa.destroy', $s->nis) }}" method="POST" onsubmit="return confirm('Hapus siswa ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; cursor: pointer;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" align="center"><i>Tidak ada data siswa.</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        @if ($siswaList->previousPageUrl())
            <a href="{{ $siswaList->previousPageUrl() }}">« Sebelumnya</a>
        @endif
        <span> | Halaman {{ $siswaList->currentPage() }} dari {{ $siswaList->lastPage() }} | </span>
        @if ($siswaList->nextPageUrl())
            <a href="{{ $siswaList->nextPageUrl() }}">Selanjutnya »</a>
        @endif
    </div>

</body>
</html>