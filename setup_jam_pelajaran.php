<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::dropIfExists('jam_pelajaran');
Schema::create('jam_pelajaran', function (Blueprint $table) {
    $table->id('id_jam');
    $table->integer('jam_ke');
    $table->enum('kelompok_hari', ['Reguler', 'Jumat'])->default('Reguler');
    $table->time('waktu_mulai');
    $table->time('waktu_selesai');
    $table->tinyInteger('is_aktif')->default(1);
    $table->timestamps();
    $table->softDeletes();
});

echo "Table jam_pelajaran created successfully!\n";

$seeder = new \Database\Seeders\JamPelajaranSeeder();
$seeder->run();

echo "JamPelajaranSeeder executed successfully!\n";
