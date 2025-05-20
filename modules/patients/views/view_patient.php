<?php include "../../../includes/header.php"; ?>

<div id="wrapper">
    <?php include '../../../includes/sidebar.php';

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $patient_id = $_GET['id'];

        if ($patient_id) {
            $stmt = $pdo->prepare("SELECT * FROM patients_opd WHERE id = :id");
            $stmt->execute([':id' => $patient_id]);
            $patient = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($patient) {
?>
                <div id="content-wrapper" class="d-flex flex-column bg-white">
                    <?php include '../../../includes/navbar.php'; ?>

                    <div id="content">
                        <h1 class="text-center mb-5"><strong>Patient Details</strong></h1>

                        <div class="d-flex justify-content-between px-5 mb-3">
                            <a href="javascript:history.back()" class="viwe_btns viwe_btns_back mt-3">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                            <a href="edit_patient.php?id=<?= $patient['id']; ?>" class="viwe_btns viwe_btns_edit mt-3">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                        </div>

                        <div class="container-fluid">
                            <div class="d-flex justify-content-between">
                                <!-- Patient Details Card -->
                                <div class="card flex-grow-1 me-2" style="border-radius: 10px;">
                                    <div class="card-header text-black text-center">
                                        <h4>Patient Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $fields = [
                                            'Patient ID' => '#' . $patient['id'],
                                            'Name' => $patient['name'],
                                            'Age' => $patient['age'],
                                            'Father/Guardian' => $patient['guardian_name'],
                                            'Gender' => $patient['gender'],
                                            'Contact' => $patient['contact'],
                                            'Whatsapp' => $patient['whatsapp_number'],
                                            'Address' => $patient['address'],
                                        ];

                                        foreach ($fields as $label => $value) {
                                            echo "<div class='row mb-3'>
                                                    <div class='col-sm-3 font-weight-bold'>{$label}</div>
                                                    <div class='col-sm-1 font-weight-bold'> : </div>
                                                    <div class='col-sm-8'>" . htmlspecialchars($value) . "</div>
                                                </div>";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <!-- Additional Details Card -->
                                <div class="card flex-grow-1" style="border-radius: 10px;">
                                    <div class="card-header text-black text-center">
                                        <h4>Additional Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $fields = [
                                            'Doctor' => $patient['doctor'],
                                            'Problem' => $patient['problem'],
                                            'Referred By' => $patient['referred_by'],
                                            'Remarks' => $patient['remarks'],
                                            'Medical History' => $patient['medical_history'],
                                            'Admission Type' => $patient['admission_type'],
                                            'Created At' => $patient['created_at'],
                                            'Updated At' => $patient['updated_at'],
                                        ];

                                        foreach ($fields as $label => $value) {
                                            echo "<div class='row mb-3'>
                                                    <div class='col-sm-3 font-weight-bold'>{$label}</div>
                                                    <div class='col-sm-1 font-weight-bold'> : </div>
                                                    <div class='col-sm-8'>" . htmlspecialchars($value) . "</div>
                                                </div>";
                                        }
                                        ?>

                                        <!-- Reports -->
                                        <div class="row mb-3">
                                            <div class="col-sm-3 font-weight-bold">Reports</div>
                                            <div class="col-sm-1 font-weight-bold"> : </div>
                                            <div class="col-sm-8">
                                                <?php
                                                if (!empty($patient['reports'])) {
                                                    $reports = explode(',', $patient['reports']);
                                                    foreach ($reports as $report) {
                                                        $fileName = basename($report);
                                                        $fileUrl = $baseurl . "/assets/uploads/patient_reports/" . rawurlencode($fileName);
                                                        echo "<a class='download_decration' href='" . htmlspecialchars($fileUrl) . "' download>" . htmlspecialchars($fileName) . " <i class='fa fa-download download-icon'></i></a><br>";
                                                    }
                                                } else {
                                                    echo "No Reports";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Billing -->
                        <div class="container-fluid my-2">
                            <div class="card flex-grow-1 me-2" style="border-radius: 10px;">
                                <div class="card-header text-black text-center">
                                    <h4>Billing Details</h4>
                                </div>
                                <div class="px-4 py-3">
                                    <?php
                                    $billingFields = [
                                        'Fee' => $patient['fee'],
                                        'Discount (%)' => $patient['discount'],
                                        'Final Amount' => $patient['final_fee'],
                                    ];
                                    foreach ($billingFields as $label => $value) {
                                        echo "<div class='row mb-3 d-flex flex-row justify-content-between'>
                                                <div class='col-sm-8 font-weight-bold'>{$label}</div>
                                                <div class='col-sm-1 font-weight-bold'>:</div>
                                                <div class='col-sm-3 d-flex justify-content-end'>" . htmlspecialchars($value) . "/-</div>
                                            </div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Report Previews -->
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
                                            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                            $fileUrl = $baseurl . "/assets/uploads/patient_reports/" . rawurlencode($fileName);
                                            $filePath = "../../../assets/uploads/patient_reports/" . $fileName;

                                            echo "<div class='col-md-4 mb-5'>";
                                            echo "<h6>$fileName</h6>";

                                            if ($fileExt === 'pdf') {
                                                echo "<embed src='" . htmlspecialchars($fileUrl) . "' type='application/pdf' width='100%' height='400px'/>";
                                            } elseif (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                echo "<img src='" . htmlspecialchars($fileUrl) . "' alt='$fileName' class='img-fluid' style='max-height: 400px;' />";
                                            } elseif ($fileExt === 'txt') {
                                                if (file_exists($filePath)) {
                                                    $content = file_get_contents($filePath);
                                                    echo "<pre style='height: 400px; overflow-y: auto; background-color: #f9f9f9; padding: 10px;'>" . htmlspecialchars($content) . "</pre>";
                                                } else {
                                                    echo "<p class='text-danger'>Text file not found.</p>";
                                                }
                                            } else {
                                                echo "<p class='text-muted'>Preview not supported.</p>";
                                            }

                                            echo "<div class='mt-2'><a class='btn btn-sm btn-outline-primary' href='" . htmlspecialchars($fileUrl) . "' download><i class='fa fa-download'></i> Download</a></div>";
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
