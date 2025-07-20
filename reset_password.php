<?php
session_start();
include 'connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        http_response_code(400);
        echo json_encode(['error' => 'Passwords do not match']);
        exit();
    }

    if (strlen($newPassword) < 8) {
        http_response_code(400);
        echo json_encode(['error' => 'Password must be at least 8 characters']);
        exit();
    }

    // Check token validity and get user ID
    $currentTime = date('Y-m-d H:i:s');
    $sql = "SELECT user_id FROM password_reset_tokens 
            WHERE token = ? AND expiration > ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $currentTime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or expired token']);
        exit();
    }

    $row = $result->fetch_assoc();
    $userId = $row['user_id'];

    // Update password
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateSql = "UPDATE users SET password_hash = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $passwordHash, $userId);

    if ($updateStmt->execute()) {
        // Delete token after successful reset
        $deleteSql = "DELETE FROM password_reset_tokens WHERE token = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("s", $token);
        $deleteStmt->execute();
        
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Password reset failed']);
    }

    $stmt->close();
    $updateStmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>