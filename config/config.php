<?php
$host = $_SERVER['HTTP_HOST'];
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";

// Detect if local
$isLocal = ($host === 'localhost');
$folder = $isLocal ? '/HMS_Bhavi' : '';

// Final base URL
$baseurl = "{$protocol}{$host}{$folder}/";
$basepath = $_SERVER['DOCUMENT_ROOT'] . $folder . "/";

// DB credentials
if ($isLocal) {
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
?>
