<?php
include "../../../config/config.php"; // Ensure PDO $pdo is available

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_beds.php?error=Invalid+request");
    exit();
}

$bed_id = $_GET['id'];

// Check if the bed exists and not already deleted
$stmt = $pdo->prepare("SELECT * FROM beds WHERE id = ? AND is_deleted = 0");
$stmt->execute([$bed_id]);
$bed = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bed) {
    header("Location: view_beds.php?error=Bed+not+found");
    exit();
}

// Optional: Prevent deleting occupied beds
if ($bed['status'] === 'Occupied') {
    header("Location: view_beds.php?error=Cannot+delete+occupied+bed");
    exit();
}

// Perform soft delete
$deleteStmt = $pdo->prepare("UPDATE beds SET is_deleted = 1 WHERE id = ?");
$deleteStmt->execute([$bed_id]);

header("Location: view_beds.php?success=Bed+soft+deleted");
exit();
?>
