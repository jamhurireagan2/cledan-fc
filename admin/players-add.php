<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Check if logged in
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = 'players-add.php';
    header('Location: login.php');
    exit();
}

$db = getDB();
$pageTitle = 'Add Player';
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name'] ?? '');
    $jersey_number = intval($_POST['jersey_number'] ?? 0);
    $position = sanitize($_POST['position'] ?? '');
    $nationality = sanitize($_POST['nationality'] ?? '');
    $date_of_birth = sanitize($_POST['date_of_birth'] ?? '');
    $height_cm = intval($_POST['height_cm'] ?? 0);
    $weight_kg = intval($_POST['weight_kg'] ?? 0);
    $bio = sanitize($_POST['bio'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validate
    if (empty($full_name) || empty($position) || $jersey_number <= 0) {
        $error = 'Please fill in all required fields';
    } else {
        // Check if jersey number is unique
        $stmt = $db->prepare("SELECT id FROM players WHERE jersey_number = ?");
        $stmt->execute([$jersey_number]);
        if ($stmt->fetch()) {
            $error = 'Jersey number already assigned to another player';
        } else {
            // Handle photo upload - FIXED
            $photo = '';
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                // Make sure directory exists
                if (!is_dir(PLAYER_IMG_PATH)) {
                    mkdir(PLAYER_IMG_PATH, 0777, true);
                }
                
                $uploadResult = uploadFile($_FILES['photo'], PLAYER_IMG_PATH);
                if ($uploadResult['success']) {
                    $photo = $uploadResult['filename'];
                } else {
                    $error = implode(', ', $uploadResult['errors']);
                }
            }
            
            // If no error from upload, insert into database
            if (empty($error)) {
                $stmt = $db->prepare("INSERT INTO players (full_name, jersey_number, position, nationality, date_of_birth, height_cm, weight_kg, bio, photo, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$full_name, $jersey_number, $position, $nationality, $date_of_birth, $height_cm, $weight_kg, $bio, $photo, $is_active]);
                
                setFlash('success', 'Player added successfully!');
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
        <h2>Add New Player</h2>
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
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo $_POST['full_name'] ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="jersey_number">Jersey Number *</label>
                <input type="number" id="jersey_number" name="jersey_number" class="form-control" min="1" max="99" value="<?php echo $_POST['jersey_number'] ?? ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="position">Position *</label>
                <select id="position" name="position" class="form-control" required>
                    <option value="">Select Position</option>
                    <option value="GK" <?php echo ($_POST['position'] ?? '') === 'GK' ? 'selected' : ''; ?>>GK - Goalkeeper</option>
                    <option value="RB" <?php echo ($_POST['position'] ?? '') === 'RB' ? 'selected' : ''; ?>>RB - Right Back</option>
                    <option value="CB" <?php echo ($_POST['position'] ?? '') === 'CB' ? 'selected' : ''; ?>>CB - Center Back</option>
                    <option value="LB" <?php echo ($_POST['position'] ?? '') === 'LB' ? 'selected' : ''; ?>>LB - Left Back</option>
                    <option value="CDM" <?php echo ($_POST['position'] ?? '') === 'CDM' ? 'selected' : ''; ?>>CDM - Defensive Mid</option>
                    <option value="CM" <?php echo ($_POST['position'] ?? '') === 'CM' ? 'selected' : ''; ?>>CM - Center Mid</option>
                    <option value="CAM" <?php echo ($_POST['position'] ?? '') === 'CAM' ? 'selected' : ''; ?>>CAM - Attacking Mid</option>
                    <option value="RW" <?php echo ($_POST['position'] ?? '') === 'RW' ? 'selected' : ''; ?>>RW - Right Wing</option>
                    <option value="LW" <?php echo ($_POST['position'] ?? '') === 'LW' ? 'selected' : ''; ?>>LW - Left Wing</option>
                    <option value="CF" <?php echo ($_POST['position'] ?? '') === 'CF' ? 'selected' : ''; ?>>CF - Center Forward</option>
                    <option value="ST" <?php echo ($_POST['position'] ?? '') === 'ST' ? 'selected' : ''; ?>>ST - Striker</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="nationality">Nationality</label>
                <input type="text" id="nationality" name="nationality" class="form-control" value="<?php echo $_POST['nationality'] ?? ''; ?>" placeholder="e.g., Kenyan">
            </div>
            
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?php echo $_POST['date_of_birth'] ?? ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="height_cm">Height (cm)</label>
                <input type="number" id="height_cm" name="height_cm" class="form-control" value="<?php echo $_POST['height_cm'] ?? ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="weight_kg">Weight (kg)</label>
                <input type="number" id="weight_kg" name="weight_kg" class="form-control" value="<?php echo $_POST['weight_kg'] ?? ''; ?>">
            </div>
            
            <div class="form-group full-width">
                <label for="bio">Biography</label>
                <textarea id="bio" name="bio" class="form-control" rows="5"><?php echo $_POST['bio'] ?? ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="photo">Player Photo</label>
                <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                <small class="form-text">Max size: 5MB. Allowed: JPG, PNG, GIF</small>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" <?php echo isset($_POST['is_active']) ? 'checked' : 'checked'; ?>>
                    Active Player
                </label>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Add Player
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

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,...");
}

textarea.form-control {
    resize: vertical;
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