<?php

namespace App\Http\Controllers;

// Mengimpor kelas Request untuk membaca data form login
use Illuminate\Http\Request;
// Mengimpor Facade Auth untuk mengelola session autentikasi
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
// Mengimpor kelas exception untuk mengembalikan pesan error validasi
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menampilkan form login dan mengirimkan daftar pilihan role dropdown.
     */
    public function showLoginForm()
    {
        // Daftar role yang tersedia untuk ditampilkan pada elemen dropdown
        $roles = [
            'guru'           => 'Guru Mata Pelajaran',
            'guru_piket'     => 'Guru Piket',
            'staf_tu'        => 'Staf TU / Admin',
            'satpam'         => 'Satpam',
            'wali_murid'     => 'Wali Murid / Orang Tua',
            'kepala_sekolah' => 'Kepala Sekolah',
            'wakasis_siswa'  => 'Wakil Kesiswaan (Siswa)',
            'wakasis_guru'   => 'Wakil Kesiswaan (Guru)',
        ];

        // Memanggil view resources/views/auth/login.blade.php
        return view('auth.login', compact('roles'));
    }

    /**
     * Memproses data input login (POST /login).
     */
    public function login(Request $request)
    {
        // 1. Validasi input form
        $credentials = $request->validate([
            'role'     => 'required|in:guru,guru_piket,staf_tu,satpam,wali_murid,kepala_sekolah,wakasis_siswa,wakasis_guru',
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'role.required'     => 'Silakan pilih role Anda terlebih dahulu.',
            'role.in'           => 'Pilihan role tidak valid.',
            'username.required' => 'NIP / NISN / Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Kriteria autentikasi ke database
        $attemptData = [
            'username'  => $credentials['username'],
            'password'  => $credentials['password'],
            'role'      => $credentials['role'],
            'is_active' => 1,
        ];

        // 3. Proses pengecekan kecocokan akun
        if (Auth::attempt($attemptData, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            return redirect()->intended($this->redirectToDashboard($user->role));
        }

        // 4. Jika login gagal
        throw ValidationException::withMessages([
            'username' => 'NIP/NISN/Username, password, atau role yang dipilih tidak cocok.',
        ]);
    }

    /**
     * Memproses logout user (POST /logout).
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    /**
     * Helper: Menentukan rute dashboard tujuan berdasarkan nama role.
     */
    private function redirectToDashboard(string $role): string
    {
        return match ($role) {
            'staf_tu'        => route('admin.dashboard'),
            'guru'           => route('guru.dashboard'),
            'guru_piket'     => route('piket.dashboard'),
            'wali_murid'     => route('wali.dashboard'),
            'satpam'         => route('satpam.dashboard'),
            'kepala_sekolah' => route('kepsek.dashboard'),
            'wakasis_siswa'  => route('wakasis.siswa.dashboard'),
            'wakasis_guru'   => route('wakasis.guru.dashboard'),
            default          => '/',
        };
    }
}