<div class="row gutters">
    <div class="col-12">
        <div class="col-12 text-right">
            <button class="btn btn-primary add_user_btn"><span class="icon-add"></span>Add User Creation</button>
            <button class="btn btn-primary back_to_userList_btn" style="display: none;"><span class="icon-arrow-left"></span>&nbsp;Back</button>
        </div></br>
        <!----------------------------- CARD START  USER CREATION TABLE ------------------------------>
        <div class="card user_creation_table_content">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table id="user_creation_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>User Name</th>
                                    <th>Role</th>
                                    <th>Designation</th>
                                    <th>Branch Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!----------------------------- CARD END  USER CREATION TABLE ------------------------------>

        <!----------------------------- CARD START  USER CREATION FORM ------------------------------>
        <div id="user_creation_content" style="display: none;">
            <form id="user_creation_form" name="user_creation_form" method="post" enctype="multipart/form-data">
                <input type="hidden" id="user_creation_id" value="0">
                <input type="hidden" id="session_user_id">
                <!-- Row start -->
                <div class="row gutters">
                    <div class="col-12">
                        <!--- ---------------------- User Info  START----------------------------- -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">User Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="name">Name</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="name" name="name" tabindex="1" placeholder="Enter Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="user_id">User ID</label>
                                            <input type="text" class="form-control" id="user_id" name="user_id" tabindex="2" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="role">Role</label><span class="text-danger">*</span>
                                            <input type="hidden" id="role_name_id">
                                            <select class="form-control" id="role" name="role" tabindex="3">
                                                <option value="">Select Role</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-md-1 col-lg-1 text-right" style="margin-top: 18px;">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary modalBtnCss" data-toggle="modal" data-target="#add_role_modal" tabindex="4" onclick="getRoleTable()"><span class="icon-add"></span></button>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" id="address" name="address" tabindex="5" placeholder="Enter Address">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="place">Place</label><span class="text-danger">*</span>
                                            <input type="hidden" id="place_name_id">
                                            <select class="form-control" id="place" name="place" tabindex="6">
                                                <option value="">Select Place</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-md-1 col-lg-1 text-right" style="margin-top: 18px;">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary modalBtnCss" data-toggle="modal" data-target="#add_place_modal" tabindex="7" onclick="getPlaceTable()"><span class="icon-add"></span></button>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" tabindex="8" placeholder="Enter Email">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="mobile_no">Mobile No</label>
                                            <input type="number" class="form-control" id="mobile_no" name="mobile_no" tabindex="9" placeholder="Enter Mobile No" onKeyPress="if(this.value.length==10) return false;">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="designation">Designation</label><span class="text-danger">*</span>
                                            <input type="hidden" id="occ_name_id">
                                            <select class="form-control" id="designation" name="designation" tabindex="10">
                                                <option value="">Select Designation</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-md-1 col-lg-1 text-right" style="margin-top: 18px;">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary modalBtnCss" data-toggle="modal" data-target="#add_occ_modal" tabindex="11" onclick="getDesignationTable()"><span class="icon-add"></span></button>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="occ_detail">Occupation Details</label>
                                            <textarea class="form-control" name="occ_detail" id="occ_detail" placeholder="Enter About Occupation" tabindex="12"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--- ---------------------- User Info  END ----------------------------- -->

                        <!--- ---------------------- Credential Info START  ----------------------------- -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Credential Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="user_name">User Name</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="user_name" name="user_name" tabindex="11" placeholder="Enter User Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="password">Password</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="password" name="password" tabindex="12" placeholder="Enter Password">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm Password</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="confirm_password" name="confirm_password" tabindex="13" placeholder="Enter Confirm Password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--- ---------------------- Credential Info END  ----------------------------- -->

                        <!--- ---------------------- Mapping Info START  ----------------------------- -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Mapping Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="company_name">Company Name</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" tabindex="14" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="branch_name">Branch Name</label><span class="text-danger">*</span>
                                            <input type="hidden" id="branch_edit_it">
                                            <select class="form-control" id="branch_name" name="branch_name" tabindex="15" multiple>
                                                <option value="">Select Branch Name</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="collection_access">Collection Summary Access</label><span class="text-danger">*</span>
                                            <input type="hidden" id="loan_cat_edit_it">
                                            <select class="form-control" id="collection_access" name="collection_access" tabindex="18" >
                                                <option value="">Select Collection Summary Access</option>
                                                <option value="1">YES</option>
                                                <option value="2">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--- ---------------------- Mapping Info END  ----------------------------- -->

                        <!--- ---------------------- Screen Mapping START  ----------------------------- -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Screen Mapping <span class="text-danger">*</span></h5>
                            </div>
                            <div class="card-body" id="dynamic-menus">

                            </div>
                        </div>
                        <!--- ---------------------- Screen Mapping END  ----------------------------- -->

                        <div class="col-12 mt-3 text-right">
                            <button name="submit_user_creation" id="submit_user_creation" class="btn btn-primary" tabindex="51"><span class="icon-check"></span>&nbsp;Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" id="reset_btn" tabindex="52">Clear</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        <!----------------------------- CARD END  USER CREATION FORM------------------------------>
    </div>
</div>

<!-- /////////////////////////////////////////////////////////////////// Role Modal Start ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade" id="add_role_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Role</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="getRoleDropdown()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3 col-md-3 col-lg-3"></div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="add_role">Role</label><span class="text-danger">*</span>
                                <input class="form-control" name="add_role" id="add_role" tabindex="2" placeholder="Enter Role">
                                <input type="hidden" id="role_id">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <button name="submit_role" id="submit_role" class="btn btn-primary" tabindex="3" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <table id="role_table" class="custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.No.</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="4" onclick="getRoleDropdown()">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Role Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Place Modal Start ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade" id="add_place_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Place</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="getPlaceDropdown()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3 col-md-3 col-lg-3"></div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="add_place">Place</label><span class="text-danger">*</span>
                                <input class="form-control" name="add_place" id="add_place" tabindex="2" placeholder="Enter Place">
                                <input type="hidden" id="add_place_id" value='0'>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <button name="submit_place" id="submit_place" class="btn btn-primary" tabindex="3" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <table id="place_modal_table" class="custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.No.</th>
                                    <th>Place</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="17" onclick="getPlaceDropdown()">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Place Modal END ////////////////////////////////////////////////////////////////////// -->
 <!-- /////////////////////////////////////////////////////////////////// Occ Type Modal Start ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade" id="add_occ_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Designation</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="getOccupationDropdown()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3 col-md-3 col-lg-3"></div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="add_occ">Designation</label><span class="text-danger">*</span>
                                <input class="form-control" name="add_occ" id="add_occ" tabindex="2" placeholder="Enter Designation">
                                <input type="hidden" id="add_occ_id" value='0'>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <button name="submit_occ" id="submit_occ" class="btn btn-primary" tabindex="3" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <table id="occ_modal_table" class="custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.No.</th>
                                    <th>Designation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="17" onclick="getOccupationDropdown()">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Occ Type Modal END ////////////////////////////////////////////////////////////////////// -->