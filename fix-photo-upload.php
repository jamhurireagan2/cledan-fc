<?php
require_once 'includes/config.php';

echo "<h1>Fix Photo Upload</h1>";

// Create directories
$dirs = [
    UPLOAD_PATH,
    PLAYER_IMG_PATH,
    STAFF_IMG_PATH,
    NEWS_IMG_PATH,
    GALLERY_IMG_PATH
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
        echo "✅ Created: " . $dir . "<br>";
    } else {
        echo "✅ Exists: " . $dir . "<br>";
    }
}

echo "<br><h2>Permissions Check</h2>";

foreach ($dirs as $dir) {
    echo $dir . " - ";
    echo is_writable($dir) ? "✅ WRITABLE" : "❌ NOT WRITABLE";
    echo "<br>";
}

echo "<br><h2>PHP Info</h2>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";

echo "<br><h2>Now try adding a player again!</h2>";
echo "Go to: <a href='/cledan-fc/admin/players-add.php'>Add Player</a>";
?>