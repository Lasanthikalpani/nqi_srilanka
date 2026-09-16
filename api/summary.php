<?php
/**
 * Public Summary API
 * Returns ONLY counts — no contact details.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// ---- Database connection ----
// ඔබේ database settings මෙතන වෙනස් කරන්න
$db_host = 'localhost';
$db_name = 'nqi_catalogue_db';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// ---- Helper ----
function countTable($pdo, $table) {
    try {
        return (int) $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    } catch (Exception $e) {
        return null; // table doesn't exist — skip
    }
}

// ---- Build summary ----
$summary = [
    'testing_labs'         => countTable($pdo, 'testing_laboratories'),
    'medical_labs'         => countTable($pdo, 'medical_laboratories'),
    'calibration_labs'     => countTable($pdo, 'calibration_laboratories'),
    'certification_bodies' => countTable($pdo, 'certification_bodies'),
    'inspection_bodies'    => countTable($pdo, 'inspection_bodies'),
    'regulatory_bodies'    => countTable($pdo, 'regulatory_bodies'),
    'chambers'             => countTable($pdo, 'chambers'),
    'training_providers'   => countTable($pdo, 'training_providers'),
];

echo json_encode([
    'success' => true,
    'summary' => $summary,
    'generated_at' => date('c'),
]);