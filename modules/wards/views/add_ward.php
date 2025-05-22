<?php include "../../../includes/header.php"; ?>

<div id="wrapper">
    <?php include '../../../includes/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <?php include '../../../includes/navbar.php'; ?>

        <div id="content" class="py-4">
       

                <div class="row   mb-4">
                    <div class="col-5">
                        <h1 class="text-center mb-4"><strong>Add Ward</strong></h1>

                        <?php
                        // Handle form submission
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $ward_name = trim($_POST['ward_name']);

                            if (!empty($ward_name)) {
                                $stmt = $pdo->prepare("INSERT INTO wards (ward_name) VALUES (:ward_name)");
                                $stmt->bindParam(':ward_name', $ward_name);
                                if ($stmt->execute()) {
                                    // Redirect to avoid resubmission
                                    header("Location: " . $_SERVER['REQUEST_URI']);
                                    exit();
                                } else {
                                    echo "<div class='alert alert-danger'>Failed to add ward.</div>";
                                }
                            } else {
                                echo "<div class='alert alert-warning'>Please enter a ward name.</div>";
                            }
                        }
                        ?>

                        <!-- Add Ward Form -->
                        <form method="POST" class="mb-5">
                            <div class="mb-3">
                                <label for="ward_name" class="form-label">Ward Name</label>
                                <input type="text" class="form-control" name="ward_name" id="ward_name" placeholder="Enter ward name" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Ward</button>
                        </form>
                    </div>
                    <div class="col-7">
                        <!-- Display Existing Wards -->
                        <?php
                        $stmt = $pdo->query("SELECT * FROM wards ORDER BY ward_name DESC");
                        $wards = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <h2 class="mb-3">Existing Wards</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 8%;">S.No</th>
                                        <th>Ward Name</th>
                                        <th style="width: 30%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($wards) > 0): ?>
                                        <?php $serial = 1; ?>
                                        <?php foreach ($wards as $ward): ?>
                                            <tr>
                                                <td><?= $serial++ ?></td>
                                                <td class="text-start"><?= htmlspecialchars($ward['ward_name']) ?></td>
                                                <td>
                                                    <a href="edit_ward.php?id=<?= $ward['id'] ?>" class="btn btn-warning btn-sm me-2">Edit</a>
                                                    <a href="delete_ward.php?id=<?= $ward['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this ward?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">No wards found.</td>
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

<?php include "../../../includes/footer.php"; ?>