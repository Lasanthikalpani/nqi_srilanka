<?php
header('Content-Type: application/json');
require_once 'admin_auth.php';

// Verify admin token
$adminAuth = new AdminAuth();
if (!$adminAuth->validateToken()) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied. Invalid or missing token.']);
    exit;
}

require_once 'db_connection.php';

// File upload directory
$uploadDir = '../uploads/documents/';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $documentId = $data['id'] ?? '';
    
    if (!$documentId) {
        throw new Exception('Document ID is required');
    }
    
    // First get document to delete associated file
    $stmt = $conn->prepare("SELECT file_path FROM documents WHERE id = :id");
    $stmt->execute([':id' => $documentId]);
    $document = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Delete the document record
    $stmt = $conn->prepare("DELETE FROM documents WHERE id = :id");
    $stmt->execute([':id' => $documentId]);
    
    // Delete the associated file if it exists
    if ($document && $document['file_path'] && file_exists($uploadDir . $document['file_path'])) {
        unlink($uploadDir . $document['file_path']);
    }
    
    echo json_encode(['status' => 'success', 'message' => 'Document deleted successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>