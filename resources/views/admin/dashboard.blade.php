<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Selamat Datang di Dashboard Admin / Staf TU</h1>
    <p>Halo, kamu berhasil login!</p>

    {{-- Tombol Logout --}}
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Keluar (Logout)</button>
    </form>
</body>
</html>