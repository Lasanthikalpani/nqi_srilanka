<?php
require_once 'connect.php';

// Function to get all document submissions with related data
function getAllSubmissions($conn) {
    $query = "SELECT ds.*, d.title as document_title, u.first_name, u.last_name 
              FROM document_submissions ds
              JOIN documents d ON ds.document_id = d.id
              JOIN users u ON ds.user_id = u.id
              ORDER BY ds.submitted_at DESC";
    $result = $conn->query($query);
    
    if (!$result) {
        error_log("Database query failed: " . $conn->error);
        return false;
    }
    
    $submissions = [];
    while ($row = $result->fetch_assoc()) {
        // Default status if not set
        if (!isset($row['status'])) {
            $row['status'] = 'pending';
        }
        $submissions[] = $row;
    }
    
    return $submissions;
}

// Function to update submission status
function updateSubmissionStatus($conn, $submissionId, $status) {
    // First check if status column exists, if not add it
    $checkColumn = $conn->query("SHOW COLUMNS FROM document_submissions LIKE 'status'");
    if ($checkColumn->num_rows == 0) {
        if (!$conn->query("ALTER TABLE document_submissions ADD COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'")) {
            error_log("Failed to add status column: " . $conn->error);
            return false;
        }
    }
    
    $stmt = $conn->prepare("UPDATE document_submissions SET status = ? WHERE id = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("si", $status, $submissionId);
    return $stmt->execute();
}

// Handle status update if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] === 'update_status' && isset($_POST['submission_id'], $_POST['status'])) {
        $submissionId = (int)$_POST['submission_id'];
        $status = $_POST['status'];
        
        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            if (updateSubmissionStatus($conn, $submissionId, $status)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Database update failed']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid status']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request']);
    }
    exit;
}

// Handle GET request - return all submissions
header('Content-Type: application/json');
$submissions = getAllSubmissions($conn);

if ($submissions === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to retrieve submissions']);
} else {
    echo json_encode($submissions);
}
?>