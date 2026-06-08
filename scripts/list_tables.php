<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");
echo "Tables in sqlite:\n";
foreach ($tables as $t) {
    echo $t->name . "\n";
}
