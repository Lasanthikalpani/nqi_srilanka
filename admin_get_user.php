<?php
session_start();
include 'connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

$userId = $_GET['id'];

$sql = "SELECT id, first_name, last_name, email, organization, user_type, is_admin FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($user);
} else {
    header("HTTP/1.1 404 Not Found");
}

$stmt->close();
$conn->close();
?>