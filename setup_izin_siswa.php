<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

\Illuminate\Support\Facades\Schema::dropIfExists('izin_siswa');
\Illuminate\Support\Facades\Schema::create('izin_siswa', function ($table) {
    $table->id();
    $table->string('nis', 10);
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
});

echo "izin_siswa table created successfully!\n";
