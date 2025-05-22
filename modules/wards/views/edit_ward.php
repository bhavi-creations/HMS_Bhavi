<?php include "../../../includes/header.php"; ?>

<div id="wrapper">
    <?php include '../../../includes/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <?php include '../../../includes/navbar.php'; ?>

        <div id="content">
            <h1 class="text-center"><strong>Edit Ward</strong></h1>
            <div class="container">

                <?php
                if (!isset($_GET['id']) || empty($_GET['id'])) {
                    echo "<div class='alert alert-danger'>No ward ID specified.</div>";
                    exit;
                }
                $ward_id = intval($_GET['id']);

                // Fetch existing ward data
                $stmt = $pdo->prepare("SELECT * FROM wards WHERE id = :id");
                $stmt->bindParam(':id', $ward_id, PDO::PARAM_INT);
                $stmt->execute();
                $ward = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$ward) {
                    echo "<div class='alert alert-danger'>Ward not found.</div>";
                    exit;
                }

                // Handle form submission
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $ward_name = trim($_POST['ward_name']);
                    if (!empty($ward_name)) {
                        $stmt = $pdo->prepare("UPDATE wards SET ward_name = :ward_name WHERE id = :id");
                        $stmt->bindParam(':ward_name', $ward_name);
                        $stmt->bindParam(':id', $ward_id, PDO::PARAM_INT);
                        if ($stmt->execute()) {
                            header("Location: add_ward.php");
                            exit();
                        } else {
                            echo "<div class='alert alert-danger'>Failed to update ward.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-warning'>Please enter a ward name.</div>";
                    }
                }
                ?>

                <form method="POST">
                    <div class="form-group">
                        <label for="ward_name">Ward Name</label>
                        <input type="text" class="form-control" name="ward_name" id="ward_name" value="<?= htmlspecialchars($ward['ward_name']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Update Ward</button>
                    <a href="add_ward.php" class="btn btn-secondary mt-2">Cancel</a>
                </form>

            </div>
        </div>
    </div>
</div>


<?php include "../../../includes/footer.php"; ?>