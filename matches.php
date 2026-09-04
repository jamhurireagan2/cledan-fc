<?php
$currentPage = 'matches';
$pageTitle = 'Matches';
require_once 'includes/functions.php';
require_once 'includes/database.php';

$db = getDB();

// Get filter from URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Build query
$query = "SELECT * FROM matches ORDER BY match_date ";
if ($filter === 'upcoming') {
    $query = "SELECT * FROM matches WHERE match_date >= NOW() AND status != 'cancelled' ORDER BY match_date ASC";
} elseif ($filter === 'past') {
    $query = "SELECT * FROM matches WHERE match_date < NOW() OR status = 'completed' ORDER BY match_date DESC";
}

$stmt = $db->query($query);
$matches = $stmt->fetchAll();

// Get league info
$stmt = $db->query("SELECT * FROM leagues WHERE is_active = 1");
$leagues = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Fixtures & Results</h1>
        <p>Stay up to date with CLEDAN FC matches</p>
    </div>
</section>

<section class="py-40">
    <div class="container">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="?filter=all" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">All Matches</a>
            <a href="?filter=upcoming" class="filter-tab <?php echo $filter === 'upcoming' ? 'active' : ''; ?>">Upcoming</a>
            <a href="?filter=past" class="filter-tab <?php echo $filter === 'past' ? 'active' : ''; ?>">Past Results</a>
        </div>

        <?php if (count($matches) > 0): ?>
            <div class="matches-list">
                <?php foreach ($matches as $match): ?>
                    <div class="match-card-large">
                        <div class="match-header">
                            <span class="match-competition">
                                <i class="fas fa-trophy"></i> League Match
                            </span>
                            <span class="match-status status-<?php echo $match['status']; ?>">
                                <?php echo ucfirst($match['status']); ?>
                                <?php if ($match['status'] === 'live'): ?>
                                    <span class="live-dot"></span>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="match-teams">
                            <div class="match-team">
                                <div class="match-team-badge" style="background: var(--primary-blue); color: white;">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="match-team-name">CLEDAN FC</div>
                            </div>
                            
                            <div class="match-score-large">
                                <?php if ($match['status'] === 'scheduled'): ?>
                                    <span class="vs-text">VS</span>
                                <?php else: ?>
                                    <span class="score-text">
                                        <?php echo $match['home_score'] ?? '0'; ?> - <?php echo $match['away_score'] ?? '0'; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="match-team">
                                <div class="match-team-badge" style="background: var(--medium-gray);">
                                    <span style="font-weight: 700; font-size: 0.8rem;"><?php echo substr($match['opponent'], 0, 2); ?></span>
                                </div>
                                <div class="match-team-name"><?php echo $match['opponent']; ?></div>
                            </div>
                        </div>
                        
                        <div class="match-details">
                            <div class="match-info">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo formatDate($match['match_date'], 'F j, Y'); ?>
                            </div>
                            <div class="match-info">
                                <i class="fas fa-clock"></i>
                                <?php echo formatDate($match['match_date'], 'g:i A'); ?>
                            </div>
                            <div class="match-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo $match['venue'] ?: getSettings('stadium_name'); ?>
                                <?php if ($match['match_type'] === 'home'): ?>
                                    <span class="badge-home">Home</span>
                                <?php else: ?>
                                    <span class="badge-away">Away</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($match['report']): ?>
                            <div class="match-report">
                                <a href="/match/<?php echo $match['id']; ?>" class="btn btn-outline btn-sm">View Match Report</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-futbol"></i>
                <h3>No Matches Found</h3>
                <p>Check back later for upcoming fixtures and results.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.filter-tab {
    padding: 10px 25px;
    background: var(--white);
    border-radius: 25px;
    color: var(--text-dark);
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
}

.filter-tab:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.filter-tab.active {
    background: var(--gold);
    color: var(--white);
}

.matches-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.match-card-large {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 25px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
}

.match-card-large:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-hover);
}

.match-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--light-gray);
}

.match-teams {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
}

.match-team {
    text-align: center;
    flex: 1;
}

.match-team-badge {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin: 0 auto 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.match-team-name {
    font-weight: 600;
    font-size: 1.1rem;
}

.match-score-large {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--primary-blue);
    padding: 0 30px;
    min-width: 100px;
    text-align: center;
}

.match-score-large .vs-text {
    color: var(--dark-gray);
    font-size: 1.2rem;
}

.match-details {
    display: flex;
    justify-content: center;
    gap: 30px;
    padding-top: 15px;
    border-top: 1px solid var(--light-gray);
    flex-wrap: wrap;
}

.match-info {
    font-size: 0.9rem;
    color: var(--dark-gray);
}

.match-info i {
    margin-right: 8px;
    color: var(--gold);
}

.badge-home {
    display: inline-block;
    padding: 2px 10px;
    background: #d1fae5;
    color: #065f46;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-left: 8px;
}

.badge-away {
    display: inline-block;
    padding: 2px 10px;
    background: #fef3c7;
    color: #92400e;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-left: 8px;
}

.live-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    margin-left: 5px;
    animation: pulse 1s infinite;
}

.match-report {
    text-align: center;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid var(--light-gray);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.empty-state i {
    font-size: 4rem;
    color: var(--light-blue);
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .match-teams {
        flex-direction: column;
        gap: 15px;
    }
    
    .match-score-large {
        font-size: 2rem;
        padding: 10px 0;
    }
    
    .match-details {
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    
    .filter-tabs {
        justify-content: center;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>