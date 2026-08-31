<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Guru & Pegawai - Admin</title>
</head>
<body>

    <a href="{{ route('admin.dashboard') }}">← Kembali ke Dashboard</a>
    <h1>Data Guru & Pegawai (Total: {{ $totalGuru }})</h1>
    <hr>

    {{-- Notifikasi Error --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 10px;">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <p style="color: green;"><b>{{ session('success') }}</b></p>
    @endif

    <!-- ========================================================== -->
    <!-- BAGIAN 1: FORM TAMBAH PEGAWAI BARU                         -->
    <!-- ========================================================== -->
    <fieldset style="margin-bottom: 25px;">
        <legend><h3>➕ Tambah Pegawai Baru</h3></legend>

        <form action="{{ route('admin.guru.store') }}" method="POST">
            @csrf

            <div>
                <label for="nip">NIP:</label><br>
                <input type="text" name="nip" id="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP" required>
            </div>
            <br>

            <div>
                <label for="nama_guru">Nama Lengkap:</label><br>
                <input type="text" name="nama_guru" id="nama_guru" value="{{ old('nama_guru') }}" placeholder="Masukkan Nama Lengkap Beserta Gelar" required>
            </div>
            <br>

            <div>
                <label for="no_hp">Nomor HP:</label><br>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
            </div>
            <br>

            <div>
                <label for="kode_mapel">Mapel Utama:</label><br>
                <select name="kode_mapel" id="kode_mapel">
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
                <label for="role">Role / Jabatan:</label><br>
                <select name="role" id="role" required>
                    <option value="guru">Guru</option>
                    <option value="guru_piket">Guru Piket</option>
                    <option value="staf_tu">Staf TU</option>
                    <option value="satpam">Satpam</option>
                    <option value="kepala_sekolah">Kepala Sekolah</option>
                    <option value="wakasis_siswa">Wakil Kesiswaan (Siswa)</option>
                    <option value="wakasis_guru">Wakil Kesiswaan (Guru)</option>
                </select>
            </div>
            <br>

            <div>
                <label for="password">Password Akun Login:</label><br>
                <input type="password" name="password" id="password" placeholder="Masukkan password untuk login" required>
            </div>
            <br>

            <button type="submit">💾 Simpan Pegawai</button>
        </form>
    </fieldset>

    <!-- ========================================================== -->
    <!-- BAGIAN 2: FILTER & PENCARIAN GURU                          -->
    <!-- ========================================================== -->
    <fieldset style="margin-bottom: 20px;">
        <legend><b>🔍 Filter & Cari Guru</b></legend>
        <form action="{{ route('admin.guru.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIP..." size="30">
            
            <select name="kode_mapel">
                <option value="">-- Semua Mapel --</option>
                @foreach ($mapelList as $m)
                    <option value="{{ $m->kode_mapel }}" {{ request('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                        {{ $m->nama_mapel }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Cari</button>
            @if (request('search') || request('kode_mapel'))
                <a href="{{ route('admin.guru.index') }}">Reset</a>
            @endif
        </form>
    </fieldset>

    <!-- ========================================================== -->
    <!-- BAGIAN 3: TABEL DAFTAR GURU & PEGAWAI                      -->
    <!-- ========================================================== -->
    <h3>📋 Daftar Guru & Pegawai</h3>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr bgcolor="#f0f0f0">
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Role / Jabatan</th>
                <th>No HP</th>
                <th>Mapel</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($guruList as $g)
                <tr>
                    <td><b>{{ $g->nip }}</b></td>
                    <td>{{ $g->nama_guru }}</td>
                    <td>{{ $g->jabatan ?? 'Guru' }}</td>
                    <td>{{ $g->no_hp ?? '-' }}</td>
                    <td>{{ $g->kode_mapel ?? '-' }}</td>
                    <td>
                        {{-- Tombol Hapus (Soft Delete) --}}
                        <form action="{{ route('admin.guru.destroy', $g->id_guru) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; cursor: pointer;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" align="center"><i>Tidak ada data guru yang cocok.</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Navigasi Halaman --}}
    <div style="margin-top: 15px;">
        @if ($guruList->previousPageUrl())
            <a href="{{ $guruList->previousPageUrl() }}">« Sebelumnya</a>
        @endif
        <span> | Halaman {{ $guruList->currentPage() }} dari {{ $guruList->lastPage() }} | </span>
        @if ($guruList->nextPageUrl())
            <a href="{{ $guruList->nextPageUrl() }}">Selanjutnya »</a>
        @endif
    </div>

</body>
</html>