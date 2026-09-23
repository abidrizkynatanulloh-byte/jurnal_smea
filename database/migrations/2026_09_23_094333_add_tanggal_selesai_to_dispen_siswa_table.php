<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('dispen_siswa') && !Schema::hasColumn('dispen_siswa', 'tanggal_selesai')) {
            Schema::table('dispen_siswa', function (Blueprint $table) {
                $table->date('tanggal_selesai')->nullable()->after('tanggal');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('dispen_siswa') && Schema::hasColumn('dispen_siswa', 'tanggal_selesai')) {
            Schema::table('dispen_siswa', function (Blueprint $table) {
                $table->dropColumn('tanggal_selesai');
            });
        }
    }
};
