<?php
// Save this as admin_reset.php in C:\xampp\htdocs\nqi_srilanka\
// Then open http://localhost/nqi_srilanka/admin_reset.php in your browser

// First, connect to database
include 'connect.php';

$email = 'admin@nqi.lk';
$password = 'test123';  // Change this to your desired password

// Generate the correct hash
$hash = password_hash($password, PASSWORD_DEFAULT);

// Update the database
$sql = "UPDATE users SET password_hash = ? WHERE email = ? AND is_admin = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $hash, $email);

echo "<h1>Admin Password Reset Tool</h1>";

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "<p style='color:green'><strong>SUCCESS!</strong> Password has been reset.</p>";
        echo "<p>Email: <strong>$email</strong></p>";
        echo "<p>New Password: <strong>$password</strong></p>";
        echo "<p><a href='admin_login.html'>Click here to login</a></p>";
    } else {
        echo "<p style='color:red'>User not found! Let's create the admin user...</p>";
        
        // Try to insert admin user if not exists
        $insert_sql = "INSERT INTO users (email, password_hash, is_admin, created_at) VALUES (?, ?, 1, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ss", $email, $hash);
        
        if ($insert_stmt->execute()) {
            echo "<p style='color:green'><strong>Admin user created!</strong></p>";
            echo "<p>Email: <strong>$email</strong></p>";
            echo "<p>Password: <strong>$password</strong></p>";
            echo "<p><a href='admin_login.html'>Click here to login</a></p>";
        } else {
            echo "<p style='color:red'>Error creating user: " . $conn->error . "</p>";
        }
        $insert_stmt->close();
    }
} else {
    echo "<p style='color:red'>Error: " . $conn->error . "</p>";
}

$stmt->close();
$conn->close();
?>