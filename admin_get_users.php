<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '0');

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// Start secure session
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true,
    'cookie_samesite' => 'Strict'
]);

// Check for token in headers or session
$token = isset($_SERVER['HTTP_AUTHORIZATION']) 
    ? str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']) 
    : $_SESSION['admin_token'] ?? null;

if (!$token || $token !== ($_SESSION['admin_token'] ?? null)) {
    header('Content-Type: application/json');
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Access denied. Invalid or missing token.'
    ]);
    exit();
}

// Database connection
require 'connect.php';

try {
    $stmt = $conn->prepare("
        SELECT 
            id, 
            first_name, 
            last_name, 
            email, 
            organization, 
            user_type, 
            is_admin 
        FROM users
        ORDER BY created_at DESC
    ");
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'id' => (int)$row['id'],
            'first_name' => htmlspecialchars($row['first_name']),
            'last_name' => htmlspecialchars($row['last_name']),
            'email' => filter_var($row['email'], FILTER_SANITIZE_EMAIL),
            'organization' => $row['organization'] ? htmlspecialchars($row['organization']) : null,
            'user_type' => htmlspecialchars($row['user_type']),
            'is_admin' => (bool)$row['is_admin']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => $users,
        'count' => count($users)
    ]);

} catch (Exception $e) {
    error_log("AdminGetUsers Error: " . $e->getMessage());
    
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database operation failed'
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>