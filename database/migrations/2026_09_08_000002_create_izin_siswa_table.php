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
        Schema::dropIfExists('izin_siswa');

        Schema::create('izin_siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 10)->collation('utf8mb4_0900_ai_ci');
            $table->enum('kategori', ['Sakit', 'Izin', 'Dispen', 'Lainnya'])->default('Izin');
            $table->text('alasan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('bukti_foto')->nullable();
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->text('catatan_penolakan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('nis');
            $table->foreign('nis')->references('nis')->on('siswa')->onDelete('cascade');
            $table->foreign('disetujui_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_siswa');
    }
};
