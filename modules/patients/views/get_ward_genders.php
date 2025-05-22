<?php
// get_ward_genders.php
// This file serves as an AJAX endpoint to fetch distinct genders with available beds for a given ward.

// It's crucial that no whitespace or other output occurs before header() and echo json_encode()
// Temporarily enable error reporting for debugging (REMOVE IN PRODUCTION)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// *** CRITICAL CHANGE: Include ONLY your database connection file directly. ***
// *** DO NOT include header.php, as it outputs HTML. ***
// include "../../../config/config.php"; 
// include _DIR_ . '/../../../config/config.php'; 
require_once '../../../config/config.php'; // Adjust the path to your database connection file

header('Content-Type: application/json'); // Respond with JSON

$genders = []; // Initialize as an empty array
$error_message = ''; // Initialize error message

// Check if $pdo is available after including db_connection.php
// Assuming db_connection.php provides a $pdo object for PDO connection
if (!isset($pdo) || !$pdo instanceof PDO) {
    $error_message = 'Database connection ($pdo) not available or not a PDO object in get_ward_genders.php. Check db_connection.php.';
    error_log($error_message);
    echo json_encode(['error' => true, 'message' => $error_message]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ward_id = $_POST['ward_id'] ?? null;

    if ($ward_id) {
        try {
            // Fetch distinct genders that have 'Available' beds for the selected ward
            $stmt = $pdo->prepare("SELECT DISTINCT gender FROM beds WHERE ward_id = :ward_id AND status = 'Available' ORDER BY gender ASC");
            if ($stmt === false) {
                $error_message = 'Failed to prepare statement: ' . implode(' ', $pdo->errorInfo());
                error_log($error_message);
            } else {
                $stmt->execute([':ward_id' => $ward_id]);
                $rawGenders = $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch just the gender values

                // Filter out any empty or null genders if they exist
                $genders = array_filter($rawGenders, function($g) {
                    return !empty($g);
                });
            }
        } catch (PDOException $e) {
            $error_message = "PDOException: " . $e->getMessage();
            error_log($error_message);
        }
    } else {
        $error_message = 'No ward_id provided.';
    }
} else {
    $error_message = 'Invalid request method. Only POST is allowed.';
}

// If there's an error, send it in the JSON response
if (!empty($error_message)) {
    echo json_encode(['error' => true, 'message' => $error_message, 'genders' => []]);
} else {
    echo json_encode(array_values($genders)); // array_values ensures numeric keys for a clean JSON array
}
exit(); // Crucial to exit after sending JSON
?>