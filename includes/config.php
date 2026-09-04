<?php
// Auto-detect environment
$isProduction = strpos($_SERVER['HTTP_HOST'], 'localhost') === false && 
                strpos($_SERVER['HTTP_HOST'], '127.0.0.1') === false;

if ($isProduction) {
    require_once __DIR__ . '/config.production.php';
} else {
    require_once __DIR__ . '/config.local.php';
}
?>