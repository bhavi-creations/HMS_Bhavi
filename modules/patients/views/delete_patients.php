<?php
// Start the session
session_start();

// Include the database configuration file
include "../../../config/db.php";

// Check if the patient ID is provided
if (isset($_GET['id']) && isset($_GET['type']) && $_GET['type'] === 'patients') {
    $patientId = intval($_GET['id']); // Ensure the ID is an integer

    try {
        // Prepare the delete statement
        $deleteQuery = "DELETE FROM patients WHERE id = :id";
        $stmt = $pdo->prepare($deleteQuery);

        // Bind the ID parameter
        $stmt->bindParam(':id', $patientId, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to see_patients.php with a success message
            header("Location: ./see_patients.php?delete_success=true");
            exit;
        } else {
            // Redirect to see_patients.php with an error message
            header("Location: ./see_patients.php?delete_error=true");
            exit;
        }
    } catch (PDOException $e) {
        // Log the error and redirect with an error message
        error_log("Error deleting patient: " . $e->getMessage());
        header("Location: ./see_patients.php?delete_error=true");
        exit;
    }
} else {
    // Redirect to see_patients.php if ID or type is missing
    header("Location: ./see_patients.php?delete_error=invalid_request");
    exit;
}
