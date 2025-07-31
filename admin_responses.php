<?php
require_once 'connect.php';

// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verify all tables exist
$requiredTables = ['documents', 'users', 'document_submissions', 'document_responses'];
foreach ($requiredTables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result === false) {
        error_log("Table check failed for $table: " . $conn->error);
        http_response_code(500);
        echo json_encode(['error' => "Database error checking for table $table"]);
        exit;
    }
    if ($result->num_rows == 0) {
        error_log("Missing table: $table");
        http_response_code(500);
        echo json_encode(['error' => "Database table $table is missing"]);
        exit;
    }
}

function getAllSubmissions($conn) {
    $query = "SELECT ds.*, d.title as document_title, u.first_name, u.last_name, 
                     dr.response_file_path, dr.response_file_name, dr.comments, dr.id as response_id
              FROM document_submissions ds
              JOIN documents d ON ds.document_id = d.id
              JOIN users u ON ds.user_id = u.id
              LEFT JOIN document_responses dr ON ds.id = dr.submission_id
              ORDER BY ds.submitted_at DESC";
    
    $result = $conn->query($query);
    
    if ($result === false) {
        error_log("Query failed: " . $conn->error);
        return false;
    }
    
    $submissions = [];
    while ($row = $result->fetch_assoc()) {
        $row['status'] = $row['status'] ?? 'pending';
        $submissions[] = $row;
    }
    
    return $submissions;
}

function handlePostRequest($conn) {
    // Validate required fields
    if (empty($_POST['submission_id'])) {
        return ['success' => false, 'error' => 'Submission ID is required'];
    }
    if (empty($_POST['status'])) {
        return ['success' => false, 'error' => 'Status is required'];
    }
    if (empty($_FILES['response_file']['name'])) {
        return ['success' => false, 'error' => 'Response file is required'];
    }

    $submissionId = (int)$_POST['submission_id'];
    $status = in_array($_POST['status'], ['approved', 'rejected']) ? $_POST['status'] : null;
    $comments = !empty($_POST['comments']) ? $conn->real_escape_string($_POST['comments']) : null;

    if (!$status) {
        return ['success' => false, 'error' => 'Invalid status value'];
    }

    // Handle file upload
    $uploadDir = 'uploads/responses/';
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return ['success' => false, 'error' => 'Failed to create upload directory'];
        }
    }

    $file = $_FILES['response_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'File upload error: ' . $file['error']];
    }

    // Validate file type (PDF only)
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($fileExt !== 'pdf') {
        return ['success' => false, 'error' => 'Only PDF files are allowed'];
    }

    // Generate unique filename
    $fileName = uniqid('resp_', true) . '.pdf';
    $targetPath = $uploadDir . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Check for existing response
        $existingResponse = $conn->query("SELECT id, response_file_path FROM document_responses WHERE submission_id = $submissionId");
        $oldFilePath = null;
        $responseId = null;
        
        if ($existingResponse && $existingResponse->num_rows > 0) {
            $row = $existingResponse->fetch_assoc();
            $oldFilePath = $row['response_file_path'];
            $responseId = $row['id'];
        }

        // Update submission status
        $updateStmt = $conn->prepare("UPDATE document_submissions SET status = ? WHERE id = ?");
        $updateStmt->bind_param('si', $status, $submissionId);
        if (!$updateStmt->execute()) {
            throw new Exception("Failed to update submission: " . $updateStmt->error);
        }

        if ($responseId) {
            // Update existing response
            $responseStmt = $conn->prepare("UPDATE document_responses 
                                          SET response_file_path = ?, 
                                              response_file_name = ?, 
                                              comments = ?,
                                              created_at = NOW()
                                          WHERE id = ?");
            $responseStmt->bind_param('sssi', $targetPath, $file['name'], $comments, $responseId);
        } else {
            // Insert new response
            $responseStmt = $conn->prepare("INSERT INTO document_responses 
                                          (submission_id, response_file_path, response_file_name, comments) 
                                          VALUES (?, ?, ?, ?)");
            $responseStmt->bind_param('isss', $submissionId, $targetPath, $file['name'], $comments);
        }

        if (!$responseStmt->execute()) {
            throw new Exception("Failed to " . ($responseId ? "update" : "insert") . " response: " . $responseStmt->error);
        }

        $conn->commit();

        // Delete old file after successful update
        if ($oldFilePath && file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }

        return ['success' => true];
    } catch (Exception $e) {
        $conn->rollback();
        // Clean up uploaded file if transaction failed
        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// Handle request based on method
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $submissions = getAllSubmissions($conn);
        
        if ($submissions === false) {
            throw new Exception("Failed to fetch submissions: " . $conn->error);
        }
        
        echo json_encode($submissions);
    } 
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $response = handlePostRequest($conn);
        echo json_encode($response);
    } 
    else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error in admin_responses.php: " . $e->getMessage());
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>