<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tugas_kelas_kosong')) {
            Schema::create('tugas_kelas_kosong', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_jadwal')->nullable();
                $table->date('tanggal');
                $table->unsignedBigInteger('id_guru');
                $table->unsignedBigInteger('id_kelas');
                $table->text('deskripsi_tugas');
                $table->enum('status', ['Diberikan', 'Dikerjakan', 'Selesai'])->default('Diberikan');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_kelas_kosong');
    }
};
