<!-- Category Sidebar -->
<ul class="navbar-nav category-sidebar sidebar-dark accordion" id="categorySidebar" style="background-color: #e8ecef; width: 60px; position: fixed; top: 0; bottom: 0; left: 0;">
    <li class="nav-item">
        <a class="nav-link text-center category-link" href="#" data-category="pharmacy">
            <i class="fa-solid fa-capsules"></i>
            <p style="font-size: 10px;">Pharmacy</p>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-center category-link" href="#" data-category="medical">
            <i class="fa-solid fa-user-md"></i>
            <p style="font-size: 10px;">Medical</p>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-center category-link" href="#" data-category="dashboard">
            <i class="fa-solid fa-house"></i>
            <p style="font-size: 10px;">Dashboard</p>
        </a>
    </li>
</ul>

<!-- Main Sidebar -->
<ul class="navbar-nav main-sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #F4F5F9; margin-left: 60px;">
    <!-- Content will be updated dynamically -->
</ul>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Data for each category
        const sidebarContent = {
            pharmacy: `
                <a class="sidebar-brand d-flex align-items-center justify-content-center bg-light text-primary" href="index.php">
                    <div class="sidebar-brand-text mx-3">Pharmacy</div>
                </a>
                <li class="menu-item menu_tag">
                    <a class="menu-link collapsed link_tag" href="/pharmacy/manage_drugs.php">
                        <p class="menu-content">
                            <i class="fa-solid fa-pills"></i>
                            Manage Drugs
                        </p>
                    </a>
                </li>
                <li class="menu-item menu_tag">
                    <a class="menu-link collapsed link_tag" href="/pharmacy/manage_inventory.php">
                        <p class="menu-content">
                            <i class="fa-solid fa-box"></i>
                            Manage Inventory
                        </p>
                    </a>
                </li>
            `,
            medical: `
                <a class="sidebar-brand d-flex align-items-center justify-content-center bg-light text-primary" href="index.php">
                    <div class="sidebar-brand-text mx-3">Medical</div>
                </a>
                <li class="menu-item menu_tag">
                    <a class="menu-link collapsed link_tag" href="/medical/view_patients.php">
                        <p class="menu-content">
                            <i class="fa-solid fa-user-injured"></i>
                            View Patients
                        </p>
                    </a>
                </li>
                <li class="menu-item menu_tag">
                    <a class="menu-link collapsed link_tag" href="/medical/add_patient.php">
                        <p class="menu-content">
                            <i class="fa-solid fa-user-plus"></i>
                            Add Patient
                        </p>
                    </a>
                </li>
            `,
            dashboard: `
                <a class="sidebar-brand d-flex align-items-center justify-content-center bg-light text-primary" href="index.php">
                    <div class="sidebar-brand-text mx-3">Dashboard</div>
                </a>
                <li class="menu-item menu_tag">
                    <a class="menu-link collapsed link_tag" href="/dashboard/overview.php">
                        <p class="menu-content">
                            <i class="fa-solid fa-chart-line"></i>
                            Overview
                        </p>
                    </a>
                </li>
                <li class="menu-item menu_tag">
                    <a class="menu-link collapsed link_tag" href="/dashboard/reports.php">
                        <p class="menu-content">
                            <i class="fa-solid fa-file-alt"></i>
                            Reports
                        </p>
                    </a>
                </li>
            `
        };

        // Update Main Sidebar Based on Category
        $('.category-link').on('click', function () {
            const selectedCategory = $(this).data('category');
            const content = sidebarContent[selectedCategory] || '';
            $('.main-sidebar').html(content); // Update main sidebar content
            $('.category-link').parent().removeClass('active');
            $(this).parent().addClass('active');
        });

        // Default load (e.g., Pharmacy)
        $('.category-link[data-category="pharmacy"]').click();
    });
</script>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.nav-link').on('click', function() {
            $('.nav-item').removeClass('active'); // Remove active class from all nav items
            $(this).parent().addClass('active'); // Add active class to the clicked nav item
        });
    });
