<?php
// ========= ORIGINAL XAMPP CREDENTIALS (LOCAL) =========
// $host = "localhost";
// $user = "root";
// $pass = "";
// $db   = "qi_catalogue_db";

// ========= NEW INFINITYFREE CREDENTIALS =========
$host = "localhost";           // InfinityFree MySQL host
$user = "root";                    // Your InfinityFree username
$pass = "";      // ⚠️ REPLACE with your actual password
$db   = "qi_catalogue_db";    // Database name (prefix + your db name)

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully!"; // Optional: remove after testing
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
