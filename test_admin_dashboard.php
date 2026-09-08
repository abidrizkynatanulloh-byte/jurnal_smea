<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('username', '0000001')->first();
\Illuminate\Support\Facades\Auth::login($user);

$req = \Illuminate\Http\Request::create('/admin/dashboard', 'GET');
$res = $app->make(\Illuminate\Contracts\Http\Kernel::class)->handle($req);

echo "Response Status Code for /admin/dashboard: " . $res->getStatusCode() . "\n";
if ($res->getStatusCode() !== 200) {
    echo "Content snippet:\n" . substr($res->getContent(), 0, 500) . "\n";
}
