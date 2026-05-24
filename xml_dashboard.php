<?php
// xml_dashboard.php - Admin Dashboard
session_start();
require_once 'connect.php';

// Check if admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit;
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>XML Archive Dashboard</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .dashboard { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stat { font-size: 24px; font-weight: bold; color: #2196F3; }
        .recent-file { border-bottom: 1px solid #eee; padding: 10px 0; }
        .btn { display: inline-block; padding: 10px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
    </style>
</head>
<body>
    <h1>📊 XML Archive Dashboard</h1>
    
    <div class='dashboard'>
        <div class='card'>
            <h3>📈 Statistics</h3>";
    
// Get stats
$stats = $conn->query("
    SELECT 
        COUNT(*) as total_files,
        SUM(LENGTH(xml_file_path)) as total_size,
        COUNT(DISTINCT document_id) as unique_documents,
        COUNT(CASE WHEN action_type = 'download' THEN 1 END) as downloads,
        COUNT(CASE WHEN action_type = 'upload' THEN 1 END) as uploads
    FROM xml_storage
");

$stat = $stats->fetch_assoc();
echo "<p>Total Files: <span class='stat'>{$stat['total_files']}</span></p>";
echo "<p>Unique Documents: <span class='stat'>{$stat['unique_documents']}</span></p>";
echo "<p>Downloads: <span class='stat'>{$stat['downloads']}</span></p>";
echo "<p>Uploads: <span class='stat'>{$stat['uploads']}</span></p>";
echo "<p>Total Size: <span class='stat'>" . round($stat['total_size'] / 1024 / 1024, 2) . " MB</span></p>";

echo "</div>
      <div class='card'>
          <h3>🔍 Quick Actions</h3>
          <a href='view_xml.php' class='btn'>📁 View All XML</a>
          <a href='documents.html' class='btn'>📄 Document Catalogue</a>
          <a href='search_xml.php' class='btn'>🔎 Search Content</a>
          <a href='export_xml.php' class='btn'>📤 Export All</a>
      </div>
      
      <div class='card'>
          <h3>🕒 Recent Activity</h3>";

// Get recent files
$recent = $conn->query("
    SELECT xs.*, d.title 
    FROM xml_storage xs 
    LEFT JOIN documents d ON xs.document_id = d.id 
    ORDER BY xs.created_at DESC 
    LIMIT 10
");

while ($row = $recent->fetch_assoc()) {
    $time = date('H:i', strtotime($row['created_at']));
    echo "<div class='recent-file'>
            <strong>{$row['title']}</strong> ({$row['document_id']})
            <br><small>{$row['action_type']} at {$time} | " . round(filesize($row['xml_file_path'])/1024, 2) . " KB</small>
          </div>";
}

echo "</div></div>
    <div style='margin-top: 20px;'>
        <h3>📊 Usage Chart (Last 7 Days)</h3>
        <iframe src='chart_xml.php' width='100%' height='300' style='border: none;'></iframe>
    </div>
</body>
</html>";