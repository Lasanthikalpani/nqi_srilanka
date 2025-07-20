<?php
require_once 'connect.php';
header('Content-Type: application/json'); // Add this header

// Get the stakeholder ID from query parameters
$id = $_GET['id'] ?? null;

if (!$id) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Missing stakeholder ID']);
    exit;
}

try {
    // Prepare and execute deletion
    $stmt = $conn->prepare("DELETE FROM nqi_stakeholders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No record found or already deleted']);
    }
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>