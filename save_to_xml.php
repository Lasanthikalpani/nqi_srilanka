<?php
require_once 'connect.php';
require_once 'extract_pdf_content.php'; // Include the extraction function
session_start();

header('Content-Type: application/json');

function saveDocumentToXML($documentId, $action, $originalFile, $xmlFilePath, $extractContent = true) {
    // Read PDF file content (binary)
    $pdfContent = file_get_contents($originalFile);
    $base64Content = base64_encode($pdfContent);
    
    // Get document details from database
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM documents WHERE id = ?");
    $stmt->bind_param("i", $documentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $docData = $result->fetch_assoc();
    $stmt->close();
    
    // Extract PDF text content if requested
    $extractedContent = null;
    if ($extractContent && file_exists($originalFile)) {
        $extractedContent = extractPDFText($originalFile);
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
    $metadata->appendChild($xml->createElement('user_id', $_SESSION['user_id'] ?? 'unknown'));
    $metadata->appendChild($xml->createElement('timestamp', date('Y-m-d H:i:s')));
    $metadata->appendChild($xml->createElement('original_filename', basename($originalFile)));
    $metadata->appendChild($xml->createElement('file_size', filesize($originalFile)));
    $root->appendChild($metadata);
    
    // Add PDF binary data (original file)
    $pdfData = $xml->createElement('pdf_binary_data');
    $pdfData->setAttribute('mime_type', 'application/pdf');
    $pdfData->setAttribute('size_bytes', filesize($originalFile));
    $pdfData->setAttribute('encoding', 'base64');
    $pdfData->appendChild($xml->createTextNode($base64Content));
    $root->appendChild($pdfData);
    
    // Add extracted text content if available
    if ($extractedContent && $extractedContent['success']) {
        $contentElement = $xml->createElement('extracted_content');
        
        // Add full text
        $fullText = $xml->createElement('full_text');
        $fullText->appendChild($xml->createCDATASection($extractedContent['full_text']));
        $contentElement->appendChild($fullText);
        
        // Add pages separately
        $pagesElement = $xml->createElement('pages');
        foreach ($extractedContent['pages'] as $pageNum => $pageText) {
            $pageElement = $xml->createElement('page');
            $pageElement->setAttribute('number', $pageNum);
            $pageElement->appendChild($xml->createCDATASection($pageText));
            $pagesElement->appendChild($pageElement);
        }
        $contentElement->appendChild($pagesElement);
        
        // Add metadata from PDF
        if (!empty($extractedContent['metadata'])) {
            $pdfMetadata = $xml->createElement('pdf_metadata');
            foreach ($extractedContent['metadata'] as $key => $value) {
                $metaElement = $xml->createElement(strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $key)), htmlspecialchars($value));
                $pdfMetadata->appendChild($metaElement);
            }
            $contentElement->appendChild($pdfMetadata);
        }
        
        $root->appendChild($contentElement);
    } else if ($extractedContent) {
        // Add error if extraction failed
        $errorElement = $xml->createElement('extraction_error', htmlspecialchars($extractedContent['error']));
        $root->appendChild($errorElement);
    }
    
    // Add database record data
    $dbRecord = $xml->createElement('database_record');
    foreach ($docData as $key => $value) {
        if ($value !== null) {
            $element = $xml->createElement($key, htmlspecialchars($value));
            $dbRecord->appendChild($element);
        }
    }
    $root->appendChild($dbRecord);
    
    // Save XML file
    return $xml->save($xmlFilePath);
}

// Handle POST requests for saving XML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentId = $_POST['document_id'] ?? 0;
    $action = $_POST['action'] ?? 'unknown';
    $originalFilePath = $_POST['original_file_path'] ?? '';
    $extractContent = $_POST['extract_content'] ?? true;
    
    if ($documentId && $originalFilePath && file_exists($originalFilePath)) {
        // Create XML directory if it doesn't exist
        $xmlDir = 'xml_storage/';
        if (!file_exists($xmlDir)) {
            mkdir($xmlDir, 0777, true);
        }
        
        // Generate unique XML filename
        $xmlFileName = 'doc_' . $documentId . '_' . $action . '_' . time() . '_' . uniqid() . '.xml';
        $xmlFilePath = $xmlDir . $xmlFileName;
        
        // Save to XML with content extraction
        if (saveDocumentToXML($documentId, $action, $originalFilePath, $xmlFilePath, $extractContent)) {
            // Store XML reference in database
            $stmt = $conn->prepare("INSERT INTO xml_storage (document_id, action_type, original_file_path, xml_file_path, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("isss", $documentId, $action, $originalFilePath, $xmlFilePath);
            $stmt->execute();
            $stmt->close();
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Document saved as XML with extracted content',
                'xml_path' => $xmlFilePath,
                'has_extracted_content' => true
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to save XML'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid parameters or file not found'
        ]);
    }
}
?>