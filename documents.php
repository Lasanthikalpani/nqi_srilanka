<?php
require_once 'connect.php';
session_start();

header('Content-Type: application/json');

// Get filter parameters
$category = isset($_POST['category']) ? $_POST['category'] : '';
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

// Build query
$query = "SELECT * FROM documents WHERE 1=1";
$params = [];
$types = '';

if (!empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
    $types .= 's';
}

if (!empty($searchTerm)) {
    $query .= " AND (title LIKE ? OR description LIKE ?)";
    $params[] = '%' . $searchTerm . '%';
    $params[] = '%' . $searchTerm . '%';
    $types .= 'ss';
}

// Prepare and execute query
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $documents = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'documents' => $documents
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to retrieve documents: ' . $conn->error
    ]);
}

$stmt->close();
$conn->close();
?>