<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('username', '0000001')->first();
if ($user) {
    echo "Check 'admin123': " . (\Illuminate\Support\Facades\Hash::check('admin123', $user->password) ? 'MATCH' : 'NO') . "\n";
    echo "Check 'password': " . (\Illuminate\Support\Facades\Hash::check('password', $user->password) ? 'MATCH' : 'NO') . "\n";
}
