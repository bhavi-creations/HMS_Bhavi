 


<?php
ob_start(); // Start output buffering
?>

<div id="wrapper">
    <?php
    include "../../../includes/sidebar.php";
    include "../../../includes/header.php";
    include "../../../config/db.php";

    // Fetch OPD Patients
    $opd_stmt = $pdo->prepare("SELECT * FROM patients_opd WHERE admission_type = 'OPD'");
    $opd_stmt->execute();
    $opd_patients = $opd_stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <div id="content">
            <h1 class="text-center mb-5"><strong>OPD Patient Details</strong></h1>

            <!-- Search Input -->
            <div class="container mb-3">
                <input type="text" id="patientSearch" class="form-control" placeholder="Search patients">
            </div>

            <!-- OPD Patients Table -->
            <div class="container">
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="patientTableBody">
                        <?php foreach ($opd_patients as $patient): ?>
                            <tr class="text-center patient-row" id="patient-row-<?php echo $patient['id']; ?>">
                                <td class="serial-number"></td> <!-- Serial number will be set dynamically -->
                                <td><?php echo htmlspecialchars($patient['name']); ?></td>
                                <td><?php echo htmlspecialchars($patient['age']); ?></td>
                                <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                <td><?php echo htmlspecialchars($patient['doctor']); ?></td>
                                <td><?php echo htmlspecialchars($patient['contact']); ?></td>
                                <td><?php echo htmlspecialchars($patient['address']); ?></td>
                                <td><?php echo htmlspecialchars($patient['medical_history']); ?></td>
                                <td>#<?php echo htmlspecialchars($patient['id']); ?></td> 

                                <td>
                                    <div class="dropdown">
                                        <p class="see_more_actions" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            . . .
                                        </p>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a class="dropdown-item" href="add_ipd.php?id=<?php echo $patient['id']; ?>">
                                                    <i class="fa-solid fa-plus"></i> Add to IPD
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="edit_patient.php?id=<?php echo urlencode($patient['id']); ?>"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>
                                            <li>
                                                <!-- <a class="dropdown-item" href="view_patient.php?id=<?php echo $patient['id']; ?>"><i class="fa-regular fa-eye"></i> View Details</a> -->
                                                <a class="dropdown-item"  href="view_patient.php?id=<?php echo urlencode($patient['id']); ?>"><i class="fa-regular fa-eye"></i> View Details</a>
                                            
                                            </li>
                                            <li>
                                                <a class="dropdown-item delete-patient"
                                                    href="delete_patient.php?id=<?php echo $patient['id']; ?>"
                                                    data-id="<?php echo $patient['id']; ?>">
                                                    <i class="fa-solid fa-trash-can"></i> Delete
                                                </a>
                                            </li>
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

        updateSerialNumbers(); // Update serial numbers after filtering
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
                            const row = document.getElementById(`patient-row-${patientId}`);
                            row.remove(); // Remove the row
                            updateSerialNumbers(); // Update serial numbers
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

<?php
ob_end_flush(); // Flush the output buffer
?>