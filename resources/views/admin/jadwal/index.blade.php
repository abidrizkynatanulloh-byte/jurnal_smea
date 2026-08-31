<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Mengajar - Admin</title>
</head>
<body>

    <a href="{{ route('admin.dashboard') }}">← Kembali ke Dashboard</a>
    <h1>Manajemen Jadwal Mengajar</h1>
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

    <!-- FORM TAMBAH JADWAL -->
    <fieldset style="margin-bottom: 25px;">
        <legend><h3>➕ Tambah Jadwal Mengajar</h3></legend>
        <form action="{{ route('admin.jadwal.store') }}" method="POST">
            @csrf

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
                <label for="hari">Hari:</label><br>
                <select name="hari" id="hari" required>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                </select>
            </div>
            <br>

            <div>
                <label for="jam_mulai">Jam Mulai (Sesi Ke-):</label>
                <input type="number" name="jam_mulai" id="jam_mulai" min="1" max="15" value="{{ old('jam_mulai', 1) }}" required>

                &nbsp;&nbsp;
                <label for="jam_selesai">Jam Selesai (Sesi Ke-):</label>
                <input type="number" name="jam_selesai" id="jam_selesai" min="1" max="15" value="{{ old('jam_selesai', 2) }}" required>
            </div>
            <br>

            <div>
                <label for="id_guru">Guru Pengajar:</label><br>
                <select name="id_guru" id="id_guru" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach ($guruList as $g)
                        <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                            {{ $g->nama_guru }}
                        </option>
                    @endforeach
                </select>
            </div>
            <br>

            <div>
                <label for="kode_mapel">Mata Pelajaran:</label><br>
                <select name="kode_mapel" id="kode_mapel" required>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach ($mapelList as $m)
                        <option value="{{ $m->kode_mapel }}" {{ old('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                            {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>
            <br>

            <div>
                <label for="id_ruangan">Ruangan:</label><br>
                <select name="id_ruangan" id="id_ruangan" required>
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach ($ruanganList as $r)
                        <option value="{{ $r->id_ruangan }}" {{ old('id_ruangan') == $r->id_ruangan ? 'selected' : '' }}>
                            {{ $r->nama_ruangan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <br>

            <button type="submit">💾 Simpan Jadwal</button>
        </form>
    </fieldset>

    <!-- FILTER JADWAL -->
    <fieldset style="margin-bottom: 20px;">
        <legend><b>🔍 Filter Jadwal</b></legend>
        <form action="{{ route('admin.jadwal.index') }}" method="GET">
            <select name="hari">
                <option value="">-- Semua Hari --</option>
                <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
            </select>

            <select name="id_kelas">
                <option value="">-- Semua Kelas --</option>
                @foreach ($kelasList as $k)
                    <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Filter</button>
            @if (request('hari') || request('id_kelas'))
                <a href="{{ route('admin.jadwal.index') }}">Reset</a>
            @endif
        </form>
    </fieldset>

    <!-- TABEL DAFTAR JADWAL -->
    <h3>📋 Daftar Jadwal (Total: {{ $jadwalList->total() }})</h3>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr bgcolor="#f0f0f0">
                <th>Hari</th>
                <th>Jam Ke-</th>
                <th>Kelas</th>
                <th>Guru</th>
                <th>Mapel</th>
                <th>Ruangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwalList as $j)
                <tr>
                    <td><b>{{ $j->hari }}</b></td>
                    <td>Jam {{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                    <td>{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</td>
                    <td>{{ $j->guru ? $j->guru->nama_guru : '-' }}</td>
                    <td>{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</td>
                    <td>{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</td>
                    <td>
                        <form action="{{ route('admin.jadwal.destroy', $j->id_jadwal) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; cursor: pointer;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" align="center"><i>Tidak ada data jadwal.</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        @if ($jadwalList->previousPageUrl())
            <a href="{{ $jadwalList->previousPageUrl() }}">« Sebelumnya</a>
        @endif
        <span> | Halaman {{ $jadwalList->currentPage() }} dari {{ $jadwalList->lastPage() }} | </span>
        @if ($jadwalList->nextPageUrl())
            <a href="{{ $jadwalList->nextPageUrl() }}">Selanjutnya »</a>
        @endif
    </div>

</body>
</html>