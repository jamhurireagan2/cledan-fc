<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

$query = isset($_GET['q']) ? sanitize($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit();
}

$db = getDB();
$results = [];

// Search players
$stmt = $db->prepare("SELECT id, full_name as title, 'player' as type, 'fas fa-user' as icon, CONCAT('/player/', id) as url FROM players WHERE full_name LIKE ? AND is_active = 1 LIMIT 5");
$stmt->execute(['%' . $query . '%']);
$players = $stmt->fetchAll();
$results = array_merge($results, $players);

// Search news
$stmt = $db->prepare("SELECT id, title, 'news' as type, 'fas fa-newspaper' as icon, CONCAT('/news/', slug) as url FROM news WHERE title LIKE ? AND is_published = 1 LIMIT 5");
$stmt->execute(['%' . $query . '%']);
$news = $stmt->fetchAll();
$results = array_merge($results, $news);

echo json_encode($results);
?>