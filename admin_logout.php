<?php
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true,
    'cookie_samesite' => 'Strict'
]);

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Check if it's an AJAX request
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}

// Redirect to login page for regular requests
header("Location: admin_login.html");
exit();
?>