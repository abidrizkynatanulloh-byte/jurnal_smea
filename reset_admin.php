<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('username', '0000001')->first();
if ($user) {
    $user->update([
        'password'  => \Illuminate\Support\Facades\Hash::make('admin123'),
        'is_active' => 1,
    ]);
    echo "Password for 0000001 has been reset to: admin123\n";
} else {
    $stafAdmin = \App\Models\StafTu::firstOrCreate(
        ['nip' => '0000001'],
        [
            'nama_staf' => 'Administrator TU',
            'jabatan'   => 'Kepala Tata Usaha',
        ]
    );

    \App\Models\User::create([
        'username'  => '0000001',
        'password'  => \Illuminate\Support\Facades\Hash::make('admin123'),
        'role'      => 'staf_tu',
        'id_staf'   => $stafAdmin->id_staf,
        'is_active' => 1,
    ]);
    echo "Created user 0000001 with password: admin123\n";
}
