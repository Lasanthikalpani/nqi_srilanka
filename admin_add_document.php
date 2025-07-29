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

// File upload directory
$uploadDir = '../uploads/documents/';

// Create directory if it doesn't exist
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory']);
        exit;
    }
}

try {
    // Verify all required fields
    if (empty($_POST['title']) || empty($_POST['category'])) {
        throw new Exception('Title and category are required fields');
    }

    // Get form data
    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $category = $conn->real_escape_string($_POST['category'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $version = $conn->real_escape_string($_POST['version'] ?? '');
    $effectiveDate = $conn->real_escape_string($_POST['effective_date'] ?? '');
    
    // Handle file upload
    $fileName = '';
    $originalFileName = '';
    if (isset($_FILES['file'])) {
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $originalFileName = $conn->real_escape_string($_FILES['file']['name']);
            $fileSize = $_FILES['file']['size'];
            $fileType = $_FILES['file']['type'];
            
            // Sanitize file name
            $fileName = preg_replace("/[^a-zA-Z0-9._-]/", "", $originalFileName);
            $fileName = time() . '_' . $fileName;
            
            // Check file size (limit to 10MB)
            if ($fileSize > 10485760) {
                throw new Exception('File size exceeds 10MB limit');
            }
            
            // Move uploaded file
            $destPath = $uploadDir . $fileName;
            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                throw new Exception('Failed to move uploaded file. Check directory permissions.');
            }
        } elseif ($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            throw new Exception('File upload error: ' . $_FILES['file']['error']);
        }
    }
    
    // Insert document into database
    $sql = "INSERT INTO documents (title, category, description, file_path, file_name, version, effective_date, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    $filePathValue = $fileName ? 'uploads/documents/' . $fileName : null;
    $fileNameValue = $fileName ? $originalFileName : null;
    $effectiveDateValue = !empty($effectiveDate) ? $effectiveDate : null;
    
    $stmt->bind_param("sssssss", 
        $title, 
        $category, 
        $description, 
        $filePathValue, 
        $fileNameValue, 
        $version, 
        $effectiveDateValue
    );
    
    if (!$stmt->execute()) {
        throw new Exception('Database error: ' . $stmt->error);
    }
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Document added successfully',
        'success' => true,
        'data' => [
            'id' => $stmt->insert_id,
            'title' => $title
        ]
    ]);
    
} catch (Exception $e) {
    // Delete the file if it was uploaded but database insertion failed
    if (!empty($fileName) && file_exists($uploadDir . $fileName)) {
        unlink($uploadDir . $fileName);
    }
    
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>