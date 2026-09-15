<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            // Alter siswa table: make NISN primary key and NIS nullable
            DB::statement("ALTER TABLE `siswa` DROP PRIMARY KEY, MODIFY COLUMN `nis` VARCHAR(20) NULL, ADD PRIMARY KEY (`nisn`)");
        } catch (\Throwable $e) {
            // Fallback for different driver or if already primary key
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `siswa` DROP PRIMARY KEY, MODIFY COLUMN `nis` VARCHAR(20) NOT NULL, ADD PRIMARY KEY (`nis`)");
        } catch (\Throwable $e) {
            // Fallback
        }
    }
};
