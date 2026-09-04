<?php
require_once 'database.php';

// Sanitize input
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Generate slug
function createSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

// Upload file - FIXED VERSION
function uploadFile($file, $targetDir, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'], $maxSize = 5000000) {
    $errors = [];
    
    // Debug: Log the file data
    error_log("Upload attempt - File: " . print_r($file, true));
    error_log("Upload attempt - Target Dir: " . $targetDir);
    
    // Check if file uploaded
    if (!isset($file['tmp_name']) || empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'No file uploaded or upload error occurred';
        return ['success' => false, 'errors' => $errors];
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        $errors[] = 'File too large. Max size: ' . ($maxSize / 1000000) . 'MB';
    }
    
    // Get file extension
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowedTypes)) {
        $errors[] = 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes);
    }
    
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }
    
    // Create directory if not exists
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0777, true)) {
            $errors[] = 'Failed to create upload directory: ' . $targetDir;
            return ['success' => false, 'errors' => $errors];
        }
    }
    
    // Generate unique filename
    $newFileName = time() . '_' . uniqid() . '.' . $fileExt;
    $targetPath = $targetDir . $newFileName;
    
    // Debug: Log the target path
    error_log("Target path: " . $targetPath);
    
    // Move file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'filename' => $newFileName];
    } else {
        $errors[] = 'Failed to upload file. Check directory permissions.';
        error_log("Failed to move uploaded file to: " . $targetPath);
        return ['success' => false, 'errors' => $errors];
    }
}

// ... rest of your functions remain the same ...

// Get player position badge color
function getPositionBadge($position) {
    $badges = [
        'GK' => 'badge-danger',
        'RB' => 'badge-warning',
        'CB' => 'badge-warning',
        'LB' => 'badge-warning',
        'CDM' => 'badge-success',
        'CM' => 'badge-success',
        'CAM' => 'badge-success',
        'RW' => 'badge-primary',
        'LW' => 'badge-primary',
        'CF' => 'badge-primary',
        'ST' => 'badge-primary'
    ];
    return $badges[$position] ?? 'badge-secondary';
}

// Format date
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

// Time ago function
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return $diff . ' seconds ago';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' days ago';
    } else {
        return date('F j, Y', $time);
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect
function redirect($url) {
    header('Location: ' . $url);
    exit();
}

// Set flash message
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Get flash message
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Get club settings
function getSettings($key = null) {
    $db = getDB();
    if ($key) {
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : null;
    }
    
    $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}
?>