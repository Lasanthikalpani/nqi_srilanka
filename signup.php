<?php
// Include the existing DB connection
include 'connect.php';

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

// Helper function for JSON responses
function respond($success, $error = null, $suggest = null) {
    $response = ['success' => $success];
    if ($error) $response['error'] = $error;
    if ($suggest) $response['suggest'] = $suggest;
    echo json_encode($response);
    exit();
}

// Sanitize & collect inputs
$firstName    = htmlspecialchars(trim($_POST['firstName'] ?? ''));
$lastName     = htmlspecialchars(trim($_POST['lastName'] ?? ''));
$email        = htmlspecialchars(trim($_POST['signupEmail'] ?? ''));
$password     = $_POST['signupPassword'] ?? '';
$confirmPwd   = $_POST['confirmPassword'] ?? '';
$organization = htmlspecialchars(trim($_POST['organization'] ?? ''));
$userType     = $_POST['userType'] ?? '';
$newsletter   = isset($_POST['newsletter']) ? 1 : 0;

// Basic validation
if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($userType)) {
    respond(false, 'missing_fields');
}

// Email format check
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'invalid_email');
}

// Password match check
if ($password !== $confirmPwd) {
    respond(false, 'password_mismatch');
}

// Password length check
if (strlen($password) < 8) {
    respond(false, 'password_too_short');
}

// -----------------------------------------------------------
// Check if email already exists
// -----------------------------------------------------------
$checkSql  = "SELECT id FROM users WHERE email = ? LIMIT 1";
$checkStmt = $conn->prepare($checkSql);

if (!$checkStmt) {
    respond(false, 'db_error');
}

$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    $checkStmt->close();
    $conn->close();
    respond(false, 'email_exists');
}
$checkStmt->close();

// -----------------------------------------------------------
// Hash the password
// -----------------------------------------------------------
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// -----------------------------------------------------------
// Insert into DB
// -----------------------------------------------------------
$sql = "INSERT INTO users 
        (first_name, last_name, email, password_hash, organization, user_type, newsletter_subscription)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    respond(false, 'prepare_stmt_failed');
}

$stmt->bind_param(
    "ssssssi",
    $firstName,
    $lastName,
    $email,
    $passwordHash,
    $organization,
    $userType,
    $newsletter
);

try {
    $stmt->execute();
    $stmt->close();
    $conn->close();
    
    // ✅ Success
    respond(true);
    
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        respond(false, 'email_exists');
    }
    respond(false, 'db_error');
}
?>