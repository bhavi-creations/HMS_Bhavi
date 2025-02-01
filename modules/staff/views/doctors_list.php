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
                <div class="row">
                    <?php foreach ($doctors as $doctor): ?>
                        <div class="col-md-3 my-3">
                            <div class="card-client  ">
                                <?php
                                $profile_folder = "../../../assets/uploads/doctors_profiles/";
                                $profile_image = !empty($doctor['profile_image']) ? $profile_folder . $doctor['profile_image'] : "../../../assets/uploads/doctors_profiles/default.png"; // Fallback to default image
                                ?>

                                <div class="user-picture">
                                    <img src="<?php echo $profile_image; ?>" alt="User Profile" style="width: 100px; height: 100px; border-radius: 50%;">
                                </div>

                                <div class="text-center">
                                    <p class="name-client">
                                        <?php echo htmlspecialchars($doctor['doctor_name']); ?>

                                    </p>
                                    <p><?php echo htmlspecialchars($doctor['specialization']); ?></p>
                                </div>



                                <div class="row mt-3">
                                    <div class="col-2">
                                        <div class="text_side_left div_text_space">
                                            <p>ID:</p>
                                        </div>
                                        <div class="text_side_left div_text_space">
                                            <p>Department:</p>
                                        </div>
                                        <div class="text_side_left div_text_space">
                                            <p>Experience:</p>
                                        </div>
                                        <div class="text_side_left div_text_space">
                                            <p>Phone:</p>
                                        </div>
                                    </div>
                                    <div class="col-10   ">

                                        <div class="text_side_right div_text_space">
                                            <p class="doctor_id"><?php echo htmlspecialchars($doctor['doctor_id']); ?> </p>
                                        </div>

                                        <div class="text_side_right div_text_space">
                                            <p class="department"><?php echo htmlspecialchars($doctor['department']); ?> </p>
                                        </div>
                                        <div class="text_side_right div_text_space">
                                            <p class="experience"> <?php echo htmlspecialchars($doctor['experience']); ?> years</p>
                                        </div>
                                        <div class="text_side_right div_text_space">
                                            <p class="phone"> <?php echo htmlspecialchars($doctor['phone']); ?></p>
                                        </div>

                                    </div>
                                </div>



                                <div class="social-media row">
                                    <div class="col-6">
                                        <a class="edit-doctor" href="edit_doctor.php?id=<?php echo $doctor['doctor_id']; ?>">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                    </div>
                                    <div class="col-6 ">
                                        <div class="text_side_right  ">
                                            <a href="#" class="delete-doctor" data-id="<?php echo $doctor['doctor_id']; ?>">
                                                <i class="fa-solid fa-trash-can"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class=" view_profile_card_bg">
                                    <a class="text_dec" href="view_doctor.php?id=<?php echo urlencode($doctor['doctor_id']); ?>">
                                        <div class=" padding_space"> <i class="fa-regular fa-eye"></i> View </div>

                                    </a>
                                </div>

                                <div class=""></div>


                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("doctorSearch").addEventListener("input", function() {
        let filter = this.value.toLowerCase().trim();
        let cardsContainer = document.querySelector(".row"); // Select the parent container of the cards
        let cards = Array.from(cardsContainer.querySelectorAll(".col-md-3")); // Get the cards as an array

        // Flag to check if any card matches
        let isAnyMatch = false;

        // Sort the cards based on whether they match the search filter
        let sortedCards = cards.sort((cardA, cardB) => {
            let nameA = cardA.querySelector(".name-client")?.textContent.toLowerCase().trim() || "";
            let nameB = cardB.querySelector(".name-client")?.textContent.toLowerCase().trim() || "";
            let departmentA = cardA.querySelector(".text_side_right:nth-of-type(1) p")?.textContent.toLowerCase().trim() || "";
            let departmentB = cardB.querySelector(".text_side_right:nth-of-type(1) p")?.textContent.toLowerCase().trim() || "";
            let experienceA = cardA.querySelector(".text_side_right:nth-of-type(2) p")?.textContent.toLowerCase().trim() || "";
            let experienceB = cardB.querySelector(".text_side_right:nth-of-type(2) p")?.textContent.toLowerCase().trim() || "";
            let phoneA = cardA.querySelector(".text_side_right:nth-of-type(3) p")?.textContent.toLowerCase().trim() || "";
            let phoneB = cardB.querySelector(".text_side_right:nth-of-type(3) p")?.textContent.toLowerCase().trim() || "";
            let doctorIdA = cardA.querySelector(".doctor_id")?.textContent.toLowerCase().trim() || "";
            let doctorIdB = cardB.querySelector(".doctor_id")?.textContent.toLowerCase().trim() || "";

            let isMatchA = (
                nameA.includes(filter) ||
                departmentA.includes(filter) ||
                experienceA.includes(filter) ||
                phoneA.includes(filter) ||
                doctorIdA.includes(filter) // Added check for doctor ID
            );

            let isMatchB = (
                nameB.includes(filter) ||
                departmentB.includes(filter) ||
                experienceB.includes(filter) ||
                phoneB.includes(filter) ||
                doctorIdB.includes(filter) // Added check for doctor ID
            );

            // If both cards match or neither match, keep their order
            if (isMatchA === isMatchB) return 0;
            return isMatchA ? -1 : 1; // If card A matches, it goes to the top
        });

        // Clear the container and append the sorted cards
        cardsContainer.innerHTML = '';
        sortedCards.forEach(card => {
            cardsContainer.appendChild(card);
        });

        // Now go through each card and apply visibility styles
        sortedCards.forEach(card => {
            let name = card.querySelector(".name-client")?.textContent.toLowerCase().trim() || "";
            let department = card.querySelector(".text_side_right:nth-of-type(1) p")?.textContent.toLowerCase().trim() || "";
            let experience = card.querySelector(".text_side_right:nth-of-type(2) p")?.textContent.toLowerCase().trim() || "";
            let phone = card.querySelector(".text_side_right:nth-of-type(3) p")?.textContent.toLowerCase().trim() || "";
            let doctorId = card.querySelector(".doctor_id")?.textContent.toLowerCase().trim() || ""; // Get doctor ID

            // Check if any of the fields match the filter
            let isMatch = (
                name.includes(filter) ||
                department.includes(filter) ||
                experience.includes(filter) ||
                phone.includes(filter) ||
                doctorId.includes(filter) // Added check for doctor ID
            );

            if (isMatch) {
                // Show the card with smooth transition
                card.style.visibility = "visible";
                card.style.opacity = "1";
                card.style.height = "auto";
                card.style.transition = "opacity 0.3s ease, height 0.3s ease"; // Smooth transition
            } else {
                // Hide the card smoothly
                card.style.visibility = "hidden";
                card.style.opacity = "0";
                card.style.height = "0";
                card.style.overflow = "hidden";
            }
        });

        // Show "No results" message if no card matches
        let noResultsMessage = document.getElementById("noResultsMessage");
        if (noResultsMessage) {
            noResultsMessage.style.display = isAnyMatch ? "none" : "block";
        }
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