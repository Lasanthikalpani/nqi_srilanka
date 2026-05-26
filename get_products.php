<?php
session_start();
require_once 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Get total count
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    
    // Get pending count
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE user_id = :user_id AND status = 'pending'");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $pending = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    
    // Get certified count
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE user_id = :user_id AND certification_status IN ('SLS Certified', 'ISO Certified', 'Internationally Certified')");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $certified = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    
    // Get export ready count
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE user_id = :user_id AND export_status = 'Currently Exporting'");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $export_ready = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    
    // Get recent products
    $stmt = $conn->prepare("SELECT * FROM products WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 5");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'total' => $total,
        'pending' => $pending,
        'certified' => $certified,
        'export_ready' => $export_ready,
        'recent' => $recent
    ]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>