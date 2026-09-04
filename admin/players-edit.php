<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = 'players-edit.php';
    header('Location: login.php');
    exit();
}

$db = getDB();
$pageTitle = 'Edit Player';
$error = '';
$success = '';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header('Location: players.php');
    exit();
}

// Get player data
$stmt = $db->prepare("SELECT * FROM players WHERE id = ?");
$stmt->execute([$id]);
$player = $stmt->fetch();

if (!$player) {
    header('Location: players.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name'] ?? '');
    $jersey_number = intval($_POST['jersey_number'] ?? 0);
    $position = sanitize($_POST['position'] ?? '');
    $nationality = sanitize($_POST['nationality'] ?? '');
    $date_of_birth = sanitize($_POST['date_of_birth'] ?? '');
    $height_cm = intval($_POST['height_cm'] ?? 0);
    $weight_kg = intval($_POST['weight_kg'] ?? 0);
    $bio = sanitize($_POST['bio'] ?? '');
    $goals = intval($_POST['goals'] ?? 0);
    $assists = intval($_POST['assists'] ?? 0);
    $appearances = intval($_POST['appearances'] ?? 0);
    $yellow_cards = intval($_POST['yellow_cards'] ?? 0);
    $red_cards = intval($_POST['red_cards'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($full_name) || empty($position) || $jersey_number <= 0) {
        $error = 'Please fill in all required fields';
    } else {
        // Check if jersey number is unique (excluding current player)
        $stmt = $db->prepare("SELECT id FROM players WHERE jersey_number = ? AND id != ?");
        $stmt->execute([$jersey_number, $id]);
        if ($stmt->fetch()) {
            $error = 'Jersey number already assigned to another player';
        } else {
            // Handle photo upload
            $photo = $player['photo']; // Keep existing photo by default
            
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                // Make sure directory exists
                if (!is_dir(PLAYER_IMG_PATH)) {
                    mkdir(PLAYER_IMG_PATH, 0777, true);
                }
                
                $uploadResult = uploadFile($_FILES['photo'], PLAYER_IMG_PATH);
                if ($uploadResult['success']) {
                    // Delete old photo if exists
                    if ($photo && file_exists(PLAYER_IMG_PATH . $photo)) {
                        unlink(PLAYER_IMG_PATH . $photo);
                    }
                    $photo = $uploadResult['filename'];
                } else {
                    $error = implode(', ', $uploadResult['errors']);
                }
            }
            
            if (empty($error)) {
                $stmt = $db->prepare("UPDATE players SET full_name = ?, jersey_number = ?, position = ?, nationality = ?, date_of_birth = ?, height_cm = ?, weight_kg = ?, bio = ?, photo = ?, goals = ?, assists = ?, appearances = ?, yellow_cards = ?, red_cards = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$full_name, $jersey_number, $position, $nationality, $date_of_birth, $height_cm, $weight_kg, $bio, $photo, $goals, $assists, $appearances, $yellow_cards, $red_cards, $is_active, $id]);
                
                setFlash('success', 'Player updated successfully!');
                header('Location: players.php');
                exit();
            }
        }
    }
}

