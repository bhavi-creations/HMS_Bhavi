<?php
include "config/db.php";

// Validate the 'id' and 'type' parameters
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['type'])) {
    echo '<script>alert("Invalid Request")</script>';
    echo '<script>window.location.href="see_staff.php"</script>';
    exit;
}

$delId = intval($_GET['id']); // Sanitize the 'id' parameter
$type = $_GET['type']; // Get the 'type' parameter (e.g., 'doctor' or 'nurse')

// Determine the table to update based on the type
$table = ($type === 'doctor') ? 'doctors' : (($type === 'nurse') ? 'nurses' : null);

// If the table is invalid, redirect with an error
if ($table === null) {
    echo '<script>alert("Invalid Type")</script>';
    echo '<script>window.location.href="see_staff.php"</script>';
    exit;
}

try {
    // Update the `status` to 0 for soft deletion
    $query = "UPDATE $table SET status = 0 WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $delId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo '<script>alert("Deleted Successfully")</script>';
        echo '<script>window.location.href="see_staff.php"</script>';
    } else {
        echo '<script>alert("Failed to Delete")</script>';
    }
} catch (PDOException $e) {
    echo '<script>alert("Error: ' . $e->getMessage() . '")</script>';
    echo '<script>window.location.href="see_staff.php"</script>';
}
?>
