<?php
ob_start(); // Start output buffering
?>

<div id="wrapper">
    <?php
    include '../../../includes/sidebar.php';
    include "../../../includes/header.php";
    include "../../../config/db.php";

    // Capture the referrer URL or fallback to dashboard
    $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../../dashboard.php';

    if (isset($_GET['id'])) {
        $patient_id = $_GET['id'];

        // Validate the ID format (YYMMDD-XXXXXX)
        if (preg_match('/^[A-Z]{2}\d{6}\d{6}$/', $patient_id)) {

            try {
                // Fetch patient details
                $stmt = $pdo->prepare("SELECT * FROM patients_opd WHERE id = :id");
                $stmt->execute([':id' => $patient_id]);
                $patient = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$patient) {
                    echo "<div class='alert alert-danger text-center'>Patient not found!</div>";
                    exit;
                }

                if (isset($_GET['delete_report'])) {
                    $report_to_delete = filter_input(INPUT_GET, 'delete_report', FILTER_SANITIZE_STRING);
                    $existing_reports = $patient['reports'] ? explode(',', $patient['reports']) : [];

                    // Remove the specified report
                    $updated_reports = array_filter($existing_reports, fn($report) => $report !== $report_to_delete);

                    // Update the database
                    $stmt = $pdo->prepare("UPDATE patients_opd SET reports = :reports WHERE id = :id");
                    $stmt->execute([
                        ':reports' => implode(',', $updated_reports),
                        ':id' => $patient_id,
                    ]);

                    // Delete the file from the server
                    if (file_exists($report_to_delete)) {
                        unlink($report_to_delete);
                    }

                    header("Location: edit_patient.php?id=$patient_id");
                    exit();
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Sanitize form inputs
                    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                    $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
                    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
                    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_NUMBER_INT);
                    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
                    $doctor = filter_input(INPUT_POST, 'doctor', FILTER_SANITIZE_STRING);
                    $medical_history = filter_input(INPUT_POST, 'medical_history', FILTER_SANITIZE_STRING);
                    $admission_type = filter_input(INPUT_POST, 'admission_type', FILTER_SANITIZE_STRING);

                    // Handle file uploads
                    $uploaded_files = [];
                    if (!empty($_FILES['reports']['name'][0])) {
                        $upload_dir = '../../../assets/uploads/patient_reports/';
                        foreach ($_FILES['reports']['name'] as $key => $file_name) {
                            $target_path = $upload_dir . basename($file_name);
                            if (move_uploaded_file($_FILES['reports']['tmp_name'][$key], $target_path)) {
                                $uploaded_files[] = $target_path; // Save full file path
                            }
                        }
                    }

                    // Combine new and old documents
                    $existing_documents = $patient['reports'] ? explode(',', $patient['reports']) : [];
                    $all_documents = array_merge($existing_documents, $uploaded_files);
                    $documents_str = implode(',', $all_documents);

                    // Update database
                    $stmt = $pdo->prepare("
                        UPDATE patients_opd 
                        SET 
                            name = :name,
                            age = :age,
                            gender = :gender,
                            contact = :contact,
                            address = :address,
                            doctor = :doctor,
                            medical_history = :medical_history,
                            admission_type = :admission_type,
                            reports = :reports,
                            updated_at = NOW()
                        WHERE id = :id
                    ");
                    $stmt->execute([
                        ':name' => $name,
                        ':age' => $age,
                        ':gender' => $gender,   
                        ':contact' => $contact,
                        ':address' => $address,
                        ':doctor' => $doctor,
                        ':medical_history' => $medical_history,
                        ':admission_type' => $admission_type,
                        ':reports' => $documents_str,
                        ':id' => $patient_id,
                    ]);

                    // Redirect to the referring page
                    $redirect_url = filter_input(INPUT_POST, 'referrer', FILTER_SANITIZE_URL);
                    header("Location: " . $redirect_url);
                    exit();
                }
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger text-center'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger text-center'>Invalid patient ID format!</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>Patient ID is missing!</div>";
        exit;
    }
    ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <div id="content">
            <h1 class="text-center"><strong>Edit Patient</strong></h1>
            <div class="container">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="referrer" value="<?= htmlspecialchars($referrer) ?>">

                    <div class="form-group">
                        <div class="col-12 mt-5 d-flex flex-row justify-content-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>


                        <div class="row">
                            <div class="col-md-4 mt-5">
                                <label class="control-label mb-2">Patient Name</label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($patient['name']) ?>" required>
                            </div>
                            <div class="col-md-4 mt-5">
                                <label class="control-label mb-2">Age</label>
                                <input type="number" class="form-control" name="age" value="<?= htmlspecialchars($patient['age']) ?>" required>
                            </div>
                            <div class="col-md-4 mt-5">
                                <label class="control-label mb-2">Gender</label>
                                <select name="gender" class="form-control" required>
                                    <option value="Male" <?= $patient['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= $patient['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= $patient['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-5">
                                <label class="control-label mb-2">Phone</label>
                                <input type="tel" class="form-control" name="contact" value="<?= htmlspecialchars($patient['contact']) ?>" required>
                            </div>
                            <div class="col-md-4 mt-5">
                                <label class="control-label mb-2">Doctor</label>
                                <input type="text" class="form-control" name="doctor" value="<?= htmlspecialchars($patient['doctor']) ?>" required>
                            </div>
                            <div class="col-md-4 mt-5">
                                <label class="control-label mb-2">Admission Type</label>
                                <select name="admission_type" class="form-control" required>
                                    <option value="Casualty" <?= $patient['admission_type'] === 'Casualty' ? 'selected' : '' ?>>Casualty</option>
                                    <option value="OPD" <?= $patient['admission_type'] === 'OPD' ? 'selected' : '' ?>>OPD</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-5">
                                <label class="control-label mb-2">Address</label>
                                <textarea class="form-control" name="address" rows="4" required><?= htmlspecialchars($patient['address']) ?></textarea>
                            </div>
                            <div class="col-md-4 mt-5">
                                <label class="control-label mb-2">Medical History</label>
                                <textarea class="form-control" name="medical_history" rows="4" required><?= htmlspecialchars($patient['medical_history']) ?></textarea>
                            </div>
                            <div class="col-md-4 mt-5">
                                <label class="control-label mb-2">Upload New Reports</label>
                                <input type="file" name="reports[]" class="form-control" multiple>
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
            </div>
        </div>
    </div>

 
</div>
<?php ob_end_flush(); ?>

 