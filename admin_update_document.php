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
    $documentId = $_POST['id'] ?? '';
    if (!$documentId) {
        throw new Exception('Document ID is required');
    }
    
    // Get form data
    $title = $_POST['title'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $version = $_POST['version'] ?? '';
    $effectiveDate = $_POST['effective_date'] ?? '';
    
    // First get current document to check for existing file
    $stmt = $conn->prepare("SELECT file_path FROM documents WHERE id = :id");
    $stmt->execute([':id' => $documentId]);
    $currentDoc = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $fileName = $currentDoc['file_path'] ?? '';
    
    // Handle file upload if new file was provided
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Delete old file if it exists
        if ($fileName && file_exists($uploadDir . $fileName)) {
            unlink($uploadDir . $fileName);
        }
        
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        
        // Sanitize file name
        $fileName = preg_replace("/[^a-zA-Z0-9._-]/", "", $fileName);
        $fileName = time() . '_' . $fileName;
        
        // Check file size (limit to 10MB)
        if ($fileSize > 10485760) {
            throw new Exception('File size exceeds 10MB limit');
        }
        
        // Move uploaded file
        $destPath = $uploadDir . $fileName;
        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            throw new Exception('Failed to move uploaded file');
        }
    }
    
    // Update document in database
    $stmt = $conn->prepare("
        UPDATE documents SET
            title = :title,
            category = :category,
            description = :description,
            file_path = :file_path,
            file_name = :file_name,
            version = :version,
            effective_date = :effective_date,
            updated_at = NOW()
        WHERE id = :id
    ");
    
    $stmt->execute([
        ':id' => $documentId,
        ':title' => $title,
        ':category' => $category,
        ':description' => $description,
        ':file_path' => $fileName,
        ':file_name' => $fileName ? ($_FILES['file']['name'] ?? null) : null,
        ':version' => $version,
        ':effective_date' => $effectiveDate ? $effectiveDate : null
    ]);
    
    echo json_encode(['status' => 'success', 'message' => 'Document updated successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>