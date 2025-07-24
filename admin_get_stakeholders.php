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
// For this example, we'll just check if it exists
if (empty($token)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied. Invalid or missing token.']);
    exit;
}

// Get search parameters from query string
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_field = isset($_GET['field']) ? $_GET['field'] : 'all';

// Build base query
$sql = "SELECT * FROM nqi_stakeholders WHERE 1=1";

// Add search conditions if search term exists
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $like_search = "%$search%";
    
    if ($search_field === 'all') {
        // Search all relevant fields
        $sql .= " AND (
            organization_name LIKE '$like_search' OR
            organization_type LIKE '$like_search' OR
            contact_person LIKE '$like_search' OR
            email LIKE '$like_search' OR
            phone LIKE '$like_search' OR
            services LIKE '$like_search' OR
            accreditation LIKE '$like_search' OR
            approval_status LIKE '$like_search'
        )";
    } else {
        // Search specific field
        switch($search_field) {
            case 'organization_name':
            case 'organization_type':
            case 'contact_person':
            case 'email':
            case 'phone':
            case 'approval_status':
                $sql .= " AND $search_field LIKE '$like_search'";
                break;
            default:
                // Default to organization_name if invalid field
                $sql .= " AND organization_name LIKE '$like_search'";
                break;
        }
    }
}

// Execute query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $stakeholders = [];
    while ($row = $result->fetch_assoc()) {
        $stakeholders[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $stakeholders]);
} else {
    echo json_encode(['status' => 'success', 'data' => []]);
}

$conn->close();
?>