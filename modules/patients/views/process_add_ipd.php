<?php
// Start the session at the very beginning to ensure $_SESSION is available
session_start();

require_once '../../../config/config.php'; // Adjust the path to your database connection file

// Temporarily enable error reporting for debugging (REMOVE IN PRODUCTION)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- START DEBUGGING ---
// Log all received POST data to the PHP error log
error_log("--- process_add_ipd.php received POST data ---");
error_log(var_export($_POST, true));
error_log("----------------------------------------------");
// --- END DEBUGGING ---

// This function is not directly used for redirects, but kept for consistency if needed
function displayJsMessage($type, $message) {
    // For process_add_ipd.php, messages are stored in $_SESSION for redirect.
    // This echo is mostly for direct access debugging.
    echo "<div class='alert alert-" . $type . " text-center'>" . htmlspecialchars($message) . "</div>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize all necessary data from the form
    $ipd_id = filter_var($_POST['ipd_id'] ?? null, FILTER_SANITIZE_STRING); // This might be an existing ID or null for new
    $opd_casualty_id = filter_var($_POST['opd_casualty_id'] ?? null, FILTER_SANITIZE_STRING); // Assuming this is passed for new IPD
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $gender_patient = filter_var($_POST['patient_gender_original'], FILTER_SANITIZE_STRING); // Patient's original gender
    $guardian_name = filter_var($_POST['guardian_name'], FILTER_SANITIZE_STRING);
    $contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
    $whatsapp_number = filter_var($_POST['whatsapp_number'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $problem = filter_var($_POST['problem'], FILTER_SANITIZE_STRING);
    $doctor = filter_var($_POST['doctor'], FILTER_SANITIZE_STRING);
    $referred_by = filter_var($_POST['referred_by'], FILTER_SANITIZE_STRING);
    $remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
    $medical_history = filter_var($_POST['medical_history'], FILTER_SANITIZE_STRING);

    $fee = filter_var($_POST['fee'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $discount = filter_var($_POST['discount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    // final_fee is a GENERATED column, DO NOT retrieve or try to insert/update it directly
    // $final_fee = filter_var($_POST['final_fee'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 

    $ward_id = filter_var($_POST['ward_id'], FILTER_SANITIZE_NUMBER_INT);
    $bed_id = filter_var($_POST['bed_id'], FILTER_SANITIZE_NUMBER_INT);
    $assigned_bed_gender = filter_var($_POST['bed_gender'], FILTER_SANITIZE_STRING); // Gender selected for the bed
    $admission_date = filter_var($_POST['admission_date'], FILTER_SANITIZE_STRING);

    // Provide a default for 'reports' since it's NOT NULL and not in the form
    $reports = ''; // Default to empty string; adjust if you have a specific default

    $errors = [];

    // Basic validation
    if (empty($name) || empty($age) || empty($gender_patient) || empty($problem) || empty($doctor) || empty($ward_id) || empty($bed_id) || empty($assigned_bed_gender) || empty($admission_date)) {
        $errors[] = "Please fill all required fields (including Ward, Bed, and Bed Gender).";
    }
    if (!is_numeric($fee) || $fee < 0) {
        $errors[] = "Invalid fee format.";
    }
    if (!is_numeric($discount) || $discount < 0 || $discount > 100) {
        $errors[] = "Invalid discount format (must be between 0 and 100).";
    }
    // No validation for final_fee as it's generated

    // Check if PDO object is available
    if (!isset($pdo) || !$pdo instanceof PDO) {
        $errors[] = "Database connection not established. Please check config.php.";
        error_log("PDO object not available in process_add_ipd.php");
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction(); // Start transaction

            // Check if bed is available before assigning
            $checkBedStmt = $pdo->prepare("SELECT status FROM beds WHERE id = :bed_id");
            $checkBedStmt->execute([':bed_id' => $bed_id]);
            $bedStatus = $checkBedStmt->fetchColumn();

            if ($bedStatus !== 'Available') {
                $pdo->rollBack();
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Selected bed is not available. Please choose another bed.'];
                header("Location: add_ipd.php?ipd_id=" . urlencode($ipd_id)); // Redirect back to form
                exit();
            }

            $success_message = "";
            $rows_affected = 0;

            // Determine if it's an INSERT or UPDATE for patients_ipd
            if (empty($ipd_id)) { // If ipd_id is not provided, it's a new admission
                // Generate a unique ipd_id for new records (since it's VARCHAR and not auto-increment)
                $new_ipd_id = 'IPD_' . uniqid(); 
                error_log("Attempting INSERT for new IPD patient with generated ID: " . $new_ipd_id);

                $stmt_insert_patient = $pdo->prepare("INSERT INTO patients_ipd (
                    ipd_id, opd_casualty_id, name, age, gender, guardian_name, contact, whatsapp_number, address,
                    problem, doctor, referred_by, remarks, medical_history,
                    fee, discount, reports, ward_id, bed_id, assigned_bed_gender, admission_date, status
                ) VALUES (
                    :ipd_id, :opd_casualty_id, :name, :age, :gender, :guardian_name, :contact, :whatsapp_number, :address,
                    :problem, :doctor, :referred_by, :remarks, :medical_history,
                    :fee, :discount, :reports, :ward_id, :bed_id, :assigned_bed_gender, :admission_date, 'Admitted'
                )");

                $execute_params = [
                    ':ipd_id' => $new_ipd_id, // Use the newly generated ID
                    ':opd_casualty_id' => $opd_casualty_id,
                    ':name' => $name,
                    ':age' => $age,
                    ':gender' => $gender_patient,
                    ':guardian_name' => $guardian_name,
                    ':contact' => $contact,
                    ':whatsapp_number' => $whatsapp_number,
                    ':address' => $address,
                    ':problem' => $problem,
                    ':doctor' => $doctor,
                    ':referred_by' => $referred_by,
                    ':remarks' => $remarks,
                    ':medical_history' => $medical_history,
                    ':fee' => $fee,
                    ':discount' => $discount,
                    ':reports' => $reports, // Added reports
                    ':ward_id' => $ward_id,
                    ':bed_id' => $bed_id,
                    ':assigned_bed_gender' => $assigned_bed_gender,
                    ':admission_date' => $admission_date
                ];
                error_log("INSERT params: " . var_export($execute_params, true));

                $stmt_insert_patient->execute($execute_params);
                $rows_affected = $stmt_insert_patient->rowCount();
                if ($rows_affected > 0) {
                    $ipd_id = $new_ipd_id; // Set ipd_id to the new generated one for messages/redirects
                    $success_message = "Patient admitted successfully with IPD ID: " . $ipd_id;
                    error_log("Patient inserted successfully. New IPD ID: " . $ipd_id);
                } else {
                    throw new Exception("Failed to insert new patient IPD record. Rows affected: " . $rows_affected . ". SQL Error Info: " . var_export($stmt_insert_patient->errorInfo(), true));
                }

            } else { // If ipd_id is provided, update existing record (e.g., assigning a bed to an existing IPD patient)
                error_log("Attempting UPDATE for existing IPD patient with ID: " . $ipd_id);
                $stmt_update_patient = $pdo->prepare("UPDATE patients_ipd SET
                    ward_id = :ward_id,
                    bed_id = :bed_id,
                    assigned_bed_gender = :assigned_bed_gender,
                    admission_date = :admission_date,
                    fee = :fee,
                    discount = :discount,
                    -- final_fee is a generated column, DO NOT update it directly
                    status = 'Admitted'
                    WHERE ipd_id = :ipd_id");

                $execute_params = [
                    ':ipd_id' => $ipd_id,
                    ':ward_id' => $ward_id,
                    ':bed_id' => $bed_id,
                    ':assigned_bed_gender' => $assigned_bed_gender,
                    ':admission_date' => $admission_date,
                    ':fee' => $fee,
                    ':discount' => $discount
                ];
                error_log("UPDATE params: " . var_export($execute_params, true));

                $stmt_update_patient->execute($execute_params);
                $rows_affected = $stmt_update_patient->rowCount();
                if ($rows_affected > 0) {
                    $success_message = "IPD details updated successfully for IPD ID: " . $ipd_id;
                    error_log("IPD record updated successfully. Rows affected: " . $rows_affected);
                } else {
                    $success_message = "No changes made to IPD details for ID: " . $ipd_id . ". (Perhaps data was identical)";
                    error_log("No changes made to IPD details for ID: " . $ipd_id . ". Rows affected: " . $rows_affected);
                }
            }

            // Update bed status to 'Occupied'
            error_log("Attempting to update bed status for bed ID: " . $bed_id);
            $stmt_update_bed = $pdo->prepare("UPDATE beds SET status = 'Occupied' WHERE id = :bed_id");
            $stmt_update_bed->execute([':bed_id' => $bed_id]);
            if ($stmt_update_bed->rowCount() == 0) {
                throw new Exception("Failed to update bed status for bed ID: " . $bed_id . ". Rows affected: " . $stmt_update_bed->rowCount() . ". SQL Error Info: " . var_export($stmt_update_bed->errorInfo(), true));
            }
            error_log("Bed status updated successfully for bed ID: " . $bed_id);


            $pdo->commit(); // Commit transaction
            error_log("Transaction committed successfully.");

            $_SESSION['message'] = ['type' => 'success', 'text' => $success_message];
            header("Location: patients_ipd_list.php"); // Redirect to IPD list page
            exit();

        } catch (PDOException $e) {
            $pdo->rollBack(); // Rollback transaction on error
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Database Error: ' . htmlspecialchars($e->getMessage()) . ' (SQLSTATE: ' . $e->getCode() . ')'];
            error_log("PDO Error in process_add_ipd.php: " . $e->getMessage() . " - SQLSTATE: " . $e->getCode() . " - Trace: " . $e->getTraceAsString()); // Log the error with trace
            header("Location: add_ipd.php?ipd_id=" . urlencode($ipd_id) . "&error=db_error"); // Redirect back with error
            exit();
        } catch (Exception $e) {
            $pdo->rollBack(); // Rollback transaction on error
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Application Error: ' . htmlspecialchars($e->getMessage())];
            error_log("App Error in process_add_ipd.php: " . $e->getMessage() . " - Trace: " . $e->getTraceAsString()); // Log the error with trace
            header("Location: add_ipd.php?ipd_id=" . urlencode($ipd_id) . "&error=app_error"); // Redirect back with error
            exit();
        }
    } else {
        // If there are validation errors
        $_SESSION['message'] = ['type' => 'danger', 'text' => implode('<br>', $errors)];
        error_log("Validation errors in process_add_ipd.php: " . implode('; ', $errors));
        header("Location: add_ipd.php?ipd_id=" . urlencode($ipd_id) . "&error=validation_error"); // Redirect back with errors
        exit();
    }
} else {
    // Redirect if accessed directly without POST
    header("Location: patients_ipd_list.php");
    exit();
}
?>
