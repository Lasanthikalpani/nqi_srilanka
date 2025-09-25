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
    // Get submissions with only their latest response
    $query = "SELECT 
                ds.id,
                ds.file_path,
                ds.file_name,
                ds.submitted_at,
                ds.status,
                d.title as document_title,
                latest_response.id as response_id,
                latest_response.response_file_path,
                latest_response.response_file_name,
                latest_response.comments as response_comments,
                latest_response.created_at as response_date
              FROM document_submissions ds
              JOIN documents d ON ds.document_id = d.id
              LEFT JOIN (
                  SELECT dr1.*
                  FROM document_responses dr1
                  INNER JOIN (
                      SELECT submission_id, MAX(created_at) as max_date
                      FROM document_responses
                      GROUP BY submission_id
                  ) dr2 ON dr1.submission_id = dr2.submission_id AND dr1.created_at = dr2.max_date
              ) latest_response ON latest_response.submission_id = ds.id
              WHERE ds.document_id = ? AND ds.user_id = ?
              ORDER BY ds.submitted_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $documentId, $userId);
    
    if (!$stmt->execute()) {
        throw new Exception('Database query failed');
    }

    $result = $stmt->get_result();
    $submissions = [];
    
    while ($row = $result->fetch_assoc()) {
        $submission = [
            'id' => $row['id'],
            'file_path' => $row['file_path'],
            'file_name' => $row['file_name'],
            'submitted_at' => $row['submitted_at'],
            'status' => $row['status'],
            'document_title' => $row['document_title'],
            'response' => null
        ];
        
        // Add response if exists (only latest one)
        if ($row['response_id']) {
            $submission['response'] = [
                'response_file_path' => $row['response_file_path'],
                'response_file_name' => $row['response_file_name'],
                'response_comments' => $row['response_comments'],
                'response_date' => $row['response_date']
            ];
        }
        
        $submissions[] = $submission;
    }
    
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