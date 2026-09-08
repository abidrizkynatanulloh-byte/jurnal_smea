<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$gurus = \App\Models\Guru::take(10)->get();
foreach ($gurus as $g) {
    echo "ID: {$g->id_guru} | NIP: {$g->nip} | Nama: {$g->nama_guru} | Jabatan: {$g->jabatan}\n";
}
