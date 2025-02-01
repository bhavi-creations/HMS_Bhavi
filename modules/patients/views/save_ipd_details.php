<?php
include "../../../config/db.php"; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $bed_number = $_POST['bed_number'];
    $ward_type = $_POST['ward_type'];
    $notes = $_POST['notes'];

    // Update IPD details in the database
    $stmt = $pdo->prepare("
        UPDATE patients_ipd
        SET bed_number = :bed_number, ward_type = :ward_type, notes = :notes
        WHERE id = :id
    ");
    $stmt->execute([
        'bed_number' => $bed_number,
        'ward_type' => $ward_type,
        'notes' => $notes,
        'id' => $patient_id,
    ]);

    // Redirect back to casualty or IPD list
    header("Location: causality.php?status=success");
    exit;
}
?>
