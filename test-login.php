<?php
require_once 'includes/database.php';

$db = getDB();

// Check if admin user exists
$stmt = $db->query("SELECT * FROM users WHERE username = 'admin'");
$user = $stmt->fetch();

if ($user) {
    echo "User found!<br>";
    echo "Username: " . $user['username'] . "<br>";
    echo "Password hash: " . $user['password_hash'] . "<br>";
    
    // Test the password
    $testPassword = 'admin123';
    if (password_verify($testPassword, $user['password_hash'])) {
        echo "Password 'admin123' is CORRECT!<br>";
    } else {
        echo "Password 'admin123' is INCORRECT. Resetting...<br>";
        
        // Reset password
        $newHash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$newHash, $user['id']]);
        echo "Password has been reset to 'admin123'!";
    }
} else {
    echo "Admin user not found. Creating...<br>";
    
    // Create admin user
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, full_name, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin@cledanfc.com', $hash, 'CLEDAN FC Admin', 'admin']);
    echo "Admin user created with password 'admin123'!";
}
?>