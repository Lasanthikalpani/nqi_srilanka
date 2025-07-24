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

$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($data['organization_name']) || empty($data['organization_type']) || 
    empty($data['contact_person']) || empty($data['email']) || empty($data['phone'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Required fields are missing']);
    exit;
}

// Prepare the insert query
$sql = "INSERT INTO nqi_stakeholders (
        organization_name,
        organization_type,
        organization_type_other,
        contact_person,
        designation,
        email,
        phone,
        website,
        core_services,
        services,
        services_other,
        accreditation,
        accreditation_details,
        compliance_update,
        regional_branches,
        regional_branch_list,
        comments,
        approval_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssssssssss", 
    $data['organization_name'],
    $data['organization_type'],
    $data['organization_type_other'],
    $data['contact_person'],
    $data['designation'],
    $data['email'],
    $data['phone'],
    $data['website'],
    $data['core_services'],
    $data['services'],
    $data['services_other'],
    $data['accreditation'],
    $data['accreditation_details'],
    $data['compliance_update'],
    $data['regional_branches'],
    $data['regional_branch_list'],
    $data['comments'],
    $data['approval_status']
);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Stakeholder added successfully', 'id' => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error adding stakeholder: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>