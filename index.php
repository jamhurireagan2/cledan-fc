<?php
$currentPage = 'home';
$pageTitle = 'Home';
require_once 'includes/functions.php';
require_once 'includes/session.php';

// Get latest news
$db = getDB();
$stmt = $db->prepare("SELECT * FROM news WHERE is_published = 1 ORDER BY created_at DESC LIMIT 3");
$stmt->execute();
$latestNews = $stmt->fetchAll();

// Get upcoming matches
$stmt = $db->prepare("SELECT * FROM matches WHERE match_date >= NOW() AND status != 'cancelled' ORDER BY match_date ASC LIMIT 3");
$stmt->execute();
$upcomingMatches = $stmt->fetchAll();

// Get top scorers
$stmt = $db->prepare("SELECT * FROM players WHERE is_active = 1 ORDER BY goals DESC LIMIT 4");
$stmt->execute();
$topScorers = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Welcome to <span>CLEDAN FC</span></h1>
                <p>Founded in <?php echo getSettings('club_established') ?: '2026'; ?>, we are a football club built on passion, pride, and community spirit. Join us on our journey!</p>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="/tickets" class="btn btn-primary">Get Tickets</a>
                    <a href="/squad" class="btn btn-secondary">Meet the Squad</a>
                </div>
            </div>
            <div class="hero-badge">
                <img src="/assets/images/badge.png" alt="CLEDAN FC Badge">
            </div>
        </div>
    </div>
</section>

<!-- Latest News -->
<section class="py-40">
    <div class="container">
        <div class="section-title">
            <h2>Latest News</h2>
            <p>Stay updated with the latest from CLEDAN FC</p>
        </div>
        
        <div class="news-grid">
            <?php if (count($latestNews) > 0): ?>
                <?php foreach ($latestNews as $news): ?>
                    <article class="news-card">
                        <?php if ($news['featured_image']): ?>
                            <img src="/uploads/news/<?php echo $news['featured_image']; ?>" alt="<?php echo $news['title']; ?>" class="news-image">
                        <?php else: ?>
                            <div class="news-image" style="background: linear-gradient(135deg, var(--primary-blue), var(--light-blue)); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        <?php endif; ?>
                        <div class="news-content">
                            <div class="news-meta">
                                <span><i class="far fa-calendar-alt"></i> <?php echo formatDate($news['created_at']); ?></span>
                                <span class="news-category"><?php echo str_replace('-', ' ', ucfirst($news['category'])); ?></span>
                            </div>
                            <h3 class="news-title"><a href="/news/<?php echo $news['slug']; ?>"><?php echo $news['title']; ?></a></h3>
                            <p class="news-excerpt"><?php echo $news['excerpt'] ?: substr(strip_tags($news['content']), 0, 150) . '...'; ?></p>
                            <a href="/news/<?php echo $news['slug']; ?>" class="btn btn-outline btn-sm mt-20">Read More</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: 1 / -1; color: var(--dark-gray);">No news available yet.</p>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-20">
            <a href="/news" class="btn btn-primary">View All News</a>
        </div>
    </div>
</section>

<!-- Upcoming Matches -->
<section class="py-40" style="background: var(--white);">
    <div class="container">
        <div class="section-title">
            <h2>Upcoming Fixtures</h2>
            <p>Don't miss the next CLEDAN FC match</p>
        </div>
        
        <div class="matches-grid">
            <?php if (count($upcomingMatches) > 0): ?>
                <?php foreach ($upcomingMatches as $match): ?>
                    <div class="match-card">
                        <div class="match-header">
                            <span class="match-competition">
                                <i class="fas fa-trophy"></i> League Match
                            </span>
                            <span class="match-status status-scheduled">Scheduled</span>
                        </div>
                        <div class="match-teams">
                            <div class="match-team">
                                <div class="match-team-badge" style="background: var(--primary-blue); color: white;">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="match-team-name">CLEDAN FC</div>
                            </div>
                            <div class="match-score">
                                <span class="vs">VS</span>
                            </div>
                            <div class="match-team">
                                <div class="match-team-badge" style="background: var(--medium-gray);">
                                    <span style="font-weight: 700; font-size: 0.8rem;"><?php echo substr($match['opponent'], 0, 2); ?></span>
                                </div>
                                <div class="match-team-name"><?php echo $match['opponent']; ?></div>
                            </div>
                        </div>
                        <div class="match-venue">
                            <i class="fas fa-calendar-alt"></i> <?php echo formatDate($match['match_date'], 'F j, Y g:i A'); ?>
                            <br>
                            <i class="fas fa-map-marker-alt"></i> <?php echo $match['venue'] ?: getSettings('stadium_name'); ?>
                            <?php if ($match['match_type'] === 'away'): ?>
                                <span class="badge" style="background: #e5e7eb; color: #4b5563; padding: 2px 10px; border-radius: 12px; font-size: 0.75rem;">Away</span>
                            <?php else: ?>
                                <span class="badge" style="background: var(--gold); color: white; padding: 2px 10px; border-radius: 12px; font-size: 0.75rem;">Home</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: 1 / -1; color: var(--dark-gray);">No upcoming matches scheduled.</p>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-20">
            <a href="/matches" class="btn btn-primary">View All Fixtures</a>
        </div>
    </div>
</section>

<!-- Top Scorers -->
<section class="py-40">
    <div class="container">
        <div class="section-title">
            <h2>Top Scorers</h2>
            <p>Our leading goal contributors this season</p>
        </div>
        
        <div class="players-grid">
            <?php if (count($topScorers) > 0): ?>
                <?php foreach ($topScorers as $player): ?>
                    <div class="player-card">
                        <div class="player-image-wrapper">
                            <?php if ($player['photo']): ?>
    <img src="/cledan-fc/uploads/players/<?php echo $player['photo']; ?>" alt="<?php echo $player['full_name']; ?>" class="player-image">
<?php else: ?>
    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem; background: linear-gradient(135deg, var(--primary-blue), var(--light-blue));">
        <i class="fas fa-user"></i>
    </div>
<?php endif; ?>
                            <div class="player-number-badge"><?php echo $player['jersey_number']; ?></div>
                        </div>
                        <div class="player-info">
                            <h3><?php echo $player['full_name']; ?></h3>
                            <span class="player-position pos-<?php 
                                $pos = strtolower($player['position']);
                                if (in_array($pos, ['gk'])) echo 'gk';
                                elseif (in_array($pos, ['rb', 'cb', 'lb'])) echo 'defender';
                                elseif (in_array($pos, ['cdm', 'cm', 'cam'])) echo 'midfielder';
                                else echo 'forward';
                            ?>"><?php echo $player['position']; ?></span>
                            <div class="player-stats">
                                <span><strong><?php echo $player['goals']; ?></strong> Goals</span>
                                <span><strong><?php echo $player['appearances']; ?></strong> Apps</span>
                            </div>
                            <a href="/player/<?php echo $player['id']; ?>" class="btn btn-outline btn-sm mt-20">View Profile</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: 1 / -1; color: var(--dark-gray);">No player data available.</p>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-20">
            <a href="/squad" class="btn btn-primary">View Full Squad</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>