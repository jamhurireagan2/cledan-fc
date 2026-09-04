<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin'; ?> - <?php echo SITE_NAME; ?> Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Admin CSS here - will create separate file later */
        :root {
            --admin-primary: #1a2a6c;
            --admin-secondary: #c9a84c;
            --admin-bg: #f5f7fa;
            --admin-card: #ffffff;
            --admin-text: #1f2937;
            --admin-muted: #6b7280;
            --admin-border: #e5e7eb;
            --admin-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--admin-bg);
            color: var(--admin-text);
        }
        
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background: var(--admin-primary);
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 100;
        }
        
        .admin-sidebar .brand {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .admin-sidebar .brand img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: white;
            padding: 5px;
            margin-bottom: 10px;
        }
        
        .admin-sidebar .brand h3 {
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .admin-sidebar .brand small {
            font-size: 0.8rem;
            opacity: 0.7;
        }
        
        .admin-sidebar .nav-section {
            padding: 0 15px;
        }
        
        .admin-sidebar .nav-section .nav-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.5;
            padding: 10px 15px;
            font-weight: 600;
        }
        
        .admin-sidebar .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-bottom: 2px;
        }
        
        .admin-sidebar .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .admin-sidebar .nav-item.active {
            background: var(--admin-secondary);
            color: white;
        }
        
        .admin-sidebar .nav-item i {
            width: 22px;
            margin-right: 12px;
        }
        
        /* Main Content */
        .admin-content {
            margin-left: 260px;
            flex: 1;
            padding: 20px 30px;
        }
        
        /* Top Bar */
        .admin-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--admin-border);
        }
        
        .admin-topbar h1 {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .admin-topbar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .admin-topbar .user-info .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--admin-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: var(--admin-card);
            padding: 20px;
            border-radius: 15px;
            box-shadow: var(--admin-shadow);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
        }
        
        .stat-card .stat-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .stat-card .stat-info p {
            font-size: 0.85rem;
            color: var(--admin-muted);
            margin: 0;
        }
        
        /* Admin Grid */
        .admin-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .admin-card {
            background: var(--admin-card);
            border-radius: 15px;
            box-shadow: var(--admin-shadow);
            overflow: hidden;
        }
        
        .admin-card-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-card-header h3 {
            font-size: 1rem;
            font-weight: 600;
        }
        
        .admin-card-header h3 i {
            margin-right: 8px;
            color: var(--admin-secondary);
        }
        
        .admin-card-body {
            padding: 20px 25px;
        }
        
        /* Activity List */
        .activity-list {
            list-style: none;
        }
        
        .activity-list li {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--admin-border);
            gap: 12px;
        }
        
        .activity-list li:last-child {
            border-bottom: none;
        }
        
        .activity-date {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--admin-muted);
            min-width: 50px;
        }
        
        .activity-text {
            flex: 1;
            font-size: 0.9rem;
        }
        
        .activity-text a {
            color: var(--admin-text);
            text-decoration: none;
        }
        
        .activity-text a:hover {
            color: var(--admin-secondary);
        }
        
        /* Quick Actions */
        .quick-actions {
            background: var(--admin-card);
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--admin-shadow);
        }
        
        .quick-actions h3 {
            font-size: 1rem;
            margin-bottom: 20px;
        }
        
        .quick-actions h3 i {
            color: var(--admin-secondary);
            margin-right: 8px;
        }
        
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background: var(--admin-bg);
            border-radius: 12px;
            text-decoration: none;
            color: var(--admin-text);
            transition: all 0.3s ease;
        }
        
        .quick-action:hover {
            background: var(--admin-secondary);
            color: white;
            transform: translateY(-3px);
        }
        
        .quick-action i {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }
        
        .quick-action span {
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        /* Badges */
        .badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .badge.home {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge.away {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-published {
            color: #059669;
        }
        
        .status-draft {
            color: #6b7280;
        }
        
        .status-scheduled {
            color: #92400e;
        }
        
        .status-live {
            color: #dc2626;
            animation: pulse 1.5s infinite;
        }
        
        .status-completed {
            color: #059669;
        }
        
        .status-cancelled {
            color: #6b7280;
        }
        
        .text-muted {
            color: var(--admin-muted);
        }
        
        .btn-sm {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: var(--admin-secondary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #b8943a;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .admin-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-sidebar.active {
                transform: translateX(0);
            }
            
            .admin-content {
                margin-left: 0;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
            
            .admin-topbar {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="brand">
                <img src="/assets/images/badge.png" alt="Badge" onerror="this.style.display='none'">
                <h3><?php echo SITE_NAME; ?></h3>
                <small>Admin Panel</small>
            </div>
            
            <nav class="nav-section">
                <div class="nav-label">Main</div>
                <a href="dashboard.php" class="nav-item <?php echo $pageTitle === 'Dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
                <a href="players.php" class="nav-item <?php echo strpos($pageTitle ?? '', 'Player') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Players
                </a>
                <a href="staff.php" class="nav-item <?php echo strpos($pageTitle ?? '', 'Staff') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-user-tie"></i> Staff
                </a>
                <a href="matches.php" class="nav-item <?php echo strpos($pageTitle ?? '', 'Match') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-futbol"></i> Matches
                </a>
                
                <div class="nav-label" style="margin-top:20px;">Content</div>
                <a href="news.php" class="nav-item <?php echo strpos($pageTitle ?? '', 'News') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-newspaper"></i> News
                </a>
                <a href="gallery.php" class="nav-item">
                    <i class="fas fa-images"></i> Gallery
                </a>
                
                <div class="nav-label" style="margin-top:20px;">Commerce</div>
                <a href="tickets.php" class="nav-item <?php echo strpos($pageTitle ?? '', 'Ticket') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-ticket-alt"></i> Tickets
                </a>
                <a href="bookings.php" class="nav-item">
                    <i class="fas fa-shopping-cart"></i> Bookings
                </a>
                
                <div class="nav-label" style="margin-top:20px;">Settings</div>
                <a href="messages.php" class="nav-item">
                    <i class="fas fa-envelope"></i> Messages
                </a>
                <a href="settings.php" class="nav-item">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="logout.php" class="nav-item" style="color: #ef4444;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-content">
            <!-- Top Bar -->
            <div class="admin-topbar">
                <h1><?php echo $pageTitle ?? 'Dashboard'; ?></h1>
                <div class="user-info">
                    <span>Welcome, <?php echo $_SESSION['user_fullname'] ?? $_SESSION['username']; ?></span>
                    <div class="avatar">
                        <?php echo strtoupper(substr($_SESSION['user_fullname'] ?? $_SESSION['username'], 0, 1)); ?>
                    </div>
                </div>
            </div>