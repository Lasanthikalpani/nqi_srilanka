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

try {
    $documentId = $_GET['id'] ?? '';
    
    if (empty($documentId)) {
        throw new Exception('Document ID is required');
    }
    
    $stmt = $conn->prepare("SELECT * FROM documents WHERE id = ?");
    $stmt->bind_param("i", $documentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $document = $result->fetch_assoc();
    
    if (!$document) {
        throw new Exception('Document not found');
    }
    
    // Add full file path if exists
    if ($document['file_path']) {
        $document['file_path'] = 'uploads/documents/' . $document['file_path'];
    }
    
    echo json_encode(['status' => 'success', 'data' => $document]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>