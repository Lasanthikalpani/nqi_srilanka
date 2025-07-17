<?php
session_start();
include 'connect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=empty_fields");
        exit();
    }

    // Retrieve user from database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['email'] = $user['email'];

            // Redirect to nqi_form.php
            header("Location: nqi_form.php");
            exit();
        }
    }
    // Invalid credentials
    header("Location: login.php?error=invalid_credentials");
    exit();
}
?>