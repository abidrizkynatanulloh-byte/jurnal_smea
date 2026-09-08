<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Users count: " . \App\Models\User::count() . "\n";
echo "Wali count: " . \App\Models\User::where('role', 'wali_murid')->count() . "\n";
echo "Siswa count: " . \App\Models\Siswa::count() . "\n";

$sampleSiswa = \App\Models\Siswa::take(5)->get();
foreach ($sampleSiswa as $s) {
    $namaDepan = strtolower(explode(' ', trim($s->nama_siswa))[0]);
    echo "NIS: {$s->nis} | NISN: {$s->nisn} | Nama: {$s->nama_siswa} | NamaDepan: {$namaDepan}\n";
    $user = \App\Models\User::where('username', $s->nisn)->orWhere('username', $s->nis)->first();
    echo "   User record: " . ($user ? "FOUND (ID: {$user->id}, Role: {$user->role})" : "NOT FOUND") . "\n";
}
