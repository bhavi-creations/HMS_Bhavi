<div id="wrapper">


<?php
   include "../includes/sidebar.php";
   include "../includes/header.php";
   ?>

<div id="content-wrapper" class="d-flex flex-column   bg-white">


    <div id="content">


        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12   col-lg-12 dashBox">

                        <div class="col-md-3 col-lg-3  dash-box1">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="assets/images/b1.png" alt="" class="b1">
                                </div>
                                <div class="col-md-8">
                                    <img src="assets/images/bg.png" alt="" class="bgg">

                                </div>
                                <div class="col-md-12">
                                    <h6 class="Total12">Total Staff</h6>
                                    <h2 class="100total">100</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3  dash-box2">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="assets/images/b2.png" alt="" class="b2">
                                </div>
                                <div class="col-md-8">
                                    <img src="assets/images/bg.png" alt="" class="bgg">

                                </div>
                                <div class="col-md-12">
                                    <h6 class="Total12">Total Branches</h6>
                                    <h2 class="100total">04+</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3  dash-box3">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="assets/images/b3.png" alt="" class="b3">
                                </div>
                                <div class="col-md-8">
                                    <img src="assets/images/bg.png" alt="" class="bgg">

                                </div>
                                <div class="col-md-12">
                                    <h6 class="Total12">Total Billings</h6>
                                    <h2 class="100total">100+</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3  dash-box4">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="assets/images/b4.png" alt="" class="b2">
                                </div>
                                <div class="col-md-8">
                                    <img src="assets/images/bg.png" alt="" class="bgg">

                                </div>
                                <div class="col-md-12">
                                    <h6 class="Total12">No of Items</h6>
                                    <h2 class="100total">50+</h2>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12   col-lg-12 ul_border">
                        <h6 class="staff_dtls pt-4">Shop Sale </h6>

                        <img src="assets/images/g1.png" alt="" class="graph1 img-fluid">
                        <img src="assets/images/g2.png" alt="" class="graph">
                    </div>

                    <div class="col-md-12   col-lg-12 ul_border">


                        <div id="addReportTable" class="table-container  ">

                            <div class="container">
                                <div class="row d-flex flex-row justify-content-between pt-4 pb-3">
                                    <div class="">
                                        <h6 class="staff_dtls">Add Branch </h6>
                                    </div>

                                </div>
                            </div>


                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <div class="row">




                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt"> Upload Image</label>
                                            <input type="file" class="form-control field_input_bg" name="address">

                                        </div>
                                        <div class="col-md-6  mt-5">
                                            <label class="control-label mb-2 field_txt">Incharge Name</label>
                                            <input type="text" class="form-control field_input_bg" name="age">
                                        </div>

                                        <div class="col-md-6  mt-5">
                                            <label class="control-label mb-2 field_txt">Brance Area</label>
                                            <input type="number" class="form-control field_input_bg" name="phone">


                                        </div>
                                        <div class="col-md-6 mt-5">
                                            <label class="control-label mb-2 field_txt"> No. of staff</label>
                                            <input type="text" class="form-control field_input_bg" name="address">

                                        </div>
                                        <div class="col-md-6  mt-5">
                                            <label class="control-label mb-2 field_txt">Monthly Expenses</label>
                                            <input type="text" class="form-control field_input_bg" name="age">
                                        </div>
                                        <div class="col-md-6  mt-5">
                                            <label class="control-label mb-2 field_txt">Phone NO.</label>
                                            <input type="Number" class="form-control field_input_bg" name="age">
                                        </div>
                                        <div class="col-md-6  mt-5">
                                            <label class="control-label mb-2 field_txt">No. of Items</label>
                                            <input type="Number" class="form-control field_input_bg" name="age">
                                        </div>
                                        <div class="col-md-6 mt-5">


                                            <div
                                                class="row last_back_submit  d-flex flex-row justify-content-between  px-3">
                                                <button class="back_btn_staff">Back</button>
                                                <button class="submit_btn_staff">Submit</button>

                                            </div>

                                        </div>



                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="reportsTable" class="table-container active">


                            <div class="container">
                                <div class="row d-flex flex-row justify-content-between pt-4 pb-3">
                                    <div class="">
                                        <h6 class="staff_dtls">Items Sales</h6>
                                    </div>

                                </div>
                            </div>

                            <table class="table_stf">
                                <thead class="table_bg indexTr">
                                    <tr>
                                        <th class="th_names">Sno</th>
                                        <th class="th_names">Items</th>
                                        <th class="th_names">Total Sales</th>
                                        <th class="th_names">Orders</th>
                                        <th class="th_names">Rate</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr_hover">
                                        <td class="td_id_num">1</td>
                                        <td class="td_id_mob">

                                            <img src="img/profile (1).png" alt="John Doe" class="td_profile_pic">
                                            Ram
                                        </td>


                                        <td class="td_id_mob">20,000</td>
                                        <td class="td_id_num">800+</td>
                                        <td class="td_id_num">250/kg</td>
                                        <!-- <td>
                                               <button class="edit_icon"><i
                                                       class="fa-regular fa-pen-to-square"></i></button>
                                               <button class="dlt_icon"><i
                                                       class="fa-regular fa-trash-can"></i></button>
                                           </td> -->
                                    </tr>
                                    <!-- Add more rows as needed -->
                                </tbody>
                            </table>
                        </div>

                        <!-- <div id="totalBranchTable" class="table-container  ">


                               <div class="container">
                                   <div class="row d-flex flex-row justify-content-between pt-4 pb-3">
                                       <div class="">
                                           <h6 class="staff_dtls">Total Items</h6>
                                       </div>
                                       <div class="">
                                           <h6 class="kkd_brnch">Kakinada Branch
                                               <svg class="kkdIcon ml-3" xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                                   <path d="M17.7178 4.96555L10 12.1861L2.28216 4.96555C1.67358 4.33767 1.06501 4.32459 0.456432 4.9263C-0.152144 5.52802 -0.152144 6.11665 0.456432 6.6922L9.08714 14.8546C9.30844 15.1162 9.61272 15.247 10 15.247C10.3873 15.247 10.6916 15.1162 10.9129 14.8546L19.5436 6.6922C20.1521 6.11665 20.1521 5.52802 19.5436 4.9263C18.935 4.32459 18.3264 4.33767 17.7178 4.96555Z" fill="#202224" />
                                               </svg>
                                           </h6>
                                       </div>
                                   </div>
                               </div>

                               <table class="table_stf">
                                   <thead class="table_bg">
                                       <tr>
                                           <th class="th_names">ID</th>
                                           <th class="th_names">Items</th>                                                
                                           <th class="th_names">Kg's</th>
                                           <th class="th_names">Actions</th>

                                       </tr>
                                   </thead>
                                   <tbody>
                                       <tr class="tr_hover">
                                           <td class="td_id_num">1</td>

                                           <td class="td_id_num">
                                               <img src="img/profile (1).png" alt="John Doe" class="td_profile_pic">
                                               Kaja        
                                           </td>
                                           <td class="td_id_mob">55</td>
                                           
                                           <td>
                                               <button class="edit_icon"><i class="fa-regular fa-pen-to-square"></i></button>
                                               <button class="dlt_icon"><i class="fa-regular fa-trash-can"></i></button>
                                           </td>
                                       </tr>
                                       
                                   </tbody>
                               </table>
                           </div> -->

                    </div>
                </div>
            </div>
        </section>

    </div>


</div>



<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>




<?php
   include "../includes/footer.php";
   ?>
</div>