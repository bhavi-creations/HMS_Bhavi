<?php
ob_start(); // Start output buffering
?>


<div id="wrapper">
    <?php
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo '<script>alert("Patient ID is missing!"); window.location.href="patients_list.php";</script>';
        exit();
    }

    $patient_id = $_GET['id'];

    include '../../../includes/sidebar.php';
    include "../../../includes/header.php";
    include "../../../config/db.php";

    // Fetch the existing patient data for editing
    try {
        $stmt = $pdo->prepare("SELECT * FROM patients_opd WHERE id = :id");
        $stmt->execute([':id' => $patient_id]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$patient) {
            echo '<script>alert("Patient not found!"); window.location.href="patients_list.php";</script>';
            exit();
        }
    } catch (PDOException $e) {
        echo '<script>alert("Error: ' . htmlspecialchars($e->getMessage()) . '");</script>';
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['submit_doc_btn'])) {
            // Sanitize and validate form inputs
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
            $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
            $contact = filter_var($_POST['contact'], FILTER_SANITIZE_NUMBER_INT);
            $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
            $medical_history = filter_var($_POST['medical_history'], FILTER_SANITIZE_STRING);
            $admission_type = filter_var($_POST['admission_type'], FILTER_SANITIZE_STRING);
            $doctor = filter_var($_POST['doctor'], FILTER_SANITIZE_STRING);

            // Validate the sanitized inputs
            if (!$name || !$age || !$gender || !$contact || !$address || !$medical_history || !$admission_type || !$doctor) {
                echo '<script>alert("Please fill in all fields correctly.");</script>';
            } else {
                try {
                    // Handle report uploads (optional)
                    $reports = [];
                    if (!empty($_FILES['reports']['name'][0])) {
                        $target_dir = "../../../assets/uploads/patient_reports/";

                        // Loop through files if multiple files are uploaded
                        foreach ($_FILES['reports']['name'] as $index => $filename) {
                            $target_file = $target_dir . basename($filename);
                            if (move_uploaded_file($_FILES['reports']['tmp_name'][$index], $target_file)) {
                                $reports[] = $target_file;  // Store report file paths
                            }
                        }
                    }

                    $reports_str = implode(',', $reports);  // Combine file paths into a comma-separated string

                    // Update the patient's data in the database
                    $stmt = $pdo->prepare(
                        "UPDATE patients_opd SET 
                    name = :name, age = :age, gender = :gender, doctor = :doctor, contact = :contact, 
                    address = :address, medical_history = :medical_history, admission_type = :admission_type, 
                    reports = :reports WHERE id = :id"
                    );
                    $stmt->execute([
                        ':id' => $patient_id,
                        ':name' => $name,
                        ':age' => $age,
                        ':gender' => $gender,
                        ':doctor' => $doctor,
                        ':contact' => $contact,
                        ':address' => $address,
                        ':medical_history' => $medical_history,
                        ':admission_type' => $admission_type,
                        ':reports' => $reports_str
                    ]);

                    echo '<script>alert("Patient data updated successfully!");</script>';
                    header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $patient_id);
                    exit();
                } catch (PDOException $e) {
                    echo '<script>alert("Error: ' . htmlspecialchars($e->getMessage()) . '");</script>';
                }
            }
        }
    }

    ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <div id="content">
            <h1 class="text-center"> <strong> Edit Patient </strong></h1>
            <div class="container">
                <div id="adddoctorTable" class="table-container ul_border px-4 active">
                    <form method="post" enctype="multipart/form-data" onsubmit="return confirmSubmission()">
                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-4 mt-5">
                                    <label class="control-label mb-2 field_txt">Patient Name</label>
                                    <input type="text" class="form-control field_input_bg" name="name" value="<?= htmlspecialchars($patient['name']) ?>" required>
                                </div>

                                <div class="col-md-4 mt-5">
                                    <label class="control-label mb-2 field_txt">Age</label>
                                    <input type="number" class="form-control field_input_bg" name="age" value="<?= htmlspecialchars($patient['age']) ?>" required>
                                </div>

                                <div class="col-md-4 mt-5">
                                    <label class="control-label mb-2 field_txt">Gender</label>
                                    <select name="gender" class="form-control field_input_bg" required>
                                        <option value="" disabled>Select Gender</option>
                                        <option value="Male" <?= $patient['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= $patient['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= $patient['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mt-5">
                                    <label class="control-label mb-2 field_txt">Phone</label>
                                    <input type="tel" class="form-control field_input_bg" name="contact" value="<?= htmlspecialchars($patient['contact']) ?>" required>
                                </div>

                                <div class="col-md-4 mt-5">
                                    <label class="control-label mb-2 field_txt">Address</label>
                                    <textarea class="form-control field_input_bg" name="address" rows="1" required><?= htmlspecialchars($patient['address']) ?></textarea>
                                </div>

                                <div class="col-md-4 mt-5">
                                    <label class="control-label mb-2 field_txt">Doctor</label>
                                    <input type="text" class="form-control field_input_bg" name="doctor" value="<?= htmlspecialchars($patient['doctor']) ?>" required>
                                </div>

                                <div class="col-md-4 mt-5">
                                    <label class="control-label mb-2 field_txt">Admission Type</label>
                                    <select name="admission_type" class="form-control field_input_bg" required>
                                        <option value="Casualty" <?= $patient['admission_type'] == 'Casualty' ? 'selected' : '' ?>>Casualty</option>
                                        <option value="OPD" <?= $patient['admission_type'] == 'OPD' ? 'selected' : '' ?>>OPD</option>
                                    </select>
                                </div>

                                <!-- Report Upload Section -->
                                <div class="col-md-4 mt-5">
                                    <label class="control-label mb-2 field_txt">Upload Reports</label>
                                    <input type="file" name="reports[]" class="form-control field_input_bg" multiple>
                                </div>

                                <div class="col-md-4 mt-5">
                                    <label class="control-label mb-2 field_txt">Medical History</label>
                                    <textarea class="form-control field_input_bg" name="medical_history" rows="4" required><?= htmlspecialchars($patient['medical_history']) ?></textarea>
                                </div>

                                <div class="col-md-4 mt-5">
                                    <div class="row last_back_submit d-flex flex-column align-items-center gap-4 px-3">
                                        <button type="button" class="back_btn_staff">Back</button>
                                        <button type="submit" class="submit_btn_staff" name="submit_doc_btn">Submit</button>
                                    </div>
                                </div>

                            </div>



                            <div class="container my-5">
                            <div class="card" style="border-radius: 10px;">
                                <div class="card-header text-black text-center">
                                    <h4>Patient Reports</h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if (!empty($patient['reports'])) {
                                        $reports = explode(',', $patient['reports']);
                                        echo "<div class='row'>";
                                        foreach ($reports as $report) {
                                            $fileName = basename($report);
                                            $fileExt = pathinfo($report, PATHINFO_EXTENSION);

                                            echo "<div class='col-md-3 my-5 text-center'>";

                                            // Check if the file is a PDF
                                            if ($fileExt === 'pdf') {
                                                echo "<h5>$fileName</h5>";
                                                echo "<embed src='" . htmlspecialchars($report) . "' width='100%' height='400px' type='application/pdf'>";
                                            }
                                            // If it's an image
                                            elseif (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                echo "<h5>$fileName</h5>";
                                                echo "<img src='" . htmlspecialchars($report) . "' alt='$fileName' width='100%' style='max-height: 400px;'>";
                                            }
                                            // If it's a text file (or other supported types)
                                            elseif ($fileExt === 'txt') {
                                                echo "<h5>$fileName</h5>";
                                                $fileContent = file_get_contents($report);
                                                echo "<pre>$fileContent</pre>";
                                            }

                                            // Delete button
                                            echo "<a href='edit_patient.php?id={$patient_id}&delete_report=" . urlencode($report) . "' class='btn btn-danger btn-sm mt-3'>Delete</a>";
                                            echo "</div>";
                                        }
                                        echo "</div>";
                                    } else {
                                        echo "<p class='text-center'>No Reports Available</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        </div>
                    </form>

                    <script>
                        function confirmSubmission() {
                            return confirm('Are you sure you want to submit the form?');
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_end_flush(); ?>