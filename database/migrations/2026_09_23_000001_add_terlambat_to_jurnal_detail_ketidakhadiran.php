<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('jurnal_detail_ketidakhadiran')) {
            // Ubah kolom keterangan menjadi ENUM yang menyertakan 'Terlambat' atau VARCHAR(20)
            DB::statement("ALTER TABLE `jurnal_detail_ketidakhadiran` MODIFY COLUMN `keterangan` ENUM('Sakit', 'Izin', 'Alpa', 'Terlambat') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('jurnal_detail_ketidakhadiran')) {
            DB::statement("ALTER TABLE `jurnal_detail_ketidakhadiran` MODIFY COLUMN `keterangan` ENUM('Sakit', 'Izin', 'Alpa') NOT NULL");
        }
    }
};
