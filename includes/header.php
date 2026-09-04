<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CLEDAN FC - Official website of the football club">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <!-- CSS - USING ABSOLUTE PATHS FROM ROOT -->
    <link rel="stylesheet" href="/cledan-fc/assets/css/style.css">
    <link rel="stylesheet" href="/cledan-fc/assets/css/responsive.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" href="/cledan-fc/assets/images/favicon.ico" type="image/x-icon">
    
    <?php if (isset($extraCss)) echo $extraCss; ?>
</head>
<body>
    <!-- Mobile Menu Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <!-- Header -->
    <header class="club-header">
        <div class="container">
            <nav class="navbar">
                <div class="nav-brand">
                    <a href="/cledan-fc/">
                        <img src="/cledan-fc/assets/images/badge.png" alt="CLEDAN FC Badge" class="club-badge" width="60" height="60">
                        <span class="club-name">CLEDAN FC</span>
                    </a>
                </div>
                
                <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                
                <ul class="nav-menu" id="navMenu">
                    <li class="nav-item"><a href="/cledan-fc/" class="nav-link <?php echo $currentPage === 'home' ? 'active' : ''; ?>">Home</a></li>
                    <li class="nav-item"><a href="/cledan-fc/squad" class="nav-link <?php echo $currentPage === 'squad' ? 'active' : ''; ?>">Squad</a></li>
                    <li class="nav-item"><a href="/cledan-fc/matches" class="nav-link <?php echo $currentPage === 'matches' ? 'active' : ''; ?>">Matches</a></li>
                    <li class="nav-item"><a href="/cledan-fc/news" class="nav-link <?php echo $currentPage === 'news' ? 'active' : ''; ?>">News</a></li>
                    <li class="nav-item"><a href="/cledan-fc/tickets" class="nav-link <?php echo $currentPage === 'tickets' ? 'active' : ''; ?>">Tickets</a></li>
                    <li class="nav-item"><a href="/cledan-fc/about" class="nav-link <?php echo $currentPage === 'about' ? 'active' : ''; ?>">About</a></li>
                    <li class="nav-item"><a href="/cledan-fc/contact" class="nav-link <?php echo $currentPage === 'contact' ? 'active' : ''; ?>">Contact</a></li>
                    
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item"><a href="/cledan-fc/admin/dashboard.php" class="nav-link"><i class="fas fa-user-shield"></i> Admin</a></li>
                        <li class="nav-item"><a href="/cledan-fc/admin/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php $flash = getFlash(); ?>
    <?php if ($flash): ?>
        <div class="flash-message flash-<?php echo $flash['type']; ?>">
            <div class="container">
                <?php echo $flash['message']; ?>
                <button class="flash-close" onclick="this.parentElement.parentElement.remove();">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <main>