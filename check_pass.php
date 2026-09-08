<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$s = \App\Models\Siswa::where('nisn', '0094537732')->first();
if ($s) {
    $firstWord = strtolower(explode(' ', trim($s->nama_siswa))[0]);
    echo "Siswa: {$s->nama_siswa}, First word: {$firstWord}\n";
    $u = \App\Models\User::where('username', '0094537732')->first();
    if ($u) {
        echo "Check password '{$firstWord}': " . (\Illuminate\Support\Facades\Hash::check($firstWord, $u->password) ? 'MATCH' : 'NO MATCH') . "\n";
        echo "Check password 'password': " . (\Illuminate\Support\Facades\Hash::check('password', $u->password) ? 'MATCH' : 'NO MATCH') . "\n";
    }
}
