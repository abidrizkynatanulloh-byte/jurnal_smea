<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Selamat Datang di Dashboard Admin / Staf TU</h1>
    <p>Halo, kamu berhasil login!</p><hr>
<h3>Menu Utama:</h3>
    <ul>
        {{-- Link menuju 1 halaman kelola user (Lihat tabel + Tambah user) --}}
        <li>
            <a href="{{ route('admin.users.index') }}">👥 Kelola Pengguna & Tambah User Baru</a>
        </li>
    </ul>
<hr>
    {{-- Tombol Logout --}}
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Keluar (Logout)</button>
    </form>
</body>
</html>