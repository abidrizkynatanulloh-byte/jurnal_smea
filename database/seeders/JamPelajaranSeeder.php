<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JamPelajaran;
use Illuminate\Support\Facades\DB;

class JamPelajaranSeeder extends Seeder
{
    /**
     * Seed data Alokasi Jam KBM Baru Revisi (SMK Negeri 1 Boyolangu).
     */
    public function run(): void
    {
        // Truncate / Reset data lama di tabel jam_pelajaran
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        JamPelajaran::withTrashed()->forceDelete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. DATA JADWAL REGULER (SENIN - KAMIS)
        $reguler = [
            ['jam_ke' => 1,  'kelompok_hari' => 'Reguler', 'waktu_mulai' => '07:00:00', 'waktu_selesai' => '07:40:00', 'is_aktif' => 1],
            ['jam_ke' => 2,  'kelompok_hari' => 'Reguler', 'waktu_mulai' => '07:40:00', 'waktu_selesai' => '08:20:00', 'is_aktif' => 1],
            ['jam_ke' => 3,  'kelompok_hari' => 'Reguler', 'waktu_mulai' => '08:20:00', 'waktu_selesai' => '09:00:00', 'is_aktif' => 1],
            ['jam_ke' => 4,  'kelompok_hari' => 'Reguler', 'waktu_mulai' => '09:00:00', 'waktu_selesai' => '09:40:00', 'is_aktif' => 1],
            // Istirahat 1: 09:40 - 10:00 (20 Menit)
            ['jam_ke' => 5,  'kelompok_hari' => 'Reguler', 'waktu_mulai' => '10:00:00', 'waktu_selesai' => '10:35:00', 'is_aktif' => 1],
            ['jam_ke' => 6,  'kelompok_hari' => 'Reguler', 'waktu_mulai' => '10:35:00', 'waktu_selesai' => '11:10:00', 'is_aktif' => 1],
            ['jam_ke' => 7,  'kelompok_hari' => 'Reguler', 'waktu_mulai' => '11:10:00', 'waktu_selesai' => '11:45:00', 'is_aktif' => 1],
            // Istirahat 2: 11:45 - 13:15 (90 Menit)
            ['jam_ke' => 8,  'kelompok_hari' => 'Reguler', 'waktu_mulai' => '13:15:00', 'waktu_selesai' => '13:50:00', 'is_aktif' => 1],
            ['jam_ke' => 9,  'kelompok_hari' => 'Reguler', 'waktu_mulai' => '13:50:00', 'waktu_selesai' => '14:25:00', 'is_aktif' => 1],
            ['jam_ke' => 10, 'kelompok_hari' => 'Reguler', 'waktu_mulai' => '14:25:00', 'waktu_selesai' => '15:00:00', 'is_aktif' => 1],
        ];

        // 2. DATA JADWAL JUMAT
        $jumat = [
            ['jam_ke' => 1,  'kelompok_hari' => 'Jumat', 'waktu_mulai' => '07:00:00', 'waktu_selesai' => '07:30:00', 'is_aktif' => 1],
            ['jam_ke' => 2,  'kelompok_hari' => 'Jumat', 'waktu_mulai' => '07:30:00', 'waktu_selesai' => '08:00:00', 'is_aktif' => 1],
            ['jam_ke' => 3,  'kelompok_hari' => 'Jumat', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '08:30:00', 'is_aktif' => 1],
            ['jam_ke' => 4,  'kelompok_hari' => 'Jumat', 'waktu_mulai' => '08:30:00', 'waktu_selesai' => '09:00:00', 'is_aktif' => 1],
            ['jam_ke' => 5,  'kelompok_hari' => 'Jumat', 'waktu_mulai' => '09:00:00', 'waktu_selesai' => '09:30:00', 'is_aktif' => 1],
            // Istirahat 1: 09:30 - 09:50 (20 Menit)
            ['jam_ke' => 6,  'kelompok_hari' => 'Jumat', 'waktu_mulai' => '09:50:00', 'waktu_selesai' => '10:20:00', 'is_aktif' => 1],
            ['jam_ke' => 7,  'kelompok_hari' => 'Jumat', 'waktu_mulai' => '10:20:00', 'waktu_selesai' => '10:50:00', 'is_aktif' => 1],
            ['jam_ke' => 8,  'kelompok_hari' => 'Jumat', 'waktu_mulai' => '10:50:00', 'waktu_selesai' => '11:20:00', 'is_aktif' => 1],
            // Istirahat 2: 11:20 - 13:00 (100 Menit)
            ['jam_ke' => 9,  'kelompok_hari' => 'Jumat', 'waktu_mulai' => '13:00:00', 'waktu_selesai' => '13:30:00', 'is_aktif' => 1],
            ['jam_ke' => 10, 'kelompok_hari' => 'Jumat', 'waktu_mulai' => '13:30:00', 'waktu_selesai' => '14:00:00', 'is_aktif' => 1],
            ['jam_ke' => 11, 'kelompok_hari' => 'Jumat', 'waktu_mulai' => '14:00:00', 'waktu_selesai' => '14:30:00', 'is_aktif' => 1],
            ['jam_ke' => 12, 'kelompok_hari' => 'Jumat', 'waktu_mulai' => '14:30:00', 'waktu_selesai' => '15:00:00', 'is_aktif' => 1],
            ['jam_ke' => 13, 'kelompok_hari' => 'Jumat', 'waktu_mulai' => '15:00:00', 'waktu_selesai' => '15:30:00', 'is_aktif' => 1],
        ];

        foreach (array_merge($reguler, $jumat) as $item) {
            JamPelajaran::create($item);
        }
    }
}
