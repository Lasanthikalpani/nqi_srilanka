<?php
// view_xml_content.php
$xmlFile = $_GET['xml'] ?? '';

if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
    
    echo "<h1>XML Document Content Viewer</h1>";
    
    // Show metadata
    echo "<h2>Metadata</h2>";
    echo "<table border='1'>";
    foreach ($xml->metadata->children() as $key => $value) {
        echo "<tr><td><strong>$key:</strong></td><td>$value</td></tr>";
    }
    echo "</table>";
    
    // Show extracted text if available
    if (isset($xml->extracted_content)) {
        echo "<h2>Extracted PDF Text Content</h2>";
        
        if (isset($xml->extracted_content->full_text)) {
            echo "<h3>Full Text:</h3>";
            echo "<textarea style='width:100%; height:300px;'>";
            echo htmlspecialchars((string)$xml->extracted_content->full_text);
            echo "</textarea>";
        }
        
        if (isset($xml->extracted_content->pages)) {
            echo "<h3>Text by Pages:</h3>";
            foreach ($xml->extracted_content->pages->page as $page) {
                $pageNum = (string)$page['number'];
                echo "<h4>Page $pageNum:</h4>";
                echo "<textarea style='width:100%; height:150px;'>";
                echo htmlspecialchars((string)$page);
                echo "</textarea>";
            }
        }
    } else {
        echo "<p>No extracted content found in this XML file.</p>";
    }
    
    // Link to download original PDF from XML
    if (isset($xml->pdf_binary_data)) {
        $base64 = (string)$xml->pdf_binary_data;
        echo "<h2>Original PDF</h2>";
        echo "<a href='data:application/pdf;base64,$base64' download='original.pdf'>Download Original PDF</a>";
    }
    
} else {
    echo "<h2>XML file not found!</h2>";
    
    // List available XML files
    $xmlDir = 'xml_storage/';
    if (is_dir($xmlDir)) {
        echo "<h3>Available XML Files:</h3>";
        $files = scandir($xmlDir);
        echo "<ul>";
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $filePath = $xmlDir . $file;
                echo "<li><a href='?xml=" . urlencode($filePath) . "'>$file</a></li>";
            }
        }
        echo "</ul>";
    }
}
?>