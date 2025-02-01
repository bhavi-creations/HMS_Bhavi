<?php
ob_start(); // Start output buffering
?>


 

<div id="wrapper">

    <?php
    include "includes/sidebar.php";
    include "includes/header.php";
    include "config/db.php";

    // Initialize variables
    $doctorSearchQuery = isset($_POST['doctor_search']) ? trim($_POST['doctor_search']) : '';
    $nurseSearchQuery = isset($_POST['nurse_search']) ? trim($_POST['nurse_search']) : '';
    $activeTab = $_POST['active_tab'] ?? 'adddoctor'; // Default to 'adddoctor'

    // Fetch doctors based on search query
    try {
        $doctorsQuery = "SELECT * FROM doctors WHERE status = 1";
        if (!empty($doctorSearchQuery)) {
            $doctorsQuery .= " AND (name LIKE :search OR email LIKE :search OR specialization LIKE :search)";
        }
        $stmt = $pdo->prepare($doctorsQuery);
        if (!empty($doctorSearchQuery)) {
            $stmt->bindValue(':search', "%$doctorSearchQuery%", PDO::PARAM_STR);
        }
        $stmt->execute();
        $doctorsResult = $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Error fetching doctors: " . $e->getMessage());
    }

    // Fetch nurses based on search query
    try {
        $nursesQuery = "SELECT * FROM nurses WHERE status = 1";
        if (!empty($nurseSearchQuery)) {
            $nursesQuery .= " AND (name LIKE :search OR email LIKE :search OR department LIKE :search)";
        }
        $stmt = $pdo->prepare($nursesQuery);
        if (!empty($nurseSearchQuery)) {
            $stmt->bindValue(':search', "%$nurseSearchQuery%", PDO::PARAM_STR);
        }
        $stmt->execute();
        $nursesResult = $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Error fetching nurses: " . $e->getMessage());
    }
    ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <div id="content">
            <div class="container branch_container">
                <div class="row">
                    <div class="col-md-4 col-lg-2 ul_border mb-4">
                        <ul class="ul_style">
                            <li id="adddoctor" class=" add_staff_list_detils open_table<?= $activeTab === 'adddoctor' ? 'active' : ''; ?>">Doctor Details</li>
                            <li id="addnurse" class="add_staff_list_detils open_table <?= $activeTab === 'addnurse' ? 'active' : ''; ?>">Nurse Details</li>
                        </ul>
                    </div>

                    <div class="col-md-11 col-lg-12 ul_border">
                        <!-- Doctor Table -->
                        <div id="adddoctorTable" class="table-container <?= $activeTab === 'adddoctor' ? 'active' : ''; ?>">
                            <div class="container">
                                <form method="POST" action="">
                                    <input type="hidden" name="active_tab" value="adddoctor">
                                    <div class="row d-flex flex-row justify-content-between pt-4 pb-3">
                                        <div>
                                            <h6 class="staff_dtls">Our Doctors</h6>
                                        </div>
                                        <div class="d-flex">
                                            <input type="text" name="doctor_search" class="form-control"
                                                placeholder="Search Doctors..."
                                                value="<?= htmlspecialchars($doctorSearchQuery); ?>">
                                            <button type="submit" class="btn btn-primary ms-2">Search</button>
                                        </div>
                                    </div>
                                </form>
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
                                <tbody>
                                    <?php if (!empty($doctorsResult)): ?>
                                        <?php $serial = 1; ?>
                                        <?php foreach ($doctorsResult as $row): ?>
                                            <tr>
                                                <td><?= $serial++; ?></td>
                                                <td><?= htmlspecialchars($row['name']); ?></td>
                                                <td><?= htmlspecialchars($row['specialization']); ?></td>
                                                <td><?= htmlspecialchars($row['email']); ?></td>
                                                <td>
                                                    <?php if (!empty($row['photo'])): ?>
                                                        <img src="assets/uploads/doctors/<?= htmlspecialchars($row['photo']); ?>"
                                                            alt="Photo" width="50" height="50">
                                                    <?php else: ?>
                                                        <span>No photo available</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($row['availability_schedule']); ?></td>
                                                <td>
                                                    <a href="edit_staff.php?id=<?= $row['id']; ?>&type=doctor"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="delete_staff.php?id=<?= $row['id']; ?>&type=doctor"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No doctors found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Nurse Table -->
                        <div id="addnurseTable" class="table-container <?= $activeTab === 'addnurse' ? 'active' : ''; ?>">
                            <div class="container">
                                <form method="POST" action="">
                                    <input type="hidden" name="active_tab" value="addnurse">
                                    <div class="row d-flex flex-row justify-content-between pt-4 pb-3">
                                        <div>
                                            <h6 class="staff_dtls">Our Nurses</h6>
                                        </div>
                                        <div class="d-flex">
                                            <input type="text" name="nurse_search" class="form-control"
                                                placeholder="Search Nurses..."
                                                value="<?= htmlspecialchars($nurseSearchQuery); ?>">
                                            <button type="submit" class="btn btn-primary  ms-2">Search</button>
                                        </div>
                                    </div>
                                </form>
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
                                <tbody>
                                    <?php if (!empty($nursesResult)): ?>
                                        <?php $serial = 1; ?>
                                        <?php foreach ($nursesResult as $row): ?>
                                            <tr>
                                                <td><?= $serial++; ?></td>
                                                <td><?= htmlspecialchars($row['name']); ?></td>
                                                <td><?= htmlspecialchars($row['department']); ?></td>
                                                <td><?= htmlspecialchars($row['email']); ?></td>
                                                <td>
                                                    <?php if (!empty($row['photo'])): ?>
                                                        <img src="assets/uploads/nurses/<?= htmlspecialchars($row['photo']); ?>"
                                                            alt="Photo" width="50" height="50">
                                                    <?php else: ?>
                                                        <span>No photo available</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($row['availability_schedule']); ?></td>
                                                <td>
                                                    <a href="edit_staff.php?id=<?= $row['id']; ?>&type=nurse"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="delete_staff.php?id=<?= $row['id']; ?>&type=nurse"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this nurse?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No nurses found</td>
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
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.open_table');
        const tables = document.querySelectorAll('.table-container');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                tables.forEach(table => table.classList.remove('active'));

                this.classList.add('active');
                const tableId = this.id + 'Table';
                document.getElementById(tableId).classList.add('active');
            });
        });
    });
</script>