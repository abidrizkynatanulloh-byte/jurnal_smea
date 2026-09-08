<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Guru count: " . \App\Models\Guru::count() . "\n";
echo "Guru with deleted_at not null count: " . \App\Models\Guru::onlyTrashed()->count() . "\n";
echo "Guru Piket assignments count: " . \App\Models\GuruPiket::count() . "\n";

$jabatans = \App\Models\Guru::select('jabatan', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
    ->groupBy('jabatan')
    ->get();

echo "\nGuru per Jabatan:\n";
foreach ($jabatans as $j) {
    echo " - " . ($j->jabatan ?: 'NULL') . ": {$j->total}\n";
}

echo "\nUsers per Role:\n";
$roles = \App\Models\User::select('role', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
    ->groupBy('role')
    ->get();
foreach ($roles as $r) {
    echo " - {$r->role}: {$r->total}\n";
}