require_once 'includes/admin-header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <h2>Edit Player</h2>
        <a href="players.php" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Players
        </a>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data" class="admin-form">
        <div class="form-grid">
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo $player['full_name']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="jersey_number">Jersey Number *</label>
                <input type="number" id="jersey_number" name="jersey_number" class="form-control" min="1" max="99" value="<?php echo $player['jersey_number']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="position">Position *</label>
                <select id="position" name="position" class="form-control" required>
                    <option value="">Select Position</option>
                    <?php
                    $positions = ['GK', 'RB', 'CB', 'LB', 'CDM', 'CM', 'CAM', 'RW', 'LW', 'CF', 'ST'];
                    foreach ($positions as $pos):
                    ?>
                        <option value="<?php echo $pos; ?>" <?php echo $player['position'] === $pos ? 'selected' : ''; ?>>
                            <?php echo $pos; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="nationality">Nationality</label>
                <input type="text" id="nationality" name="nationality" class="form-control" value="<?php echo $player['nationality']; ?>">
            </div>
            
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?php echo $player['date_of_birth']; ?>">
            </div>
            
            <div class="form-group">
                <label for="height_cm">Height (cm)</label>
                <input type="number" id="height_cm" name="height_cm" class="form-control" value="<?php echo $player['height_cm']; ?>">
            </div>
            
            <div class="form-group">
                <label for="weight_kg">Weight (kg)</label>
                <input type="number" id="weight_kg" name="weight_kg" class="form-control" value="<?php echo $player['weight_kg']; ?>">
            </div>
            
            <div class="form-group full-width">
                <label for="bio">Biography</label>
                <textarea id="bio" name="bio" class="form-control" rows="5"><?php echo $player['bio']; ?></textarea>
            </div>
            
            <!-- PHOTO SECTION - FIXED -->
            <div class="form-group full-width">
                <label>Current Photo</label>
                <div style="margin: 10px 0; padding: 15px; background: #f9fafb; border-radius: 10px; display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                    <?php if ($player['photo'] && file_exists(PLAYER_IMG_PATH . $player['photo'])): ?>
                        <img src="/cledan-fc/uploads/players/<?php echo $player['photo']; ?>" alt="Current Photo" style="width: 100px; height: 100px; border-radius: 10px; object-fit: cover; border: 2px solid var(--gold);">
                        <div>
                            <span style="font-weight: 600;">Current Photo</span>
                            <br>
                            <span style="font-size: 0.85rem; color: var(--admin-muted);">Filename: <?php echo $player['photo']; ?></span>
                        </div>
                    <?php else: ?>
                        <div style="width: 100px; height: 100px; border-radius: 10px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #6b7280;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <span style="font-weight: 600;">No Photo</span>
                            <br>
                            <span style="font-size: 0.85rem; color: var(--admin-muted);">Upload a new photo below</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <label for="photo">Upload New Photo (Optional)</label>
                <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                <small class="form-text">Leave empty to keep current photo. Max size: 5MB. Allowed: JPG, PNG, GIF</small>
            </div>
            
            <!-- Stats Section -->
            <div class="form-group full-width" style="margin-top: 20px;">
                <h3 style="font-size: 1rem; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid var(--gold);">Statistics</h3>
            </div>
            
            <div class="form-group">
                <label for="goals">Goals</label>
                <input type="number" id="goals" name="goals" class="form-control" min="0" value="<?php echo $player['goals']; ?>">
            </div>
            
            <div class="form-group">
                <label for="assists">Assists</label>
                <input type="number" id="assists" name="assists" class="form-control" min="0" value="<?php echo $player['assists']; ?>">
            </div>
            
            <div class="form-group">
                <label for="appearances">Appearances</label>
                <input type="number" id="appearances" name="appearances" class="form-control" min="0" value="<?php echo $player['appearances']; ?>">
            </div>
            
            <div class="form-group">
                <label for="yellow_cards">Yellow Cards</label>
                <input type="number" id="yellow_cards" name="yellow_cards" class="form-control" min="0" value="<?php echo $player['yellow_cards']; ?>">
            </div>
            
            <div class="form-group">
                <label for="red_cards">Red Cards</label>
                <input type="number" id="red_cards" name="red_cards" class="form-control" min="0" value="<?php echo $player['red_cards']; ?>">
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" <?php echo $player['is_active'] ? 'checked' : ''; ?>>
                    Active Player
                </label>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Update Player
            </button>
            <a href="players.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.alert-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.admin-form {
    background: var(--admin-card);
    padding: 30px;
    border-radius: 15px;
    box-shadow: var(--admin-shadow);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    margin-bottom: 5px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    font-size: 0.9rem;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid var(--admin-border);
    border-radius: 10px;
    font-size: 0.95rem;
    font-family: inherit;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--admin-secondary);
    box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.2);
}

.form-text {
    display: block;
    margin-top: 5px;
    font-size: 0.8rem;
    color: var(--admin-muted);
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid var(--admin-border);
    display: flex;
    gap: 15px;
}

.btn-secondary {
    display: inline-block;
    padding: 10px 20px;
    background: var(--admin-bg);
    color: var(--admin-text);
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid var(--admin-border);
}

.btn-secondary:hover {
    background: var(--admin-border);
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
}
</style>

<?php require_once 'includes/admin-footer.php'; ?>