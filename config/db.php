 
<?php
$host = 'localhost';
// Determine if the server is localhost
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $user = "root";
    $password = "";
    $dbname = "hospital_db";
} else {
    $user = "bhavicreations";
    $password = "d8Az75YlgmyBnVM";
    $dbname = "hospital_db";
}

 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>




 
