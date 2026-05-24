<?php
// view_xml.php - Enhanced XML Viewer
$xmlFile = $_GET['file'] ?? '';
$action = $_GET['action'] ?? 'view'; // view, raw, download

if (empty($xmlFile)) {
    // Dashboard view - show all XML files
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>XML Storage Dashboard</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
            .stats { background: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .file-list { margin-top: 20px; }
            .file-item { border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 5px; background: #f9f9f9; }
            .file-item:hover { background: #f0f0f0; }
            .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 12px; margin-right: 5px; }
            .badge-download { background: #4CAF50; color: white; }
            .badge-upload { background: #2196F3; color: white; }
            .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin-right: 5px; }
            .btn-view { background: #2196F3; color: white; }
            .btn-download { background: #4CAF50; color: white; }
            .btn-delete { background: #f44336; color: white; }
            .search-box { margin: 20px 0; padding: 10px; width: 300px; }
        </style>
    </head>
    <body>
    <div class='container'>
        <h1>📁 XML Storage Dashboard</h1>";
    
    $xmlDir = 'xml_storage/';
    if (is_dir($xmlDir)) {
        $files = scandir($xmlDir);
        $xmlFiles = array_filter($files, function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'xml';
        });
        
        $totalSize = 0;
        $fileDetails = [];
        
        foreach ($xmlFiles as $file) {
            $filePath = $xmlDir . $file;
            $size = filesize($filePath);
            $totalSize += $size;
            
            // Parse file name to get info
            preg_match('/doc_(\d+)_(\w+)_/', $file, $matches);
            $docId = $matches[1] ?? '?';
            $actionType = $matches[2] ?? 'unknown';
            
            $fileDetails[] = [
                'name' => $file,
                'path' => $filePath,
                'size' => $size,
                'doc_id' => $docId,
                'action' => $actionType,
                'modified' => filemtime($filePath)
            ];
        }
        
        // Sort by modified date (newest first)
        usort($fileDetails, function($a, $b) {
            return $b['modified'] - $a['modified'];
        });
        
        echo "<div class='stats'>
                <strong>📊 Statistics:</strong>
                <br>Total Files: " . count($fileDetails) . "
                <br>Total Size: " . round($totalSize / 1024 / 1024, 2) . " MB
              </div>";
        
        echo "<div class='file-list'>";
        foreach ($fileDetails as $file) {
            $sizeKB = round($file['size'] / 1024, 2);
            $date = date('Y-m-d H:i:s', $file['modified']);
            
            echo "<div class='file-item'>";
            echo "<strong>{$file['name']}</strong>";
            echo "<span class='badge badge-{$file['action']}'>{$file['action']}</span>";
            echo "<br><small>Document ID: {$file['doc_id']} | Size: {$sizeKB} KB | Modified: {$date}</small>";
            echo "<br>";
            echo "<a href='?file=" . urlencode($file['path']) . "' class='btn btn-view'>👁️ View</a>";
            echo "<a href='{$file['path']}' download class='btn btn-download'>⬇️ Download XML</a>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No XML storage directory found.</p>";
    }
    
    echo "</div></body></html>";
    exit;
}

// Handle specific actions
if ($action === 'download' && file_exists($xmlFile)) {
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename="' . basename($xmlFile) . '"');
    readfile($xmlFile);
    exit;
}

// Normal view mode
if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
    
    // Enhanced viewer with tabs and better display
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>XML Viewer - " . basename($xmlFile) . "</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
            .header { background: white; padding: 20px; border-radius: 10px 10px 0 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
            .tabs { display: flex; background: #e0e0e0; border-radius: 5px 5px 0 0; overflow: hidden; }
            .tab { padding: 12px 20px; cursor: pointer; border: none; background: none; font-size: 16px; flex: 1; text-align: center; }
            .tab:hover { background: #d0d0d0; }
            .tab.active { background: white; font-weight: bold; }
            .content { background: white; padding: 20px; border-radius: 0 0 10px 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
            .metadata-table { width: 100%; border-collapse: collapse; }
            .metadata-table th { background: #4CAF50; color: white; text-align: left; padding: 10px; }
            .metadata-table td { padding: 10px; border: 1px solid #ddd; }
            .extracted-text { background: #f9f9f9; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: auto; white-space: pre-wrap; font-family: 'Courier New', monospace; }
            .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }
            .btn-primary { background: #2196F3; color: white; }
            .btn-success { background: #4CAF50; color: white; }
            .btn-warning { background: #FF9800; color: white; }
            .highlight { background: yellow; padding: 2px; }
            .search-box { margin: 10px 0; padding: 8px; width: 300px; }
        </style>
    </head>
    <body>
    
    <div class='header'>
        <h1>📄 XML Document Viewer</h1>
        <p><strong>File:</strong> " . basename($xmlFile) . " (" . round(filesize($xmlFile) / 1024, 2) . " KB)</p>
        
        <div style='margin: 15px 0;'>
            <a href='?file=" . urlencode($xmlFile) . "&action=download' class='btn btn-success'>⬇️ Download XML</a>
            <a href='view_xml.php' class='btn btn-primary'>📁 Back to Dashboard</a>
            <button onclick='printXML()' class='btn btn-warning'>🖨️ Print</button>
        </div>
    </div>
    
    <div class='tabs'>
        <button class='tab active' onclick='openTab(event, \"metadata\")'>📋 Metadata</button>
        <button class='tab' onclick='openTab(event, \"content\")' id='contentTab'>📝 Extracted Content</button>
        <button class='tab' onclick='openTab(event, \"raw\")'>🔧 Raw XML</button>
        <button class='tab' onclick='openTab(event, \"json\")'>📊 JSON View</button>
    </div>
    
    <div class='content'>";
    
    // Metadata Tab
    echo "<div id='metadata' class='tab-content' style='display: block;'>
            <h2>Document Metadata</h2>
            <table class='metadata-table'>";
    foreach ($xml->metadata->children() as $key => $value) {
        $displayKey = ucwords(str_replace('_', ' ', $key));
        echo "<tr><th width='30%'>$displayKey</th><td>$value</td></tr>";
    }
    echo "</table>
          </div>";
    
    // Extracted Content Tab
    echo "<div id='content' class='tab-content' style='display: none;'>
            <h2>Extracted PDF Content</h2>
            
            <input type='text' id='searchText' class='search-box' placeholder='Search in extracted text...' onkeyup='highlightText()'>
            <button onclick='clearHighlight()' class='btn'>Clear Highlight</button>
            
            <div class='section'>";
    
    if (isset($xml->extracted_content->full_text)) {
        $fullText = htmlspecialchars((string)$xml->extracted_content->full_text);
        echo "<h3>Full Text Content:</h3>";
        echo "<div class='extracted-text' id='textContent'>$fullText</div>";
        echo "<p><small>Characters: " . strlen($fullText) . " | Words: " . str_word_count($fullText) . "</small></p>";
    } else {
        echo "<p>No extracted text content found.</p>";
    }
    
    if (isset($xml->extracted_content->pages)) {
        echo "<h3>Text by Pages:</h3>";
        foreach ($xml->extracted_content->pages->page as $page) {
            $pageNum = (string)$page['number'];
            $pageText = htmlspecialchars((string)$page);
            echo "<h4>Page $pageNum:</h4>";
            echo "<div class='extracted-text'>$pageText</div>";
        }
    }
    
    echo "</div></div>";
    
    // Raw XML Tab
    echo "<div id='raw' class='tab-content' style='display: none;'>
            <h2>Raw XML Source</h2>
            <div class='extracted-text'>" . htmlspecialchars($xml->asXML()) . "</div>
          </div>";
    
    // JSON Tab
    echo "<div id='json' class='tab-content' style='display: none;'>
            <h2>JSON Representation</h2>
            <div class='extracted-text' id='jsonContent'></div>
          </div>";
    
    echo "</div>
    
    <script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName('tab-content');
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = 'none';
        }
        tablinks = document.getElementsByClassName('tab');
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(' active', '');
        }
        document.getElementById(tabName).style.display = 'block';
        evt.currentTarget.className += ' active';
        
        // Load JSON if needed
        if (tabName === 'json') {
            var xml = " . json_encode($xml) . ";
            document.getElementById('jsonContent').textContent = JSON.stringify(xml, null, 2);
        }
    }
    
    function highlightText() {
        var searchText = document.getElementById('searchText').value.toLowerCase();
        var textElement = document.getElementById('textContent');
        if (!textElement) return;
        
        var originalText = textElement.textContent;
        if (searchText === '') {
            textElement.innerHTML = originalText;
            return;
        }
        
        var regex = new RegExp('(' + searchText + ')', 'gi');
        var highlighted = originalText.replace(regex, '<span class=\"highlight\">$1</span>');
        textElement.innerHTML = highlighted;
    }
    
    function clearHighlight() {
        document.getElementById('searchText').value = '';
        highlightText();
    }
    
    function printXML() {
        window.print();
    }
    
    // Open content tab by default if there's extracted content
    " . (isset($xml->extracted_content) ? "document.getElementById('contentTab').click();" : "") . "
    </script>
    
    </body>
    </html>";
    
} else {
    echo "<h2 style='color: red;'>XML file not found!</h2>";
    echo "<a href='view_xml.php'>Back to Dashboard</a>";
}
?>