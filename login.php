<?php
session_start();
include 'connect.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Both email and password are required']);
    exit();
}

$sql = "SELECT id, password_hash, first_name FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['error' => 'No account found with this email']);
        exit();
    }
    
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['authenticated'] = true;
        $_SESSION['user_name'] = $user['first_name']; // Store user's name for personalization
        
        echo json_encode(['success' => true]);
    } else {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['error' => 'Incorrect password. Please try again.']);
    }
    
    $stmt->close();
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'A system error occurred. Please try again later.']);
}

$conn->close();
?>