<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$wo = \App\Models\WorkOrder::find(2);
if ($wo) {
    echo "Progress percentage is: " . $wo->progress_percentage . "\n";
    echo "Progress entries count: " . $wo->progressEntries()->count() . "\n";
} else {
    echo "WorkOrder 2 not found.\n";
}
