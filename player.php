<?php
$currentPage = 'squad';
require_once 'includes/functions.php';
require_once 'includes/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    header('Location: /squad');
    exit();
}

$db = getDB();
$stmt = $db->prepare("SELECT * FROM players WHERE id = ? AND is_active = 1");
$stmt->execute([$id]);
$player = $stmt->fetch();

if (!$player) {
    header('Location: /squad');
    exit();
}

$pageTitle = $player['full_name'];
require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1><?php echo $player['full_name']; ?></h1>
        <p>Player Profile</p>
    </div>
</section>

<section class="py-40">
    <div class="container">
        <div class="player-profile">
            <div class="player-profile-image">
                <?php if ($player['photo']): ?>
    <img src="/cledan-fc/uploads/players/<?php echo $player['photo']; ?>" alt="<?php echo $player['full_name']; ?>">
<?php else: ?>
    <div style="width:100%;height:400px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--primary-blue),var(--light-blue));color:white;font-size:6rem;">
        <i class="fas fa-user"></i>
    </div>
<?php endif; ?>
                <div class="player-number-large">#<?php echo $player['jersey_number']; ?></div>
            </div>
            
            <div class="player-profile-info">
                <h2><?php echo $player['full_name']; ?></h2>
                <span class="player-position pos-<?php 
                    $pos = strtolower($player['position']);
                    if (in_array($pos, ['gk'])) echo 'gk';
                    elseif (in_array($pos, ['rb', 'cb', 'lb'])) echo 'defender';
                    elseif (in_array($pos, ['cdm', 'cm', 'cam'])) echo 'midfielder';
                    else echo 'forward';
                ?>"><?php echo $player['position']; ?></span>
                
                <div class="player-details">
                    <div class="detail-item">
                        <span class="label">Nationality</span>
                        <span class="value"><?php echo $player['nationality'] ?: 'N/A'; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Date of Birth</span>
                        <span class="value"><?php echo $player['date_of_birth'] ? formatDate($player['date_of_birth']) : 'N/A'; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Height</span>
                        <span class="value"><?php echo $player['height_cm'] ? $player['height_cm'] . ' cm' : 'N/A'; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Weight</span>
                        <span class="value"><?php echo $player['weight_kg'] ? $player['weight_kg'] . ' kg' : 'N/A'; ?></span>
                    </div>
                </div>
                
                <?php if ($player['bio']): ?>
                    <div class="player-bio">
                        <h3>Biography</h3>
                        <p><?php echo nl2br($player['bio']); ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="player-stats-detailed">
                    <h3>Season Statistics</h3>
                    <div class="stats-grid-detailed">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $player['appearances']; ?></span>
                            <span class="stat-label">Appearances</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $player['goals']; ?></span>
                            <span class="stat-label">Goals</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $player['assists']; ?></span>
                            <span class="stat-label">Assists</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $player['yellow_cards']; ?></span>
                            <span class="stat-label">Yellow Cards</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.player-profile {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 50px;
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 30px;
    box-shadow: var(--shadow);
}

.player-profile-image {
    position: relative;
    border-radius: var(--border-radius);
    overflow: hidden;
}

.player-profile-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.player-number-large {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: var(--gold);
    color: white;
    font-size: 2rem;
    font-weight: 800;
    padding: 10px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

.player-profile-info h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.player-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin: 20px 0;
}

.detail-item {
    background: var(--light-gray);
    padding: 10px 15px;
    border-radius: 10px;
}

.detail-item .label {
    display: block;
    font-size: 0.8rem;
    color: var(--dark-gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-item .value {
    font-weight: 600;
    font-size: 1rem;
}

.player-bio {
    margin: 20px 0;
    padding: 20px;
    background: var(--light-gray);
    border-radius: 10px;
}

.player-bio h3 {
    margin-bottom: 10px;
}

.stats-grid-detailed {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 15px;
}

.stat-item {
    text-align: center;
    padding: 20px;
    background: var(--light-gray);
    border-radius: 10px;
}

.stat-item .stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-blue);
}

.stat-item .stat-label {
    font-size: 0.85rem;
    color: var(--dark-gray);
}

@media (max-width: 768px) {
    .player-profile {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .player-profile-image img {
        height: 250px;
    }
    
    .player-details {
        grid-template-columns: 1fr;
    }
    
    .stats-grid-detailed {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>