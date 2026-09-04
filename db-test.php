<?php
require_once 'includes/config.production.php';

echo "<h1>Database Connection Test</h1>";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ <strong style='color: green;'>Connection successful!</strong><br>";
    echo "Host: " . DB_HOST . "<br>";
    echo "Database: " . DB_NAME . "<br>";
    echo "User: " . DB_USER . "<br>";
    
    // Test query
    $stmt = $pdo->query("SELECT * FROM settings");
    echo "✅ Query successful!<br>";
    while ($row = $stmt->fetch()) {
        echo $row['setting_key'] . " = " . $row['setting_value'] . "<br>";
    }
} catch (PDOException $e) {
    echo "❌ <strong style='color: red;'>Connection failed:</strong> " . $e->getMessage() . "<br>";
    echo "<br><strong>Check your config.production.php:</strong><br>";
    echo "Host: " . DB_HOST . "<br>";
    echo "Database: " . DB_NAME . "<br>";
    echo "User: " . DB_USER . "<br>";
    echo "Password: " . (DB_PASS ? "Set (hidden)" : "Not set") . "<br>";
}
?>