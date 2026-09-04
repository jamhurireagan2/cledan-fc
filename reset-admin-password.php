<?php
require_once 'includes/database.php';

$db = getDB();

// Check if admin exists
$stmt = $db->prepare("SELECT * FROM users WHERE username = 'admin'");
$stmt->execute();
$user = $stmt->fetch();

if ($user) {
    // Reset password
    $newPassword = 'admin123';
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $stmt->execute([$hash, $user['id']]);
    
    echo "✅ Password reset successful!<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "Hash: " . $hash . "<br>";
} else {
    // Create admin user
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, full_name, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin@cledanfc.com', $hash, 'CLEDAN FC Admin', 'admin']);
    echo "✅ Admin user created!<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
}

// Show all users
echo "<br><strong>All users in database:</strong><br>";
$stmt = $db->query("SELECT id, username, email, role, password_hash FROM users");
$users = $stmt->fetchAll();
foreach ($users as $u) {
    echo "ID: {$u['id']}, Username: {$u['username']}, Email: {$u['email']}, Role: {$u['role']}<br>";
}
?>