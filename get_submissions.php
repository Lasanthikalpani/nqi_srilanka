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

// Validate document ID
$documentId = filter_input(INPUT_POST, 'document_id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$documentId) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid document ID'
    ]);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    // Get submissions with their responses
    $query = "SELECT 
                ds.id,
                ds.file_path,
                ds.file_name,
                ds.submitted_at,
                ds.status,
                d.title as document_title,
                dr.id as response_id,
                dr.response_file_path,
                dr.response_file_name,
                dr.comments as response_comments,
                dr.created_at as response_date
              FROM document_submissions ds
              JOIN documents d ON ds.document_id = d.id
              LEFT JOIN document_responses dr ON dr.submission_id = ds.id
              WHERE ds.document_id = ? AND ds.user_id = ?
              ORDER BY ds.submitted_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $documentId, $userId);
    
    if (!$stmt->execute()) {
        throw new Exception('Database query failed');
    }

    $result = $stmt->get_result();
    $submissions = [];
    
    // Group responses with their submissions
    while ($row = $result->fetch_assoc()) {
        $submissionId = $row['id'];
        
        if (!isset($submissions[$submissionId])) {
            $submissions[$submissionId] = [
                'id' => $submissionId,
                'file_path' => $row['file_path'],
                'file_name' => $row['file_name'],
                'submitted_at' => $row['submitted_at'],
                'status' => $row['status'],
                'document_title' => $row['document_title'],
                'responses' => []
            ];
        }
        
        // Add response if exists
        if ($row['response_id']) {
            $submissions[$submissionId]['responses'][] = [
                'response_file_path' => $row['response_file_path'],
                'response_file_name' => $row['response_file_name'],
                'response_comments' => $row['response_comments'],
                'response_date' => $row['response_date']
            ];
        }
    }

    // Convert to indexed array
    $submissions = array_values($submissions);
    
    echo json_encode([
        'status' => 'success',
        'submissions' => $submissions
    ]);

} catch (Exception $e) {
    error_log('Error in get_submissions.php: ' . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred while retrieving submissions'
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>