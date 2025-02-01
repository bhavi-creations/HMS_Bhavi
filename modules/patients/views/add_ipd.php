<?php
include "../../../config/db.php"; // Database connection

// Check if patient ID is provided
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    // Fetch patient details from OPD
    $stmt = $pdo->prepare("SELECT * FROM patients_opd WHERE id = :id");
    $stmt->execute(['id' => $patient_id]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($patient) {
        // Insert patient into IPD table
        $insert_stmt = $pdo->prepare("
            INSERT INTO patients_ipd (name, age, gender, doctor, contact, address, medical_history, admission_type)
            VALUES (:name, :age, :gender, :doctor, :contact, :address, :medical_history, 'IPD')
        ");
        $insert_stmt->execute([
            'name' => $patient['name'],
            'age' => $patient['age'],
            'gender' => $patient['gender'],
            'doctor' => $patient['doctor'],
            'contact' => $patient['contact'],
            'address' => $patient['address'],
            'medical_history' => $patient['medical_history'],
        ]);

        // Optional: Remove the patient from OPD if required
        // $delete_stmt = $pdo->prepare("DELETE FROM patients_opd WHERE id = :id");
        // $delete_stmt->execute(['id' => $patient_id]);
    } else {
        die("Patient not found.");
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Patient to IPD</title>
    <!-- Include CSS and JS -->
</head>
<body>
    <h1 class="text-center">Add Patient to IPD</h1>
    <form action="save_ipd_details.php" method="POST">
        <!-- Patient Details -->
        <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Age:</label>
            <input type="text" class="form-control" name="age" value="<?php echo htmlspecialchars($patient['age']); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Gender:</label>
            <input type="text" class="form-control" name="gender" value="<?php echo htmlspecialchars($patient['gender']); ?>" readonly>
        </div>

        <!-- Additional Fields -->
        <div class="form-group">
            <label>Bed Number:</label>
            <input type="text" class="form-control" name="bed_number" required>
        </div>
        <div class="form-group">
            <label>Ward Type:</label>
            <select class="form-control" name="ward_type">
                <option value="ICU">ICU</option>
                <option value="General">General</option>
            </select>
        </div>
        <div class="form-group">
            <label>Additional Notes:</label>
            <textarea class="form-control" name="notes"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</body>
</html>
