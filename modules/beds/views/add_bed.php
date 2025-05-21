<?php
include "../../../includes/header.php";
?>

<div id="wrapper">
    <?php include '../../../includes/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <?php include '../../../includes/navbar.php'; ?>
        <h1 class="text-center mb-4"><strong>Add / Manage Beds</strong></h1>
        <div id="content" class="py-4">

            <?php
            // ADD beds
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_beds'])) {
                $ward_id = $_POST['ward_id'] ?? null;
                $gender = $_POST['gender'] ?? null;
                $bed_count = intval($_POST['bed_count'] ?? 0);

                if ($ward_id && $gender && $bed_count > 0) {
                    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM beds WHERE ward_id = :ward_id AND gender = :gender");
                    $checkStmt->execute(['ward_id' => $ward_id, 'gender' => $gender]);
                    $exists = $checkStmt->fetchColumn();

                    if ($exists) {
                        echo "<div class='alert alert-warning'>Beds for this Ward and Gender already exist. You can edit them below.</div>";
                    } else {
                        $pdo->beginTransaction();
                        $insertStmt = $pdo->prepare("INSERT INTO beds (ward_id, bed_number, gender, status) VALUES (:ward_id, :bed_number, :gender, 'Available')");
                        for ($i = 1; $i <= $bed_count; $i++) {
                            $insertStmt->execute([
                                'ward_id' => $ward_id,
                                'bed_number' => $i,
                                'gender' => $gender,
                            ]);
                        }
                        $pdo->commit();
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    }
                } else {
                    echo "<div class='alert alert-danger'>Please fill all fields correctly.</div>";
                }
            }

            // EDIT bed count
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_beds'])) {
                $ward_id = $_POST['edit_ward_id'] ?? null;
                $gender = $_POST['edit_gender'] ?? null;
                $new_bed_count = intval($_POST['edit_bed_count'] ?? 0);

                if ($ward_id && $gender && $new_bed_count > 0) {
                    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM beds WHERE ward_id = :ward_id AND gender = :gender");
                    $countStmt->execute(['ward_id' => $ward_id, 'gender' => $gender]);
                    $current_count = $countStmt->fetchColumn();

                    if ($new_bed_count > $current_count) {
                        $pdo->beginTransaction();
                        $maxBedStmt = $pdo->prepare("SELECT MAX(CAST(bed_number AS UNSIGNED)) FROM beds WHERE ward_id = :ward_id AND gender = :gender");
                        $maxBedStmt->execute(['ward_id' => $ward_id, 'gender' => $gender]);
                        $max_bed = (int) $maxBedStmt->fetchColumn();
                        $insertStmt = $pdo->prepare("INSERT INTO beds (ward_id, bed_number, gender, status) VALUES (:ward_id, :bed_number, :gender, 'Available')");
                        for ($i = $max_bed + 1; $i <= $new_bed_count; $i++) {
                            $insertStmt->execute([
                                'ward_id' => $ward_id,
                                'bed_number' => $i,
                                'gender' => $gender,
                            ]);
                        }
                        $pdo->commit();
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    } elseif ($new_bed_count < $current_count) {
                        $bedsToRemove = $current_count - $new_bed_count;
                        $selectDelStmt = $pdo->prepare("SELECT id FROM beds WHERE ward_id = :ward_id AND gender = :gender AND status = 'Available' ORDER BY CAST(bed_number AS UNSIGNED) DESC LIMIT :limit");
                        $selectDelStmt->bindParam(':ward_id', $ward_id, PDO::PARAM_INT);
                        $selectDelStmt->bindParam(':gender', $gender, PDO::PARAM_STR);
                        $selectDelStmt->bindParam(':limit', $bedsToRemove, PDO::PARAM_INT);
                        $selectDelStmt->execute();
                        $bedsToDelete = $selectDelStmt->fetchAll(PDO::FETCH_COLUMN);

                        if (count($bedsToDelete) < $bedsToRemove) {
                            echo "<div class='alert alert-danger'>Cannot reduce beds. Some beds are currently occupied or under maintenance.</div>";
                        } else {
                            $pdo->beginTransaction();
                            $delStmt = $pdo->prepare("DELETE FROM beds WHERE id = :id");
                            foreach ($bedsToDelete as $bed_id) {
                                $delStmt->execute(['id' => $bed_id]);
                            }
                            $pdo->commit();
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit;
                        }
                    } else {
                        echo "<div class='alert alert-info'>No change in bed count.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Please fill all fields correctly.</div>";
                }
            }

            // DELETE beds by ward + gender
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_beds'])) {
                $ward_id = intval($_POST['delete_ward_id']);
                $gender = $_POST['delete_gender'];
                $stmt = $pdo->prepare("DELETE FROM beds WHERE ward_id = :ward_id AND gender = :gender AND status = 'Available'");
                $stmt->execute(['ward_id' => $ward_id, 'gender' => $gender]);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }

            // Fetch dropdown options
            $wardsStmt = $pdo->query("SELECT * FROM wards ORDER BY ward_name ASC");
            $wards = $wardsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch beds summary
            $bedsCounts = $pdo->query("
                SELECT w.id as ward_id, w.ward_name, b.gender, COUNT(b.id) as bed_count
                FROM beds b
                JOIN wards w ON b.ward_id = w.id
                GROUP BY w.id, b.gender
                ORDER BY w.ward_name, b.gender
            ")->fetchAll(PDO::FETCH_ASSOC);

            $wardTotals = [];
            foreach ($bedsCounts as $row) {
                $wardTotals[$row['ward_id']]['ward_name'] = $row['ward_name'];
                $wardTotals[$row['ward_id']]['genders'][$row['gender']] = $row['bed_count'];
                $wardTotals[$row['ward_id']]['total'] = ($wardTotals[$row['ward_id']]['total'] ?? 0) + $row['bed_count'];
            }
            ?>

            <h3>Add Beds for Ward & Gender</h3>
            <form method="POST" class="mb-5">
                <input type="hidden" name="add_beds" value="1">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="ward_id" class="form-label">Select Ward</label>
                        <select name="ward_id" id="ward_id" class="form-select" required>
                            <option value="">-- Select Ward --</option>
                            <?php foreach ($wards as $ward): ?>
                                <option value="<?= $ward['id'] ?>"><?= htmlspecialchars($ward['ward_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="gender" class="form-label">Select Gender</label>
                        <select name="gender" id="gender" class="form-select" required>
                            <option value="">-- Select Gender --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="bed_count" class="form-label">Number of Beds</label>
                        <input type="number" name="bed_count" id="bed_count" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Add Beds</button>
                    </div>
                </div>
            </form>

            <h3>Existing Beds Summary</h3>
            <table class="table table-bordered table-striped text-center mb-4">
                <thead class="table-light">
                    <tr>
                        <th>Ward Name</th>
                        <th>Male Beds</th>
                        <th>Female Beds</th>
                        <th>Total Beds</th>
                        <th>Edit Bed Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wardTotals as $ward_id => $data): ?>
                        <tr>
                            <td class="text-start"><?= htmlspecialchars($data['ward_name']) ?></td>
                            <td><?= $data['genders']['Male'] ?? 0 ?></td>
                            <td><?= $data['genders']['Female'] ?? 0 ?></td>
                            <td><?= $data['total'] ?></td>
                            <td>
                                <?php foreach (['Male', 'Female'] as $gender): ?>
                                    <?php if (!empty($data['genders'][$gender])): ?>
                                        <button class="btn btn-sm btn-warning mb-1"
                                            onclick="showEditForm(<?= $ward_id ?>, '<?= addslashes($data['ward_name']) ?>', '<?= $gender ?>', <?= $data['genders'][$gender] ?>)">
                                            Edit <?= $gender ?>
                                        </button><br>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?php foreach (['Male', 'Female'] as $gender): ?>
                                    <?php if (!empty($data['genders'][$gender])): ?>
                                        <form method="POST" style="display:inline-block;" onsubmit="return confirm('Delete all <?= $gender ?> beds for <?= htmlspecialchars(addslashes($data['ward_name'])) ?>?');">
                                            <input type="hidden" name="delete_beds" value="1">
                                            <input type="hidden" name="delete_ward_id" value="<?= $ward_id ?>">
                                            <input type="hidden" name="delete_gender" value="<?= $gender ?>">
                                            <button type="submit" class="btn btn-sm btn-danger mb-1">Delete <?= $gender ?> Beds</button><br>
                                        </form>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Edit Modal -->
            <div id="editBedModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); align-items:center; justify-content:center;">
                <div style="background:#fff; padding:20px; border-radius:5px; max-width:400px; margin:auto; position:relative;">
                    <h5>Edit Bed Count</h5>
                    <form method="POST" id="editBedForm">
                        <input type="hidden" name="edit_beds" value="1">
                        <input type="hidden" name="edit_ward_id" id="edit_ward_id">
                        <input type="hidden" name="edit_gender" id="edit_gender">
                        <div class="mb-3">
                            <label id="editWardLabel" class="form-label"></label>
                        </div>
                        <div class="mb-3">
                            <label for="edit_bed_count" class="form-label">Number of Beds</label>
                            <input type="number" name="edit_bed_count" id="edit_bed_count" class="form-control" min="1" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" onclick="closeEditForm()">Cancel</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                    <button onclick="closeEditForm()" style="position:absolute; top:5px; right:10px; font-size:20px; border:none; background:none;">&times;</button>
                </div>
            </div>

            <script>
                function showEditForm(wardId, wardName, gender, count) {
                    document.getElementById('edit_ward_id').value = wardId;
                    document.getElementById('edit_gender').value = gender;
                    document.getElementById('edit_bed_count').value = count;
                    document.getElementById('editWardLabel').textContent = `${wardName} - ${gender}`;
                    document.getElementById('editBedModal').style.display = 'flex';
                }

                function closeEditForm() {
                    document.getElementById('editBedModal').style.display = 'none';
                }
            </script>

        </div>
    </div>
</div>
