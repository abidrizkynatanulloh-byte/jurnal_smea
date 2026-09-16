<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$jadwals = App\Models\Jadwal::with(['guru', 'kelas', 'mapel'])->get()->filter(function($j) {
    return str_contains($j->guru->nama_guru ?? '', 'Astra');
})->values();

foreach ($jadwals as $j) {
    echo "ID: {$j->id_jadwal} | Hari: {$j->hari} | Jam: {$j->jam_mulai} - {$j->jam_selesai} | Kelas: {$j->kelas->nama_kelas}\n";
    echo "  Times: " . json_encode($j->getWaktuMulaiSelesai()) . "\n";
    echo "  Status Waktu: " . $j->statusWaktuMengajar() . "\n";
}
