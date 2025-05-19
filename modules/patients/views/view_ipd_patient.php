<?php include "../../../includes/header.php"; ?>

<div id="wrapper">

    <?php include '../../../includes/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">

        <?php include '../../../includes/navbar.php'; ?>

        <div id="content">
            <h1 class="text-center mb-5"><strong>IPD Patient Details</strong></h1>
            <div class="container card shadow mb-4">
                <div class="row">
                    <?php
                    if (isset($_GET['ipd_id']) && !empty($_GET['ipd_id'])) {
                        $ipd_id = filter_var($_GET['ipd_id'], FILTER_SANITIZE_STRING);

                        try {
                            $stmt = $pdo->prepare("SELECT pi.*, po.name AS opd_name, po.contact AS opd_contact, po.address AS opd_address, po.medical_history AS opd_medical_history
                                                   FROM patients_ipd pi
                                                   LEFT JOIN patients_opd po ON pi.opd_casualty_id = po.id
                                                   WHERE pi.ipd_id = :ipd_id");
                            $stmt->execute([':ipd_id' => $ipd_id]);
                            $patient = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($patient) {
                    ?>
                                <div class="card-header py-3 mb-4">
                                    <h6 class="m-0 font-weight-bold text-primary">Patient Information (IPD ID: <?php echo htmlspecialchars($patient['ipd_id']); ?>)</h6>
                                </div>
                                <div class="row px-4">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($patient['name']); ?></p>
                                        <p><strong>Age:</strong> <?php echo htmlspecialchars($patient['age']); ?></p>
                                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender']); ?></p>
                                        <p><strong>Guardian Name:</strong> <?php echo htmlspecialchars($patient['guardian_name']); ?></p>
                                        <p><strong>Contact:</strong> <?php echo htmlspecialchars($patient['contact']); ?></p>
                                        <p><strong>WhatsApp Number:</strong> <?php echo htmlspecialchars($patient['whatsapp_number']); ?></p>
                                        <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($patient['address'])); ?></p>
                                        <p><strong>Problem:</strong> <?php echo htmlspecialchars($patient['problem']); ?></p>
                                           <p><strong>Doctor:</strong> <?php echo htmlspecialchars($patient['doctor']); ?></p>
                                        <p><strong>Referred By:</strong> <?php echo htmlspecialchars($patient['referred_by']); ?></p>
                                        <p><strong>Remarks:</strong> <?php echo nl2br(htmlspecialchars($patient['remarks'])); ?></p>
                                        <p><strong>Medical History (OPD):</strong> <?php echo nl2br(htmlspecialchars($patient['opd_medical_history'])); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Admission Date:</strong> <?php echo htmlspecialchars($patient['admission_date']); ?></p>
                                        <p><strong>Ward Number:</strong> <?php echo htmlspecialchars($patient['ward_number']); ?></p>
                                        <p><strong>Bed Number:</strong> <?php echo htmlspecialchars($patient['bed_number']); ?></p>
                                        <p><strong>Fee:</strong> ₹<?php echo htmlspecialchars(number_format($patient['fee'], 2)); ?></p>
                                        <p><strong>Discount:</strong> <?php echo htmlspecialchars($patient['discount']); ?>%</p>
                                        <p><strong>Final Fee:</strong> ₹<?php echo htmlspecialchars(number_format($patient['final_fee'], 2)); ?></p>
                                        <p><strong>Reports:</strong></p>
                                        <div>
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
                                        <p><strong>Discharge Date:</strong> <?php echo htmlspecialchars($patient['discharge_date'] ? $patient['discharge_date'] : 'N/A'); ?></p>
                                        <p><strong>Discharge Status:</strong> <?php echo htmlspecialchars($patient['discharge_status']); ?></p>
                                        <p><strong>Created At:</strong> <?php echo htmlspecialchars($patient['created_at']); ?></p>
                                        <p><strong>Updated At:</strong> <?php echo htmlspecialchars($patient['updated_at']); ?></p>
                                    </div>
                                </div>
                                <div class="my-4 px-4">
                                    <a href="ipd.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to IPD List</a>
                                    <a href="edit_ipd_patient.php?ipd_id=<?php echo urlencode($patient['ipd_id']); ?>" class="btn btn-primary ml-2"><i class="fa-solid fa-pen-to-square"></i> Edit IPD Info</a>
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
                    <?php
                            } else {
                                echo "<div class='alert alert-danger text-center'>Error: Invalid IPD ID.</div>";
                            }
                        } catch (PDOException $e) {
                            echo "<div class='alert alert-danger text-center'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-warning text-center'>No IPD ID provided. Please try again.</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

 