<?php
ob_start(); // Start output buffering
?>

<div id="wrapper">

    <?php
    include '../../../includes/sidebar.php';
    include "../../../includes/header.php";
    include "../../../config/db.php";

    // Fetch all doctors
    try {
        $doctorsQuery = "SELECT * FROM doctors WHERE status = 1";  // Ensure the 'doctors' table and columns exist
        $doctorsResult = $pdo->query($doctorsQuery);
    } catch (PDOException $e) {
        die("Error fetching doctors: " . $e->getMessage());
    }

    try {
        $nursesQuery = "SELECT * FROM nurses WHERE status = 1"; // Ensure the 'nurses' table and columns exist
        $nursesResult = $pdo->query($nursesQuery);
    } catch (PDOException $e) {
        die("Error fetching nurses: " . $e->getMessage());
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
                        if (isset($_GET['upload_success']) && $_GET['upload_success'] === 'incharge') {
                            $activeTable = 'addnurseTable';
                            $activeListItem = 'incharges';
                        }
                        ?>

                        <ul class="ul_style">
                            <li id="adddoctor" class="add_staff_list_detils open_table">Doctor Details</li>
                            <li id="addnurse" class="add_incharge_list_detils open_table"> Nurse Details</li>
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
                                <div class="row  pt-4 pb-3">
                                    <div class="col">
                                        <h6 class="staff_dtls">Our Doctor </h6>
                                    </div>
                                    <div class="col">
                                        <input type="text" id="doctorSearch" class="form-control  " placeholder="Search doctors">
                                    </div>
                                </div>
                            </div>

                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Specialization</th>
                                        <th>Email</th>
                                        <th>Photo</th>
                                        <th>Availability</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="doctorTableBody">
                                    <?php if ($doctorsResult->rowCount() > 0): ?>
                                        <?php $serial = 1; ?> <!-- Initialize serial number -->
                                        <?php foreach ($doctorsResult as $row): ?>
                                            <tr>
                                                <td><?= $serial++; ?></td>
                                                <td><?= htmlspecialchars($row['name']); ?></td>
                                                <td><?= htmlspecialchars($row['specialization']); ?></td>
                                                <td><?= htmlspecialchars($row['email']); ?></td>
                                                <td>
                                                    <?php if (!empty($row['photo'])): ?>
                                                        <img src="../../../assets/uploads/doctors/<?= htmlspecialchars($row['photo']); ?>" alt="Photo" width="50" height="50">
                                                    <?php else: ?>
                                                        <span>No photo available</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($row['availability_schedule']); ?></td>
                                                <td>
                                                    <a href="./edit_staff.php?id=<?= $row['id']; ?>&type=doctor" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="./delete_staff.php?id=<?= $row['id']; ?>&type=doctor" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No staff members found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                        </div>

                        <div id="addnurseTable" class="table-container <?= $activeTable == 'addnurseTable' ? 'active' : '' ?>">
                            <div class="container">
                                <div class="row d-flex flex-row justify-content-between pt-4 pb-3">
                                    <div class="col">
                                        <h6 class="staff_dtls">Our Nurse </h6>
                                    </div>
                                    <div class="col">
                                        <input type="text" id="nurseSearch" class="form-control" placeholder="Search nurses">
                                    </div>
                                </div>
                            </div>

                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Email</th>
                                        <th>Photo</th>
                                        <th>Availability</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="nurseTableBody">
                                    <?php if ($nursesResult->rowCount() > 0): ?>
                                        <?php $serial = 1; ?> <!-- Initialize serial number -->
                                        <?php foreach ($nursesResult as $row): ?>
                                            <tr>
                                                <td><?= $serial++; ?></td>
                                                <td><?= htmlspecialchars($row['name']); ?></td>
                                                <td><?= htmlspecialchars($row['department']); ?></td>
                                                <td><?= htmlspecialchars($row['email']); ?></td>
                                                <td>
                                                    <?php if (!empty($row['photo'])): ?>
                                                        <img src="../../../assets/uploads/nurses/<?= htmlspecialchars($row['photo']); ?>" alt="Photo" width="50" height="50">
                                                    <?php else: ?>
                                                        <span>No photo available</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($row['availability_schedule']); ?></td>
                                                <td>
                                                    <a href="./edit_staff.php?id=<?= $row['id']; ?>&type=nurse" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="./delete_staff.php?id=<?= $row['id']; ?>&type=nurse" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this nurse?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No staff members found</td>
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
    // Search functionality for doctors
    document.getElementById("doctorSearch").addEventListener("input", function() {
        let filter = this.value.toLowerCase();
        let rows = document.getElementById("doctorTableBody").getElementsByTagName("tr");

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

    // Search functionality for nurses
    document.getElementById("nurseSearch").addEventListener("input", function() {
        let filter = this.value.toLowerCase();
        let rows = document.getElementById("nurseTableBody").getElementsByTagName("tr");

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