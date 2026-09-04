<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../includes/config.php';
require_once '../includes/database.php';

$db = getDB();
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'live') {
    // Get live match
    $stmt = $db->prepare("SELECT * FROM matches WHERE status = 'live' ORDER BY match_date DESC LIMIT 1");
    $stmt->execute();
    $match = $stmt->fetch();
    
    if ($match) {
        echo json_encode($match);
    } else {
        // Get next upcoming match
        $stmt = $db->prepare("SELECT * FROM matches WHERE match_date >= NOW() AND status != 'cancelled' ORDER BY match_date ASC LIMIT 1");
        $stmt->execute();
        $match = $stmt->fetch();
        echo json_encode($match ?: ['status' => 'no_match']);
    }
} elseif ($action === 'upcoming') {
    $stmt = $db->prepare("SELECT * FROM matches WHERE match_date >= NOW() AND status != 'cancelled' ORDER BY match_date ASC LIMIT 5");
    $stmt->execute();
    echo json_encode($stmt->fetchAll());
} elseif ($action === 'results') {
    $stmt = $db->prepare("SELECT * FROM matches WHERE status = 'completed' ORDER BY match_date DESC LIMIT 5");
    $stmt->execute();
    echo json_encode($stmt->fetchAll());
} else {
    echo json_encode(['error' => 'Invalid action']);
}
?>