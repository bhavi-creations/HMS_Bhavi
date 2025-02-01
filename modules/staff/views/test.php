<?php
ob_start(); // Start output buffering
?>

<div id="wrapper">
    <?php
    include "../../../includes/sidebar.php";
    include "../../../includes/header.php";
    include "../../../config/db.php";

    // Fetch Doctors List
    $doctor_stmt = $pdo->prepare("SELECT * FROM doctors_list ORDER BY doctor_id ASC");
    $doctor_stmt->execute();
    $doctors = $doctor_stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <div id="content">
            <h1 class="text-center mb-5"><strong>Doctor Details</strong></h1>

            <!-- Search Input -->
            <div class="container mb-3">
                <input type="text" id="doctorSearch" class="form-control" placeholder="Search Doctors">
            </div>

            <!-- Doctors Table -->
            <div class="container scroll_bar_y">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>S.no</th>
                            <th>Doctor Name</th>
                            <th>Doctor ID</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Specialization</th>
                            <th>Experience</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="doctorTableBody">
                        <?php foreach ($doctors as $index => $doctor): ?>
                            <tr class="text-center doctor-row" id="doctor-row-<?php echo $doctor['doctor_id']; ?>">
                                <td class="serial-number"><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($doctor['doctor_name']); ?></td>
                                <td><?php echo htmlspecialchars($doctor['doctor_id']); ?></td>
                                <td><?php echo htmlspecialchars($doctor['phone']); ?></td>
                                <td><?php echo htmlspecialchars($doctor['department']); ?></td>
                                <td><?php echo htmlspecialchars($doctor['specialization']); ?></td>
                                <td><?php echo htmlspecialchars($doctor['experience']); ?> years</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <p class="see_more_actions" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            . . .
                                        </p>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li><a class="dropdown-item" href="edit_doctor.php?id=<?php echo $doctor['doctor_id']; ?>"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" href="view_doctor.php?id=<?php echo urlencode($doctor['doctor_id']); ?>">
                                                    <i class="fa-regular fa-eye"></i> View Details
                                                </a>

                                           

                                            </li>

                                            <li>
                                                <a class="dropdown-item delete-doctor" href="#" data-id="<?php echo $doctor['doctor_id']; ?>">
                                                    <i class="fa-solid fa-trash-can"></i> Delete
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("doctorSearch").addEventListener("input", function() {
        let filter = this.value.toLowerCase();
        let rows = document.getElementById("doctorTableBody").getElementsByTagName("tr");

        Array.from(rows).forEach(row => {
            let match = false;
            let cells = row.getElementsByTagName("td");

            Array.from(cells).forEach(cell => {
                if (cell.textContent.toLowerCase().includes(filter)) {
                    match = true;
                }
            });

            row.style.display = match ? "" : "none";
        });
    });

    function updateSerialNumbers() {
        const rows = document.querySelectorAll("#doctorTableBody .doctor-row");
        rows.forEach((row, index) => {
            row.querySelector(".serial-number").textContent = index + 1;
        });
    }

    document.querySelectorAll(".delete-doctor").forEach(button => {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete this doctor?")) {
                const doctorId = this.getAttribute("data-id");

                fetch('delete_doctor.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: doctorId
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            document.getElementById(`doctor-row-${doctorId}`).remove();
                            updateSerialNumbers(); // Update serial numbers after deletion
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        alert('An error occurred while processing the request.');
                        console.error('Error:', error);
                    });
            }
        });
    });
</script>

<?php
ob_end_flush(); // Flush the output buffer
?>