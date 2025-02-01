<?php
ob_start(); // Start output buffering
?>

<div id="wrapper">

    <?php
    include '../../../includes/sidebar.php';
    include "../../../includes/header.php";
    include "../../../config/db.php";

    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['submit_doc_btn'])) {
    
            // Sanitize and validate form inputs
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
            $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
            $guardian_name = filter_var($_POST['guardian_name'], FILTER_SANITIZE_STRING);
            $contact = filter_var($_POST['contact'], FILTER_SANITIZE_NUMBER_INT);
            $whatsapp_number = filter_var($_POST['whatsapp_number'], FILTER_SANITIZE_NUMBER_INT);
            $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
            $problem = filter_var($_POST['problem'], FILTER_SANITIZE_STRING);
            $doctor = filter_var($_POST['doctor'], FILTER_SANITIZE_STRING);
            $referred_by = filter_var($_POST['referred_by'], FILTER_SANITIZE_STRING);
            $remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
            $admission_type = filter_var($_POST['admission_type'], FILTER_SANITIZE_STRING);
            $medical_history = filter_var($_POST['medical_history'], FILTER_SANITIZE_STRING);
            $fee = filter_var($_POST['fee'], FILTER_VALIDATE_FLOAT);
            $discount = filter_var($_POST['discount'], FILTER_VALIDATE_FLOAT);
            $final_fee = filter_var($_POST['final_fee'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            // Validate the sanitized inputs
            if (!$name || !$age || !$gender || !$guardian_name || !$contact || !$whatsapp_number || !$address || !$problem || !$doctor || !$referred_by || !$remarks || !$admission_type || !$medical_history || !$fee || !$discount) {
                echo '<script>alert("Please fill in all fields correctly.");</script>';
            } else {
                try {
                    // Set the "OP" prefix for both types
                    $type_prefix = 'OP';
    
                    // Fetch the highest ID for Outpatient (OP) type
                    $stmt = $pdo->prepare("SELECT id FROM patients_opd WHERE id LIKE :type_prefix ORDER BY id DESC LIMIT 1");
                    $stmt->execute([':type_prefix' => $type_prefix . '%']);
                    $last_id = $stmt->fetchColumn();
    
                    if ($last_id) {
                        $last_number = (int)substr($last_id, 8); // Extract the numeric part after the date
                        $new_number = str_pad($last_number + 1, 6, '0', STR_PAD_LEFT); // 6 digits padding
                    } else {
                        $new_number = '000001'; // Start with 000001 if no IDs exist for this type
                    }
    
                    // Generate the new ID in format OPyyymmdd###### (e.g., OP240121000001)
                    $id = $type_prefix . date('ymd') . $new_number; // Combine type prefix, date, and incremented number
    
                    // Handle report uploads
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
    
                    // Insert data into the database
                    $stmt = $pdo->prepare(
                        "INSERT INTO patients_opd 
                        (id, name, age, gender, doctor, guardian_name,  contact, whatsapp_number,  address, problem, referred_by, remarks, admission_type, medical_history, fee, discount, reports) 
                        VALUES 
                        (:id, :name, :age, :gender, :doctor, :guardian_name, :contact, :whatsapp_number, :address, :problem, :referred_by, :remarks, :admission_type, :medical_history, :fee, :discount, :reports)"
                    );
                    
                    $stmt->execute([
                        ':id' => $id,
                        ':name' => $name,
                        ':age' => $age,
                        ':gender' => $gender,
                        ':doctor' => $doctor,
                        ':guardian_name' => $guardian_name,
                        ':contact' => $contact,
                        ':whatsapp_number' => $whatsapp_number,
                        ':address' => $address,
                        ':problem' => $problem,
                        ':referred_by' => $referred_by,
                        ':remarks' => $remarks,
                        ':admission_type' => $admission_type,
                        ':medical_history' => $medical_history,
                        ':fee' => $fee,
                        ':discount' => $discount,
                        ':reports' => $reports_str
                    ]);
                    
    
                    echo '<script>alert("Patient data inserted successfully. Patient ID: ' . $id . '");</script>';
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit();
                } catch (PDOException $e) {
                    echo '<script>alert("Error: ' . htmlspecialchars($e->getMessage()) . '");</script>';
                }
            }
        }
    }
    
    
    ob_end_flush(); // Flush the output buffer
    ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <div id="content">
            <h1 class="text-center"><strong> Add Patient </strong></h1>
            <div class="container">
                <div id="adddoctorTable" class="table-container ul_border px-4 active">
                    <form method="post" enctype="multipart/form-data" onsubmit="return confirmSubmission()">
                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Patient Name</label>
                                    <input type="text" class="form-control field_input_bg" name="name" required>
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Age</label>
                                    <input type="number" class="form-control field_input_bg" name="age" required>
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Gender</label>
                                    <select name="gender" class="form-control field_input_bg" required>
                                        <option value="" disabled selected>ANY</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Father/Guardian</label>
                                    <input type="text" class="form-control field_input_bg" name="guardian_name"  >
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">contact</label>
                                    <input type="tel" class="form-control field_input_bg" name="contact" required>
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">WhatsApp Number</label>
                                    <input type="tel" class="form-control field_input_bg" name="whatsapp_number"  >
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Address</label>
                                    <textarea class="form-control field_input_bg" name="address" rows="1"  ></textarea>
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Problem</label>
                                    <input type="text" class="form-control field_input_bg" name="problem"  >
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Doctor</label>
                                    <input type="text" class="form-control field_input_bg" name="doctor"  >
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Referred By</label>
                                    <input type="text" class="form-control field_input_bg" name="referred_by"  >
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Remarks</label>
                                    <input type="text" class="form-control field_input_bg" name="remarks"  >
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Admission Type</label>
                                    <select name="admission_type" class="form-control field_input_bg" required>
                                        <option value="Casualty">Casualty</option>
                                        <option value="OPD">OPD</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Upload Reports</label>
                                    <input type="file" name="reports[]" class="form-control field_input_bg" multiple>
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Medical History</label>
                                    <textarea class="form-control field_input_bg" name="medical_history" rows="1"  ></textarea>
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Fee</label>
                                    <input type="number" class="form-control field_input_bg" name="fee" id="fee" required oninput="calculateDiscount()">
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Discount (%)</label>
                                    <input type="number" class="form-control field_input_bg" name="discount" id="discount" required oninput="calculateDiscount()">
                                </div>

                                <div class="col-md-3 mt-5">
                                    <label class="control-label mb-2 field_txt">Final Amount</label>
                                     
                                    <input type="text" class="form-control field_input_bg" id="final_amount" name="final_fee" readonly>

                                </div>

                                <div class="col-md-3 mt-5">
                                    <div class="row last_back_submit d-flex flex-column align-items-center gap-4 px-3">
                                        <button type="button" class="back_btn_staff">Back</button>
                                        <button type="submit" class="submit_btn_staff" name="submit_doc_btn">Submit</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>

                    <script>
                        function confirmSubmission() {
                            return confirm('Are you sure you want to submit the form?');
                        }

                        function calculateDiscount() {
                            var fee = parseFloat(document.getElementById('fee').value) || 0;
                            var discount = parseFloat(document.getElementById('discount').value) || 0;
                            var finalAmount = fee - (fee * discount / 100);
                            document.getElementById('final_amount').value = finalAmount.toFixed(2);
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        new DataTable('#example');
        new DataTable('#example1');
    </script>

</div>