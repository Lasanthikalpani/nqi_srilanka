<?php
header('Content-Type: application/json');
require_once 'connect.php';

// Check for authorization token
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied. Invalid or missing token.']);
    exit;
}

$token = str_replace('Bearer ', '', $headers['Authorization']);

// In a real application, you would validate the token here
if (empty($token)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied. Invalid or missing token.']);
    exit;
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Stakeholder ID is required']);
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM nqi_stakeholders WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stakeholder = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $stakeholder]);
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Stakeholder not found']);
}

$stmt->close();
$conn->close();
?>