<?php
$str = 'class="bg-white"';
echo preg_replace('/\bbg-white\b/', 'bg-slate-800', $str);
echo "\n";
