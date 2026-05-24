<?php
require_once 'connect.php';
session_start();

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$documentId = $_POST['documentId'] ?? 0;
$userId = $_SESSION['user_id'];

// Check if file was uploaded
if (!isset($_FILES['documentFile']) || $_FILES['documentFile']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded or upload error']);
    exit;
}

// Validate document ID
if ($documentId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid document ID']);
    exit;
}

// Create uploads directory if it doesn't exist
$uploadDir = 'uploads/submissions/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Create XML directory if it doesn't exist
$xmlDir = 'xml_storage/';
if (!file_exists($xmlDir)) {
    mkdir($xmlDir, 0777, true);
}

// Generate unique filename for submission
$originalName = basename($_FILES['documentFile']['name']);
$extension = pathinfo($originalName, PATHINFO_EXTENSION);
$newFilename = uniqid() . '.' . $extension;
$targetPath = $uploadDir . $newFilename;

// Function to save document as XML
function saveDocumentToXML($documentId, $action, $originalFile, $userId, $conn) {
    // Check if file exists
    if (!file_exists($originalFile)) {
        error_log("File not found for XML conversion: " . $originalFile);
        return false;
    }
    
    try {
        // Read PDF file content
        $pdfContent = file_get_contents($originalFile);
        if ($pdfContent === false) {
            error_log("Failed to read file: " . $originalFile);
            return false;
        }
        
        $base64Content = base64_encode($pdfContent);
        
        // Get document details from database
        $stmt = $conn->prepare("SELECT * FROM documents WHERE id = ?");
        $stmt->bind_param("i", $documentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $docData = $result->fetch_assoc();
        $stmt->close();
        
        if (!$docData) {
            error_log("Document not found in database: ID " . $documentId);
            return false;
        }
        
        // Create XML structure
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;
        
        $root = $xml->createElement('document_activity');
        $xml->appendChild($root);
        
        // Add metadata
        $metadata = $xml->createElement('metadata');
        $metadata->appendChild($xml->createElement('document_id', $documentId));
        $metadata->appendChild($xml->createElement('document_title', htmlspecialchars($docData['title'])));
        $metadata->appendChild($xml->createElement('document_category', htmlspecialchars($docData['category'])));
        $metadata->appendChild($xml->createElement('action', $action));
        $metadata->appendChild($xml->createElement('user_id', $userId));
        $metadata->appendChild($xml->createElement('user_action', 'upload_edited'));
        $metadata->appendChild($xml->createElement('timestamp', date('Y-m-d H:i:s')));
        $metadata->appendChild($xml->createElement('original_filename', basename($originalFile)));
        $metadata->appendChild($xml->createElement('file_size', filesize($originalFile)));
        $metadata->appendChild($xml->createElement('file_type', mime_content_type($originalFile)));
        $root->appendChild($metadata);
        
        // Add PDF data
        $pdfData = $xml->createElement('pdf_data');
        $pdfData->setAttribute('mime_type', 'application/pdf');
        $pdfData->setAttribute('size_bytes', filesize($originalFile));
        $pdfData->setAttribute('encoding', 'base64');
        
        // Use CDATA for base64 content to avoid XML parsing issues
        $cdata = $xml->createCDATASection($base64Content);
        $pdfData->appendChild($cdata);
        $root->appendChild($pdfData);
        
        // Add database record data
        $dbRecord = $xml->createElement('database_record');
        foreach ($docData as $key => $value) {
            if ($value !== null) {
                $element = $xml->createElement($key, htmlspecialchars($value));
                $dbRecord->appendChild($element);
            }
        }
        $root->appendChild($dbRecord);
        
        // Generate XML filename
        $xmlFileName = 'upload_' . $documentId . '_' . $userId . '_' . time() . '_' . uniqid() . '.xml';
        $xmlFilePath = 'xml_storage/' . $xmlFileName;
        
        // Save XML file
        if ($xml->save($xmlFilePath)) {
            // Store XML reference in database
            $stmt = $conn->prepare("INSERT INTO xml_storage (document_id, action_type, original_file_path, xml_file_path, user_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("isssi", $documentId, $action, $originalFile, $xmlFilePath, $userId);
            $stmt->execute();
            $stmt->close();
            
            return $xmlFilePath;
        }
        
        return false;
    } catch (Exception $e) {
        error_log("Error saving XML: " . $e->getMessage());
        return false;
    }
}

// Move uploaded file
if (move_uploaded_file($_FILES['documentFile']['tmp_name'], $targetPath)) {
    // Save to database
    $stmt = $conn->prepare("INSERT INTO document_submissions (document_id, user_id, file_path, file_name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $documentId, $userId, $targetPath, $originalName);
    
    if ($stmt->execute()) {
        // Save as XML (run in background to not delay response)
        register_shutdown_function(function() use ($documentId, $targetPath, $userId, $conn) {
            try {
                $xmlPath = saveDocumentToXML($documentId, 'upload', $targetPath, $userId, $conn);
                if ($xmlPath) {
                    error_log("XML saved successfully: " . $xmlPath);
                } else {
                    error_log("Failed to save XML for document: " . $documentId);
                }
            } catch (Exception $e) {
                error_log("Error in XML shutdown function: " . $e->getMessage());
            }
        });
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Document submitted successfully',
            'submission_id' => $stmt->insert_id
        ]);
    } else {
        // Delete the uploaded file if DB insert fails
        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
        echo json_encode([
            'status' => 'error', 
            'message' => 'Database error: ' . $conn->error
        ]);
    }
    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Failed to move uploaded file. Check directory permissions.'
    ]);
}

$conn->close();
?>