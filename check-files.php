<?php
echo "<h1>File Check</h1>";

$files = [
    'assets/css/style.css',
    'assets/css/responsive.css',
    'assets/js/main.js',
    'assets/images/badge.png'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file - EXISTS<br>";
    } else {
        echo "❌ $file - MISSING<br>";
    }
}

echo "<br>Current directory: " . __DIR__;
?>
