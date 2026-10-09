<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_event');
            $table->enum('jenis', ['event', 'pulang_pagi'])->default('event');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            // Khusus jenis pulang_pagi: waktu jam pulang (misal 12:40:00)
            // Guru yang jam_mulai mengajarnya >= jam_pulang di hari itu => bebas
            $table->time('jam_pulang')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_sekolah');
    }
};
