<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $matchId = intval($data['match_id'] ?? 0);
    $customerName = sanitize($data['name'] ?? '');
    $customerEmail = sanitize($data['email'] ?? '');
    $customerPhone = sanitize($data['phone'] ?? '');
    $quantity = intval($data['quantity'] ?? 1);
    $section = sanitize($data['section'] ?? '');
    
    // Validate
    if (!$matchId || empty($customerName) || empty($customerEmail) || $quantity < 1) {
        echo json_encode(['success' => false, 'error' => 'Invalid booking data']);
        exit();
    }
    
    if (!validateEmail($customerEmail)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email address']);
        exit();
    }
    
    $db = getDB();
    
    // Check ticket availability
    $stmt = $db->prepare("SELECT * FROM tickets WHERE match_id = ? AND section = ?");
    $stmt->execute([$matchId, $section]);
    $ticket = $stmt->fetch();
    
    if (!$ticket || $ticket['available_quantity'] < $quantity) {
        echo json_encode(['success' => false, 'error' => 'Not enough tickets available']);
        exit();
    }
    
    // Calculate total price
    $totalPrice = $ticket['price'] * $quantity;
    
    // Generate booking reference
    $reference = 'CLEDAN-' . strtoupper(uniqid());
    
    // Create booking
    $stmt = $db->prepare("INSERT INTO bookings (ticket_id, customer_name, customer_email, customer_phone, quantity, total_price, booking_reference) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$ticket['id'], $customerName, $customerEmail, $customerPhone, $quantity, $totalPrice, $reference]);
    
    // Update available quantity
    $stmt = $db->prepare("UPDATE tickets SET available_quantity = available_quantity - ? WHERE id = ?");
    $stmt->execute([$quantity, $ticket['id']]);
    
    echo json_encode([
        'success' => true,
        'reference' => $reference,
        'total' => $totalPrice,
        'message' => 'Booking confirmed!'
    ]);
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>