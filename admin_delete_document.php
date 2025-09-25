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

// Get the JSON input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required field
if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Document ID is required']);
    exit;
}

// File upload directory
$uploadDir = '../uploads/documents/';

// Start transaction for atomic operation
$conn->begin_transaction();

try {
    // First get the document to delete associated file
    $stmt = $conn->prepare("SELECT file_path FROM documents WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $document = $result->fetch_assoc();

    if (!$document) {
        throw new Exception('Document not found');
    }

    // Delete the document record
    $stmt = $conn->prepare("DELETE FROM documents WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    
    if (!$stmt->execute()) {
        throw new Exception('Error deleting document record');
    }

    // Delete the associated file if it exists
    if ($document['file_path'] && file_exists($uploadDir . basename($document['file_path']))) {
        if (!unlink($uploadDir . basename($document['file_path']))) {
            throw new Exception('Error deleting document file');
        }
    }
    
    $conn->commit();
    echo json_encode([
        'status' => 'success', 
        'message' => 'Document deleted successfully',
        'deleted_id' => $data['id'] // Return the deleted ID for frontend reference
    ]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>