<?php
require_once '../../../config/config.php'; // Adjust the path to your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admit_to_ip'])) {
    $opd_casualty_id = filter_var($_POST['patient_id'], FILTER_SANITIZE_STRING);

    try {
        // Fetch patient details from patients_opd
        $stmt_select = $pdo->prepare("SELECT * FROM patients_opd WHERE id = :id");
        $stmt_select->execute([':id' => $opd_casualty_id]);
        $patient_data = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($patient_data) {
            // Generate new IPD ID
            $type_prefix = 'IP';
            $current_date = date('ymd');
            $stmt_last_ipd = $pdo->prepare("SELECT ipd_id FROM patients_ipd WHERE ipd_id LIKE :prefix ORDER BY ipd_id DESC LIMIT 1");
            $stmt_last_ipd->execute([':prefix' => $type_prefix . $current_date . '%']);
            $last_ipd_id = $stmt_last_ipd->fetchColumn();
            $new_number = $last_ipd_id ? str_pad((int)substr($last_ipd_id, 8) + 1, 6, '0', STR_PAD_LEFT) : '000001';
            $ipd_id = $type_prefix . $current_date . $new_number;

            // Insert initial data into patients_ipd
            $stmt_insert = $pdo->prepare("INSERT INTO patients_ipd (
                ipd_id, opd_casualty_id, name, age, gender, guardian_name, contact, whatsapp_number,
                address, problem, doctor, referred_by, remarks, medical_history, fee, discount, final_fee, reports
            ) VALUES (
                :ipd_id, :opd_casualty_id, :name, :age, :gender, :guardian_name, :contact, :whatsapp_number,
                :address, :problem, :doctor, :referred_by, :remarks, :medical_history, :fee, :discount, :final_fee, :reports
            )");

            $stmt_insert->execute([
                ':ipd_id' => $ipd_id,
                ':opd_casualty_id' => $opd_casualty_id,
                ':name' => $patient_data['name'],
                ':age' => $patient_data['age'],
                ':gender' => $patient_data['gender'],
                ':guardian_name' => $patient_data['guardian_name'],
                ':contact' => $patient_data['contact'],
                ':whatsapp_number' => $patient_data['whatsapp_number'],
                ':address' => $patient_data['address'],
                ':problem' => $patient_data['problem'],
                ':doctor' => $patient_data['doctor'],
                ':referred_by' => $patient_data['referred_by'],
                ':remarks' => $patient_data['remarks'],
                ':medical_history' => $patient_data['medical_history'],
                ':fee' => $patient_data['fee'],
                ':discount' => $patient_data['discount'],
                ':final_fee' => $patient_data['final_fee'],
                ':reports' => $patient_data['reports']
            ]);

            // Redirect to add_ipd.php with the new IPD ID
            header("Location: add_ipd.php?ipd_id=" . urlencode($ipd_id));
            exit();

        } else {
            echo "<div class='alert alert-danger text-center'>Error: Patient not found.</div>";
        }

    } catch (PDOException $e) {
        echo "<div class='alert alert-danger text-center'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
} else {
    header("Location: ipd.php"); // Redirect if accessed directly
    exit();
}
?>