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
        <legend><h3>➕ Form Tambah User Baruuuuu</h3></legend>

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
                <label for="username">NIP / NISN / USN (Username Login):</label><br>
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
    <!-- BAGIAN 2: FITUR PENCARIAN (SEARCH BAR)                     -->
    <!-- ========================================================== -->
    <fieldset style="margin-bottom: 20px;">
        <legend><b>🔍 Cari Pengguna</b></legend>
        <form action="{{ route('admin.users.index') }}" method="GET">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari berdasarkan NIP / NISN / Nama / Role..." 
                size="40"
            >
            <button type="submit">Cari</button>
            @if (request('search'))
                <a href="{{ route('admin.users.index') }}">Reset</a>
            @endif
        </form>
    </fieldset>

    <!-- ========================================================== -->
    <!-- BAGIAN 3: TABEL DAFTAR USER                                -->
    <!-- ========================================================== -->
    <h3>📋 Daftar Pengguna Terdaftar ({{ $users->total() }} Total Akun)</h3>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr bgcolor="#f0f0f0">
                <th>No</th>
                <th>Username / NIP / NISN</th>
                <th>Nama Pengguna</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $u)
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td><b>{{ $u->username }}</b></td>
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
                    <td>{{ strtoupper(str_replace('_', ' ', $u->role)) }}</td>
                    <td>
                        @if ($u->is_active)
                            <span style="color: green;">Aktif</span>
                        @else
                            <span style="color: gray;">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        {{-- 1. Tombol Edit Akun --}}
                        <a href="{{ route('admin.users.edit', $u->id) }}">✏️ Edit</a>

                        &nbsp;|&nbsp;

                        {{-- 2. Form Tombol Hapus (Soft Delete) --}}
                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/menonaktifkan akun {{ $u->username }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; background: none; border: none; cursor: pointer; text-decoration: underline;">
                                🗑️ Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" align="center"><i>Tidak ada pengguna yang cocok dengan pencarian.</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Navigasi Pagination Halaman --}}
    <div style="margin-top: 15px;">
        @if ($users->previousPageUrl())
            <a href="{{ $users->previousPageUrl() }}">« Sebelumnya</a>
        @endif
        <span> | Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }} | </span>
        @if ($users->nextPageUrl())
            <a href="{{ $users->nextPageUrl() }}">Selanjutnya »</a>
        @endif
    </div>

</body>
</html>