<?php
require_once 'extract_pdf_content.php';

// Test with your job application PDF
$pdfFile = 'C:/xampp/htdocs/nqi_srilanka/uploads/submissions/6988ca72803db.pdf';

if (file_exists($pdfFile)) {
    echo "<h1>Testing PDF Text Extraction</h1>";
    echo "<p>File: $pdfFile</p>";
    
    $result = extractPDFText($pdfFile);
    
    if ($result['success']) {
        echo "<h2>✅ Success! Text extracted</h2>";
        echo "<h3>Full Text:</h3>";
        echo "<textarea style='width:100%; height:300px; border: 2px solid green;'>";
        echo htmlspecialchars($result['full_text']);
        echo "</textarea>";
        
        echo "<h3>By Pages:</h3>";
        foreach ($result['pages'] as $pageNum => $pageText) {
            echo "<h4>Page $pageNum:</h4>";
            echo "<textarea style='width:100%; height:150px;'>";
            echo htmlspecialchars($pageText);
            echo "</textarea>";
        }
        
        echo "<h3>PDF Metadata:</h3>";
        echo "<pre>";
        print_r($result['metadata']);
        echo "</pre>";
    } else {
        echo "<h2>❌ Extraction Failed</h2>";
        echo "<p>Error: " . $result['error'] . "</p>";
    }
} else {
    echo "<h2>File not found!</h2>";
    echo "<p>Looking for: $pdfFile</p>";
    
    // List files in submissions directory
    $submissionsDir = 'C:/xampp/htdocs/nqi_srilanka/uploads/submissions/';
    if (is_dir($submissionsDir)) {
        echo "<h3>Files in submissions directory:</h3>";
        $files = scandir($submissionsDir);
        echo "<ul>";
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $filePath = $submissionsDir . $file;
                $size = filesize($filePath);
                echo "<li>$file ($size bytes) - <a href='test_single.php?file=" . urlencode($file) . "'>Test</a></li>";
            }
        }
        echo "</ul>";
    }
}
?>