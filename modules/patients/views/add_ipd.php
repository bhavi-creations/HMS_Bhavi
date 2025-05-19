<?php include "../../../includes/header.php"; ?>

<div id="wrapper">
    <?php include '../../../includes/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column bg-white">
        <?php include '../../../includes/navbar.php'; ?>

        <div id="content">
            <h1 class="text-center"><strong> Admit In-Patient </strong></h1>
            <div class="container">
                <?php
                if (isset($_GET['ipd_id']) && !empty($_GET['ipd_id'])) {
                    $ipd_id = filter_var($_GET['ipd_id'], FILTER_SANITIZE_STRING);

                    try {
                        $stmt = $pdo->prepare("SELECT * FROM patients_ipd WHERE ipd_id = :ipd_id");
                        $stmt->execute([':ipd_id' => $ipd_id]);
                        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($patient) {
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
                                                <label class="control-label mb-2 field_txt">Gender</label>
                                                <input type="text" class="form-control field_input_bg" name="gender" value="<?php echo htmlspecialchars($patient['gender']); ?>" readonly>
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
                                                <label class="control-label mb-2 field_txt">Ward Number</label>
                                                <input type="text" class="form-control field_input_bg" name="ward_number">
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Bed Number</label>
                                                <input type="text" class="form-control field_input_bg" name="bed_number">
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="control-label mb-2 field_txt">Admission Date</label>
                                                <input type="datetime-local" class="form-control field_input_bg" name="admission_date" value="<?php echo date('Y-m-d\TH:i'); ?>">
                                            </div>
                                            <div class="col-md-3 mt-5">
                                                <div class="row last_back_submit d-flex flex-column align-items-center gap-2">
                                                    <div>
                                                        <a href="patients_ipd_list.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Cancel</a>
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

                                // Initial calculation on page load
                                calculateDiscountIPD();
                            </script>
                            <?php
                        } else {
                            echo "<div class='alert alert-danger text-center'>Error: Invalid IPD ID.</div>";
                        }
                    } catch (PDOException $e) {
                        echo "<div class='alert alert-danger text-center'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-warning text-center'>No IPD ID provided. Please try again.</div>";
                }
                ?>
            </div>
        </div>
        <?php include '../../../includes/footer.php'; ?>
    </div>
</div>

<?php include "../../../includes/end.php"; ?>