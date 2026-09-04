<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Check if logged in
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = 'dashboard.php';
    header('Location: login.php');
    exit();
}

$db = getDB();

// Get statistics
$stats = [];

// Total players
$stmt = $db->query("SELECT COUNT(*) as count FROM players WHERE is_active = 1");
$stats['players'] = $stmt->fetch()['count'];

// Total staff
$stmt = $db->query("SELECT COUNT(*) as count FROM staff WHERE is_active = 1");
$stats['staff'] = $stmt->fetch()['count'];

// Upcoming matches
$stmt = $db->query("SELECT COUNT(*) as count FROM matches WHERE match_date >= NOW() AND status != 'cancelled'");
$stats['upcoming_matches'] = $stmt->fetch()['count'];

// Total matches
$stmt = $db->query("SELECT COUNT(*) as count FROM matches");
$stats['total_matches'] = $stmt->fetch()['count'];

// News count
$stmt = $db->query("SELECT COUNT(*) as count FROM news WHERE is_published = 1");
$stats['news'] = $stmt->fetch()['count'];

// Pending bookings
$stmt = $db->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'");
$stats['pending_bookings'] = $stmt->fetch()['count'];

// Unread messages
$stmt = $db->query("SELECT COUNT(*) as count FROM contact_messages WHERE status = 'unread'");
$stats['unread_messages'] = $stmt->fetch()['count'];

// Recent activity - latest news
$stmt = $db->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 5");
$recentNews = $stmt->fetchAll();

// Upcoming matches
$stmt = $db->prepare("SELECT * FROM matches WHERE match_date >= NOW() ORDER BY match_date ASC LIMIT 5");
$stmt->execute();
$upcomingMatches = $stmt->fetchAll();

$pageTitle = 'Dashboard';
require_once 'includes/admin-header.php';
?>

<div class="admin-dashboard">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #4ecdc4;">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['players']; ?></h3>
                <p>Active Players</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #45b7d1;">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['staff']; ?></h3>
                <p>Staff Members</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #f7dc6f;">
                <i class="fas fa-futbol"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['upcoming_matches']; ?></h3>
                <p>Upcoming Matches</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #ff6b6b;">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['news']; ?></h3>
                <p>Published News</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #f9a825;">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['pending_bookings']; ?></h3>
                <p>Pending Bookings</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #e74c3c;">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['unread_messages']; ?></h3>
                <p>Unread Messages</p>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="admin-grid">
        <!-- Recent News -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-newspaper"></i> Recent News</h3>
                <a href="news.php" class="btn-sm btn-primary">View All</a>
            </div>
            <div class="admin-card-body">
                <?php if (count($recentNews) > 0): ?>
                    <ul class="activity-list">
                        <?php foreach ($recentNews as $news): ?>
                            <li>
                                <span class="activity-date"><?php echo formatDate($news['created_at'], 'M d'); ?></span>
                                <span class="activity-text">
                                    <a href="news-edit.php?id=<?php echo $news['id']; ?>"><?php echo $news['title']; ?></a>
                                </span>
                                <span class="activity-status <?php echo $news['is_published'] ? 'status-published' : 'status-draft'; ?>">
                                    <?php echo $news['is_published'] ? 'Published' : 'Draft'; ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No news articles yet.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Upcoming Matches -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="fas fa-calendar-alt"></i> Upcoming Matches</h3>
                <a href="matches.php" class="btn-sm btn-primary">View All</a>
            </div>
            <div class="admin-card-body">
                <?php if (count($upcomingMatches) > 0): ?>
                    <ul class="activity-list">
                        <?php foreach ($upcomingMatches as $match): ?>
                            <li>
                                <span class="activity-date"><?php echo formatDate($match['match_date'], 'M d'); ?></span>
                                <span class="activity-text">
                                    CLEDAN FC vs <?php echo $match['opponent']; ?>
                                    <?php if ($match['match_type'] === 'away'): ?>
                                        <span class="badge away">Away</span>
                                    <?php else: ?>
                                        <span class="badge home">Home</span>
                                    <?php endif; ?>
                                </span>
                                <span class="activity-status status-<?php echo $match['status']; ?>">
                                    <?php echo ucfirst($match['status']); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No upcoming matches.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
        <div class="quick-actions-grid">
            <a href="players-add.php" class="quick-action">
                <i class="fas fa-user-plus"></i>
                <span>Add Player</span>
            </a>
            <a href="matches-add.php" class="quick-action">
                <i class="fas fa-plus-circle"></i>
                <span>Add Match</span>
            </a>
            <a href="news-add.php" class="quick-action">
                <i class="fas fa-plus-circle"></i>
                <span>Add News</span>
            </a>
            <a href="tickets-add.php" class="quick-action">
                <i class="fas fa-ticket-alt"></i>
                <span>Add Tickets</span>
            </a>
        </div>
    </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>