</script>


<ul class="navbar-nav   sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #F4F5F9;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center  bg-light text-primary" href="index.php">
        <div class="sidebar-brand-icon  ">
            <!-- <i class="fas fa-stethoscope"></i> -->
        </div>
        <div class="sidebar-brand-text mx-3">LOGO</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">





    <li class="menu-item menu_tag ">
        <a class="menu-link collapsed link_tag" href="/Hospital-management-system-own/public/index.php">
            <p class="menu-content">

                <i class="fa-solid fa-house"></i>
                Dashboard
            </p>
        </a>
    </li>


    <li class="menu-item dropdown menu_tag  ">
        <a class="menu-link dropdown-toggle link_tag  " href="#" id="patientsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <p class="menu-content  ">
                <i class="fa-solid fa-users"></i>
                Patients
            </p>
        </a>
        <ul class="dropdown-menu" aria-labelledby="patientsDropdown">
            <li>
                <a class="dropdown-item   " href="/Hospital-management-system-own/modules/patients/views/add_patients.php"><i class="fa-solid fa-plus"></i>  Add Patient</a>
            </li>


            <li>
                <a class="dropdown-item  " href="/Hospital-management-system-own/modules/patients/views/causality.php"><i class="fa-solid fa-eye"></i> View causality</a>
            </li>

            <li>
                <a class="dropdown-item  " href="/Hospital-management-system-own/modules/patients/views/opd.php"><i class="fa-solid fa-eye"></i> View OPD</a>
            </li>
            <li>
                <a class="dropdown-item  " href="/Hospital-management-system-own/modules/patients/views/ipd.php"><i class="fa-solid fa-eye"></i> View IPD</a>
            </li>
        </ul>
    </li>

    <li class="menu-item dropdown menu_tag  ">
        <a class="menu-link dropdown-toggle link_tag  " href="#" id="patientsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <p class="menu-content  ">
                <i class="fa-solid fa-users"></i>
                Staff
            </p>
        </a>
        <ul class="dropdown-menu" aria-labelledby="patientsDropdown">
            <li>
                <a class="dropdown-item   " href="/Hospital-management-system-own/modules/staff/views/add_doctors.php">Add Doctor</a>
            </li>
            <li>
                <a class="dropdown-item  " href="/Hospital-management-system-own/modules/staff/views/add_nurses.php">Add Nurse</a>
            </li>
            <li>
                <a class="dropdown-item  " href="/Hospital-management-system-own/modules/staff/views/add_other_staff.php">Other Staff</a>
            </li>
        </ul>
    </li>


    <li class="menu-item menu_tag">
        <a class="menu-link collapsed link_tag" href="/Hospital-management-system-own/modules/patients/views/add_patients.php">
            <p class="menu-content">
                <i class="fa-solid fa-users"></i>
                Add Patients
            </p>
        </a>
    </li>


    <li class="menu-item menu_tag">
        <a class="menu-link collapsed link_tag" href="/Hospital-management-system-own/modules/patients/views/see_patients.php">
            <p class="menu-content">
                <i class="fa-solid fa-users"></i>
                View Patients
            </p>
        </a>
    </li>



    <li class="menu-item menu_tag">
        <a class="menu-link collapsed link_tag" href="/Hospital-management-system-own/modules/staff/views/add_staff.php">
            <p class="menu-content">
                <i class="fa-solid fa-user-doctor"></i>
                Add Staff
            </p>
        </a>
    </li>


    <li class="menu-item menu_tag">
        <a class="menu-link collapsed link_tag" href="/Hospital-management-system-own/modules/staff/views/see_staff.php">
            <p class="menu-content">
                <i class="fa-solid fa-user-doctor"></i>
                See Staff
            </p>
        </a>
    </li>




    <li class="menu-item menu_tag">
        <a class="menu-link collapsed link_tag" href="logout.php">
            <p class="menu-content">
                <i class="fa-solid fa-power-off"></i>
                Logout
            </p>
        </a>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <!-- <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div> -->




</ul>
