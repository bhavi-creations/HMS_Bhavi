<?php
include "../../../includes/header.php";
include "../../../config/config.php"; // Ensure PDO $pdo is available

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invalid bed ID.</div>";
    exit();
}

$bed_id = $_GET['id'];

// Fetch the bed details
$stmt = $pdo->prepare("SELECT * FROM beds WHERE id = ?");
$stmt->execute([$bed_id]);
$bed = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bed) {
    echo "<div class='alert alert-danger'>Bed not found.</div>";
    exit();
}

// Fetch all wards
$wards = $pdo->query("SELECT * FROM wards ORDER BY ward_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ward_id = $_POST['ward_id'];
    $bed_number = trim($_POST['bed_number']);
    $gender = $_POST['gender'];
    $status = $_POST['status'];

    if (!empty($ward_id) && !empty($bed_number) && !empty($gender) && !empty($status)) {
        $updateStmt = $pdo->prepare("
            UPDATE beds 
            SET ward_id = ?, bed_number = ?, gender = ?, status = ?
            WHERE id = ?
        ");
        if ($updateStmt->execute([$ward_id, $bed_number, $gender, $status, $bed_id])) {
            header("Location: view_beds.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Failed to update bed.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>All fields are required.</div>";
    }
}
?>

<div id="wrapper">
    <?php include '../../../includes/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <?php include '../../../includes/navbar.php'; ?>
        <div id="content" class="py-4">
            <h1 class="text-center mb-4"><strong>Edit Bed</strong></h1>

            <form method="POST" class="col-md-6 offset-md-3">
                <div class="mb-3">
                    <label for="ward_id" class="form-label">Ward</label>
                    <select name="ward_id" id="ward_id" class="form-select" required>
                        <option value="">-- Select Ward --</option>
                        <?php foreach ($wards as $ward): ?>
                            <option value="<?= $ward['id'] ?>" <?= $ward['id'] == $bed['ward_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ward['ward_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="bed_number" class="form-label">Bed Number</label>
                    <input type="text" class="form-control" name="bed_number" id="bed_number" value="<?= htmlspecialchars($bed['bed_number']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" id="gender" class="form-select" required>
                        <option value="Male" <?= $bed['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $bed['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Available" <?= $bed['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="Occupied" <?= $bed['status'] === 'Occupied' ? 'selected' : '' ?>>Occupied</option>
                        <option value="Under Maintenance" <?= $bed['status'] === 'Under Maintenance' ? 'selected' : '' ?>>Under Maintenance</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Bed</button>
            </form>
        </div>
    </div>
</div>
