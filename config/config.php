<?php
// Detect host
$host = $_SERVER['HTTP_HOST'];
$folder = '';

// If running locally, include folder name
if ($host === 'localhost') {
    $folder = '/Hospital-management-system-own';
}

// Base URL (used in HTML)
$baseurl = "http://{$host}{$folder}/";

// Base path (used in PHP includes)
$basepath = $_SERVER['DOCUMENT_ROOT'] . $folder . "/";

// DB credentials (optional)
if ($host === 'localhost') {
    $db_username = "root";
    $db_password = "";
    $db_name     = "hospital_db";
} else {
    $db_username = "live_db_user";
    $db_password = "secure_password";
    $db_name     = "live_db_name";
}

// PDO connection (optional)
try {
    $pdo = new PDO("mysql:host=localhost;dbname={$db_name}", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
