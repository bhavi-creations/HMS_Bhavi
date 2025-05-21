<?php
include "../../../includes/header.php";
?>

<div id="wrapper">
    <?php include '../../../includes/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <?php include '../../../includes/navbar.php'; ?>
        <h1 class="text-center mb-4"><strong>Manage Beds</strong></h1>

        <div id="content" class="py-4">

            <?php
            // Fetch beds with ward and patient details
            $stmt = $pdo->query("
    SELECT 
        beds.*, 
        wards.ward_name, 
        patients_ipd.name AS patient_name
    FROM beds
    JOIN wards ON beds.ward_id = wards.id
    LEFT JOIN patients_ipd ON beds.assigned_to_patient_id = patients_ipd.ipd_id
    WHERE beds.is_deleted = 0
    ORDER BY wards.ward_name ASC, beds.bed_number ASC
");
            $beds = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th style="width:5%;">S.No</th>
                            <th>Ward Name</th>
                            <th>Bed Number</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Assigned Patient ID</th>
                            <th>Assigned Patient Name</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($beds) > 0): ?>
                            <?php $serial = 1; ?>
                            <?php foreach ($beds as $bed): ?>
                                <tr>
                                    <td><?= $serial++ ?></td>
                                    <td class="text-start"><?= htmlspecialchars($bed['ward_name']) ?></td>
                                    <td><?= htmlspecialchars($bed['bed_number']) ?></td>
                                    <td><?= htmlspecialchars($bed['gender']) ?></td>
                                    <td>
                                        <?php
                                        if ($bed['status'] == 'Occupied') {
                                            echo '<span class="badge bg-danger">Occupied</span>';
                                        } elseif ($bed['status'] == 'Available') {
                                            echo '<span class="badge bg-success">Available</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">' . htmlspecialchars($bed['status']) . '</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?= $bed['assigned_to_patient_id'] ? htmlspecialchars($bed['assigned_to_patient_id']) : '-' ?>
                                    </td>
                                    <td>
                                        <?= $bed['patient_name'] ? htmlspecialchars($bed['patient_name']) : '-' ?>
                                    </td>
                                    <td>
                                        <a href="edit_bed.php?id=<?= $bed['id'] ?>" class="btn btn-warning btn-sm me-2">Edit</a>
                                        <a href="delete_bed.php?id=<?= $bed['id'] ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this bed?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No beds found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>