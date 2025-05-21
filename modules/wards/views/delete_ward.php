<?php
require_once "../../../includes/header.php"; // to get $pdo and session

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: add_ward.php");
    exit();
}

$ward_id = intval($_GET['id']);

// Delete ward
$stmt = $pdo->prepare("DELETE FROM wards WHERE id = :id");
$stmt->bindParam(':id', $ward_id, PDO::PARAM_INT);
$stmt->execute();

header("Location: add_ward.php");
exit();
