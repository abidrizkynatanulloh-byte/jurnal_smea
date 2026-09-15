<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `guru` DROP FOREIGN KEY `guru_ibfk_1`");
        DB::statement("ALTER TABLE `jadwal` DROP FOREIGN KEY `jadwal_ibfk_3`");

        DB::statement("ALTER TABLE `mapel` MODIFY COLUMN `kode_mapel` VARCHAR(30) NOT NULL");
        DB::statement("ALTER TABLE `guru` MODIFY COLUMN `kode_mapel` VARCHAR(30) NULL");
        DB::statement("ALTER TABLE `jadwal` MODIFY COLUMN `kode_mapel` VARCHAR(30) NULL");

        DB::statement("ALTER TABLE `guru` ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`kode_mapel`) REFERENCES `mapel` (`kode_mapel`) ON DELETE RESTRICT ON UPDATE CASCADE");
        DB::statement("ALTER TABLE `jadwal` ADD CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`kode_mapel`) REFERENCES `mapel` (`kode_mapel`) ON DELETE RESTRICT ON UPDATE CASCADE");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `guru` DROP FOREIGN KEY `guru_ibfk_1`");
        DB::statement("ALTER TABLE `jadwal` DROP FOREIGN KEY `jadwal_ibfk_3`");

        DB::statement("ALTER TABLE `mapel` MODIFY COLUMN `kode_mapel` VARCHAR(10) NOT NULL");
        DB::statement("ALTER TABLE `guru` MODIFY COLUMN `kode_mapel` VARCHAR(10) NULL");
        DB::statement("ALTER TABLE `jadwal` MODIFY COLUMN `kode_mapel` VARCHAR(10) NULL");

        DB::statement("ALTER TABLE `guru` ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`kode_mapel`) REFERENCES `mapel` (`kode_mapel`) ON DELETE RESTRICT ON UPDATE CASCADE");
        DB::statement("ALTER TABLE `jadwal` ADD CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`kode_mapel`) REFERENCES `mapel` (`kode_mapel`) ON DELETE RESTRICT ON UPDATE CASCADE");
    }
};
