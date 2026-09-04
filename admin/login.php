<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/database.php';

// Get settings
$db = getDB();
$stmt = $db->query("SELECT setting_key, setting_value FROM settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
$clubName = $settings['club_name'] ?? 'CLEDAN FC';
$error = '';

// Check if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter username and password';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user) {
            // User found, check password
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_fullname'] = $user['full_name'];
                
                // Update last login
                $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
                
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Invalid password. Please try again.';
            }
        } else {
            $error = 'Username not found. Please check your credentials.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Login - <?php echo $clubName; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a2a6c, #0d1b3e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-box {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        .login-box .badge {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-box .badge img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #c9a84c;
            padding: 5px;
        }
        .login-box h1 {
            text-align: center;
            color: #1a2a6c;
            font-size: 1.8rem;
        }
        .login-box .sub {
            text-align: center;
            color: #6b7280;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #c9a84c;
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.2);
        }
        .btn {
            width: 100%;
            padding: 14px;
            background: #c9a84c;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        .btn:hover {
            background: #b8943a;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(201, 168, 76, 0.3);
        }
        .error {
            background: #fef2f2;
            color: #991b1b;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #ef4444;
            font-size: 0.9rem;
        }
        .error i {
            margin-right: 8px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 0.85rem;
            color: #6b7280;
        }
        .footer strong {
            color: #1a2a6c;
        }
        .footer a {
            color: #c9a84c;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .debug-info {
            margin-top: 15px;
            padding: 10px;
            background: #f3f4f6;
            border-radius: 8px;
            font-size: 0.8rem;
            color: #6b7280;
            text-align: center;
        }
        .debug-info code {
            background: #e5e7eb;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="badge">
            <img src="/cledan-fc/assets/images/badge.png" alt="Badge" onerror="this.style.display='none'">
        </div>
        <h1><?php echo $clubName; ?></h1>
        <p class="sub">Admin Panel Login</p>
        
        <?php if ($error): ?>
            <div class="error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Username or Email</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username or email" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required autofocus>
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div class="footer">
            <p>Default credentials: <strong>admin</strong> / <strong>admin123</strong></p>
            <p><a href="/cledan-fc/"><i class="fas fa-arrow-left"></i> Return to Website</a></p>
            
            <?php if (isset($_GET['debug']) && $_GET['debug'] == 1): ?>
                <div class="debug-info">
                    <?php
                    // Show debug info (only when ?debug=1 is in URL)
                    $db = getDB();
                    $stmt = $db->query("SELECT id, username, email, role FROM users");
                    $users = $stmt->fetchAll();
                    echo "Users in database: " . count($users) . "<br>";
                    foreach ($users as $u) {
                        echo "• " . $u['username'] . " (" . $u['email'] . ") - " . $u['role'] . "<br>";
                    }
                    ?>
                    <small>Add <code>?debug=1</code> to URL to see this</small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>