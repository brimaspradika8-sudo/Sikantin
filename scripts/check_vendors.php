<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Vendor;

echo "Vendors: " . Vendor::count() . PHP_EOL;
foreach (Vendor::all() as $v) {
    echo $v->id . ' - ' . $v->name . PHP_EOL;
}
