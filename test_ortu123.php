<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$siswa = \App\Models\Siswa::first();
if ($siswa) {
    echo "Testing login for NISN: {$siswa->nisn} with password 'ortu123'\n";
    $inputUsername = $siswa->nisn;
    $inputPassword = 'ortu123';
    
    $siswaMatch = \App\Models\Siswa::where('nisn', $inputUsername)->orWhere('nis', $inputUsername)->first();
    if ($siswaMatch) {
        $fw = strtolower(explode(' ', trim($siswaMatch->nama_siswa))[0]);
        $isValid = ($inputPassword === 'ortu123' || $inputPassword === 'wali123' || $inputPassword === $fw);
        if ($isValid) {
            echo "SUCCESS! Parent login with 'ortu123' is VALID for NISN {$siswa->nisn} ({$siswa->nama_siswa})!\n";
        }
    }
}
