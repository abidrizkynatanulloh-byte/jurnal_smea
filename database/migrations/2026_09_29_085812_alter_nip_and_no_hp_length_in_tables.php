<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0");

        // Alter guru
        if (Schema::hasTable('guru')) {
            if (Schema::hasColumn('guru', 'nip')) {
                DB::statement("ALTER TABLE `guru` MODIFY COLUMN `nip` VARCHAR(50) NULL");
            }
            if (Schema::hasColumn('guru', 'no_hp')) {
                DB::statement("ALTER TABLE `guru` MODIFY COLUMN `no_hp` VARCHAR(30) NULL");
            }
        }

        // Alter staf_tu
        if (Schema::hasTable('staf_tu')) {
            if (Schema::hasColumn('staf_tu', 'nip')) {
                DB::statement("ALTER TABLE `staf_tu` MODIFY COLUMN `nip` VARCHAR(50) NULL");
            }
            if (Schema::hasColumn('staf_tu', 'no_hp')) {
                DB::statement("ALTER TABLE `staf_tu` MODIFY COLUMN `no_hp` VARCHAR(30) NULL");
            }
        }

        // Alter satpam
        if (Schema::hasTable('satpam')) {
            if (Schema::hasColumn('satpam', 'usn')) {
                DB::statement("ALTER TABLE `satpam` MODIFY COLUMN `usn` VARCHAR(50) NULL");
            }
            if (Schema::hasColumn('satpam', 'no_hp')) {
                DB::statement("ALTER TABLE `satpam` MODIFY COLUMN `no_hp` VARCHAR(30) NULL");
            }
        }

        // Alter siswa
        if (Schema::hasTable('siswa')) {
            if (Schema::hasColumn('siswa', 'no_hp')) {
                DB::statement("ALTER TABLE `siswa` MODIFY COLUMN `no_hp` VARCHAR(30) NULL");
            }
            if (Schema::hasColumn('siswa', 'no_hp_ortu')) {
                DB::statement("ALTER TABLE `siswa` MODIFY COLUMN `no_hp_ortu` VARCHAR(30) NULL");
            }
        }

        // Alter kelas
        if (Schema::hasTable('kelas') && Schema::hasColumn('kelas', 'wali_kelas')) {
            DB::statement("ALTER TABLE `kelas` MODIFY COLUMN `wali_kelas` VARCHAR(50) NULL");
        }

        // Alter users
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'username')) {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `username` VARCHAR(60) NOT NULL");
        }

        DB::statement("SET FOREIGN_KEY_CHECKS=1");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
