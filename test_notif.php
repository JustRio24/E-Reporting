<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ns = app(\App\Services\NotificationService::class);
try {
    $ns->notify(1, 'Test', 'Test msg');
    echo "Notification inserted!\n";
} catch (\Exception $e) {
    echo "Error inserting notification: " . $e->getMessage() . "\n";
}
echo "Total notifications: " . \App\Models\Notification::count() . "\n";
