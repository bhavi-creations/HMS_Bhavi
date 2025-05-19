 

<div id="wrapper">

    <?php
    include "includes/sidebar.php";
    include "includes/header.php";
    include "config/db.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['submit_doc_btn'])) {

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
                    // Use the $pdo connection to insert data
                    $stmt = $pdo->prepare(
                        "INSERT INTO patients (name, age, gender, contact, address, medical_history) 
                         VALUES (:name, :age, :gender, :contact, :address, :medical_history)"
                    );
                    $stmt->execute([
                        ':name' => $name,
                        ':age' => $age,
                        ':gender' => $gender,
                        ':contact' => $contact,
                        ':address' => $address,
                        ':medical_history' => $medical_history
                    ]);

                    echo '<script>alert("Patient data inserted successfully.");</script>';
                    header('Location: ' . $_SERVER['PHP_SELF']);
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
            <div class="container branch_container">

                <div class="row">

                    <div class="col-md-4 col-lg-2 ul_border mb-4">
                        <?php
                        $activeTable = 'adddoctorTable';
                        ?>

                        <ul class="ul_style">
                            <li id="adddoctor" class="add_staff_list_detils open_table">+ Add Patient</li>
                        </ul>

                        <script>
                            const listItems = document.querySelectorAll('.open_table');

                            listItems.forEach(item => {
                                item.addEventListener('click', function () {
                                    listItems.forEach(i => i.classList.remove('active'));
                                    this.classList.add('active');
                                });
                            });

                            // Set the default active table
                            document.getElementById('adddoctorTable').classList.add('active');
                        </script>
                    </div>

                    <div class="col-md-11 col-lg-12 ul_border">

                        <div id="adddoctorTable" class="table-container active">
                            <div class="container">
                                <div class="row d-flex flex-row justify-content-between pt-4 pb-3">
                                    <div class="">
                                        <h6 class="staff_dtls">Add Patient</h6>
                                    </div>
                                </div>
                            </div>

                            <form method="post" enctype="multipart/form-data" onsubmit="return confirmSubmission()">
                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Patient Name</label>
                                            <input type="text" class="form-control field_input_bg" name="name" required>
                                        </div>

                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Age</label>
                                            <input type="number" class="form-control field_input_bg" name="age" required>
                                        </div>

                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Gender</label>
                                            <select name="gender" class="form-control field_input_bg" required>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Phone</label>
                                            <input type="tel" class="form-control field_input_bg" name="contact" required>
                                        </div>

                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Address</label>
                                            <textarea class="form-control field_input_bg" name="address" rows="4" required></textarea>
                                        </div>

                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt">Medical History</label>
                                            <textarea class="form-control field_input_bg" name="medical_history" rows="4" required></textarea>
                                        </div>

                                        <div class="col-md-12 mt-5">
                                            <div class="row last_back_submit d-flex flex-row justify-content-between px-3">
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
        new DataTable('#example1');
    </script>

</div>
