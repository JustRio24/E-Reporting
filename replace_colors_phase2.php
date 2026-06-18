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
    'bg-slate-900' => 'bg-gradient-to-br from-slate-900 via-slate-950 to-zinc-900',
    'bg-slate-800' => 'bg-slate-800/60 backdrop-blur-md',
    
    // Text colors
    'text-slate-50' => 'text-white',
    'text-slate-400' => 'text-slate-300',
    
    // Borders
    'border-slate-700' => 'border-slate-700/50',
    
    // Safety measures to avoid double applying if already applied somehow,
    // though the previous pass only applied basic classes.
];

$count = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;
    
    foreach ($replacements as $old => $new) {
        // Only replace if it doesn't already contain the new string, to prevent double matching
        // Actually, since text-slate-50 could match within other things, let's use word boundaries
        // Wait, what if there's bg-slate-800/60 already? Let's use negative lookahead or simple str_replace if we know the exact state.
        // We know exactly what is in the files because we just put it there.
        // We can safely use word boundary regex, but we must ensure we don't match bg-slate-900 inside the new gradient class.
        
        // Custom logic:
        if ($old === 'bg-slate-900') {
            // Replace bg-slate-900 only if it's not preceded by 'from-', 'via-', or 'to-'
            $content = preg_replace('/(?<!from-|via-|to-)\bbg-slate-900\b/', $new, $content);
        } elseif ($old === 'bg-slate-800') {
            // Replace bg-slate-800 only if not followed by '/60'
            $content = preg_replace('/\bbg-slate-800\b(?!\/60)/', $new, $content);
        } elseif ($old === 'border-slate-700') {
            $content = preg_replace('/\bborder-slate-700\b(?!\/50)/', $new, $content);
        } else {
            $content = preg_replace('/\b' . preg_quote($old, '/') . '\b/', $new, $content);
        }
    }
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        $count++;
    }
}

echo "Updated $count files for phase 2.\n";
