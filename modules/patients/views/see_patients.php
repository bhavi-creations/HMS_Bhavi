<?php
ob_start(); // Start output buffering
?>

<div id="wrapper">

    <?php
    include '../../../includes/sidebar.php';
    include "../../../includes/header.php";
    include "../../../config/db.php";

    // Fetch all patients
    try {
        $patientsQuery = "SELECT * FROM patients WHERE status = 1";  // Ensure the 'patients' table and columns exist
        $patientsResult = $pdo->query($patientsQuery);
    } catch (PDOException $e) {
        die("Error fetching patients: " . $e->getMessage());
    }
    ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <div id="content">
            <div class="container branch_container">
                <div class="row">
                    <div class="col-md-4 col-lg-2 ul_border mb-4">
                        <?php
                        $activeTable = 'adddoctorTable';
                        $activeListItem = 'details';
                        ?>

                        <ul class="ul_style">
                            <li id="adddoctor" class="add_staff_list_detils open_table">Patients Details</li>
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

                    <div class="col-md-11 col-lg-12 ul_border">

                        <div id="adddoctorTable" class="table-container <?= $activeTable == 'adddoctorTable' ? 'active' : '' ?>">
                            <div class="container">
                                <div class="row d-flex flex-row justify-content-between pt-4 pb-3">
                                    <div class="col">
                                        <h6 class="staff_dtls">Our Patients</h6>
                                    </div>
                                    <div class="col">
                                        <input type="text" id="patientSearch" class="form-control" placeholder="Search patients">
                                    </div>
                                </div>
                            </div>

                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Medical History</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="patientTableBody">
                                    <?php if ($patientsResult->rowCount() > 0): ?>
                                        <?php $serial = 1; ?> <!-- Initialize serial number -->
                                        <?php foreach ($patientsResult as $row): ?>
                                            <tr>
                                                <td><?= $serial++; ?></td>
                                                <td><?= htmlspecialchars($row['name']); ?></td>
                                                <td><?= htmlspecialchars($row['age']); ?></td>
                                                <td><?= htmlspecialchars($row['gender']); ?></td>
                                                <td><?= htmlspecialchars($row['contact']); ?></td>
                                                <td><?= htmlspecialchars($row['address']); ?></td>
                                                <td><?= htmlspecialchars($row['medical_history']); ?></td>
                                                <td>
                                                    <a href="./edit_patient.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="./delete_patient.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this patient?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No patients found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Search functionality for patients
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
</script>

<?php
ob_end_flush(); // End output buffering and flush the content
?>
