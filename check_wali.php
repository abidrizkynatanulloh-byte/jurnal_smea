<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$walis = \App\Models\User::where('role', 'wali_murid')->take(5)->get();
foreach ($walis as $w) {
    echo "Wali Username: {$w->username}, nisn_siswa: {$w->nisn_siswa}, Siswa: " . ($w->siswa ? $w->siswa->nama_siswa : 'NULL') . "\n";
}
