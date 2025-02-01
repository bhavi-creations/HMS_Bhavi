<?php
ob_start(); // Start output buffering
?>

<div id="wrapper">


    <?php

        include '../../../includes/sidebar.php';
        include "../../../includes/header.php";
        include "../../../config/db.php";

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $patient_id = $_GET['id'];

            try {
                // Fetch existing patient details
                $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = :id");
                $stmt->execute([':id' => $patient_id]);
                $patient = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$patient) {
                    echo '<script>alert("Patient not found."); window.location.href = "./see_patients.php";</script>';
                    exit();
                }
            } catch (PDOException $e) {
                echo '<script>alert("Error: ' . htmlspecialchars($e->getMessage()) . '");</script>';
            }
        } else {
            echo '<script>alert("Invalid Patient ID."); window.location.href = "./see_patients.php";</script>';
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['update_patient_btn'])) {

                // Sanitize and validate form inputs
                $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
                $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
                $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
                $contact = filter_var($_POST['contact'], FILTER_SANITIZE_NUMBER_INT);
                $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
                $medical_history = filter_var($_POST['medical_history'], FILTER_SANITIZE_STRING);

                // Validate the sanitized inputs
                if (!$name || !$age || !$gender || !$contact || !$address || !$medical_history) {
                    echo '<script>alert("Please fill in all fields correctly.");</script>';
                } else {
                    try {
                        // Update patient details
                        $stmt = $pdo->prepare(
                            "UPDATE patients SET name = :name, age = :age, gender = :gender, contact = :contact, address = :address, medical_history = :medical_history WHERE id = :id"
                        );
                        $stmt->execute([
                            ':name' => $name,
                            ':age' => $age,
                            ':gender' => $gender,
                            ':contact' => $contact,
                            ':address' => $address,
                            ':medical_history' => $medical_history,
                            ':id' => $patient_id
                        ]);

                        echo '<script>alert("Patient details updated successfully."); window.location.href = "./see_patients.php";</script>';
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
                <div class="container branch_container">
                    <div class="row">



                        <div class="col-md-11 col-lg-12 ul_border">

                            <div id="editPatientTable" class="table-container active">
                                <div class="container">
                                    <div class="row d-flex flex-row justify-content-between pt-4 pb-3">
                                        <div class="">
                                            <h6 class="staff_dtls">Edit Patient</h6>
                                        </div>
                                    </div>
                                </div>

                                <form method="post" enctype="multipart/form-data" onsubmit="return confirmUpdate()">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 mt-5">
                                                <label class="control-label mb-2 field_txt">Patient Name</label>
                                                <input type="text" class="form-control field_input_bg" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" required>
                                            </div>

                                            <div class="col-md-6 mt-5">
                                                <label class="control-label mb-2 field_txt">Age</label>
                                                <input type="number" class="form-control field_input_bg" name="age" value="<?php echo htmlspecialchars($patient['age']); ?>" required>
                                            </div>

                                            <div class="col-md-6 mt-5">
                                                <label class="control-label mb-2 field_txt">Gender</label>
                                                <select name="gender" class="form-control field_input_bg" required>
                                                    <option value="Male" <?php echo $patient['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                                    <option value="Female" <?php echo $patient['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                                    <option value="Other" <?php echo $patient['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mt-5">
                                                <label class="control-label mb-2 field_txt">Phone</label>
                                                <input type="tel" class="form-control field_input_bg" name="contact" value="<?php echo htmlspecialchars($patient['contact']); ?>" required>
                                            </div>

                                            <div class="col-md-6 mt-5">
                                                <label class="control-label mb-2 field_txt">Address</label>
                                                <textarea class="form-control field_input_bg" name="address" rows="4" required><?php echo htmlspecialchars($patient['address']); ?></textarea>
                                            </div>

                                            <div class="col-md-6 mt-5">
                                                <label class="control-label mb-2 field_txt">Medical History</label>
                                                <textarea class="form-control field_input_bg" name="medical_history" rows="4" required><?php echo htmlspecialchars($patient['medical_history']); ?></textarea>
                                            </div>

                                            <div class="col-md-12 mt-5">
                                                <div class="row last_back_submit d-flex flex-row justify-content-between px-3">
                                                    <button type="button" class="back_btn_staff" onclick="window.history.back();">Back</button>
                                                    <button type="submit" class="submit_btn_staff" name="update_patient_btn">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <script>
                                    function confirmUpdate() {
                                        return confirm('Are you sure you want to update the patient details?');
                                    }
                                </script>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <?php include "../../../includes/footer.php"; ?> -->
</div>