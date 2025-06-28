<?php
include 'connect.php';
$result = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");

while ($row = $result->fetch_assoc()) {
    echo "<p><strong>{$row['name']}</strong>: {$row['message']}<br><small>{$row['created_at']}</small></p><hr>";
}
$conn->close();
?>