<?php
session_start();
include 'connect.php';
header('Content-Type: application/json');

$email = $_POST['email'] ?? '';

if (empty($email)) {
    http_response_code(400);
    echo json_encode(['error' => 'Email is required']);
    exit();
}

// Check if email exists and get user ID
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Don't reveal if email exists or not for security
    echo json_encode(['success' => true]);
    exit();
}

$user = $result->fetch_assoc();
$userId = $user['id'];

// Generate secure token
$token = bin2hex(random_bytes(32));
$expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Delete any existing tokens for this user
$deleteSql = "DELETE FROM password_reset_tokens WHERE user_id = ?";
$deleteStmt = $conn->prepare($deleteSql);
$deleteStmt->bind_param("i", $userId);
$deleteStmt->execute();

// Insert new token
$insertSql = "INSERT INTO password_reset_tokens (user_id, token, expiration) VALUES (?, ?, ?)";
$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param("iss", $userId, $token, $expiration);

if ($insertStmt->execute()) {
    // Create response array
    $response = ['success' => true];
    
    // For development environments, include the token in response
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $isLocal = strpos($host, 'localhost') !== false || 
               strpos($host, '127.0.0.1') !== false ||
               strpos($host, '::1') !== false;
    
    if ($isLocal) {
        $response['token'] = $token;
    }
    
    // Log the reset link (for debugging)
    $resetLink = "http://$host/forgot-password.html?token=$token";
    error_log("Password reset link for user $userId: $resetLink");
    
    echo json_encode($response);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to process reset request']);
}

$stmt->close();
$conn->close();
?>