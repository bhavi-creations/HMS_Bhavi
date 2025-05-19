<?php
require_once '../../../config/config.php'; // Adjust the path to your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ipd_id = filter_var($_POST['ipd_id'], FILTER_SANITIZE_STRING);
    $ward_number = filter_var($_POST['ward_number'], FILTER_SANITIZE_STRING);
    $bed_number = filter_var($_POST['bed_number'], FILTER_SANITIZE_STRING);
    $admission_date = filter_var($_POST['admission_date'], FILTER_SANITIZE_STRING);

    // Sanitize fee and discount using FILTER_SANITIZE_NUMBER_FLOAT
    $fee = filter_var($_POST['fee'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $discount = filter_var($_POST['discount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Validate if they are indeed numbers after sanitization
    if (!is_numeric($fee)) {
        $error = "Invalid fee format.";
    }
    if (!is_numeric($discount)) {
        $error = "Invalid discount format.";
    }

    if (!isset($error)) {
        try {
            $stmt_update = $pdo->prepare("UPDATE patients_ipd SET
                ward_number = :ward_number,
                bed_number = :bed_number,
                admission_date = :admission_date,
                fee = :fee,
                discount = :discount
                WHERE ipd_id = :ipd_id");

            $stmt_update->execute([
                ':ipd_id' => $ipd_id,
                ':ward_number' => $ward_number,
                ':bed_number' => $bed_number,
                ':admission_date' => $admission_date,
                ':fee' => $fee,
                ':discount' => $discount
            ]);

            if ($stmt_update->rowCount() > 0) {
                header("Location: ipd.php?admission_success=true"); // Redirect to IP list with success message
                exit();
            } else {
                echo "<div class='alert alert-danger text-center'>Error: Could not update IPD details.</div>";
            }

        } catch (PDOException $e) {
            echo "<div class='alert alert-danger text-center'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>" . $error . "</div>";
    }
} else {
    header("Location: ipd.php"); // Redirect if accessed directly
    exit();
}
?>