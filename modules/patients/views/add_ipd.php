<?php include "../../../includes/header.php"; ?>

<div id="wrapper">
    <?php include '../../../includes/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <?php include '../../../includes/navbar.php'; ?>

        <div id="content">
            <h1 class="text-center"><strong> Admit In-Patient </strong></h1>
            <div class="container">
                <?php
                // Message container for JavaScript messages
                echo '<div id="messageContainer" class="px-3"></div>';

                // Function to display messages via JavaScript (reused from manage_beds_dynamic)
                function displayJsMessage($type, $message) {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const messageContainer = document.getElementById('messageContainer');
                            if (messageContainer) {
                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-' + '" . $type . "' + ' alert-dismissible fade show';
                                alertDiv.setAttribute('role', 'alert');
                                alertDiv.innerHTML = '" . addslashes($message) . "';
                                
                                // Add a close button
                                const closeButton = document.createElement('button');
                                closeButton.type = 'button';
                                closeButton.className = 'btn-close';
                                closeButton.setAttribute('data-bs-dismiss', 'alert');
                                closeButton.setAttribute('aria-label', 'Close');
                                alertDiv.appendChild(closeButton);

                                messageContainer.appendChild(alertDiv);

                                // Auto-hide after 5 seconds
                                setTimeout(function() {
                                    if (alertDiv.classList.contains('show')) {
                                        alertDiv.classList.remove('show');
                                        alertDiv.classList.add('fade');
                                    }
                                    alertDiv.remove();
                                }, 5000);
                            }
                        });
                    </script>";
                }

                if (isset($_GET['ipd_id']) && !empty($_GET['ipd_id'])) {
                    $ipd_id = filter_var($_GET['ipd_id'], FILTER_SANITIZE_STRING);

                    try {
                        $stmt = $pdo->prepare("SELECT * FROM patients_ipd WHERE ipd_id = :ipd_id");
                        $stmt->execute([':ipd_id' => $ipd_id]);
                        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($patient) {
                            // Fetch wards for the dropdown
                            $wardsStmt = $pdo->query("SELECT id, ward_name FROM wards ORDER BY ward_name ASC");
                            $wards = $wardsStmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div id="adddoctorTable" class="table-container ul_border px-4 active">
                                <form method="post" action="process_add_ipd.php">
                                    <input type="hidden" name="ipd_id" value="<?php echo htmlspecialchars($patient['ipd_id']); ?>">
                                    <input type="hidden" name="opd_casualty_id" value="<?php echo htmlspecialchars($patient['opd_casualty_id']); ?>">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Patient Name</label>
                                                <input type="text" class="form-control field_input_bg" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" readonly>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Age</label>
                                                <input type="number" class="form-control field_input_bg" name="age" value="<?php echo htmlspecialchars($patient['age']); ?>" readonly>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Gender (Patient)</label>
                                                <input type="text" class="form-control field_input_bg" name="patient_gender_display" value="<?php echo htmlspecialchars($patient['gender']); ?>" readonly>
                                                <input type="hidden" name="patient_gender_original" id="patient_gender_original" value="<?= htmlspecialchars($patient['gender']); ?>">
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Guardian Name</label>
                                                <input type="text" class="form-control field_input_bg" name="guardian_name" value="<?php echo htmlspecialchars($patient['guardian_name']); ?>" readonly>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Contact</label>
                                                <input type="tel" class="form-control field_input_bg" name="contact" value="<?php echo htmlspecialchars($patient['contact']); ?>" readonly>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">WhatsApp Number</label>
                                                <input type="tel" class="form-control field_input_bg" name="whatsapp_number" value="<?php echo htmlspecialchars($patient['whatsapp_number']); ?>" readonly>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Address</label>
                                                <textarea class="form-control field_input_bg" name="address" rows="1" readonly><?php echo htmlspecialchars($patient['address']); ?></textarea>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Problem</label>
                                                <input type="text" class="form-control field_input_bg" name="problem" value="<?php echo htmlspecialchars($patient['problem']); ?>" readonly>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Doctor</label>
                                                <input type="text" class="form-control field_input_bg" name="doctor" value="<?php echo htmlspecialchars($patient['doctor']); ?>" readonly>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Referred By</label>
                                                <input type="text" class="form-control field_input_bg" name="referred_by" value="<?php echo htmlspecialchars($patient['referred_by']); ?>" readonly>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Remarks</label>
                                                <input type="text" class="form-control field_input_bg" name="remarks" value="<?php echo htmlspecialchars($patient['remarks']); ?>" readonly>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Medical History</label>
                                                <textarea class="form-control field_input_bg" name="medical_history" rows="1" readonly><?php echo htmlspecialchars($patient['medical_history']); ?></textarea>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Fee</label>
                                                <input type="number" class="form-control field_input_bg" name="fee" id="fee_ipd" value="<?php echo htmlspecialchars($patient['fee']); ?>" oninput="calculateDiscountIPD()">
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Discount (%)</label>
                                                <input type="number" class="form-control field_input_bg" name="discount" id="discount_ipd" value="<?php echo htmlspecialchars($patient['discount']); ?>" oninput="calculateDiscountIPD()">
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Final Amount</label>
                                                <input type="text" class="form-control field_input_bg" id="final_amount_ipd" name="final_fee" value="<?php echo htmlspecialchars($patient['final_fee']); ?>" readonly>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Ward</label>
                                                <select class="form-control field_input_bg" name="ward_id" id="ward_id" required>
                                                    <option value="">-- Select Ward --</option>
                                                    <?php foreach ($wards as $ward): ?>
                                                        <option value="<?= $ward['id'] ?>"><?= htmlspecialchars($ward['ward_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Gender for Bed</label>
                                                <select class="form-control field_input_bg" name="bed_gender" id="bed_gender_select" required>
                                                    <option value="">-- Select Gender --</option>
                                                    </select>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Bed Number</label>
                                                <select class="form-control field_input_bg" name="bed_id" id="bed_id" required>
                                                    <option value="">-- Select Bed --</option>
                                                    </select>
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Admission Date</label>
                                                <input type="datetime-local" class="form-control field_input_bg" name="admission_date" value="<?php echo date('Y-m-d\TH:i'); ?>">
                                            </div>
                                            <div class="col-md-3 mt-5">
                                                <div class="row last_back_submit d-flex flex-column align-items-center gap-2">
                                                    <div>
                                                        <a href="ipd.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Cancel</a>
                                                    </div>
                                                    <div>
                                                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Admit Patient</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <script>
                                function calculateDiscountIPD() {
                                    const fee = parseFloat(document.getElementById('fee_ipd').value) || 0;
                                    const discount = parseFloat(document.getElementById('discount_ipd').value) || 0;
                                    const finalAmount = fee - (fee * discount / 100);
                                    document.getElementById('final_amount_ipd').value = finalAmount.toFixed(2);
                                }

                                // Function to fetch and populate bed genders based on selected ward
                                async function fetchAndPopulateBedGenders() {
                                    const wardId = document.getElementById('ward_id').value;
                                    const bedGenderSelect = document.getElementById('bed_gender_select');
                                    const bedSelect = document.getElementById('bed_id');

                                    // Reset and disable dropdowns
                                    bedGenderSelect.innerHTML = '<option value="">Loading Genders...</option>';
                                    bedGenderSelect.disabled = true;
                                    bedSelect.innerHTML = '<option value="">-- Select Bed --</option>';
                                    bedSelect.disabled = true;

                                    if (!wardId) {
                                        bedGenderSelect.innerHTML = '<option value="">-- Select Ward First --</option>';
                                        bedGenderSelect.disabled = false;
                                        return;
                                    }

                                    try {
                                        const response = await fetch('get_ward_genders.php', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded',
                                            },
                                            body: `ward_id=${wardId}`
                                        });

                                        // Try to parse JSON, catch if it fails
                                        let genders;
                                        try {
                                            genders = await response.json();
                                        } catch (jsonError) {
                                            console.error('JSON parsing error for get_ward_genders.php:', jsonError);
                                            console.error('Response text:', await response.text()); // Log raw response
                                            displayJsMessage('danger', 'Error processing gender data from server.');
                                            bedGenderSelect.innerHTML = '<option value="">Error loading genders</option>';
                                            bedGenderSelect.disabled = false;
                                            return;
                                        }
                                        

                                        bedGenderSelect.innerHTML = '<option value="">-- Select Gender --</option>';
                                        if (genders.length > 0) {
                                            genders.forEach(gender => {
                                                const option = document.createElement('option');
                                                option.value = gender;
                                                option.textContent = gender;
                                                bedGenderSelect.appendChild(option);
                                            });

                                            // Attempt to pre-select patient's gender in the bed gender dropdown
                                            const patientOriginalGender = document.getElementById('patient_gender_original').value;
                                            if (genders.includes(patientOriginalGender)) {
                                                bedGenderSelect.value = patientOriginalGender;
                                            } else {
                                                // If patient's gender is not available, select the first available gender
                                                if (bedGenderSelect.options.length > 1) { // Check if there's at least one actual gender option
                                                    bedGenderSelect.selectedIndex = 1; 
                                                }
                                            }
                                            
                                            // Now that gender is selected (or defaulted), fetch beds
                                            fetchAndPopulateBeds();

                                        } else {
                                            bedGenderSelect.innerHTML = '<option value="">No Genders with Available Beds</option>';
                                        }
                                        bedGenderSelect.disabled = false;

                                    } catch (error) {
                                        console.error('Error fetching bed genders (network/other):', error);
                                        displayJsMessage('danger', 'Error loading bed genders. Please try again.');
                                        bedGenderSelect.innerHTML = '<option value="">Error loading genders</option>';
                                        bedGenderSelect.disabled = false;
                                    }
                                }

                                // Function to fetch and populate beds based on selected ward and gender
                                async function fetchAndPopulateBeds() {
                                    const wardId = document.getElementById('ward_id').value;
                                    const bedGender = document.getElementById('bed_gender_select').value;

                                    const bedSelect = document.getElementById('bed_id');
                                    bedSelect.innerHTML = '<option value="">Loading Beds...</option>'; // Loading state
                                    bedSelect.disabled = true; // Disable until loaded

                                    if (!wardId || !bedGender || bedGender === '-- Select Gender --') { // Added check for default gender option
                                        bedSelect.innerHTML = '<option value="">-- Select Ward and Gender --</option>';
                                        bedSelect.disabled = false;
                                        return;
                                    }

                                    try {
                                        const response = await fetch('get_available_beds.php', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded',
                                            },
                                            body: `ward_id=${wardId}&gender=${bedGender}` // Pass the bed gender
                                        });

                                        // Try to parse JSON, catch if it fails
                                        let beds;
                                        try {
                                            beds = await response.json();
                                        } catch (jsonError) {
                                            console.error('JSON parsing error for get_available_beds.php:', jsonError);
                                            console.error('Response text:', await response.text()); // Log raw response
                                            displayJsMessage('danger', 'Error processing bed data from server.');
                                            bedSelect.innerHTML = '<option value="">Error loading beds</option>';
                                            bedSelect.disabled = false;
                                            return;
                                        }

                                        bedSelect.innerHTML = '<option value="">-- Select Bed --</option>';
                                        if (beds.length > 0) {
                                            beds.forEach(bed => {
                                                const option = document.createElement('option');
                                                option.value = bed.id; // Assuming bed.id is the primary key for the bed
                                                option.textContent = `Bed ${bed.bed_number}`;
                                                bedSelect.appendChild(option);
                                            });
                                        } else {
                                            bedSelect.innerHTML = '<option value="">No Available Beds</option>';
                                        }
                                        bedSelect.disabled = false;
                                    } catch (error) {
                                        console.error('Error fetching beds (network/other):', error);
                                        displayJsMessage('danger', 'Error loading beds. Please try again.');
                                        bedSelect.innerHTML = '<option value="">Error loading beds</option>';
                                        bedSelect.disabled = false;
                                    }
                                }

                                // Event listeners
                                document.addEventListener('DOMContentLoaded', function() {
                                    calculateDiscountIPD(); // Initial calculation on page load

                                    const wardSelect = document.getElementById('ward_id');
                                    const bedGenderSelect = document.getElementById('bed_gender_select');

                                    // Initial load of bed genders when ward is selected (or on page load if ward is pre-selected)
                                    wardSelect.addEventListener('change', fetchAndPopulateBedGenders);
                                    bedGenderSelect.addEventListener('change', fetchAndPopulateBeds);

                                    // Trigger initial load of genders and then beds if a ward is already selected (e.g., if page refreshes with a selected ward)
                                    if (wardSelect.value) {
                                        fetchAndPopulateBedGenders();
                                    } else {
                                        // If no ward is selected initially, disable bed gender and bed dropdowns
                                        bedGenderSelect.disabled = true;
                                        document.getElementById('bed_id').disabled = true;
                                    }
                                });
                            </script>
                            <?php
                        } else {
                            displayJsMessage('danger', 'Error: Invalid IPD ID.');
                        }
                    } catch (PDOException $e) {
                        displayJsMessage('danger', 'Error: ' . htmlspecialchars($e->getMessage()));
                    }
                } else {
                    displayJsMessage('warning', 'No IPD ID provided. Please try again.');
                }
                ?>
            </div>
        </div> 
    </div>
</div

<?php include "../../../includes/footer.php"; ?>

 