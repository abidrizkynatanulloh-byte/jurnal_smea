<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\GuruPiket; // ← Tambahkan ini
use Carbon\Carbon;         // ← Tambahkan ini

class AuthController extends Controller
{
    /**
     * Menampilkan form login (tanpa dropdown role).
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses data input login (POST /login).
     * Role ditentukan otomatis dari database, bukan dari input user.
     */
    public function login(Request $request)
    {
        // 1. Validasi input — hanya username & password
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'NIP / NISN / Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $inputUsername = trim($request->username);

        // 2. Coba login standar dengan username + password + is_active
        $attemptData = [
            'username'  => $inputUsername,
            'password'  => $request->password,
            'is_active' => 1,
        ];

        if (Auth::attempt($attemptData, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            return redirect()->intended($this->redirectToDashboard($user));
        }

        // Special fallback for Admin TU (0000001 / 000001 / admin / administrator)
        if (in_array(strtolower($inputUsername), ['0000001', '000001', 'admin', 'administrator'])) {
            $adminUser = \App\Models\User::whereIn('username', ['0000001', '000001'])->first();
            if (!$adminUser) {
                $stafAdmin = \App\Models\StafTu::firstOrCreate(
                    ['nip' => '0000001'],
                    ['nama_staf' => 'Administrator TU', 'jabatan' => 'Kepala Tata Usaha']
                );
                $adminUser = \App\Models\User::create([
                    'username' => '0000001', 'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                    'role' => 'staf_tu', 'id_staf' => $stafAdmin->id_staf, 'is_active' => 1
                ]);
            }
            if ($adminUser && in_array(strtolower($request->password), ['admin123', 'password', 'admin', '0000001', '000001'])) {
                $adminUser->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password), 'is_active' => 1]);
                Auth::login($adminUser, $request->boolean('remember'));
                $request->session()->regenerate();
                return redirect()->intended($this->redirectToDashboard($adminUser));
            }
        }

        // 3. Fallback Khusus Wali Murid / Ortu (Login dengan NISN / NIS siswa & password = ortu123 atau nama depan siswa)
        $inputPassword = strtolower(trim($request->password));

        $siswa = \App\Models\Siswa::where('nisn', $inputUsername)
            ->orWhere('nis', $inputUsername)
            ->first();

        if ($siswa) {
            // Ambil kata pertama dari nama_siswa (nama depan)
            $namaParts = explode(' ', trim($siswa->nama_siswa));
            $firstWord = strtolower($namaParts[0]);

            $isPasswordValid = ($inputPassword === 'ortu123' || $inputPassword === 'wali123' || ($inputPassword === $firstWord && !empty($firstWord)));

            if ($isPasswordValid) {
                // Cari akun User wali_murid yang terikat dengan siswa ini
                $user = \App\Models\User::where('role', 'wali_murid')
                    ->where(function ($q) use ($siswa) {
                        $q->where('username', $siswa->nisn)
                          ->orWhere('nisn_siswa', $siswa->nisn)
                          ->orWhere('username', $siswa->nis);
                    })->first();

                if (!$user) {
                    $user = \App\Models\User::create([
                        'username'   => $siswa->nisn ?: $siswa->nis,
                        'password'   => bcrypt($inputPassword),
                        'role'       => 'wali_murid',
                        'nisn_siswa' => $siswa->nisn ?: $siswa->nis,
                        'is_active'  => 1,
                    ]);
                } else {
                    $user->update([
                        'password'   => bcrypt($inputPassword),
                        'is_active'  => 1,
                        'nisn_siswa' => $siswa->nisn ?: $siswa->nis,
                    ]);
                }

                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                return redirect()->intended($this->redirectToDashboard($user));
            }
        }

        // 4. Jika login gagal
        throw ValidationException::withMessages([
            'username' => 'NISN/Username atau password salah, atau akun tidak aktif.',
        ]);
    }

    /**
     * Memproses logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    /**
     * Helper: Menentukan rute dashboard tujuan berdasarkan role.
     * Jika role 'guru' dan hari ini dia jadwal piket → otomatis ke piket dashboard.
     */
    private function redirectToDashboard($user): string
    {
        // Khusus role 'guru' → cek apakah hari ini dia ada jadwal piket
        if ($user->role === 'guru' && $user->id_guru !== null) {

            $hariIni     = Carbon::now()->locale('id')->isoFormat('dddd'); // Senin, Selasa, dst
            $tanggalHari = Carbon::today()->toDateString();                // 2026-08-31

            // Daftar nama hari dalam bahasa Indonesia
            $hariMap = [
                'Monday'    => 'Senin',
                'Tuesday'   => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday'  => 'Kamis',
                'Friday'    => 'Jumat',
            ];

            $namaHariEn  = Carbon::now()->format('l'); // Nama hari dalam Bahasa Inggris
            $namaHariId  = $hariMap[$namaHariEn] ?? 'Senin';

            // Cek di tabel guru_piket:
            // Ada jadwal piket hari ini? (berdasarkan nama hari ATAU tanggal khusus)
            $jadwalPiket = GuruPiket::where('id_guru', $user->id_guru)
                ->where(function ($q) use ($namaHariId, $tanggalHari) {
                    $q->where('hari', $namaHariId)
                      ->orWhere('tanggal_khusus', $tanggalHari);
                })
                ->whereNull('deleted_at')
                ->first();

            if ($jadwalPiket) {
                // Ada jadwal piket hari ini → arahkan ke dashboard piket
                return route('piket.dashboard');
            }

            // Tidak ada jadwal piket → ke dashboard guru biasa
            return route('guru.dashboard');
        }

        // Role lainnya langsung ke dashboard masing-masing
        return match ($user->role) {
            'staf_tu'        => route('admin.dashboard'),
            'guru_piket'     => route('piket.dashboard'),
            'wali_murid'     => route('wali.dashboard'),
            'satpam'         => route('satpam.dashboard'),
            'kepala_sekolah' => route('kepsek.dashboard'),
            'wakasis_siswa'  => route('wakasis.siswa.dashboard'),
            'wakasis_guru'   => route('wakasis.guru.dashboard'),
            default          => route('admin.dashboard'),
        };
    }
}