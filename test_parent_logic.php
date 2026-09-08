<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$s = \App\Models\Siswa::where('nisn', '0086735815')->first();
if ($s) {
    $firstWord = strtolower(explode(' ', trim($s->nama_siswa))[0]);
    echo "Found siswa: {$s->nama_siswa}, NISN: {$s->nisn}, First Word: {$firstWord}\n";

    // Test password match logic directly
    $inputUsername = '0086735815';
    $inputPassword = 'ahmad';
    
    $siswaMatch = \App\Models\Siswa::where('nisn', $inputUsername)->orWhere('nis', $inputUsername)->first();
    if ($siswaMatch) {
        $fw = strtolower(explode(' ', trim($siswaMatch->nama_siswa))[0]);
        if (strtolower(trim($inputPassword)) === $fw) {
            echo "SUCCESS! Password '{$inputPassword}' matched student first name '{$fw}'!\n";
        }
    }
}
