 
<ul class="navbar-nav category-sidebar sidebar-dark accordion" id="categorySidebar" style="background-color: #e8ecef; width: 60px; position: fixed; top: 0; bottom: 0; left: 0;">
    <li class="nav-item ">
        <a class="nav-link text-center category-link " href="#" data-category="pharmacy">
            <i class="fa-solid fa-capsules  all_black_icon "></i>
            <p class=" all_black    ">Pharmacy</p>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-center category-link" href="#" data-category="recption">
            <i class="fa-solid fa-user-md all_black_icon"></i>
            <p class=" all_black    ">Recption</p>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-center category-link" href="#" data-category="lab">
            <i class="fa-solid fa-house all_black_icon"></i>
            <p class=" all_black    ">Lab</p>
        </a>
    </li>
</ul>

<!-- Main Sidebar -->
<ul class="navbar-nav main-sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #F4F5F9; margin-left: 60px;  ">
    <!-- Content will be updated dynamically -->
</ul>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Data for each category
        const sidebarContent = {
            pharmacy: `
                <a class="sidebar-brand d-flex align-items-center justify-content-center bg-light text-primary" href="index.php">
                    <div class="sidebar-brand-text mx-3 inline_heading  ">Pharmacy</div>
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
            recption: `
                <a class="sidebar-brand d-flex align-items-center justify-content-center bg-light text-primary" href="index.php">
                    <div class="sidebar-brand-text mx-3 inline_heading">HMS</div>
                </a>
                <li class="menu-item dropdown menu_tag  ">
                    <a class="menu-link dropdown-toggle link_tag  " href="#" id="patientsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <p class="menu-content  ">
                            <i class="fa-solid fa-users"></i>
                            Patients
                        </p>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="patientsDropdown">
                        <li>
 
                            <a class="dropdown-item  " href="/Hospital-management-system-own/modules/patients/views/add_patients.php">
                            <i class="fa-solid fa-plus"></i> Add Patient
                            </a>


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
                            Doctors
                        </p>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="patientsDropdown">
                        <li>
                            <a class="dropdown-item   " href="/Hospital-management-system-own/modules/staff/views/add_doctor.php"><i class="fa-solid fa-plus"></i>  Add Doctor</a>
                        </li>
                        <li>
                            <a class="dropdown-item  " href="/Hospital-management-system-own/modules/staff/views/doctors_list.php"><i class="fa-solid fa-eye"></i> View Doctor</a>
                        </li>
                         
                    </ul>
                </li>
            `,
            lab: `
                <a class="sidebar-brand d-flex align-items-center justify-content-center bg-light text-primary" href="index.php">
                    <div class="sidebar-brand-text mx-3 inline_heading">Lab</div>
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

        // Function to update the main sidebar
        function updateSidebar(category) {
            const content = sidebarContent[category] || '';
            $('.main-sidebar').html(content); // Update main sidebar content
            $('.category-link').parent().removeClass('active');
            $(`.category-link[data-category="${category}"]`).parent().addClass('active');
        }

        // Handle category link click
        $('.category-link').on('click', function() {
            const selectedCategory = $(this).data('category');
            localStorage.setItem('selectedCategory', selectedCategory); // Save selection
            updateSidebar(selectedCategory); // Update the sidebar
        });

        // Load the sidebar based on the stored selection or default to "pharmacy"
        const storedCategory = localStorage.getItem('selectedCategory') || 'pharmacy';
        updateSidebar(storedCategory);
    });
</script>