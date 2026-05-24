<?php
// extract_pdf_content.php
require_once 'vendor/autoload.php'; // For composer installation
// OR if manual installation:
// require_once 'pdfparser/vendor/autoload.php';

use Smalot\PdfParser\Parser;

function extractPDFText($pdfFilePath) {
    try {
        $parser = new Parser();
        $pdf = $parser->parseFile($pdfFilePath);
        
        // Extract text with formatting
        $text = $pdf->getText();
        
        // Alternative: Get pages separately
        $pages = $pdf->getPages();
        $pageTexts = [];
        
        foreach ($pages as $pageNumber => $page) {
            $pageTexts[$pageNumber + 1] = $page->getText();
        }
        
        return [
            'full_text' => $text,
            'pages' => $pageTexts,
            'metadata' => $pdf->getDetails(),
            'success' => true
        ];
        
    } catch (Exception $e) {
        return [
            'error' => $e->getMessage(),
            'success' => false
        ];
    }
}

// Test function
if (isset($_GET['test'])) {
    $pdfFile = $_GET['file'] ?? 'uploads/submissions/6988ca72803db.pdf';
    
    if (file_exists($pdfFile)) {
        $result = extractPDFText($pdfFile);
        
        echo "<h2>PDF Text Extraction Results</h2>";
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        
        // Show extracted text
        echo "<h3>Extracted Text:</h3>";
        echo "<textarea style='width:100%; height:300px;'>" . htmlspecialchars($result['full_text']) . "</textarea>";
    } else {
        echo "File not found: $pdfFile";
    }
}
?>