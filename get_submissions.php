<?php
require_once 'connect.php';

header('Content-Type: application/json');

$documentId = $_POST['document_id'] ?? 0;

$query = "SELECT ds.*, CONCAT(u.first_name, ' ', u.last_name) as user_name 
          FROM document_submissions ds
          JOIN users u ON ds.user_id = u.id
          WHERE ds.document_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $documentId);

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