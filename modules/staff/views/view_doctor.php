<?php
ob_start(); // Start output buffering  
?>

<div id="wrapper">
    <?php
    include "../../../includes/sidebar.php";
    include "../../../includes/header.php";
    include "../../../config/db.php";


    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $doctor_id = $_GET['id'];

        $stmt = $pdo->prepare("SELECT * FROM doctors_list WHERE doctor_id = :doctor_id");
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_STR);
        $stmt->execute();

        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doctor) {

    ?>

            <div id="content-wrapper" class="d-flex flex-column bg-white">
                <div id="content">
                    <h1 class="text-center mb-5"><strong>Doctor Details</strong></h1>
                    <div class="container-fluid">
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-header text-black text-center">
                                <h4>Doctor Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3 font-weight-bold">Doctor ID</div>
                                    <div class="col-sm-1 font-weight-bold"> : </div>
                                    <div class="col-sm-8">#<?php echo htmlspecialchars($doctor['doctor_id']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3 font-weight-bold">Name</div>
                                    <div class="col-sm-1 font-weight-bold"> : </div>
                                    <div class="col-sm-8"> <?php echo htmlspecialchars($doctor['doctor_name']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3 font-weight-bold">Gender</div>
                                    <div class="col-sm-1 font-weight-bold"> : </div>
                                    <div class="col-sm-8"> <?php echo htmlspecialchars($doctor['gender']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3 font-weight-bold">Date of Birth</div>
                                    <div class="col-sm-1 font-weight-bold"> : </div>
                                    <div class="col-sm-8"> <?php echo htmlspecialchars($doctor['dob']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3 font-weight-bold">Specialization</div>
                                    <div class="col-sm-1 font-weight-bold"> : </div>
                                    <div class="col-sm-8"> <?php echo htmlspecialchars($doctor['specialization']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3 font-weight-bold">Qualification</div>
                                    <div class="col-sm-1 font-weight-bold"> : </div>
                                    <div class="col-sm-8"> <?php echo htmlspecialchars($doctor['qualification']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3 font-weight-bold">Experience</div>
                                    <div class="col-sm-1 font-weight-bold"> : </div>
                                    <div class="col-sm-8"> <?php echo htmlspecialchars($doctor['experience']); ?> years</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3 font-weight-bold">Department</div>
                                    <div class="col-sm-1 font-weight-bold"> : </div>
                                    <div class="col-sm-8"> <?php echo htmlspecialchars($doctor['department']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3 font-weight-bold">Registration Number</div>
                                    <div class="col-sm-1 font-weight-bold"> : </div>
                                    <div class="col-sm-8"> <?php echo htmlspecialchars($doctor['registration_number']); ?></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
    <?php
        } else {
            echo "<div class='alert alert-danger text-center'>Doctor not found.</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>Valid Doctor ID is required.</div>";
    }
    ?>
</div>



<?php
ob_end_flush(); // Flush the output buffer
?>