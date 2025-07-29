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

// Define absolute path for upload directory
$uploadDir = __DIR__ . '/uploads/documents/';

// Create directory if it doesn't exist
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error', 
            'message' => 'Failed to create upload directory',
            'directory' => $uploadDir
        ]);
        exit;
    }
}

try {
    // Verify all required fields
    if (empty($_POST['title']) || empty($_POST['category'])) {
        throw new Exception('Title and category are required fields');
    }

    // Validate and sanitize input
    $title = trim($conn->real_escape_string($_POST['title'] ?? ''));
    $category = trim($conn->real_escape_string($_POST['category'] ?? ''));
    $description = trim($conn->real_escape_string($_POST['description'] ?? ''));
    $version = trim($conn->real_escape_string($_POST['version'] ?? ''));
    $effectiveDate = trim($conn->real_escape_string($_POST['effective_date'] ?? ''));
    
    // Additional validation
    if (strlen($title) > 255) {
        throw new Exception('Title must be 255 characters or less');
    }
    
    // Handle file upload
    $fileName = '';
    $originalFileName = '';
    $filePathValue = null;
    
    if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error: ' . $_FILES['file']['error']);
        }
        
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $originalFileName = basename($_FILES['file']['name']);
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        
        // Validate file
        if ($fileSize > 10485760) { // 10MB limit
            throw new Exception('File size exceeds 10MB limit');
        }
        
        // Get file extension
        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        
        // Validate file extension
        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new Exception('Invalid file type. Allowed types: ' . implode(', ', $allowedExtensions));
        }
        
        // Sanitize file name
        $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "", pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $fileExtension;
        $destPath = $uploadDir . $fileName;
        
        // Verify the file is an actual file
        if (!is_uploaded_file($fileTmpPath)) {
            throw new Exception('Possible file upload attack');
        }
        
        // Move uploaded file
        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            error_log("Failed to move file to: " . $destPath);
            throw new Exception('Failed to save uploaded file. Please try again.');
        }
        
        // Set relative path for database
        $filePathValue = 'uploads/documents/' . $fileName;
        $fileNameValue = $originalFileName;
    }
    
    // Insert document into database
    $sql = "INSERT INTO documents (title, category, description, file_path, file_name, version, effective_date, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    // Handle empty effective date
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
    
    // Success response
    echo json_encode([
        'status' => 'success',
        'message' => 'Document added successfully',
        'data' => [
            'id' => $stmt->insert_id,
            'title' => $title,
            'file_path' => $filePathValue
        ]
    ]);
    
} catch (Exception $e) {
    // Clean up if something went wrong
    if (!empty($fileName) && file_exists($uploadDir . $fileName)) {
        unlink($uploadDir . $fileName);
    }
    
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage(),
        'debug_info' => [
            'file_error' => $_FILES['file']['error'] ?? null,
            'post_data' => $_POST
        ]
    ]);
}
?>