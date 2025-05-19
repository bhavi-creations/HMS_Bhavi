 
<div id="wrapper">

    <?php

    include "includes/sidebar.php";
    include "includes/header.php";
    include "config/db.php";

   
    if (!isset($_GET['id']) || !isset($_GET['type'])) {
        die("Invalid request. Missing parameters.");
    }

    $id = intval($_GET['id']);
    $type = htmlspecialchars($_GET['type']); // 'doctor' or 'nurse'

    // Determine the table and redirect path
    $table = $type === 'nurse' ? 'nurses' : 'doctors';
    $redirectPath = $type === 'nurse' ? 'see_staff.php?type=nurse' : 'see_staff.php?type=doctor';

    // Fetch the staff details
    try {
        $query = "SELECT * FROM $table WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$staff) {
            die("Staff not found.");
        }
    } catch (PDOException $e) {
        die("Error fetching staff details: " . $e->getMessage());
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $specialization_or_department = htmlspecialchars($_POST['specialization_or_department']);
        $availability_schedule = htmlspecialchars($_POST['availability_schedule']);
        $photo = $staff['photo'];

        // Handle photo upload if a new file is provided
        if (!empty($_FILES['photo']['name'])) {
            $uploadDir = "assets/uploads/$table/";
            $photoName = basename($_FILES['photo']['name']);
            $photoPath = $uploadDir . $photoName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                $photo = $photoName;
            } else {
                echo "<script>alert('Error uploading photo');</script>";
            }
        }

        // Update the staff record
        try {
            $updateQuery = "UPDATE $table SET 
            name = :name, 
            email = :email, 
            " . ($type === 'nurse' ? 'department' : 'specialization') . " = :specialization_or_department, 
            availability_schedule = :availability_schedule, 
            photo = :photo 
            WHERE id = :id";

            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'specialization_or_department' => $specialization_or_department,
                'availability_schedule' => $availability_schedule,
                'photo' => $photo,
                'id' => $id
            ]);

            header("Location: $redirectPath?edit_success=1");

            exit();
        } catch (PDOException $e) {
            die("Error updating staff: " . $e->getMessage());
        }
    }
    
    ?>


    <div id="content-wrapper" class="d-flex flex-column bg-white">

        <div id="content">
            <div class="container branch_container">


                <div class="col-md-11 col-lg-12 ul_border">


                    <h2 class="edit_h_tag">Edit <?= ucfirst($type); ?> Details</h2>

                    <div class="container">


                        <form method="POST" enctype="multipart/form-data">

                            <div class="form-group">

                                <div class="row">

                                    <div class="mb-5 col-md-6">
                                        <label class="control-label mb-2 field_txt" for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control field_input_bg" value="<?= htmlspecialchars($staff['name']); ?>" required>
                                    </div>

                                    <div class="mb-5 col-md-6">
                                        <label class="control-label mb-2 field_txt" for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control field_input_bg" value="<?= htmlspecialchars($staff['email']); ?>" required>
                                    </div>

                                    <div class="mb-5 col-md-6">
                                        <label class="control-label mb-2 field_txt" for="specialization_or_department"><?= $type === 'nurse' ? 'Department' : 'Specialization'; ?></label>
                                        <input type="text" name="specialization_or_department" id="specialization_or_department" class="form-control field_input_bg" value="<?= htmlspecialchars($staff[$type === 'nurse' ? 'department' : 'specialization']); ?>" required>
                                    </div>

                                    <div class="mb-5 col-md-6">
                                        <label class="control-label mb-2 field_txt" for="availability_schedule">Availability Schedule</label>
                                        <input type="text" name="availability_schedule" id="availability_schedule" class="form-control field_input_bg" value="<?= htmlspecialchars($staff['availability_schedule']); ?>" required>
                                    </div>

                                    <div class=" mt-3 col-md-6">
                                        <label class="control-label mb-2 field_txt" for="photo">Photo</label>
                                        <input type="file" name="photo" id="photo" class="form-control field_input_bg">
                                        <?php if (!empty($staff['photo'])): ?>
                                            <img src="assets/uploads/<?= htmlspecialchars($table); ?>/<?= htmlspecialchars($staff['photo']); ?>" alt="Current Photo" width="100">
                                        <?php else: ?>
                                            <span>No photo available</span>
                                        <?php endif; ?>
                                    </div>



                                    <div class="mt-5 col-md-6 ">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="<?= $redirectPath; ?>" class="btn btn-secondary ">Cancel</a>
                                    </div>


                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <?php
    include "includes/footer.php";
    ?>


</div>