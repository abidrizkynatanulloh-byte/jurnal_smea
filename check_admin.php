<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('username', '0000001')->first();
if ($user) {
    echo "Admin User Found:\n";
    echo "ID: {$user->id}\n";
    echo "Username: {$user->username}\n";
    echo "Role: {$user->role}\n";
    echo "Is Active: {$user->is_active}\n";
    echo "Deleted At: " . ($user->deleted_at ? $user->deleted_at : 'NULL') . "\n";
    
    // Test common passwords: 'password', 'admin', '123456', '0000001'
    $passwords = ['password', 'admin', '123456', '0000001', 'admin123', 'staftu'];
    foreach ($passwords as $p) {
        $check = \Illuminate\Support\Facades\Hash::check($p, $user->password);
        echo "Password '{$p}': " . ($check ? 'MATCH' : 'NO MATCH') . "\n";
    }
} else {
    echo "User 0000001 NOT FOUND in database!\n";
}
