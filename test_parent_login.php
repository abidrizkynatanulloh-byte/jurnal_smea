<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$s = \App\Models\Siswa::where('nisn', '0086735815')->first();
if ($s) {
    echo "Found siswa: {$s->nama_siswa}, NISN: {$s->nisn}\n";
    $firstWord = strtolower(explode(' ', trim($s->nama_siswa))[0]);
    echo "Testing login with username={$s->nisn}, password={$firstWord}\n";
    
    // Simulate login logic
    $req = \Illuminate\Http\Request::create('/login', 'POST', [
        'username' => $s->nisn,
        'password' => $firstWord
    ]);
    
    $controller = new \App\Http\Controllers\AuthController();
    try {
        $res = $controller->login($req);
        echo "Login Successful! Logged in user: " . (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->username : 'NO') . "\n";
    } catch (\Exception $e) {
        echo "Login Failed: " . $e->getMessage() . "\n";
    }
}
