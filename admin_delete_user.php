<?php
header('Content-Type: application/json');

// Enable CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Start secure session
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true,
    'cookie_samesite' => 'Strict'
]);

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied. Please login.']);
    exit();
}

include 'connect.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit();
}

$userId = isset($input['id']) ? (int)$input['id'] : 0;

// Validate user ID
if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit();
}

// Prevent deleting yourself
if ($userId == $_SESSION['admin_id']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'You cannot delete your own account']);
    exit();
}

try {
    // First check if user exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit();
    }
    
    $checkStmt->close();

    // Check if user has related records in nqi_stakeholders
    $checkStakeholdersStmt = $conn->prepare("SELECT COUNT(*) as count FROM nqi_stakeholders WHERE user_id = ?");
    $checkStakeholdersStmt->bind_param("i", $userId);
    $checkStakeholdersStmt->execute();
    $stakeholdersResult = $checkStakeholdersStmt->get_result();
    $stakeholdersCount = $stakeholdersResult->fetch_assoc()['count'];
    $checkStakeholdersStmt->close();

    if ($stakeholdersCount > 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'message' => 'Cannot delete user. This user has associated stakeholder records. Please delete or reassign those records first.'
        ]);
        exit();
    }

    // If no related records, proceed with deletion
    $deleteStmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $deleteStmt->bind_param("i", $userId);
    
    if ($deleteStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        throw new Exception("Delete operation failed");
    }
    
    $deleteStmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} finally {
    if (isset($conn)) $conn->close();
}
?>