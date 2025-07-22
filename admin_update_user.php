<?php
session_start();
include 'connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

$userId = $_POST['id'];
$firstName = htmlspecialchars(trim($_POST['first_name']));
$lastName = htmlspecialchars(trim($_POST['last_name']));
$email = htmlspecialchars(trim($_POST['email']));
$organization = htmlspecialchars(trim($_POST['organization']));
$userType = $_POST['user_type'];
$isAdmin = isset($_POST['is_admin']) ? 1 : 0;

// Basic validation
if (empty($firstName) || empty($lastName) || empty($email) || empty($userType)) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
    exit();
}

// Check if email already exists for another user
$sql = "SELECT id FROM users WHERE email = ? AND id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $email, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already exists']);
    exit();
}

// Update user
$sql = "UPDATE users SET 
        first_name = ?, 
        last_name = ?, 
        email = ?, 
        organization = ?, 
        user_type = ?, 
        is_admin = ? 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssii", $firstName, $lastName, $email, $organization, $userType, $isAdmin, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$stmt->close();
$conn->close();
?>