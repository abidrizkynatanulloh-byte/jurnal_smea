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
        if (!Schema::hasColumn('guru', 'alasan_hapus')) {
            Schema::table('guru', function (Blueprint $table) {
                $table->string('alasan_hapus', 255)->nullable()->after('deleted_at');
            });
        }

        if (!Schema::hasColumn('siswa', 'alasan_hapus')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->string('alasan_hapus', 255)->nullable()->after('deleted_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('guru', 'alasan_hapus')) {
            Schema::table('guru', function (Blueprint $table) {
                $table->dropColumn('alasan_hapus');
            });
        }

        if (Schema::hasColumn('siswa', 'alasan_hapus')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropColumn('alasan_hapus');
            });
        }
    }
};
