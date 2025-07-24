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

if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Stakeholder ID is required']);
    exit;
}

// Prepare the update query
$sql = "UPDATE nqi_stakeholders SET 
        organization_name = ?,
        organization_type = ?,
        organization_type_other = ?,
        contact_person = ?,
        designation = ?,
        email = ?,
        phone = ?,
        website = ?,
        core_services = ?,
        services = ?,
        services_other = ?,
        accreditation = ?,
        accreditation_details = ?,
        compliance_update = ?,
        regional_branches = ?,
        regional_branch_list = ?,
        comments = ?,
        approval_status = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssssssssssi", 
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
    $data['approval_status'],
    $data['id']
);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Stakeholder updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error updating stakeholder: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>