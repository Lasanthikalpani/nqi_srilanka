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

try {
    $sql = "SELECT * FROM documents ORDER BY category, title";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception('Database error: ' . $conn->error);
    }
    
    $documents = [];
    while ($row = $result->fetch_assoc()) {
        // Add full file path if exists
        if ($row['file_path']) {
            $row['file_path'] = 'uploads/documents/' . $row['file_path'];
        }
        $documents[] = $row;
    }
    
    echo json_encode(['status' => 'success', 'data' => $documents]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>