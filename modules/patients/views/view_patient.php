<?php
ob_start(); // Start output buffering  
?>

<div id="wrapper">
    <?php


    include "../../../includes/sidebar.php";
    include "../../../includes/header.php";
    include "../../../config/db.php";

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        // $patient_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $patient_id = $_GET['id'];

        if ($patient_id) {
            // Fetch patient details
            $stmt = $pdo->prepare("SELECT * FROM patients_opd WHERE id = :id");
            $stmt->execute([':id' => $patient_id]);
            $patient = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($patient) {
    ?>
                <div id="content-wrapper" class="d-flex flex-column bg-white">
                    <div id="content">
                        <h1 class="text-center mb-5"><strong>Patient Details</strong></h1>

                        <div class="d-flex justify-content-between px-5 mb-3">
                            <div>
                                <a href="javascript:history.back()" class=" viwe_btns viwe_btns_back mt-3"><i class="fa-solid fa-arrow-left"></i> Back</a>
                            </div>
                            <div>
                                <a href="edit_patient.php?id=<?php echo $patient['id']; ?>" class=" viwe_btns viwe_btns_edit mt-3"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="d-flex justify-content-between">
                                <!-- Patient Details Card -->
                                <div class="card flex-grow-1 me-2" style="border-radius: 10px;">
                                    <div class="card-header text-black   text-center">
                                        <h4>Patient Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Patient ID</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>

                                            <div class="col-sm-8">#<?php echo htmlspecialchars($patient['id']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Name</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"> <?php echo htmlspecialchars($patient['name']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Age</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"> <?php echo htmlspecialchars($patient['age']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Father/Guardian</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"> <?php echo htmlspecialchars($patient['guardian_name']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Gender</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"> <?php echo htmlspecialchars($patient['gender']); ?></div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Contact</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"> <?php echo htmlspecialchars($patient['contact']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Whatsapp</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"> <?php echo htmlspecialchars($patient['whatsapp_number']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Address</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"> <?php echo htmlspecialchars($patient['address']); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Details Card -->
                                <div class="card flex-grow-1" style="border-radius: 10px;">
                                    <div class="card-header text-black  text-center">
                                        <h4>Additional Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Doctor</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"><?php echo htmlspecialchars($patient['doctor']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Problem </div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"><?php echo htmlspecialchars($patient['problem']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Referred By </div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"><?php echo htmlspecialchars($patient['referred_by']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Remarks </div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"><?php echo htmlspecialchars($patient['remarks']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Medical History</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"><?php echo htmlspecialchars($patient['medical_history']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Admission Type</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"><?php echo htmlspecialchars($patient['admission_type']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Reports</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8">


                                                <?php
                                                if (!empty($patient['reports'])) {
                                                    $reports = explode(',', $patient['reports']);
                                                    foreach ($reports as $report) {
                                                        $fileName = basename($report);
                                                        echo "<a class='download_decration' href='" . htmlspecialchars($report) . "' download>" . htmlspecialchars($fileName) . " <i class='fa fa-download download-icon'></i></a><br>";
                                                    }
                                                } else {
                                                    echo "No Reports";
                                                }
                                                ?>
                                                <style>
                                                    .download_decration {
                                                        text-decoration: none;
                                                        color: #000;
                                                    }

                                                    .download_decration:hover {
                                                        color: rgb(10, 200, 26);
                                                        text-decoration: none;

                                                    }

                                                    .download-icon {
                                                        color: rgb(10, 200, 26);
                                                    }

                                                    .download-icon:hover {
                                                        color: rgb(10, 200, 26);
                                                    }
                                                </style>
                                            </div>

                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Created At </div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"><?php echo htmlspecialchars($patient['created_at']); ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Updated At </div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8"> <?php echo htmlspecialchars($patient['updated_at']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="container-fluid my-2">
 
                            <div class="card flex-grow-1 me-2 " style="border-radius: 10px;">
                                <div class="card-header text-black  text-center">
                                    <h4> Billing Details</h4>
                                </div>

                                <div class="px-4 py-3">
                                    <div class="row mb-3 d-flex flex-row justify-content-between">
                                        <div class="col-sm-8 font-weight-bold">Fee</div>
                                        <div class="col-sm-1 font-weight-bold">:</div>
                                        <div class="col-sm-3 d-flex justify-content-end"><?php echo htmlspecialchars($patient['fee']); ?>/-</div>
                                    </div>
                                    <div class="row mb-3 d-flex flex-row justify-content-between">
                                        <div class="col-sm-8 font-weight-bold">Discount (%)</div>
                                        <div class="col-sm-1 font-weight-bold">:</div>
                                        <div class="col-sm-3 d-flex justify-content-end"><?php echo htmlspecialchars($patient['discount']); ?>/-</div>
                                    </div>
                                    <div class="row mb-3 d-flex flex-row justify-content-between">
                                        <div class="col-sm-8 font-weight-bold">Final Amount</div>
                                        <div class="col-sm-1 font-weight-bold">:</div>
                                        <div class="col-sm-3 d-flex justify-content-end"><?php echo htmlspecialchars($patient['final_fee']); ?>/-</div>
                                    </div>
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

                                            echo "<div class='col-md-3 my-5'>";

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
                </div>
</div>
<?php
            } else {
                echo "<div class='alert alert-danger text-center'>Patient not found.</div>";
            }
        } else {
            echo "<div class='alert alert-danger text-center'>Invalid Patient ID.</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>Patient ID is required.</div>";
    }

?>
</div>
<script>
    function printPatientDetails() {
        window.print();
    }
</script>

<?php
ob_end_flush(); // Flush the output buffer
?>