<?php
// Include database connection
require_once 'connect.php';

// Process search parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_field = isset($_GET['field']) ? $_GET['field'] : 'organization_name';

// Build the base query
$query = "SELECT * FROM nqi_stakeholders WHERE approval_status = 'approved'";

// Add search conditions
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $like_search = "%$search%";
    
    switch($search_field) {
        case 'organization_name':
            $query .= " AND organization_name LIKE '$like_search'";
            break;
        case 'organization_type':
            $query .= " AND organization_type LIKE '$like_search'";
            break;
        case 'services':
            $query .= " AND services LIKE '$like_search'";
            break;
        case 'accreditation':
            $query .= " AND accreditation LIKE '$like_search'";
            break;
    }
}

// Execute query
$result = $conn->query($query);
$total_results = $result ? $result->num_rows : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NQI Search Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #1abc9c;
            --light: #ecf0f1;
            --dark: #34495e;
            --success: #27ae60;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            color: #333;
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        header {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            pointer-events: none;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        h1 {
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .search-info {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 800px;
        }
        
        .stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .result-count {
            background: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .result-count i {
            color: var(--accent);
        }
        
        .new-search {
            background: var(--accent);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .new-search:hover {
            background: #16a085;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 188, 156, 0.4);
        }
        
        .stakeholders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stakeholder-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s forwards;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stakeholder-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: linear-gradient(to right, var(--primary), var(--dark));
            color: white;
            padding: 20px;
        }
        
        .org-name {
            font-size: 1.4rem;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .org-type {
            font-size: 0.9rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .card-body {
            padding: 20px;
            flex-grow: 1;
        }
        
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        .contact-item i {
            color: var(--secondary);
            min-width: 20px;
            padding-top: 3px;
        }
        
        .services-section {
            margin-top: 20px;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.1rem;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
            color: var(--primary);
        }
        
        .services-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .service-badge {
            background: #e1f0f7;
            color: var(--secondary);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .accreditation-badge {
            background: #e8f6f3;
            color: var(--accent);
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }
        
        .card-footer {
            background: #f9f9f9;
            padding: 15px 20px;
            border-top: 1px solid #eee;
            font-size: 0.85rem;
            color: #7f8c8d;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .no-results i {
            font-size: 3rem;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        
        .no-results h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .no-results p {
            color: #7f8c8d;
            max-width: 500px;
            margin: 0 auto;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-size: 0.9rem;
            border-top: 1px solid #eee;
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .stakeholders-grid {
                grid-template-columns: 1fr;
            }
            
            .header-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-top">
                <h1><i class="fas fa-landmark"></i> Search Results</h1>
                <a href="search.html" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Search
                </a>
            </div>
            <div class="search-info">
                Showing results for "<?php echo htmlspecialchars($search); ?>" in <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $search_field))); ?>
            </div>
        </header>
        
        <div class="stats">
            <div class="result-count">
                <i class="fas fa-database"></i>
                Found <?php echo $total_results; ?> approved stakeholder<?php echo $total_results != 1 ? 's' : ''; ?>
            </div>
            <a href="search.html" class="new-search">
                <i class="fas fa-search"></i> New Search
            </a>
        </div>
        
        <div class="stakeholders-grid">
            <?php
            if ($total_results > 0) {
                while($row = $result->fetch_assoc()) {
                    $services = !empty($row['services']) ? explode(',', $row['services']) : [];
                    ?>
                    <div class="stakeholder-card" style="animation-delay: <?php echo rand(0, 300); ?>ms">
                        <div class="card-header">
                            <h2 class="org-name"><i class="fas fa-building"></i> <?php echo htmlspecialchars($row['organization_name']); ?></h2>
                            <div class="org-type">
                                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($row['organization_type']); ?>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="contact-info">
                                <div class="contact-item">
                                    <i class="fas fa-user"></i>
                                    <div>
                                        <strong><?php echo htmlspecialchars($row['contact_person']); ?></strong><br>
                                        <?php echo htmlspecialchars($row['designation']); ?>
                                    </div>
                                </div>
                                
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <div><?php echo htmlspecialchars($row['email']); ?></div>
                                </div>
                                
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <div><?php echo htmlspecialchars($row['phone']); ?></div>
                                </div>
                                
                                <?php if (!empty($row['website'])): ?>
                                <div class="contact-item">
                                    <i class="fas fa-globe"></i>
                                    <div>
                                        <a href="<?php echo htmlspecialchars($row['website']); ?>" target="_blank">
                                            <?php echo htmlspecialchars($row['website']); ?>
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="services-section">
                                <h3 class="section-title"><i class="fas fa-cogs"></i> Services Offered</h3>
                                <div class="services-list">
                                    <?php foreach ($services as $service): ?>
                                        <?php $service = trim($service); ?>
                                        <?php if (!empty($service)): ?>
                                            <div class="service-badge">
                                                <?php echo htmlspecialchars($service); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    
                                    <?php if (!empty($row['services_other'])): ?>
                                        <div class="service-badge">
                                            <?php echo htmlspecialchars($row['services_other']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if (!empty($row['accreditation']) && $row['accreditation'] != '🔴 No'): ?>
                                <div class="accreditation-badge">
                                    <i class="fas fa-award"></i>
                                    <strong>Accreditation:</strong> <?php echo htmlspecialchars($row['accreditation']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-footer">
                            <div>
                                <i class="fas fa-calendar-check"></i> 
                                <?php 
                                $date = new DateTime($row['submitted_at']);
                                echo $date->format('M d, Y'); 
                                ?>
                            </div>
                            <div>
                                Approved <i class="fas fa-check-circle" style="color: var(--success);"></i>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="no-results">
                    <i class="fas fa-inbox"></i>
                    <h3>No Approved Stakeholders Found</h3>
                    <p>There are currently no approved stakeholders matching your search criteria. Try different search terms or check back later.</p>
                    <a href="search.html" class="new-search" style="margin-top: 20px; display: inline-flex;">
                        <i class="fas fa-search"></i> Try New Search
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
        
        <footer>
            <p>National Quality Infrastructure (NQI) Stakeholders Directory &copy; <?php echo date('Y'); ?></p>
            <p>Displaying only approved stakeholders as of <?php echo date('M d, Y'); ?></p>
        </footer>
    </div>
    
    <script>
        // Simple animation for cards on page load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stakeholder-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>
<?php
// Close connection
$conn->close();
?>