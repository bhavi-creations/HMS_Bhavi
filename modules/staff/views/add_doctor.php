<?php include "../../../includes/header.php"; ?>

<div id="wrapper">

    <?php
    include '../../../includes/sidebar.php';


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['submit_doc_btn'])) {

            // Sanitize and validate form inputs for doctor
            $doctor_name = filter_var($_POST['doctor_name'], FILTER_SANITIZE_STRING);
            $dob = filter_var($_POST['dob'], FILTER_SANITIZE_STRING);
            $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
            $phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $specialization = filter_var($_POST['specialization'], FILTER_SANITIZE_STRING);
            $qualification = filter_var($_POST['qualification'], FILTER_SANITIZE_STRING);
            $experience = filter_var($_POST['experience'], FILTER_SANITIZE_STRING);
            $registration_number = filter_var($_POST['registration_number'], FILTER_SANITIZE_STRING);
            $join_date = filter_var($_POST['join_date'], FILTER_SANITIZE_STRING);
            $department = filter_var($_POST['department'], FILTER_SANITIZE_STRING);
            $shift_timings = filter_var($_POST['shift_timings'], FILTER_SANITIZE_STRING);
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
            $profile_image = $_FILES['profile_image']['name']; // File upload for profile image
            $salary = filter_var($_POST['salary'], FILTER_SANITIZE_STRING);
            $bank_account = filter_var($_POST['bank_account'], FILTER_SANITIZE_STRING);
            $tax_id = filter_var($_POST['tax_id'], FILTER_SANITIZE_STRING);
            $availability_days = filter_var($_POST['availability_days'], FILTER_SANITIZE_STRING);
            $consultation_hours = filter_var($_POST['consultation_hours'], FILTER_SANITIZE_STRING);
            $certificates = $_FILES['certificates']; // Handle multiple certificates

            // Generate Doctor ID in the format DOCYYYYMMDD-XXXX
            $current_date = date('Ymd'); // Get current date in YYYYMMDD format

            // Fetch the last inserted doctor_id, ordering by doctor_id instead of id
            $stmt = $pdo->prepare("SELECT doctor_id FROM doctors_list ORDER BY doctor_id DESC LIMIT 1");
            $stmt->execute();
            $last_doctor = $stmt->fetchColumn();

            // If we have a last doctor ID, continue incrementing it
            if ($last_doctor) {
                preg_match('/(\d{4})$/', $last_doctor, $matches); // Extract the last 4 digits
                $last_number = $matches[1];
                $next_number = str_pad(intval($last_number) + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $next_number = '0001'; // If no doctor exists, start from 0001
            }

            // Keep the date format but ensure the numbering continues across days
            $doctor_id = 'DOC' . $current_date . '-' . $next_number;

            echo $doctor_id; // Example Output: DOC20240130-0002 (Continues from the last ID)


            // Validate the sanitized inputs
            // Check if registration number already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM doctors_list WHERE registration_number = :registration_number");
            $stmt->execute([':registration_number' => $registration_number]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                echo '<script>alert("Registration number already exists. Please use a unique one.");</script>';
            } else {
                // Check if the username already exists
                $stmt_check_username = $pdo->prepare("SELECT COUNT(*) FROM doctors_list WHERE username = :username");
                $stmt_check_username->execute([':username' => $username]);
                $username_count = $stmt_check_username->fetchColumn();

                if ($username_count > 0) {
                    echo '<script>alert("Username already exists. Please use a unique one.");</script>';
                } else {
                    try {
                        // Profile image upload path
                        $profile_image_path = "../../../assets/uploads/doctors_profiles/" . basename($profile_image);
                        move_uploaded_file($_FILES['profile_image']['tmp_name'], $profile_image_path);

                        // Handle certificate uploads
                        $certificate_paths = [];
                        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']; // Allowed extensions for certificates

                        if (isset($_FILES['certificates']) && count($_FILES['certificates']['name']) > 0) {
                            foreach ($_FILES['certificates']['name'] as $key => $filename) {
                                $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                                // Validate file type
                                if (in_array($file_extension, $allowed_extensions)) {
                                    // Generate unique filename by adding a timestamp to avoid conflicts
                                    $filename = time() . "_" . basename($filename);
                                    $certificate_path = "../../../assets/uploads/doctors_profiles/" . $filename;

                                    // Move uploaded file to the certificates directory
                                    if (move_uploaded_file($_FILES['certificates']['tmp_name'][$key], $certificate_path)) {
                                        $certificate_paths[] = $certificate_path;
                                    } else {
                                        echo '<script>alert("Error uploading certificate: ' . $filename . '");</script>';
                                    }
                                } else {
                                    echo '<script>alert("Invalid file type for certificate: ' . $filename . '. Please upload an image or document.");</script>';
                                }
                            }
                        } else {
                            echo '<script>alert("No files selected!");</script>';
                        }

                        // Convert certificate paths array to a string
                        $certificates_string = implode(",", $certificate_paths);

                        // Insert into the database
                        $stmt = $pdo->prepare(
                            "INSERT INTO doctors_list 
                            (doctor_id, doctor_name, dob, gender, phone, email, specialization, qualification, experience, registration_number, join_date, department, shift_timings, username, password, address, profile_image, salary, bank_account, tax_id, availability_days, consultation_hours, certificates, created_at, updated_at) 
                            VALUES 
                            (:doctor_id, :doctor_name, :dob, :gender, :phone, :email, :specialization, :qualification, :experience, :registration_number, :join_date, :department, :shift_timings, :username, :password, :address, :profile_image, :salary, :bank_account, :tax_id, :availability_days, :consultation_hours, :certificates, :created_at, :updated_at)"
                        );

                        $stmt->execute([
                            ':doctor_id' => $doctor_id,
                            ':doctor_name' => $doctor_name,
                            ':dob' => $dob,
                            ':gender' => $gender,
                            ':phone' => $phone,
                            ':email' => $email,
                            ':specialization' => $specialization,
                            ':qualification' => $qualification,
                            ':experience' => $experience,
                            ':registration_number' => $registration_number,
                            ':join_date' => $join_date,
                            ':department' => $department,
                            ':shift_timings' => $shift_timings,
                            ':username' => $username,
                            ':password' => password_hash($password, PASSWORD_DEFAULT),
                            ':address' => $address,
                            ':profile_image' => $profile_image_path,
                            ':salary' => $salary,
                            ':bank_account' => $bank_account,
                            ':tax_id' => $tax_id,
                            ':availability_days' => $availability_days,
                            ':consultation_hours' => $consultation_hours,
                            ':certificates' => $certificates_string,
                            ':created_at' => date('Y-m-d H:i:s'),
                            ':updated_at' => date('Y-m-d H:i:s'),
                        ]);

                        echo '<script>alert("Doctor added successfully.");</script>';
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        exit();
                    } catch (PDOException $e) {
                        echo '<script>alert("Error: ' . htmlspecialchars($e->getMessage()) . '");</script>';
                    }
                }
            }
        }
    }

    ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">

        <?php include '../../../includes/navbar.php'; ?>
        <div id="content">
            <h1 class="text-center"><strong> Add Doctor </strong></h1>
            <div class="container">
                <div id="addDoctorTable" class="table-container ul_border py-3 px-4 active">

                    <!-- Doctor Add Form -->
                    <form action="add_doctor.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-3 my-2">
                                <label for="doctor_name">Doctor Name</label>
                                <input type="text" class="form-control" id="doctor_name" name="doctor_name" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="dob">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="" disabled selected>ANY</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>



                            <div class="form-group col-3 my-2">
                                <label for="phone">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="specialization">Specialization</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="qualification">Qualification</label>
                                <input type="text" class="form-control" id="qualification" name="qualification" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="experience">Experience</label>
                                <input type="number" class="form-control" id="experience" name="experience" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="registration_number">Registration Number</label>
                                <input type="text" class="form-control" id="registration_number" name="registration_number" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="join_date">Joining Date</label>
                                <input type="date" class="form-control" id="join_date" name="join_date" required>
                            </div>


                            <div class="form-group col-3 my-2">
                                <label for="department">Department</label>
                                <input type="text" class="form-control" id="department" name="department" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="shift_timings">Shift Times</label>
                                <textarea class="form-control" id="shift_timings" name="shift_timings" rows="1" required></textarea>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="username">User Name</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="1" required></textarea>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="profile_image">Profile Image</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="salary">Salary</label>
                                <input type="number" class="form-control" id="salary" name="salary" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="bank_account">Bank Account</label>
                                <input type="text" class="form-control" id="bank_account" name="bank_account" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="tax_id">Tax ID</label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id" required>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="availability_days">Availability Days</label>
                                <textarea class="form-control" id="availability_days" name="availability_days" rows="1" required></textarea>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="consultation_hours">Consultation Hours</label>
                                <textarea class="form-control" id="consultation_hours" name="consultation_hours" rows="1" required></textarea>
                            </div>

                            <div class="form-group col-3 my-2">
                                <label for="certificates">Certificates (Multiple files)</label>
                                <input type="file" class="form-control" id="certificates" name="certificates[]" accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" multiple required>


                            </div>


                            <div class="form-group col-12 text-center my-2">
                                <button type="submit" name="submit_doc_btn" class="btn btn-primary">Add Doctor</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>