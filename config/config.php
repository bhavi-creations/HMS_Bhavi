
<?php
// Detect the protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";

// Detect host
$host = $_SERVER['HTTP_HOST'];

// Use folder for local dev
$folder = ($host === 'localhost') ? '/HMS_Bhavi' : '';

// Build URLs and paths
$baseurl = $protocol . $host . $folder . '/';
$basepath = $_SERVER['DOCUMENT_ROOT'] . $folder . '/';

// Database credentials
if ($host === 'localhost') {
    $db_username = "root";
    $db_password = "";
    $db_name     = "hospital_db";
} else {
    $db_username = "bhavicreations";
    $db_password = "d8Az75YlgmyBnVM";
    $db_name     = "hospital_db";
}

// PDO connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname={$db_name}", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
