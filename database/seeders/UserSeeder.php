<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\StafTu;
use App\Models\Satpam;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Menjalankan proses pengisian otomatis akun users untuk seluruh 7 role sistem.
     */
    public function run(): void
    {
        // =====================================================================
        // 1. BUAT AKUN ADMIN / STAF TU DEFAULT (0000001 / admin123)
        // =====================================================================
        $stafAdmin = StafTu::firstOrCreate(
            ['nip' => '0000001'],
            [
                'nama_staf' => 'Administrator TU',
                'jabatan'   => 'Kepala Tata Usaha',
            ]
        );

        User::updateOrCreate(
            ['username' => '0000001'],
            [
                'password'  => Hash::make('admin123'),
                'role'      => 'staf_tu',
                'id_staf'   => $stafAdmin->id_staf,
                'is_active' => 1,
            ]
        );

        // =====================================================================
        // 2. BUAT AKUN KEPALA SEKOLAH (kepsek01 / kepsek123)
        // =====================================================================
        User::updateOrCreate(
            ['username' => 'kepsek01'],
            [
                'password'  => Hash::make('kepsek123'),
                'role'      => 'kepala_sekolah',
                'is_active' => 1,
            ]
        );

        // =====================================================================
        // 3. BUAT AKUN SATPAM (satpam01 / satpam123)
        // =====================================================================
        $satpam = Satpam::firstOrCreate(
            ['usn' => 'satpam01'],
            [
                'nama_satpam' => 'Pak Budi (Satpam Gerbang)',
                'no_hp'       => '081234567890',
            ]
        );

        User::updateOrCreate(
            ['username' => 'satpam01'],
            [
                'password'  => Hash::make('satpam123'),
                'role'      => 'satpam',
                'id_satpam' => $satpam->id_satpam,
                'is_active' => 1,
            ]
        );

        // =====================================================================
        // 4. BUAT AKUN WAKA KESISWAAN (wakasis01 / wakasis123)
        // =====================================================================
        User::updateOrCreate(
            ['username' => 'wakasis01'],
            [
                'password'  => Hash::make('wakasis123'),
                'role'      => 'wakasis_siswa',
                'is_active' => 1,
            ]
        );

        // =====================================================================
        // 5. BUAT AKUN WAKA KURIKULUM & SDM (wakakur01 / wakakur123)
        // =====================================================================
        User::updateOrCreate(
            ['username' => 'wakakur01'],
            [
                'password'  => Hash::make('wakakur123'),
                'role'      => 'wakasis_guru',
                'is_active' => 1,
            ]
        );

        // =====================================================================
        // 6. BUAT AKUN GURU PIKET DEDIKASI (piket01 / piket123)
        // =====================================================================
        User::updateOrCreate(
            ['username' => 'piket01'],
            [
                'password'  => Hash::make('piket123'),
                'role'      => 'guru_piket',
                'is_active' => 1,
            ]
        );

        // =====================================================================
        // 7. BUAT AKUN UNTUK SEMUA GURU (Password disamakan: guru123)
        // =====================================================================
        $guruList = Guru::all();
        $passwordGuru = Hash::make('guru123');

        foreach ($guruList as $guru) {
            if ($guru->nip) {
                User::updateOrCreate(
                    ['username' => trim($guru->nip)],
                    [
                        'password'  => $passwordGuru,
                        'role'      => 'guru',
                        'id_guru'   => $guru->id_guru,
                        'is_active' => 1,
                    ]
                );
            }
        }

        // =====================================================================
        // 8. BUAT AKUN UNTUK SEMUA WALI MURID (Password disamakan: wali123)
        // =====================================================================
        $siswaList = Siswa::all();
        $passwordWali = Hash::make('wali123');

        foreach ($siswaList as $siswa) {
            if ($siswa->nisn) {
                User::updateOrCreate(
                    ['username' => trim($siswa->nisn)],
                    [
                        'password'   => $passwordWali,
                        'role'       => 'wali_murid',
                        'nisn_siswa' => trim($siswa->nisn),
                        'is_active'  => 1,
                    ]
                );
            }
        }

        // Akun sample wali murid jika belum ada data siswa
        User::updateOrCreate(
            ['username' => 'ortu01'],
            [
                'password'  => Hash::make('wali123'),
                'role'      => 'wali_murid',
                'is_active' => 1,
            ]
        );
    }
}