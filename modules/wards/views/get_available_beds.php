<?php
// get_available_beds.php
// This file serves as an AJAX endpoint to fetch available beds.

// Include your database connection (assuming $pdo is available)
// Adjust the path as necessary based on your file structure
include "../../../includes/header.php"; // This should include your config.php which has $pdo

header('Content-Type: application/json'); // Respond with JSON

$beds = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ward_id = $_POST['ward_id'] ?? null;
    $gender = $_POST['gender'] ?? null;

    if ($ward_id && $gender) {
        try {
            // Fetch available beds for the selected ward and gender
            $stmt = $pdo->prepare("SELECT id, bed_number FROM beds WHERE ward_id = :ward_id AND gender = :gender AND status = 'Available' ORDER BY CAST(bed_number AS UNSIGNED) ASC");
            $stmt->execute([':ward_id' => $ward_id, ':gender' => $gender]);
            $beds = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error, but don't expose sensitive info to the client
            error_log("Error fetching available beds: " . $e->getMessage());
            // You might send an empty array or an error flag in a real application
        }
    }
}

echo json_encode($beds);
exit(); // Crucial to exit after sending JSON
?>
