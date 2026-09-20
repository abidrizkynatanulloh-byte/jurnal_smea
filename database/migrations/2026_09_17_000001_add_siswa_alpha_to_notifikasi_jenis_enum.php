<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE `notifikasi` MODIFY COLUMN `jenis` ENUM('dispen_siswa','izin_guru','izin_sakit_siswa','siswa_alpha','umum') NOT NULL DEFAULT 'umum'");
        } catch (\Throwable $e) {
            // Ignore if already altered
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `notifikasi` MODIFY COLUMN `jenis` ENUM('dispen_siswa','izin_guru','izin_sakit_siswa','umum') NOT NULL DEFAULT 'umum'");
        } catch (\Throwable $e) {
            // Ignore
        }
    }
};
