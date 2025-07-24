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
// For this example, we'll just check if it exists
if (empty($token)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied. Invalid or missing token.']);
    exit;
}

// Fetch all stakeholders
$sql = "SELECT * FROM nqi_stakeholders";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $stakeholders = [];
    while ($row = $result->fetch_assoc()) {
        $stakeholders[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $stakeholders]);
} else {
    echo json_encode(['status' => 'success', 'data' => []]);
}

$conn->close();
?>