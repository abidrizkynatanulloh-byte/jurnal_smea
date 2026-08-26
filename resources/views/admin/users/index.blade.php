<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna (Users) - Admin</title>
</head>
<body>

    <a href="{{ route('admin.dashboard') }}">← Kembali ke Dashboard</a>
    <h1>Kelola Pengguna (Users)</h1>
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
    <!-- BAGIAN 1: FORM TAMBAH USER BARU (DI ATAS)                   -->
    <!-- ========================================================== -->
    <fieldset style="margin-bottom: 25px;">
        <legend><h3>➕ Form Tambah User Baru</h3></legend>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div>
                <label for="role">Pilih Role / Peran:</label><br>
                <select name="role" id="role" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <br>

            <div>
                <label for="nama">Nama Lengkap:</label><br>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso, S.Pd" required>
            </div>
            <br>

            <div>
                <label for="username">NIP / NISN / Username Login:</label><br>
                <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Masukkan NIP / NISN / USN" required>
            </div>
            <br>

            <div>
                <label for="password">Password Akun:</label><br>
                <input type="password" name="password" id="password" placeholder="Masukkan password" required>
            </div>
            <br>

            <div>
                <label for="no_hp">Nomor HP (Opsional):</label><br>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
            </div>
            <br>

            <button type="submit">Simpan User Baru</button>
        </form>
    </fieldset>

    <!-- ========================================================== -->
    <!-- BAGIAN 2: TABEL DAFTAR USER (DI BAWAH)                     -->
    <!-- ========================================================== -->
    <h3>📋 Daftar Pengguna Terdaftar</h3>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Username / NIP / NISN</th>
                <th>Nama Pengguna</th>
                <th>Role</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $u)
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td>{{ $u->username }}</td>
                    <td>
                        @if ($u->guru)
                            {{ $u->guru->nama_guru }}
                        @elseif ($u->stafTu)
                            {{ $u->stafTu->nama_staf }}
                        @elseif ($u->satpam)
                            {{ $u->satpam->nama_satpam }}
                        @elseif ($u->siswa)
                            Wali dari: {{ $u->siswa->nama_siswa }}
                        @else
                            -
                        @endif
                    </td>
                    <td><b>{{ strtoupper(str_replace('_', ' ', $u->role)) }}</b></td>
                    <td>{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" align="center">Belum ada data user.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <br>
<div style="margin-top: 15px;">
    {{-- Tombol Halaman Sebelumnya --}}
    @if ($users->previousPageUrl())
        <a href="{{ $users->previousPageUrl() }}">« Sebelumnya</a>
    @endif

    {{-- Info Halaman Saat Ini --}}
    <span> | Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }} | </span>

    {{-- Tombol Halaman Selanjutnya --}}
    @if ($users->nextPageUrl())
        <a href="{{ $users->nextPageUrl() }}">Selanjutnya »</a>
    @endif
</div>

</body>
</html>