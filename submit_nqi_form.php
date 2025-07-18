<?php
session_start(); // Start the session to access $_SESSION variables
include 'connect.php'; // Your database connection

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Assuming you have a login.php
    exit();
}

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Collect and sanitize form inputs
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// Sanitize email headers to prevent injection
function sanitize_header($data) {
    return str_replace(["\r", "\n"], '', $data);
}

$organization_name        = sanitize($_POST['organization_name']);
$organization_type        = sanitize($_POST['organization_type']);
$organization_type_other  = sanitize($_POST['organization_type_other'] ?? ''); // Use null coalescing for optional fields
$contact_person           = sanitize($_POST['contact_person'] ?? '');
$designation              = sanitize($_POST['designation'] ?? '');
$email                    = sanitize($_POST['email']);
$phone                    = sanitize($_POST['phone']);
$website                  = sanitize($_POST['website'] ?? '');
$core_services            = sanitize($_POST['core_services'] ?? '');
$services                 = isset($_POST['services']) ? implode(", ", $_POST['services']) : '';
$services_other           = sanitize($_POST['services_other'] ?? '');
$accreditation            = sanitize($_POST['accreditation']);
$accreditation_details    = sanitize($_POST['accreditation_details'] ?? '');
$compliance_update        = sanitize($_POST['compliance_update']);
$regional_branches        = sanitize($_POST['regional_branches']);
$regional_branch_list     = sanitize($_POST['regional_branch_list'] ?? '');
$comments                 = sanitize($_POST['comments'] ?? '');

// Modified SQL INSERT statement to include user_id and use original column names
$sql = "INSERT INTO nqi_stakeholders (
    user_id, organization_name, organization_type, organization_type_other, contact_person, designation, email, phone, website,
    core_services, services, services_other, accreditation, accreditation_details, compliance_update,
    regional_branches, regional_branch_list, comments
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
// Bind parameters: 'i' for integer (user_id), 's' for string.
// Ensure the order of types matches the order of columns in the SQL query.
$stmt->bind_param("isssssssssssssssss",
    $user_id,
    $organization_name, $organization_type, $organization_type_other, $contact_person, $designation,
    $email, $phone, $website, $core_services, $services, $services_other,
    $accreditation, $accreditation_details, $compliance_update, $regional_branches, $regional_branch_list, $comments
);

// Handle output nicely
if ($stmt->execute()) {
    // Send email notification
    $to = "nqi@example.com"; // REPLACE WITH ACTUAL EMAIL
    $subject = "New NQI Stakeholder Submission: " . sanitize_header($organization_name);
    
    // Build email body
    $body = "NEW NQI STAKEHOLDER SUBMISSION\n";
    $body .= "================================\n\n";
    $body .= "ORGANIZATION DETAILS\n";
    $body .= "-------------------\n";
    $body .= "Name: $organization_name\n";
    $body .= "Type: $organization_type" . ($organization_type_other ? " ($organization_type_other)" : "") . "\n";
    $body .= "Contact: $contact_person\n";
    $body .= "Designation: $designation\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n";
    $body .= "Website: " . ($website ? $website : "N/A") . "\n\n";
    
    $body .= "SERVICES\n";
    $body .= "--------\n";
    $body .= "Core Services:\n$core_services\n\n";
    $body .= "Services Offered:\n" . ($services ? $services : "None selected");
    $body .= $services_other ? "\nOther: $services_other\n" : "\n";
    
    $body .= "ACCREDITATION & COMPLIANCE\n";
    $body .= "--------------------------\n";
    $body .= "Accreditation: $accreditation\n";
    $body .= "Details: " . ($accreditation_details ? $accreditation_details : "N/A") . "\n";
    $body .= "Compliance Update: $compliance_update\n\n";
    
    $body .= "REGIONAL PRESENCE\n";
    $body .= "-----------------\n";
    $body .= "Regional Branches: $regional_branches\n";
    $body .= "Branch List: " . ($regional_branch_list ? $regional_branch_list : "N/A") . "\n\n";
    
    $body .= "ADDITIONAL COMMENTS\n";
    $body .= "-------------------\n";
    $body .= ($comments ? $comments : "None provided") . "\n\n";
    
    $body .= "================================\n";
    $body .= "Automated Notification | NQI Form";

    // Secure email headers
    $headers = "From: NQI Form <noreply@example.com>\r\n";
    $headers .= "Reply-To: " . sanitize_header($contact_person) . " <" . sanitize_header($email) . ">\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";
    
    // Send notification
    mail($to, $subject, $body, $headers);
    
    // Show success message
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Thank You – NQI Stakeholder Form</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f0fff0; padding: 40px; text-align: center; }
            h2 { color: #28a745; }
            p { font-size: 18px; }
            a { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
        </style>
    </head>
    <body>
        <h2>✅ Thank you!</h2>
        <p>Your information has been successfully submitted.</p>
        <a href="nqi_form.html">🔁 Submit another response</a>
    </body>
    </html>
    <?php
} else {
    // Show error message
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error – Submission Failed</title>
        <style>
            body { font-family: Arial, sans-serif; background: #fff0f0; padding: 40px; text-align: center; }
            h2 { color: #dc3545; }
            p { font-size: 18px; }
            code { background: #f9dcdc; padding: 4px; border-radius: 4px; }
        </style>
    </head>
    <body>
        <h2>❌ Error!</h2>
        <p>There was a problem submitting your form.</p>
        <p><code><?= $stmt->error ?></code></p>
        <a href="nqi_form.html">🔁 Go back to form</a>
    </body>
    </html>
    <?php
}

$stmt->close();
$conn->close();
?>