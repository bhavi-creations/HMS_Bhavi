<?php
include "../../../config/db.php";

// Validate the 'id' and 'type' parameters
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['type'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
    exit;
}

$delId = intval($_GET['id']); // Sanitize the 'id' parameter
$type = $_GET['type']; // Get the 'type' parameter (e.g., 'doctor' or 'nurse')

// Determine the table to update based on the type
$table = ($type === 'doctor') ? 'doctors' : (($type === 'nurse') ? 'nurses' : null);

// If the table is invalid, return error
if ($table === null) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Type']);
    exit;
}

try {
    // Update the `status` to 0 for soft deletion
    $query = "UPDATE $table SET status = 0 WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $delId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Deleted Successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to Delete']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}
?>
