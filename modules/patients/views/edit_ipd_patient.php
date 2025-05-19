<?php
include "../../../includes/header.php";

// Check if IPD ID is provided in the URL
if (isset($_GET['ipd_id']) && !empty($_GET['ipd_id'])) {
    $ipd_id = filter_var($_GET['ipd_id'], FILTER_SANITIZE_STRING);

    // Fetch IPD patient data based on the ID
    try {
        $stmt = $pdo->prepare("SELECT * FROM patients_ipd WHERE ipd_id = :ipd_id");
        $stmt->execute([':ipd_id' => $ipd_id]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$patient) {
            // If no patient found with the given ID, redirect or display an error
            header("Location: patients_ipd_list.php"); // Redirect to the list page
            exit();
        }
    } catch (PDOException $e) {
        // Handle database errors
        die("Error fetching patient data: " . $e->getMessage());
    }
} else {
    // If no IPD ID is provided, redirect or display an error
    header("Location: patients_ipd_list.php"); // Redirect to the list page
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input fields
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    $guardian_name = filter_var($_POST['guardian_name'], FILTER_SANITIZE_STRING);
    $contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
    $whatsapp_number = filter_var($_POST['whatsapp_number'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $problem = filter_var($_POST['problem'], FILTER_SANITIZE_STRING);
    $doctor = filter_var($_POST['doctor'], FILTER_SANITIZE_STRING);
    $referred_by = filter_var($_POST['referred_by'], FILTER_SANITIZE_STRING);
    $remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
    $admission_date_date = $_POST['admission_date'];
    $admission_date_time = $_POST['admission_time'];
    $admission_date = date('Y-m-d H:i:s', strtotime("$admission_date_date $admission_date_time"));
    $ward_number = filter_var($_POST['ward_number'], FILTER_SANITIZE_STRING);
    $bed_number = filter_var($_POST['bed_number'], FILTER_SANITIZE_STRING);
    $fee = filter_var($_POST['fee'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $discount = filter_var($_POST['discount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $final_fee = filter_var($_POST['final_fee'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $discharge_date_date = $_POST['discharge_date'];
    $discharge_date_time = $_POST['discharge_time'];
    // Handle the case where the clear button was used.
    if (empty($discharge_date_date) && empty($discharge_date_time)) {
        $discharge_date = null; // Set it to null in the database
    } else {
        $discharge_date = date('Y-m-d H:i:s', strtotime("$discharge_date_date $discharge_date_time"));
    }
    $discharge_status = filter_var($_POST['discharge_status'], FILTER_SANITIZE_STRING);

    // Handle file uploads
    $uploaded_reports = [];
    if (!empty($_FILES['reports']['name'][0])) {
        $upload_dir = "../../../uploads/reports/"; // Specify your upload directory. Make sure this directory exists and is writable
        $allowed_types = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        $max_file_size = 5 * 1024 * 1024; // 5MB

        foreach ($_FILES['reports']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['reports']['name'][$key]);
            $file_size = $_FILES['reports']['size'][$key];
            $file_type = $_FILES['reports']['type'][$key];
            $file_error = $_FILES['reports']['error'][$key];

            if ($file_error === UPLOAD_ERR_OK) {
                if (in_array($file_type, $allowed_types) && $file_size <= $max_file_size) {
                    $new_file_name = uniqid() . "_" . $file_name;
                    $destination = $upload_dir . $new_file_name;

                    if (move_uploaded_file($tmp_name, $destination)) {
                        $uploaded_reports[] = $destination;
                    } else {
                        $upload_error_message = "Error uploading file: " . $file_name;
                        // Consider logging this error or displaying it to the user
                    }
                } else {
                    $upload_error_message = "Invalid file type or size for: " . $file_name;
                    // Consider displaying this error to the user
                }
            } elseif ($file_error !== UPLOAD_ERR_NO_FILE) {
                $upload_error_message = "Upload error for: " . $file_name . " (Code: " . $file_error . ")";
                // Consider displaying this error to the user
            }
        }
    }

    // Merge existing reports with newly uploaded ones, and handle removed reports
    $existing_reports = !empty($_POST['existing_reports']) ? explode(',', $_POST['existing_reports']) : [];
    $reports_to_remove = !empty($_POST['existing_reports_to_remove']) ? $_POST['existing_reports_to_remove'] : [];

     // Filter out the reports to be removed
    $existing_reports = array_filter($existing_reports, function($report) use ($reports_to_remove) {
        return !in_array($report, $reports_to_remove);
    });

    $all_reports = array_merge($existing_reports, $uploaded_reports);
    $reports_string = implode(',', $all_reports);


    // Update patient data in the database
    try {
        $stmt = $pdo->prepare("UPDATE patients_ipd SET
            name = :name,
            age = :age,
            gender = :gender,
            guardian_name = :guardian_name,
            contact = :contact,
            whatsapp_number = :whatsapp_number,
            address = :address,
            problem = :problem,
            doctor = :doctor,
            referred_by = :referred_by,
            remarks = :remarks,
            admission_date = :admission_date,
            ward_number = :ward_number,
            bed_number = :bed_number,
            fee = :fee,
            discount = :discount,
            final_fee = :final_fee,
            discharge_date = :discharge_date,
            discharge_status = :discharge_status,
            reports = :reports,
            updated_at = NOW()
            WHERE ipd_id = :ipd_id");

        $stmt->execute([
            ':ipd_id' => $ipd_id,
            ':name' => $name,
            ':age' => $age,
            ':gender' => $gender,
            ':guardian_name' => $guardian_name,
            ':contact' => $contact,
            ':whatsapp_number' => $whatsapp_number,
            ':address' => $address,
            ':problem' => $problem,
            ':doctor' => $doctor,
            ':referred_by' => $referred_by,
            ':remarks' => $remarks,
            ':admission_date' => $admission_date,
            ':ward_number' => $ward_number,
            ':bed_number' => $bed_number,
            ':fee' => $fee,
            ':discount' => $discount,
            ':final_fee' => $final_fee,
            ':discharge_date' => $discharge_date,
            ':discharge_status' => $discharge_status,
            ':reports' => $reports_string
        ]);

        // After successful update, delete the files that were marked for removal.
        foreach ($reports_to_remove as $file_path) {
             if (file_exists($file_path)) {
                unlink($file_path); // Delete the file.
             }
        }
        // Redirect to the view page after successful update
        header("Location: view_ipd_patient.php?ipd_id=" . urlencode($ipd_id));
        exit();

    } catch (PDOException $e) {
        // Handle database errors
        die("Error updating patient data: " . $e->getMessage());
    }
}

?>

<div id="wrapper">

    <?php include '../../../includes/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">

        <?php include '../../../includes/navbar.php'; ?>

        <div id="content">
            <h1 class="text-center mb-5"><strong>Edit IPD Patient Information</strong></h1>
            <div class="container card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Patient (IPD ID: <?php echo htmlspecialchars($patient['ipd_id']); ?>)</h6>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($patient['age']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="Male" <?php echo ($patient['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($patient['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo ($patient['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="guardian_name" class="form-label">Father/Guardian Name</label>
                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="<?php echo htmlspecialchars($patient['guardian_name']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($patient['contact']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                                    <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number" value="<?php echo htmlspecialchars($patient['whatsapp_number']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($patient['address']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="problem" class="form-label">Problem</label>
                                    <input type="text" class="form-control" id="problem" name="problem" value="<?php echo htmlspecialchars($patient['problem']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="doctor" class="form-label">Doctor</label>
                                    <input type="text" class="form-control" id="doctor" name="doctor" value="<?php echo htmlspecialchars($patient['doctor']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="referred_by" class="form-label">Referred By</label>
                                    <input type="text" class="form-control" id="referred_by" name="referred_by" value="<?php echo htmlspecialchars($patient['referred_by']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="3"><?php echo htmlspecialchars($patient['remarks']); ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admission_date" class="form-label">Admission Date</label>
                                    <input type="date" class="form-control" id="admission_date" name="admission_date" value="<?php echo isset($patient['admission_date']) ? htmlspecialchars(date('Y-m-d', strtotime($patient['admission_date']))) : ''; ?>" required>
                                </div>
                                 <div class="mb-3">
                                    <label for="admission_time" class="form-label">Admission Time</label>
                                    <input type="time" class="form-control" id="admission_time" name="admission_time" value="<?php echo isset($patient['admission_date']) ? htmlspecialchars(date('H:i', strtotime($patient['admission_date']))) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ward_number" class="form-label">Ward Number</label>
                                    <input type="text" class="form-control" id="ward_number" name="ward_number" value="<?php echo htmlspecialchars($patient['ward_number']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="bed_number" class="form-label">Bed Number</label>
                                    <input type="text" class="form-control" id="bed_number" name="bed_number" value="<?php echo htmlspecialchars($patient['bed_number']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="fee" class="form-label">Fee</label>
                                    <input type="number" step="0.01" class="form-control" id="fee" name="fee" value="<?php echo htmlspecialchars($patient['fee']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="discount" class="form-label">Discount (%)</label>
                                    <input type="number" step="0.01" class="form-control" id="discount" name="discount" value="<?php echo htmlspecialchars($patient['discount']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="final_fee" class="form-label">Final Fee</label>
                                    <input type="number" step="0.01" class="form-control" id="final_fee" name="final_fee" value="<?php echo htmlspecialchars($patient['final_fee']); ?>">
                                </div>
                                 <div class="mb-3 d-flex align-items-center">
                                    <div style="flex: 1;">
                                        <label for="discharge_date" class="form-label">Discharge Date</label>
                                        <input type="date" class="form-control" id="discharge_date" name="discharge_date"
                                               value="<?php echo isset($patient['discharge_date']) ? htmlspecialchars(date('Y-m-d', strtotime($patient['discharge_date']))) : ''; ?>">
                                    </div>
                                    <div style="flex: 1;">
                                        <label for="discharge_time" class="form-label">Discharge Time</label>
                                        <input type="time" class="form-control" id="discharge_time" name="discharge_time"
                                               value="<?php echo isset($patient['discharge_date']) ? htmlspecialchars(date('H:i', strtotime($patient['discharge_date']))) : ''; ?>">
                                    </div>
                                    <button type="button" class="btn btn-danger clear-datetime-btn"
                                            onclick="clearDischargeDateTime()">
                                        <i class="fa-solid fa-eraser"></i> Clear
                                    </button>
                                </div>
                                <div class="mb-3">
                                    <label for="discharge_status" class="form-label">Discharge Status</label>
                                    <select class="form-select" id="discharge_status" name="discharge_status">
                                        <option value="Admitted" <?php echo ($patient['discharge_status'] === 'Admitted') ? 'selected' : ''; ?>>Admitted</option>
                                        <option value="Discharged" <?php echo ($patient['discharge_status'] === 'Discharged') ? 'selected' : ''; ?>>Discharged</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="reports" class="form-label">Reports (Upload New)</label>
                                    <input type="file" class="form-control" id="reports" name="reports[]" multiple>
                                    <small class="form-text text-muted">You can upload multiple files (PDF, JPG, PNG, GIF). Maximum size 5MB per file.</small>
                                </div>
                                <?php if (!empty($patient['reports'])): ?>
                                    <div class="mb-3">
                                        <label class="form-label">Current Reports</label><br>
                                        <?php
                                        $existing_reports = explode(',', $patient['reports']);
                                        foreach ($existing_reports as $report):
                                            $fileName = basename($report);
                                            echo '<div class="form-check">';
                                            echo '<input class="form-check-input" type="checkbox" name="existing_reports_to_remove[]" value="' . htmlspecialchars($report) . '" id="report_' . htmlspecialchars(urlencode($report)) . '">';
                                            echo '<label class="form-check-label" for="report_' . htmlspecialchars(urlencode($report)) . '">' . htmlspecialchars($fileName) . '</label>';
                                            echo '</div>';
                                        endforeach;
                                        ?>
                                        <input type="hidden" name="existing_reports" value="<?php echo htmlspecialchars($patient['reports']); ?>">
                                        <small class="form-text text-muted">Check the box to remove existing reports.</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update Patient</button>
                        <a href="view_ipd_patient.php?ipd_id=<?php echo urlencode($patient['ipd_id']); ?>" class="btn btn-secondary"><i class="fa-solid fa-ban"></i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../../includes/footer.php"; ?>

<script>
function clearDischargeDateTime() {
    document.getElementById("discharge_date").value = "";
    document.getElementById("discharge_time").value = "";
}
</script>
