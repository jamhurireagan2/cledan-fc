<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Check if logged in
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = 'players.php';
    header('Location: login.php');
    exit();
}

$db = getDB();
$pageTitle = 'Players Management';

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $db->prepare("DELETE FROM players WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    setFlash('success', 'Player deleted successfully');
    header('Location: players.php');
    exit();
}

// Get all players
$stmt = $db->query("SELECT * FROM players ORDER BY jersey_number ASC");
$players = $stmt->fetchAll();

require_once 'includes/admin-header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h2>Players Management</h2>
            <p class="text-muted">Manage your squad</p>
        </div>
        <a href="players-add.php" class="btn-primary">
            <i class="fas fa-plus"></i> Add Player
        </a>
    </div>
    
    <?php $flash = getFlash(); ?>
    <?php if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type']; ?>">
            <?php echo $flash['message']; ?>
        </div>
    <?php endif; ?>
    
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Number</th>
                    <th>Stats</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($players) > 0): ?>
                    <?php foreach ($players as $player): ?>
                        <tr>
                            <td><?php echo $player['id']; ?></td>
                            <td>
                                <?php if ($player['photo']): ?>
                                    <img src="/uploads/players/<?php echo $player['photo']; ?>" alt="<?php echo $player['full_name']; ?>" class="table-avatar">
                                <?php else: ?>
                                    <div class="table-avatar placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo $player['full_name']; ?></strong></td>
                            <td><span class="badge position-badge"><?php echo $player['position']; ?></span></td>
                            <td><span class="number-badge"><?php echo $player['jersey_number']; ?></span></td>
                            <td>
                                <span class="stat-badge">⚽ <?php echo $player['goals']; ?></span>
                                <span class="stat-badge">🅰️ <?php echo $player['assists']; ?></span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $player['is_active'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $player['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="players-edit.php?id=<?php echo $player['id']; ?>" class="btn-action edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="players.php?delete=<?php echo $player['id']; ?>" class="btn-action delete delete-confirm">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No players found. <a href="players-add.php">Add your first player</a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
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

.page-header .text-muted {
    color: var(--admin-muted);
    font-size: 0.9rem;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.table-container {
    background: var(--admin-card);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: var(--admin-shadow);
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table thead {
    background: var(--admin-bg);
}

.admin-table th {
    padding: 15px 20px;
    text-align: left;
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--admin-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.admin-table td {
    padding: 12px 20px;
    border-bottom: 1px solid var(--admin-border);
    vertical-align: middle;
}

.admin-table tbody tr:hover {
    background: #f9fafb;
}

.table-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    background: var(--admin-bg);
}

.table-avatar.placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--admin-muted);
}

.number-badge {
    display: inline-block;
    width: 32px;
    height: 32px;
    line-height: 32px;
    text-align: center;
    background: var(--admin-primary);
    color: white;
    border-radius: 50%;
    font-weight: 700;
    font-size: 0.9rem;
}

.position-badge {
    display: inline-block;
    padding: 4px 12px;
    background: #e5e7eb;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.stat-badge {
    display: inline-block;
    padding: 2px 8px;
    background: #f3f4f6;
    border-radius: 8px;
    font-size: 0.8rem;
    margin-right: 4px;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.inactive {
    background: #fef2f2;
    color: #991b1b;
}

.btn-action {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 8px;
    color: var(--admin-muted);
    transition: all 0.3s ease;
    margin: 0 2px;
}

.btn-action:hover {
    background: var(--admin-bg);
}

.btn-action.edit:hover {
    color: #3b82f6;
}

.btn-action.delete:hover {
    color: #ef4444;
}

.btn-primary {
    display: inline-block;
    padding: 10px 20px;
    background: var(--admin-secondary);
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #b8943a;
    transform: translateY(-2px);
}

.text-center {
    text-align: center;
}

.text-muted {
    color: var(--admin-muted);
}

@media (max-width: 768px) {
    .admin-table {
        display: block;
        overflow-x: auto;
    }
    
    .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
}
</style>

<?php require_once 'includes/admin-footer.php'; ?>