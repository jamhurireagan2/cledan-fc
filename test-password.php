<?php
require_once 'includes/database.php';

$db = getDB();

// Get admin user
$stmt = $db->prepare("SELECT * FROM users WHERE username = 'admin'");
$stmt->execute();
$user = $stmt->fetch();

if ($user) {
    echo "<h2>Admin User Found</h2>";
    echo "ID: " . $user['id'] . "<br>";
    echo "Username: " . $user['username'] . "<br>";
    echo "Email: " . $user['email'] . "<br>";
    echo "Role: " . $user['role'] . "<br>";
    echo "Password Hash: " . $user['password_hash'] . "<br><br>";
    
    // Test password 'admin123'
    $testPassword = 'admin123';
    if (password_verify($testPassword, $user['password_hash'])) {
        echo "✅ <strong>Password 'admin123' is CORRECT!</strong><br>";
        echo "You can now login at: <a href='/cledan-fc/admin/login.php'>/cledan-fc/admin/login.php</a>";
    } else {
        echo "❌ Password 'admin123' is INCORRECT.<br>";
        echo "Trying to reset...<br>";
        
        $newHash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$newHash, $user['id']]);
        echo "Password reset attempted. Please try again.";
    }
} else {
    echo "❌ Admin user not found!<br>";
    echo "Creating admin user...<br>";
    
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, full_name, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin@cledanfc.com', $hash, 'CLEDAN FC Admin', 'admin']);
    
    echo "✅ Admin created! Please try logging in.";
}
?>