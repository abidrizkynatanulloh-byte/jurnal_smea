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
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'kota_lahir')) {
                $table->string('kota_lahir', 100)->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('siswa', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('kota_lahir');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (Schema::hasColumn('siswa', 'kota_lahir')) {
                $table->dropColumn('kota_lahir');
            }
            if (Schema::hasColumn('siswa', 'tanggal_lahir')) {
                $table->dropColumn('tanggal_lahir');
            }
        });
    }
};
