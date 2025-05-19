<?php include "../../../includes/header.php"; ?>

<div id="wrapper">

    <?php
    include '../../../includes/sidebar.php';

    // Fetch IPD Patients with Details
    $ipd_stmt = $pdo->prepare("SELECT pi.*, po.name AS opd_name, po.contact AS opd_contact
                               FROM patients_ipd pi
                               LEFT JOIN patients_opd po ON pi.opd_casualty_id = po.id");
    $ipd_stmt->execute();
    $ipd_patients = $ipd_stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">

        <?php include '../../../includes/navbar.php'; ?>

        <div id="content">
            <h1 class="text-center mb-5"><strong>IPD Patient Details</strong></h1>

            <div class="container mb-3">
                <input type="text" id="patientSearch" class="form-control" placeholder="Search IPD patients">
            </div>

            <div class="container">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>S.no</th>
                            <th>IPD ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Doctor</th>
                            <th>Contact</th>
                            <th>Ward</th>
                            <th>Bed</th>
                            <th>Admission Date</th>
                            <th>Discharge Date</th>
                            <th>Discharge Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="patientTableBody">
                        <?php foreach ($ipd_patients as $patient): ?>
                            <tr class="text-center patient-row" id="patient-row-<?php echo htmlspecialchars($patient['ipd_id']); ?>">
                                <td class="serial-number"></td>
                                <td><?php echo htmlspecialchars($patient['ipd_id']); ?></td>
                                <td><?php echo htmlspecialchars($patient['name']); ?></td>
                                <td><?php echo htmlspecialchars($patient['age']); ?></td>
                                <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                <td><?php echo htmlspecialchars($patient['doctor']); ?></td>
                                <td><?php echo htmlspecialchars($patient['contact']); ?></td>
                                <td><?php echo htmlspecialchars($patient['ward_number']); ?></td>
                                <td><?php echo htmlspecialchars($patient['bed_number']); ?></td>
                                <td><?php echo htmlspecialchars($patient['admission_date']); ?></td>
                                <td><?php echo htmlspecialchars($patient['discharge_date']); ?></td>
                                <td><?php echo htmlspecialchars($patient['discharge_status']); ?></td>
                                <td>
                                    <div class="dropdown">
                                        <p class="see_more_actions" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            . . .
                                        </p>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a class="dropdown-item" href="view_ipd_patient.php?ipd_id=<?php echo urlencode($patient['ipd_id']); ?>">
                                                    <i class="fa-regular fa-eye"></i> View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="edit_ipd_patient.php?ipd_id=<?php echo urlencode($patient['ipd_id']); ?>">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item discharge-patient"
                                                   href="discharge_patient.php?ipd_id=<?php echo urlencode($patient['ipd_id']); ?>"
                                                   data-ipd-id="<?php echo htmlspecialchars($patient['ipd_id']); ?>">
                                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Discharge
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item delete-patient"
                                                   href="delete_ipd_patient.php?ipd_id=<?php echo urlencode($patient['ipd_id']); ?>"
                                                   data-ipd-id="<?php echo htmlspecialchars($patient['ipd_id']); ?>">
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
            serialCell.textContent = index + 1;
        });
    }

    updateSerialNumbers();

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

        updateSerialNumbers();
    });

    // Delete IPD patient functionality with AJAX
    document.querySelectorAll(".delete-patient").forEach(button => {
        button.addEventListener("click", function(e) {
            e.preventDefault();

            if (confirm("Are you sure you want to delete this IPD record?")) {
                const ipdId = this.getAttribute("data-ipd-id");

                fetch('delete_ipd_patient.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            ipd_id: ipdId
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            const row = document.getElementById(`patient-row-${ipdId}`);
                            row.remove();
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

    // Discharge IPD patient functionality (you'll need to create discharge_patient.php)
    document.querySelectorAll(".discharge-patient").forEach(button => {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            const ipdId = this.getAttribute("data-ipd-id");
            window.location.href = `discharge_patient.php?ipd_id=${encodeURIComponent(ipdId)}`;
            // You might want to implement an AJAX-based discharge process as well
        });
    });
</script>

 