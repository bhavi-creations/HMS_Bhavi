<?php include "../../../includes/header.php"; ?>

<div id="wrapper">

    <?php
    include '../../../includes/sidebar.php';



    // Fetch Casualty Patients
    $casualty_stmt = $pdo->prepare("SELECT * FROM patients_opd WHERE admission_type = 'Casualty'");
    $casualty_stmt->execute();
    $casualty_patients = $casualty_stmt->fetchAll(PDO::FETCH_ASSOC);


    
    // Fetch all IPD patient IDs for quick checking
    $ipd_ids_stmt = $pdo->prepare("SELECT opd_casualty_id FROM patients_ipd");
    $ipd_ids_stmt->execute();
    $admitted_ipd_patient_ids = $ipd_ids_stmt->fetchAll(PDO::FETCH_COLUMN);
    ?>


    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <?php include '../../../includes/navbar.php'; ?>


        <div id="content">
            <h1 class="text-center mb-5"><strong>Casualty Patient Details</strong></h1>

            <!-- Search Input -->
            <div class="container mb-3">
                <input type="text" id="patientSearch" class="form-control" placeholder="Search patients">
            </div>

            <!-- Casualty Patients Table -->
            <div class="container  scroll_bar_y">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>S.no</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Doctor</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Medical History</th>
                            <th>Patient ID</th>
                            <!-- <th>Reports</th> -->
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="patientTableBody">
                        <?php foreach ($casualty_patients as $patient): ?>
                            <tr class="text-center patient-row" id="patient-row-<?php echo $patient['id']; ?>">
                                <td class="serial-number"></td>

                                <td><?php echo htmlspecialchars($patient['name']); ?></td>
                                <td><?php echo htmlspecialchars($patient['age']); ?></td>
                                <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                <td><?php echo htmlspecialchars($patient['doctor']); ?></td>
                                <td><?php echo htmlspecialchars($patient['contact']); ?></td>
                                <td><?php echo htmlspecialchars($patient['address']); ?></td>
                                <td><?php echo htmlspecialchars($patient['medical_history']); ?></td>
                                <td>#<?php echo htmlspecialchars($patient['id']); ?></td>
                                <!-- <td>
                                    <?php
                                    if (!empty($patient['reports'])) {
                                        $reports = explode(',', $patient['reports']);
                                        foreach ($reports as $report) {
                                            $fileName = basename($report);
                                            echo "<a href='" . htmlspecialchars($report) . "' download>" . htmlspecialchars($fileName) . "</a><br>";
                                        }
                                    } else {
                                        echo "No Reports";
                                    }
                                    ?>
                                </td> -->


                                <td class="text-center">
                                    <div class="dropdown">
                                        <p class="see_more_actions" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            . . .
                                        </p>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                            <li><a class="dropdown-item" href="edit_patient.php?id=<?php echo urlencode($patient['id']); ?>"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>
                                            <li>
                                                <a class="dropdown-item" href="view_patient.php?id=<?php echo urlencode($patient['id']); ?>"><i class="fa-regular fa-eye"></i> View Details</a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item delete-patient"
                                                    href="delete_patient.php?id=<?php echo $patient['id']; ?>"
                                                    data-id="<?php echo $patient['id']; ?>">
                                                    <i class="fa-solid fa-trash-can"></i> Delete
                                                </a>
                                            </li>

                                            <div>
                                                <?php if (in_array($patient['id'], $admitted_ipd_patient_ids)): ?>
                                                    <button class="viwe_btns  dropdown-item  viwe_btns_admit  " disabled>
                                                        <i class="fa-solid fa-bed"></i> <strong> Admitted to IPD</strong>
                                                    </button>
                                                    <?php
                                                    // You can add a link to view their IPD details if needed
                                                    $ipd_details_stmt = $pdo->prepare("SELECT ipd_id FROM patients_ipd WHERE opd_casualty_id = :opd_id");
                                                    $ipd_details_stmt->execute([':opd_id' => $patient['id']]);
                                                    $ipd_details = $ipd_details_stmt->fetch(PDO::FETCH_ASSOC);
                                                    if ($ipd_details):
                                                    ?>
                                                        <a href="ipd.php?ipd_id=<?php echo urlencode($ipd_details['ipd_id']); ?>" class="btn btn-sm btn-info  dropdown-item ">
                                                            <i class="fa-regular  fa-eye"></i> View IPD
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <form method="post" action="process_ip_admission.php">
                                                        <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
                                                        <button type="submit" name="admit_to_ip" class="viwe_btns dropdown-item viwe_btns_admit  "><i class="fa-solid fa-bed"></i> Admit to IPD</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function updateSerialNumbers() {
        const rows = document.querySelectorAll("#patientTableBody .patient-row");
        rows.forEach((row, index) => {
            const serialCell = row.querySelector(".serial-number");
            serialCell.textContent = index + 1; // Update serial number based on index
        });
    }

    // Initial call to set serial numbers
    updateSerialNumbers();
    // Search functionality for filtering table rows
    document.getElementById("patientSearch").addEventListener("input", function() {
        let filter = this.value.toLowerCase();
        let rows = document.getElementById("patientTableBody").getElementsByTagName("tr");

        Array.from(rows).forEach(row => {
            let cells = row.getElementsByTagName("td");
            let match = false;

            Array.from(cells).forEach(cell => {
                if (cell.textContent.toLowerCase().includes(filter)) {
                    match = true;
                }
            });

            row.style.display = match ? "" : "none";
        });
    });

    // Delete patient functionality with AJAX
    document.querySelectorAll(".delete-patient").forEach(button => {
        button.addEventListener("click", function(e) {
            e.preventDefault();

            if (confirm("Are you sure you want to delete this patient?")) {
                const patientId = this.getAttribute("data-id");

                fetch('delete_patient.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: patientId
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            document.getElementById(`patient-row-${patientId}`).remove();
                            updateSerialNumbers();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        alert('An error occurred while processing the request.');
                        console.error('Error:', error);
                    });
            }
        });
    });
</script>