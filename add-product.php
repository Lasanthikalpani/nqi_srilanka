<?php
session_start();
require_once 'db_connection.php';

// Set header to return JSON response
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to register products']);
    exit();
}

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Get and sanitize form data
$product_name = trim($_POST['productName'] ?? '');
$brand_name = trim($_POST['brandName'] ?? '');
$manufacturer = trim($_POST['manufacturer'] ?? '');
$product_category = trim($_POST['productCategory'] ?? '');
$industry_sector = trim($_POST['industrySector'] ?? '');
$organization = trim($_POST['organization'] ?? '');
$conformity_service = trim($_POST['conformityService'] ?? '');
$certification_status = trim($_POST['certified'] ?? '');
$export_status = trim($_POST['exportStatus'] ?? '');
$province = trim($_POST['province'] ?? '');
$district = trim($_POST['district'] ?? '');
$city = trim($_POST['city'] ?? '');
$contact_person = trim($_POST['contactPerson'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$website = trim($_POST['website'] ?? '');
$description = trim($_POST['description'] ?? '');
$user_id = $_SESSION['user_id'];

// Validate required fields
if (empty($product_name)) {
    echo json_encode(['success' => false, 'message' => 'Please enter the product/service name']);
    exit();
}

if (empty($manufacturer)) {
    echo json_encode(['success' => false, 'message' => 'Please enter the manufacturer/provider name']);
    exit();
}

if (empty($product_category)) {
    echo json_encode(['success' => false, 'message' => 'Please select a product category']);
    exit();
}

if (empty($industry_sector)) {
    echo json_encode(['success' => false, 'message' => 'Please select an industry sector']);
    exit();
}

if (empty($province)) {
    echo json_encode(['success' => false, 'message' => 'Please select a province']);
    exit();
}

try {
    $sql = "INSERT INTO products (
        product_name, brand_name, manufacturer, product_category, industry_sector, 
        organization, conformity_service, certification_status, export_status, 
        province, district, city, contact_person, phone, email, website, 
        description, user_id, status, created_at
    ) VALUES (
        :product_name, :brand_name, :manufacturer, :product_category, :industry_sector,
        :organization, :conformity_service, :certification_status, :export_status,
        :province, :district, :city, :contact_person, :phone, :email, :website,
        :description, :user_id, 'pending', NOW()
    )";
    
    $stmt = $conn->prepare($sql);
    
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':brand_name', $brand_name);
    $stmt->bindParam(':manufacturer', $manufacturer);
    $stmt->bindParam(':product_category', $product_category);
    $stmt->bindParam(':industry_sector', $industry_sector);
    $stmt->bindParam(':organization', $organization);
    $stmt->bindParam(':conformity_service', $conformity_service);
    $stmt->bindParam(':certification_status', $certification_status);
    $stmt->bindParam(':export_status', $export_status);
    $stmt->bindParam(':province', $province);
    $stmt->bindParam(':district', $district);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':contact_person', $contact_person);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':website', $website);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':user_id', $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product registered successfully! Awaiting approval.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: Could not save product.']);
    }
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>