<?php
// InfinityFree Production Database Configuration
// YOUR ACTUAL CONNECTION DETAILS

// Database connection
define('DB_HOST', 'sql310.infinityfree.com');
define('DB_NAME', 'if0_42831320_cledanfc');
define('DB_USER', 'if0_42831320');
define('DB_PASS', 'YOUR_PASSWORD_HERE'); // <- Replace with your actual password

// Site configuration
define('SITE_NAME', 'CLEDAN FC');
define('SITE_URL', 'https://cledan-fc.gamer.gd/');
define('ADMIN_EMAIL', 'admin@cledanfc.com');

// Upload paths
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

// Error reporting (disabled for production)
error_reporting(0);
ini_set('display_errors', 0);

// Timezone
date_default_timezone_set('Africa/Nairobi');

// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
?>