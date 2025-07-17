<?php
session_start();
header('Content-Type: application/json');

$authenticated = false;

// Check if session is valid
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    // Verify user exists in database
    include 'connect.php';
    
    $sql = "SELECT id FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $authenticated = true;
    }
    
    $stmt->close();
    $conn->close();
}

echo json_encode(['authenticated' => $authenticated]);
?>