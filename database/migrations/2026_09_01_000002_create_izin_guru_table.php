<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('izin_guru')) {
            Schema::create('izin_guru', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_guru');
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                $table->string('alasan', 100);
                $table->text('keterangan')->nullable();
                $table->string('bukti_foto')->nullable();
                $table->string('kelas_terdampak')->nullable();
                
                // Persetujuan Berjenjang (Waka Kurikulum -> SDM -> Kepala Sekolah)
                $table->enum('status_waka', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
                $table->enum('status_sdm', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
                $table->enum('status_kepsek', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
                $table->enum('status_akhir', ['Diajukan', 'Disetujui', 'Ditolak'])->default('Diajukan');
                $table->text('catatan_penolakan')->nullable();
                
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('izin_guru');
    }
};
