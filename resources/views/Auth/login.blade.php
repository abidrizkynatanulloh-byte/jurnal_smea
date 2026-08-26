<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jurnal Esemkita</title>
</head>
<body>

    <h2>Login Sistem Jurnal Esemkita</h2>

    {{-- Menampilkan pesan error jika login gagal atau ada validasi yang salah --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Menampilkan pesan sukses (misalnya setelah logout) --}}
    @if (session('success'))
        <div style="color: green; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form pengiriman login via method POST ke rute 'login.post' --}}
    <form action="{{ route('login.post') }}" method="POST">
        
        {{-- Wajib: Token keamanan CSRF bawaan Laravel --}}
        @csrf

        {{-- 1. Dropdown Pilihan Role --}}
        <div>
            <label for="role">Pilih Peran (Role):</label><br>
            <select name="role" id="role" required>
                <option value="">-- Pilih Role Anda --</option>
                {{-- Melakukan perulangan daftar $roles yang dikirim dari AuthController --}}
                @foreach ($roles as $key => $label)
                    <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <br>

        {{-- 2. Input Username (NIP / NISN / USN) --}}
        <div>
            <label for="username">NIP / NISN / Username:</label><br>
            <input 
                type="text" 
                name="username" 
                id="username" 
                value="{{ old('username') }}" 
                placeholder="Masukkan NIP / NISN / USN" 
                required
            >
        </div>
        <br>

        {{-- 3. Input Password --}}
        <div>
            <label for="password">Password:</label><br>
            <input 
                type="password" 
                name="password" 
                id="password" 
                placeholder="Masukkan Password" 
                required
            >
        </div>
        <br>

        {{-- 4. Checkbox Ingat Saya (Remember Me) --}}
        <div>
            <label>
                <input type="checkbox" name="remember" value="1"> Ingat Saya
            </label>
        </div>
        <br>

        {{-- 5. Tombol Submit Login --}}
        <div>
            <button type="submit">Masuk (Login)</button>
        </div>

    </form>

</body>
</html>