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

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Stakeholder ID is required']);
    exit;
}

$id = intval($data['id']);
$sql = "DELETE FROM nqi_stakeholders WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Stakeholder deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Stakeholder not found']);
    }
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error deleting stakeholder: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>