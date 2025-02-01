<?php
include "../../../config/db.php"; // Include your database connection
header("Content-Type: application/json");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data["id"])) {
        $doctor_id = $data["id"];

        try {
            // Begin a transaction
            $pdo->beginTransaction();

            // Delete doctor profile from doctors_list table
            $stmt = $pdo->prepare("DELETE FROM doctors_list WHERE doctor_id = ?");
            $stmt->execute([$doctor_id]);

            if ($stmt->rowCount() > 0) {
                // Commit the transaction
                $pdo->commit();
                echo json_encode(["status" => "success", "message" => "Doctor deleted successfully."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Doctor not found or already deleted."]);
            }
        } catch (Exception $e) {
            // Rollback in case of error
            $pdo->rollBack();
            echo json_encode(["status" => "error", "message" => "Error deleting doctor: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid doctor ID."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
