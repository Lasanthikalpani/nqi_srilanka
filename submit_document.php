<?php
require_once 'connect.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$documentId = $_POST['documentId'] ?? 0;
$userId = $_SESSION['user_id'];

// Check if file was uploaded
if (!isset($_FILES['documentFile']) || $_FILES['documentFile']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded or upload error']);
    exit;
}

// Create uploads directory if it doesn't exist
$uploadDir = 'uploads/submissions/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generate unique filename
$originalName = basename($_FILES['documentFile']['name']);
$extension = pathinfo($originalName, PATHINFO_EXTENSION);
$newFilename = uniqid() . '.' . $extension;
$targetPath = $uploadDir . $newFilename;

// Move uploaded file
if (move_uploaded_file($_FILES['documentFile']['tmp_name'], $targetPath)) {
    // Save to database
    $stmt = $conn->prepare("INSERT INTO document_submissions (document_id, user_id, file_path, file_name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $documentId, $userId, $targetPath, $originalName);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Document submitted successfully']);
    } else {
        unlink($targetPath); // Delete the uploaded file if DB insert fails
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
}

$conn->close();
?>