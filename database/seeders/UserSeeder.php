<?php

namespace Database\Seeders;

// Mengimpor kelas Seeder bawaan Laravel
use Illuminate\Database\Seeder;
// Mengimpor Model-Model yang dibutuhkan
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\StafTu;
// Mengimpor Facade Hash untuk enkripsi password
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Menjalankan proses pengisian otomatis akun users.
     */
    public function run(): void
    {
        // =====================================================================
        // 1. BUAT AKUN ADMIN / STAF TU DEFAULT (Password: admin123)
        // =====================================================================
        $stafAdmin = StafTu::firstOrCreate(
            ['nip' => '0000001'],
            [
                'nama_staf' => 'Administrator',
                'jabatan'   => 'Administrator',
            ]
        );

        User::updateOrCreate(
            ['username' => '0000001'],
            [
                'password'  => Hash::make('admin123'), // Password disamakan: admin123
                'role'      => 'staf_tu',
                'id_staf'   => $stafAdmin->id_staf,
                'is_active' => 1,
            ]
        );
        $this->command->info('✓ Akun Admin berhasil dibuat (0000001 / admin123)');


        // =====================================================================
        // 2. BUAT AKUN UNTUK SEMUA 130 GURU (Password disamakan: guru123)
        // =====================================================================
        $guruList = Guru::all();
        $passwordGuru = Hash::make('guru123'); // Enkripsi 1 kali agar cepat

        foreach ($guruList as $guru) {
            User::updateOrCreate(
                ['username' => trim($guru->nip)],
                [
                    'password'  => $passwordGuru, // Password semua guru: guru123
                    'role'      => 'guru',
                    'id_guru'   => $guru->id_guru,
                    'is_active' => 1,
                ]
            );
        }
        $this->command->info('✓ ' . $guruList->count() . ' Akun Guru berhasil dibuat (Password: guru123)');


        // =====================================================================
        // 3. BUAT AKUN UNTUK SEMUA 31 WALI MURID (Password disamakan: wali123)
        // =====================================================================
        $siswaList = Siswa::all();
        $passwordWali = Hash::make('wali123'); // Enkripsi 1 kali agar cepat

        foreach ($siswaList as $siswa) {
            User::updateOrCreate(
                ['username' => trim($siswa->nisn)],
                [
                    'password'   => $passwordWali, // Password semua wali murid: wali123
                    'role'       => 'wali_murid',
                    'nisn_siswa' => trim($siswa->nisn),
                    'is_active'  => 1,
                ]
            );
        }
        $this->command->info('✓ ' . $siswaList->count() . ' Akun Wali Murid berhasil dibuat (Password: wali123)');
    }
}