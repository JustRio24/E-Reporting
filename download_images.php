<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Facility;

$categories = [
    'Dermaga' => 'https://loremflickr.com/600/400/port,dock',
    'Conveyor System' => 'https://loremflickr.com/600/400/conveyor,mining,industry',
    'Gudang & Penimbunan' => 'https://loremflickr.com/600/400/warehouse,storage,factory',
    'Kelistrikan & Utilitas' => 'https://loremflickr.com/600/400/powerplant,electrical,substation',
];

$dir = storage_path('app/public/facility_photos');
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Ensure context is set to ignore SSL just in case
$context = stream_context_create([
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
    "http" => [
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
    ]
]);

$paths = [];
foreach ($categories as $cat => $url) {
    $filename = 'fac_' . md5($cat) . '.jpg';
    $path = $dir . '/' . $filename;
    echo "Downloading $cat...\n";
    $image = file_get_contents($url, false, $context);
    if ($image) {
        file_put_contents($path, $image);
        $paths[$cat] = 'facility_photos/' . $filename;
        echo "Saved to $paths[$cat]\n";
    } else {
        echo "Failed to download $cat\n";
    }
}

$facilities = Facility::with('category')->get();
foreach ($facilities as $facility) {
    $catName = $facility->category->name;
    if (isset($paths[$catName])) {
        $facility->update(['photo_path' => $paths[$catName]]);
    }
}

echo "Facilities updated.\n";
