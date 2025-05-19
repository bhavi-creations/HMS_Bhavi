<?php
ob_start(); // Start output buffering
?>

<div id="wrapper">

    <?php
    include "includes/sidebar.php";
    include "includes/header.php";
    include "config/db.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['submit_doc_btn'])) {
            $name = $_POST['name'];
            $specialization = $_POST['specialization'];
            $contact = $_POST['contact'];
            $email = $_POST['email'];
            $availability_schedule = $_POST['availability_schedule'];
            $image = $_FILES['photo']['name'];

            // Validate image file type
            $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.')</script>";
            } else {
                // Move uploaded file
                $targetimg = "assets/uploads/doctors/";
                $imgrename = date('Ymd') . rand(1, 1000000) . '.' . $imageFileType;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetimg . $imgrename)) {
                    try {
                        // Use the $pdo connection to insert data
                        $stmt = $pdo->prepare("INSERT INTO doctors (name, specialization, contact, email, availability_schedule, photo) 
                                               VALUES (:name, :specialization, :contact, :email, :availability_schedule, :photo)");
                        $stmt->execute([
                            ':name' => $name,
                            ':specialization' => $specialization,
                            ':contact' => $contact,
                            ':email' => $email,
                            ':availability_schedule' => $availability_schedule,
                            ':photo' => $imgrename
                        ]);

                        echo '<script>alert("Doctor data inserted successfully.");</script>';
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        exit();
                    } catch (PDOException $e) {
                        echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
                    }
                } else {
                    echo '<script>alert("Failed to upload photo.");</script>';
                }
            }
        } elseif (isset($_POST['submit_nurse_btn'])) {
            $name = $_POST['name'];
            $department = $_POST['department'];
            $contact = $_POST['contact'];
            $email = $_POST['email'];
            $availability_schedule = $_POST['availability_schedule'];
            $image = $_FILES['photo']['name'];

            // Validate and upload image
            $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.')</script>";
            } else {
                $targetimg = "assets/uploads/nurses/";
                $imgrename = date('Ymd') . rand(1, 1000000) . '.' . $imageFileType;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetimg . $imgrename)) {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO nurses (name, department, contact, email, availability_schedule, photo) 
                                       VALUES (:name, :department, :contact, :email, :availability_schedule, :photo)");
                        $stmt->execute([
                            ':name' => $name,
                            ':department' => $department,
                            ':contact' => $contact,
                            ':email' => $email,
                            ':availability_schedule' => $availability_schedule,
                            ':photo' => $imgrename
                        ]);

                        echo '<script>alert("Nurse data inserted successfully.");</script>';
                        header('Location: ' . $_SERVER['PHP_SELF']);

                        exit();
                    } catch (PDOException $e) {
                        echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
                    }
                } else {
                    echo '<script>alert("Failed to upload photo.");</script>';
                }
            }
        }
    }
 
    ?>




    <div id="content-wrapper" class="d-flex flex-column   bg-white">


        <div id="content">



            <div class="container branch_container">


                <div class="row  ">
                    <div class="col-md-4 col-lg-2 ul_border mb-4">
                        <?php
                        $activeTable = 'adddoctorTable';
                        $activeListItem = 'details';
                        if (isset($_GET['upload_success']) && $_GET['upload_success'] === 'incharge') {
                            $activeTable = 'addnurseTable';
                            $activeListItem = 'incharges';
                        }
                        ?>




                        <ul class="ul_style">
                            <li id="adddoctor" class="add_staff_list_detils open_table">+ Add Doctor</li>
                            <li id="addnurse" class="add_incharge_list_detils open_table">+ Add Nurse</li>
 
                        </ul>

                        <script>
                            const listItems = document.querySelectorAll('.open_table');
                            const tableContainers = document.querySelectorAll('.table-container');

                            listItems.forEach(item => {
                                item.addEventListener('click', function() {
                                    listItems.forEach(i => i.classList.remove('active'));
                                    this.classList.add('active');
                                    updateTable(this.id);
                                });
                            });

                            function updateTable(id) {
                                tableContainers.forEach(container => container.classList.remove('active'));
                                document.querySelectorAll('.table-container').forEach(container => container
                                    .classList.remove('active'));
                                document.getElementById(id + 'Table').classList.add('active');
                            }


                            document.getElementById('adddoctorTable').classList.add('active');
                        </script>
                    </div>

                    <div class="col-md-11   col-lg-12 ul_border ">


                        <div id="adddoctorTable" class="table-container <?= $activeTable == 'adddoctorTable' ? 'active' : '' ?>">
                            <div class="container">
                                <div class="row d-flex flex-row justify-content-between pt-4 pb-3">
                                    <div class="">
                                        <h6 class="staff_dtls">Add Doctor</h6>
                                    </div>
                                </div>
                            </div>

                            <form method="post" enctype="multipart/form-data" onsubmit="return confirmSubmission()">
                                <div class="form-group">
                                    <div class="row">
                                        <!-- Photo Upload -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Upload Photo</label>
                                            <input type="file" class="form-control field_input_bg" name="photo" required>
                                        </div>

                                        <!-- Doctor Name -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Doctor Name</label>
                                            <input type="text" class="form-control field_input_bg" name="name" required>
                                        </div>

                                        <!-- Specialization -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Specialization</label>
                                            <input type="text" class="form-control field_input_bg" name="specialization" required>
                                        </div>

                                        <!-- Contact Number -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Contact Number</label>
                                            <input type="text" class="form-control field_input_bg" name="contact" required>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Email</label>
                                            <input type="email" class="form-control field_input_bg" name="email" required>
                                        </div>

                                        <!-- Availability Schedule -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Availability Schedule</label>
                                            <textarea class="form-control field_input_bg" name="availability_schedule" rows="4" required></textarea>
                                        </div>

                                        <!-- Submit and Back Buttons -->
                                        <div class="col-md-12 mt-5">
                                            <div class="row last_back_submit d-flex flex-row justify-content-between px-3">
                                                <button type="button" class="back_btn_staff">Back</button>
                                                <button type="submit" class="submit_btn_staff" name="submit_doc_btn">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- JavaScript for alert on form submission -->
                            <script>
                                function confirmSubmission() {
                                    return confirm('Are you sure you want to submit the form?');
                                }
                            </script>
                        </div>





                        <div id="addnurseTable" class="table-container   <?= $activeTable == 'addnurseTable' ? 'active' : '' ?>">
                            <div class="container">
                                <div class="row d-flex flex-row justify-content-between pt-4 pb-3">
                                    <div class="">
                                        <h6 class="staff_dtls">Add Nurse </h6>
                                    </div>

                                </div>
                            </div>



                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <div class="row">
                                        <!-- Photo Upload -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Upload Photo</label>
                                            <input type="file" class="form-control field_input_bg" name="photo" required>
                                        </div>

                                        <!-- Nurse Name -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Nurse Name</label>
                                            <input type="text" class="form-control field_input_bg" name="name" required>
                                        </div>

                                        <!-- Department -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Department</label>
                                            <input type="text" class="form-control field_input_bg" name="department" required>
                                        </div>

                                        <!-- Contact Number -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Contact Number</label>
                                            <input type="text" class="form-control field_input_bg" name="contact" required>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Email</label>
                                            <input type="email" class="form-control field_input_bg" name="email" required>
                                        </div>

                                        <!-- Availability Schedule -->
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Availability Schedule</label>
                                            <textarea class="form-control field_input_bg" name="availability_schedule" rows="3" required></textarea>
                                        </div>

                                        <!-- Submit and Back Buttons -->
                                        <div class="col-md-12 mt-5">
                                            <div class="row last_back_submit d-flex flex-row justify-content-between px-3">
                                                <button type="button" class="back_btn_staff" onclick="window.history.back()">Back</button>
                                                <button type="submit" class="submit_btn_staff" name="submit_nurse_btn">Submit</button>
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


    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <?php
    include "includes/footer.php";
    ?>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        new DataTable('#example');
    </script>
    <script>
        new DataTable('#example1');
    </script>

</div>