<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'cledan_fc');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration
define('SITE_NAME', 'CLEDAN FC');
define('SITE_URL', 'http://localhost/cledan-fc/');
define('ADMIN_EMAIL', 'admin@cledanfc.com');

// Upload paths - FIXED for Windows
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('PLAYER_IMG_PATH', UPLOAD_PATH . 'players/');
define('STAFF_IMG_PATH', UPLOAD_PATH . 'staff/');
define('NEWS_IMG_PATH', UPLOAD_PATH . 'news/');
define('GALLERY_IMG_PATH', UPLOAD_PATH . 'gallery/');

// Create directories if they don't exist
function createUploadDirectories() {
    $dirs = [
        UPLOAD_PATH,
        PLAYER_IMG_PATH,
        STAFF_IMG_PATH,
        NEWS_IMG_PATH,
        GALLERY_IMG_PATH
    ];
    
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
    }
}
createUploadDirectories();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Africa/Nairobi');
?>