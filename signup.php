<?php
// Include the existing DB connection
include 'connect.php';

// Sanitize & collect inputs
$firstName  = htmlspecialchars(trim($_POST['firstName']));
$lastName   = htmlspecialchars(trim($_POST['lastName']));
$email      = htmlspecialchars(trim($_POST['signupEmail']));
$password   = $_POST['signupPassword'];
$confirmPwd = $_POST['confirmPassword'];
$organization = htmlspecialchars(trim($_POST['organization']));
$userType   = $_POST['userType'];
$newsletter = isset($_POST['newsletter']) ? 1 : 0;

// Basic validation
if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($userType)) {
    // Redirect back to signup with an error message
    header("Location: signup.html?error=missing_fields");
    exit();
}

if ($password !== $confirmPwd) {
    // Redirect back to signup with an error message
    header("Location: signup.html?error=password_mismatch");
    exit();
}

if (strlen($password) < 8) {
    // Redirect back to signup with an error message
    header("Location: signup.html?error=password_too_short");
    exit();
}

// Hash the password
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Save to DB
$sql = "INSERT INTO users (first_name, last_name, email, password_hash, organization, user_type, newsletter_subscription)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ssssssi", $firstName, $lastName, $email, $passwordHash, $organization, $userType, $newsletter);

    try {
        $stmt->execute();
        // Success: Redirect to a success page or index.html
        header("Location: index.html?signup=success");
        exit();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            // Email already exists
            header("Location: signup.html?error=email_exists");
            exit();
        } else {
            // Other database error
            // Log the error for debugging: error_log("Database error: " . $e->getMessage());
            header("Location: signup.html?error=db_error");
            exit();
        }
    }

    $stmt->close();
} else {
    // Database error: Could not prepare statement.
    // Log the error for debugging: error_log("Database error: Could not prepare statement - " . $conn->error);
    header("Location: signup.html?error=prepare_stmt_failed");
    exit();
}

$conn->close();
?>