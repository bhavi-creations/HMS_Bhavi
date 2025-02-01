<?php
header('Content-Type: application/json');
include "../../../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id'])) {
        $patient_id = $input['id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM patients_opd WHERE id = :id");
            $stmt->bindParam(':id', $patient_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Patient deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete the patient']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Patient ID is required']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
