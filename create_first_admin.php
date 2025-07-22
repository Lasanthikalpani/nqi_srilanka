<?php
include 'connect.php';

// Check if there are any admin users already
$sql = "SELECT id FROM users WHERE is_admin = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    die("Admin user already exists");
}

// Create admin user
$firstName = "Admin";
$lastName = "User";
$email = "admin@nqi.lk";
$password = "SecurePassword123"; // Change this to a strong password
$passwordHash = password_hash($password, PASSWORD_BCRYPT);
$userType = "other";
$isAdmin = 1;

$sql = "INSERT INTO users (first_name, last_name, email, password_hash, user_type, is_admin) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $firstName, $lastName, $email, $passwordHash, $userType, $isAdmin);

if ($stmt->execute()) {
    echo "Admin user created successfully. Email: admin@nqi.lk, Password: SecurePassword123";
    echo "<br>IMPORTANT: Delete this file after creating the admin user!";
} else {
    echo "Error creating admin user: " . $conn->error;
}

$stmt->close();
$conn->close();
?>