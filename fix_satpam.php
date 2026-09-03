<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$id = DB::table('satpam')->insertGetId([
    'usn' => 'Satpam',
    'nama_satpam' => 'Satpam Utama'
]);

DB::table('users')->where('id', 163)->update(['id_satpam' => $id]);

echo "Success! id_satpam: " . $id;
