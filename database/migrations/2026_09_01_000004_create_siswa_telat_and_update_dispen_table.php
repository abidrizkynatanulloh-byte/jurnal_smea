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
        if (!Schema::hasTable('siswa_telat')) {
            Schema::create('siswa_telat', function (Blueprint $table) {
                $table->id();
                $table->string('nis')->index();
                $table->date('tanggal');
                $table->time('jam_terlambat');
                $table->text('alasan')->nullable();
                $table->text('tindakan')->nullable();
                $table->unsignedBigInteger('id_guru_piket')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('dispen_siswa') && !Schema::hasColumn('dispen_siswa', 'jam_ke')) {
            Schema::table('dispen_siswa', function (Blueprint $table) {
                $table->string('jam_ke')->nullable()->after('keperluan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_telat');

        if (Schema::hasTable('dispen_siswa') && Schema::hasColumn('dispen_siswa', 'jam_ke')) {
            Schema::table('dispen_siswa', function (Blueprint $table) {
                $table->dropColumn('jam_ke');
            });
        }
    }
};
