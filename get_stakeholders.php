<?php
// Include the database connection file
require_once 'connect.php';

// Start a session to potentially retrieve the user_id
session_start(); // Uncomment this line if you are using sessions for user authentication

header('Content-Type: application/json'); // Set header to return JSON

// Initialize an empty array to store stakeholders data
$stakeholders = [];

// IMPORTANT: Replace this with your actual user ID retrieval logic
// For demonstration, we'll use a hardcoded user ID (e.g., 7)
// If you have a login system, you would get the user ID from the session:
$user_id = $_SESSION['user_id'] ?? null; // Get user ID from session

if ($user_id !== null) {
    // Prepare the SQL query to select all columns from nqi_stakeholders for the specific user
    // We use a prepared statement to prevent SQL injection
    $sql = "SELECT * FROM nqi_stakeholders WHERE user_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind the user_id parameter to the prepared statement
        $stmt->bind_param("i", $user_id); // "i" indicates integer type

        // Execute the prepared statement
        $stmt->execute();

        // Get the result set from the executed statement
        $result = $stmt->get_result();

        // Check if any rows were returned
        if ($result->num_rows > 0) {
            // Fetch all rows as associative arrays
            while ($row = $result->fetch_assoc()) {
                $stakeholders[] = $row;
            }
        }
        // Close the statement
        $stmt->close();
    } else {
        // Handle error if statement preparation fails
        echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
        exit();
    }
} else {
    // Handle case where user_id is not available (e.g., user not logged in)
    echo json_encode(['error' => 'User not authenticated or user ID not found.']);
    exit();
}

// Close the database connection
$conn->close();

// Encode the stakeholders array to JSON and output it
echo json_encode($stakeholders);
?>