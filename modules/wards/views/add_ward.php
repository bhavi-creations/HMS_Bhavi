<?php include "../../../includes/header.php"; ?>

<div id="wrapper">
    <?php include '../../../includes/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <?php include '../../../includes/navbar.php'; ?>

        <div id="content">
            <h1 class="text-center"><strong>Add Ward</strong></h1>
            <div class="container">

                <?php
                // Handle form submission
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $ward_name = trim($_POST['ward_name']);

                    if (!empty($ward_name)) {
                        $stmt = $conn->prepare("INSERT INTO wards (ward_name) VALUES (:ward_name)");
                        $stmt->bindParam(':ward_name', $ward_name);
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Ward added successfully.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Failed to add ward.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-warning'>Please enter a ward name.</div>";
                    }
                }
                ?>

                <form method="POST">
                    <div class="form-group">
                        <label for="ward_name">Ward Name</label>
                        <input type="text" class="form-control" name="ward_name" id="ward_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Add Ward</button>
                </form>

            </div>
        </div>
    </div>
</div>
