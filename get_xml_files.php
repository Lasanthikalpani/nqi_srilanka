<?php
require_once 'connect.php';

header('Content-Type: application/json');

$documentId = $_GET['document_id'] ?? 0;

if ($documentId) {
    $stmt = $conn->prepare("
        SELECT 
            xs.*,
            d.title as document_title,
            (SELECT LENGTH(xml_file_path) FROM xml_storage WHERE id = xs.id) as estimated_size
        FROM xml_storage xs 
        LEFT JOIN documents d ON xs.document_id = d.id 
        WHERE xs.document_id = ? 
        ORDER BY xs.created_at DESC
    ");
    $stmt->bind_param("i", $documentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $xmlFiles = $result->fetch_all(MYSQLI_ASSOC);
    
    // Get actual file sizes
    foreach ($xmlFiles as &$file) {
        $filePath = $file['xml_file_path'];
        if (file_exists($filePath)) {
            $file['file_size'] = filesize($filePath);
            $file['file_exists'] = true;
        } else {
            $file['file_size'] = 0;
            $file['file_exists'] = false;
        }
    }
    
    echo json_encode([
        'status' => 'success',
        'xml_files' => $xmlFiles
    ]);
    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Document ID required'
    ]);
}

$conn->close();
?>