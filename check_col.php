<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$col = \Illuminate\Support\Facades\DB::select("SHOW FULL COLUMNS FROM siswa WHERE Field = 'nis'");
print_r($col);
