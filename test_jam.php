<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "JamPelajaran count: " . \App\Models\JamPelajaran::count() . "\n";
$jadwal = \App\Models\Jadwal::first();
if ($jadwal) {
    echo "First Jadwal ID: {$jadwal->id_jadwal}, jam_mulai: {$jadwal->jam_mulai}, jam_selesai: {$jadwal->jam_selesai}\n";
    echo "Status waktu: " . $jadwal->statusWaktuMengajar() . "\n";
}
