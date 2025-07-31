<?php
require_once 'connect.php';
session_start();

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access'
    ]);
    exit;
}

$documentId = $_POST['document_id'] ?? 0;
$userId = $_SESSION['user_id'];

// Only show submissions for the current user
$query = "SELECT ds.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, 
                 d.title as document_title
          FROM document_submissions ds
          JOIN users u ON ds.user_id = u.id
          JOIN documents d ON ds.document_id = d.id
          WHERE ds.document_id = ? AND ds.user_id = ?
          ORDER BY ds.submitted_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $documentId, $userId);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $submissions = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'submissions' => $submissions
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to retrieve submissions: ' . $conn->error
    ]);
}

$stmt->close();
$conn->close();
?>