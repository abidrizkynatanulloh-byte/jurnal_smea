<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$stafAdmin = \App\Models\StafTu::firstOrCreate(
    ['nip' => '0000001'],
    [
        'nama_staf' => 'Administrator TU',
        'jabatan'   => 'Kepala Tata Usaha',
    ]
);

// Ensure 0000001
\App\Models\User::updateOrCreate(
    ['username' => '0000001'],
    [
        'password'  => \Illuminate\Support\Facades\Hash::make('admin123'),
        'role'      => 'staf_tu',
        'id_staf'   => $stafAdmin->id_staf,
        'is_active' => 1,
    ]
);

// Ensure 000001
\App\Models\User::updateOrCreate(
    ['username' => '000001'],
    [
        'password'  => \Illuminate\Support\Facades\Hash::make('admin123'),
        'role'      => 'staf_tu',
        'id_staf'   => $stafAdmin->id_staf,
        'is_active' => 1,
    ]
);

// Ensure admin
\App\Models\User::updateOrCreate(
    ['username' => 'admin'],
    [
        'password'  => \Illuminate\Support\Facades\Hash::make('admin123'),
        'role'      => 'staf_tu',
        'id_staf'   => $stafAdmin->id_staf,
        'is_active' => 1,
    ]
);

echo "Admin accounts (0000001, 000001, admin) have been created/updated with password 'admin123'!\n";
