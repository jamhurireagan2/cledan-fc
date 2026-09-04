<?php
require_once 'includes/config.php';

echo "<h1>Upload Path Check</h1>";

echo "<h2>Defined Paths:</h2>";
echo "UPLOAD_PATH: " . UPLOAD_PATH . "<br>";
echo "PLAYER_IMG_PATH: " . PLAYER_IMG_PATH . "<br>";

echo "<h2>Directory Exists Check:</h2>";
echo "UPLOAD_PATH exists: " . (is_dir(UPLOAD_PATH) ? '✅ YES' : '❌ NO') . "<br>";
echo "PLAYER_IMG_PATH exists: " . (is_dir(PLAYER_IMG_PATH) ? '✅ YES' : '❌ NO') . "<br>";

echo "<h2>Directory Writable Check:</h2>";
echo "UPLOAD_PATH writable: " . (is_writable(UPLOAD_PATH) ? '✅ YES' : '❌ NO') . "<br>";
echo "PLAYER_IMG_PATH writable: " . (is_writable(PLAYER_IMG_PATH) ? '✅ YES' : '❌ NO') . "<br>";

echo "<h2>PHP Upload Settings:</h2>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";

// List files in players directory
echo "<h2>Files in players directory:</h2>";
if (is_dir(PLAYER_IMG_PATH)) {
    $files = scandir(PLAYER_IMG_PATH);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "📁 " . $file . "<br>";
        }
    }
} else {
    echo "Directory not found!<br>";
    echo "Creating directory...<br>";
    mkdir(PLAYER_IMG_PATH, 0777, true);
    echo "Directory created!<br>";
}
?>