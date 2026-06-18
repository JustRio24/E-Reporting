<?php
$dir = __DIR__ . '/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$files = [];

foreach ($iterator as $file) {
    if ($file->isFile() && strpos($file->getFilename(), '.blade.php') !== false) {
        $files[] = $file->getPathname();
    }
}

$replacements = [
    // Backgrounds
    'bg-slate-50' => 'bg-slate-900',
    'bg-gray-50' => 'bg-slate-900',
    'bg-white' => 'bg-slate-800',
    
    // Text colors
    'text-slate-900' => 'text-slate-50',
    'text-gray-900' => 'text-slate-50',
    'text-slate-800' => 'text-slate-50',
    'text-gray-800' => 'text-slate-50',
    'text-slate-500' => 'text-slate-400',
    'text-gray-500' => 'text-slate-400',
    'text-slate-600' => 'text-slate-400',
    'text-gray-600' => 'text-slate-400',
    
    // Borders
    'border-slate-200' => 'border-slate-700',
    'border-gray-200' => 'border-slate-700',
];

$count = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;
    
    foreach ($replacements as $old => $new) {
        $content = preg_replace('/\b' . preg_quote($old, '/') . '\b/', $new, $content);
    }
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        $count++;
    }
}

echo "Updated $count files.\n";
