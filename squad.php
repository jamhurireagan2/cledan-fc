<?php
$currentPage = 'squad';
$pageTitle = 'Squad';
require_once 'includes/functions.php';
require_once 'includes/database.php';

$db = getDB();

// Get all active players grouped by position
$positions = ['GK', 'RB', 'CB', 'LB', 'CDM', 'CM', 'CAM', 'RW', 'LW', 'CF', 'ST'];
$playersByPosition = [];

foreach ($positions as $pos) {
    $stmt = $db->prepare("SELECT * FROM players WHERE position = ? AND is_active = 1 ORDER BY jersey_number ASC");
    $stmt->execute([$pos]);
    $playersByPosition[$pos] = $stmt->fetchAll();
}

require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>First Team Squad</h1>
        <p>Meet the players representing CLEDAN FC</p>
    </div>
</section>

<section class="py-40">
    <div class="container">
        <?php if (empty(array_filter($playersByPosition))): ?>
            <p class="text-center text-muted">No squad data available yet.</p>
        <?php else: ?>
            <?php foreach ($playersByPosition as $position => $players): ?>
                <?php if (count($players) > 0): ?>
                    <div class="position-group">
                        <h2 class="position-title">
                            <?php
                            $posNames = [
                                'GK' => 'Goalkeepers',
                                'RB' => 'Right Backs',
                                'CB' => 'Center Backs',
                                'LB' => 'Left Backs',
                                'CDM' => 'Defensive Midfielders',
                                'CM' => 'Central Midfielders',
                                'CAM' => 'Attacking Midfielders',
                                'RW' => 'Right Wingers',
                                'LW' => 'Left Wingers',
                                'CF' => 'Center Forwards',
                                'ST' => 'Strikers'
                            ];
                            echo $posNames[$position] ?? $position;
                            ?>
                        </h2>
                        <div class="players-grid">
                            <?php foreach ($players as $player): ?>
                                <div class="player-card">
                                    <div class="player-image-wrapper">
                                        <?php if ($player['photo']): ?>
                                            <!-- FIXED: Added /cledan-fc/ to the path -->
                                            <img src="/cledan-fc/uploads/players/<?php echo $player['photo']; ?>" alt="<?php echo $player['full_name']; ?>" class="player-image">
                                        <?php else: ?>
                                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:white;font-size:4rem;background:linear-gradient(135deg,var(--primary-blue),var(--light-blue));">
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
                                        <a href="/cledan-fc/player/<?php echo $player['id']; ?>" class="btn btn-outline btn-sm mt-20">View Profile</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<style>
.position-group {
    margin-bottom: 50px;
}

.position-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 3px solid var(--gold);
    display: inline-block;
}

.players-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 30px;
}
</style>

<?php require_once 'includes/footer.php'; ?>