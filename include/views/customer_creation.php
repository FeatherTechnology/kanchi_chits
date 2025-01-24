<div class="text-right">
    <button type="button" class="btn btn-primary " id="add_customer"><span class="fa fa-plus"></span>&nbsp; Add Customer Creation</button>
    <button type="button" class="btn btn-primary" id="back_btn" style="display:none;"><span class="icon-arrow-left"></span>&nbsp; Back </button>
</div>
<br>
<div class="card customer_table_content">
    <div class="card-body">
        <div class="col-12">

            <table id="customer_create" class="table custom-table">
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Customer ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Mobile No</th>
                        <th>Place</th>
                        <th>Limit</th>
                        <th>Referred By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="customer_creation_content" style="display:none;">
    <form id="customer_creation" name="customer_creation" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" id="customer_id">

        <div class="row gutters">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Reference Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="reference_type">How To Know</label>
                                    <select type="text" class="form-control knowData" id="reference_type" name="reference_type" tabindex="1">
                                        <option value="">Select Reference Type</option>
                                        <option value="1">Promotion</option>
                                        <option value="2">Customer</option>
                                        <option value="3">Well Known Person</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 toRefresh" id="cus_name_container" style="display:none;">
                                <div class="form-group">
                                    <label for="cus_name">Existing Customer</label>
                                    <select type="text" class="form-control knowData" id="cus_name" name="cus_name" tabindex="2">
                                        <option value="">Select Existing Customer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 toRefresh" id="name_container" style="display:none;">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="hidden" id="custom_name_edit" name="custom_name_edit">
                                    <input type="text" class="form-control knowData" id="name" name="name" placeholder="Enter Name" tabindex="3">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 toRefresh" id="mobile_container" style="display:none;">
                                <div class="form-group">
                                    <label for="mobile">Mobile Number</label>
                                    <input type="text" class="form-control knowData" id="mobile" name="mobile" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Mobile Number" tabindex="4">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 toRefresh" id="cus_id_container" style="display:none;">
                                <div class="form-group">
                                    <label for="ref_cus_id"> Customer ID</label>
                                    <input type="text" class="form-control knowData" id="ref_cus_id" name="ref_cus_id" disabled placeholder="Enter Customer ID" tabindex="5" maxlength="14">
                                    <input type="hidden" id="ref_cus_id_upd" name="ref_cus_id_upd">
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 toRefresh" id="declaration_container" style="display:none;">
                                <div class="form-group">
                                    <label for="declaration">Declaration</label>
                                    <textarea class="form-control knowData" name="declaration" id="declaration" placeholder="Enter Declaration" tabindex="6"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Customer Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="cus_id"> Customer ID</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control " id="cus_id" name="cus_id" readonly tabindex="7">
                                            <input type="hidden" id="cus_id_upd" name="cus_id_upd">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="aadhar_number">Aadhar Number</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="aadhar_number" id="aadhar_number" tabindex="8" maxlength="14" data-type="adhaar-number" placeholder="Enter Aadhar Number">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="first_name">First Name</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control " id="first_name" name="first_name" placeholder="Enter First name" tabindex="9">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label><span class="text-danger">*</span>
                                            <input type="last_name" class="form-control" id="last_name" name="last_name" placeholder="Enter Last name" tabindex="10">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="dob"> DOB</label>
                                            <input type="date" class="form-control" id="dob" name="dob" placeholder="Enter Date Of Birth" tabindex="11">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="age"> Age</label>
                                            <input type="number" class="form-control" id="age" name="age" readonly placeholder="Age" tabindex="12">
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
                                        <div class="form-group">
                                            <label for="place">Place</label><span class="text-danger">*</span>
                                            <input type="hidden" id="place_name_id">
                                            <select class="form-control" id="place" name="place" tabindex="13">
                                                <option value="">Select Place</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-md-1 col-lg-1 text-right" style="margin-top: 18px;">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary modalBtnCss" data-toggle="modal" data-target="#add_place_modal" tabindex="14" onclick="getPlaceTable()"><span class="icon-add"></span></button>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mobile1">Mobile Number 1</label><span class="text-danger">*</span>
                                            <input type="number" class="form-control" id="mobile1" name="mobile1" placeholder="Enter Mobile Number 1" onKeyPress="if(this.value.length==10) return false;" tabindex="15">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mobile2">Mobile Number 2</label>
                                            <input type="number" class="form-control" id="mobile2" name="mobile2" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Mobile Number 2" tabindex="16">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>Choose Mobile Number for WhatsApp:</label><br>
                                            <label>
                                                <input type="radio" name="mobile_whatsapp" value="mobile1" id="mobile1_radio">
                                                Mobile Number 1
                                            </label><br>
                                            <label>
                                                <input type="radio" name="mobile_whatsapp" value="mobile2" id="mobile2_radio">
                                                Mobile Number 2
                                            </label>
                                            <input type="hidden" id="selected_mobile_radio">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="whatsapp">WhatsApp</label>
                                            <input type="number" class="form-control" id="whatsapp" name="whatsapp" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter WhatsApp Number" tabindex="17">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="address"> Address </label><span class="text-danger">*</span>
                                            <textarea class="form-control" name="address" id="address" placeholder="Enter Address" tabindex="18"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="native_address"> Native Address </label>
                                            <textarea class="form-control" name="native_address" id="native_address" placeholder="Enter Native Address" tabindex="19"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="pic"> Photo</label><br>
                                            <img id='imgshow' class="img_show" src='img\avatar.png' />
                                            <input type="file" class="form-control  personal_info_disble" id="pic" name="pic" tabindex="20">
                                            <input type="hidden" class="personal_info_disble" id="per_pic">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Source Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="occupation">Occupation</label>
                                    <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Enter Occupation" tabindex="21">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="occ_detail">Occupation Detail</label>
                                    <input type="text" class="form-control" id="occ_detail" name="occ_detail" placeholder="Enter Occupation Detail" tabindex="22">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="occ_place">Occupation Place</label>
                                    <input type="text" class="form-control" id="occ_place" name="occ_place" placeholder="Enter Occupation Place" tabindex="23">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="source">Source</label>
                                    <input type="text" class="form-control" id="source" name="source" placeholder="Enter source" tabindex="24">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="income">Income</label>
                                    <input type="number" class="form-control" id="income" name="income" placeholder="Enter Income" tabindex="25">
                                </div>
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12">
                                <div class="form-group">
                                    <label for="add_src"> </label>
                                    <input type="button" class="btn btn-primary modalBtnCss" id="add_src" name="add_src" value="Add" tabindex="26" style="margin: 16px;">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="tot_income"> Total Income</label>
                                    <input type="text" class="form-control" id="tot_income" name="tot_income" placeholder="Enter Total Income" disabled tabindex="27">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="chit_limit">Chit Limit</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="chit_limit" name="chit_limit" placeholder="Enter Chit limit" tabindex="28">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="reference">Reference</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="reference" name="reference" tabindex="29">
                                        <option value="">Select Reference</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <table id="source_create" class="table custom-table">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Occupation</th>
                                    <th>Occupation Detail</th>
                                    <th>Occupation Place</th>
                                    <th>Source</th>
                                    <th>Income</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Family Info <span class="text-danger">*</span>
                            <button type="button" class="btn btn-primary" id="add_group" name="add_group" data-toggle="modal" data-target="#add_fam_info_modal" onclick="getFamilyTable()" style="padding: 5px 35px; float: right;" tabindex='30'><span class="icon-add"></span></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <table id="fam_info_table" class="table custom-table">
                                        <thead>
                                            <tr>
                                                <th width="20">S.NO</th>
                                                <th>Name</th>
                                                <th>Relationship</th>
                                                <th>Age</th>
                                                <th>Live/Deceased</th>
                                                <th>Occupation</th>
                                                <th>Aadhar No</th>
                                                <th>Mobile No</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Guarantor Info <span class="text-danger">*</span>
                            <button type="button" class="btn btn-primary" id="add_guarantor" name="add_guarantor" data-toggle="modal" data-target="#add_guarantor_info_modal" onclick="getGuarantorTable();getGuarantorFamily()" style="padding: 5px 35px; float: right;" tabindex='31'><span class="icon-add"></span></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <table id="guar_info_table" class="table custom-table">
                                        <thead>
                                            <tr>
                                                <th>S.NO</th>
                                                <th>Name</th>
                                                <th>Relationship</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 ">
                <div class="text-right">

                    <button type="submit" name="submit_cus_creation" id="submit_cus_creation" class="btn btn-primary" value="Submit" tabindex="32"><span class="icon-check"></span>&nbsp;Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" tabindex="32">Clear</button>
                </div>
            </div>
        </div>
    </form>
</div>

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
                                <input class="form-control" name="add_place" id="add_place" tabindex="1" placeholder="Enter Place">
                                <input type="hidden" id="add_place_id" value='0'>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <button name="submit_place" id="submit_place" class="btn btn-primary" tabindex="1" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
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
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="1" onclick="getPlaceDropdown()">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Place Modal END ////////////////////////////////////////////////////////////////////// -->
<!--Family Info Modal-->
<div class="modal fade" id="add_fam_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Family Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="getFamilyInfoTable();getGuarantorFamily()" tabindex="1">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="family_form">
                        <div class="row">
                            <input type="hidden" name="family_id" id='family_id'>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_name">Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="fam_name" id="fam_name" tabindex="1" placeholder="Enter Name">
                                    <input type="hidden" id="addfam_name_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_relationship">Relationship</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="fam_relationship" name="fam_relationship" tabindex="1">
                                        <option value=""> Select Relationship </option>
                                        <option value="Father"> Father </option>
                                        <option value="Mother"> Mother </option>
                                        <option value="Spouse"> Spouse </option>
                                        <option value="Son"> Son </option>
                                        <option value="Daughter"> Daughter </option>
                                        <option value="Brother"> Brother </option>
                                        <option value="Sister"> Sister </option>
                                        <option value="Other"> Other </option>
                                    </select>
                                    <input type="hidden" id="addrelationship_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_age">Age</label>
                                    <input type="number" class="form-control" name="fam_age" id="fam_age" tabindex="1" placeholder="Enter Age">
                                    <input type="hidden" id="addage_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_live">Live/Deceased</label>
                                    <select class="form-control" id="fam_live" name="fam_live" tabindex="1">
                                        <option value="">Select Live/Deceased</option>
                                        <option value="1">Live</option>
                                        <option value="2">Deceased</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_occupation">Occupation</label>
                                    <input type="text" class="form-control" name="fam_occupation" id="fam_occupation" tabindex="1" placeholder="Enter Occupation">
                                    <input type="hidden" id="addoccupation_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_aadhar">Aadhar No</label>
                                    <input type="text" class="form-control" name="fam_aadhar" id="fam_aadhar" tabindex="1" maxlength="14" data-type="adhaar-number" placeholder="Enter Aadhar Number">
                                    <input type="hidden" id="addaadhar_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_mobile">Mobile No</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" name="fam_mobile" id="fam_mobile" onKeyPress="if(this.value.length==10) return false;" tabindex="1" placeholder="Enter Mobile Number">
                                    <input type="hidden" id="addmobile_id" value='0'>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="" style="visibility:hidden"></label><br>
                                    <button name="submit_family" id="submit_family" class="btn btn-primary" tabindex="1"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button id="clear_fam_form" class="btn btn-outline-secondary" tabindex="">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 overflow-x-cls">
                        <table id="family_creation_table" class="custom-table">
                            <thead>
                                <tr>
                                    <th width="10">S.No.</th>
                                    <th>Name</th>
                                    <th>Relationship</th>
                                    <th>Age</th>
                                    <th>Live/Deceased</th>
                                    <th>Occupation</th>
                                    <th>Aadhar No</th>
                                    <th>Mobile No</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="1" onclick="getFamilyInfoTable();getGuarantorFamily()">Close</button>
            </div>
        </div>
    </div>
</div>
<!--Family Modal End-->
<!--Guarantor Info Modal-->
<div class="modal fade" id="add_guarantor_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Guarantor Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="getGuarantorInfoTable();updateFieldsVisibility()" tabindex="1">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="guarantor_form">
                        <div class="row">
                            <input type="hidden" name="guarantor_id" id="guarantor_id">
                            <input type="hidden" name="relationship_type" id="relationship_type">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="gua_name">Guarantor Name</label><span class="text-danger">*</span>
                                    <select class="form-control" id="gua_name" name="gua_name" tabindex="1">
                                        <option value="">Select Guarantor Name</option>
                                        <option value="1">Family Members</option>
                                        <option value="2">Existing Customer</option>
                                        <option value="3">Others</option>
                                    </select>
                                    <input type="hidden" id="addgrelationship_id" value="0">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="fam_name_container" style="display:none;">
                                <div class="form-group">
                                    <label for="gua_family">Family Member</label><span class="text-danger">*</span>
                                    <input type="hidden" id="gua_name_edit" value="0">
                                    <select class="form-control" id="gua_family" name="gua_family" tabindex="1">
                                        <option value>Select Family Member</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="name1_container" style="display:none;">
                                <div class="form-group">
                                    <label for="guarantor1_name">Relationship</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="guarantor1_name" id="guarantor1_name" tabindex="1" readonly placeholder="Enter Name">
                                    <input type="hidden" id="addgua_name_id" value="0">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="name2_container" style="display:none;">
                                <div class="form-group">
                                    <label for="other_name">Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="other_name" id="other_name" tabindex="1" placeholder="Enter Name">
                                    <input type="hidden" id="addgua_name_id" value="0">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="existing_cus_container" style="display:none;">
                                <div class="form-group">
                                    <label for="existing_cus">Existing Customer</label><span class="text-danger">*</span>
                                    <input type="hidden" id="customer_name_edit" value="0">
                                    <select class="form-control" id="existing_cus" name="existing_cus" tabindex="1">
                                        <option value="">Select Existing Customer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="details_container" style="display:none;">
                                <div class="form-group">
                                    <label for="details">Details</label>
                                    <textarea class="form-control" name="details" id="details" placeholder="Enter Details" tabindex="1"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" id="photo_container">
                                <div class="form-group">
                                    <label for="gu_pic">Photo</label><br>
                                    <img id='gur_imgshow' class="img_show" src='img/avatar.png' />
                                    <input type="file" class="form-control" id="gu_pic" name="gu_pic" tabindex="1">
                                    <input type="hidden" id="gur_pic">
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                                <div class="form-group">
                                    <button name="submit_guarantor" id="submit_guarantor" class="btn btn-primary" tabindex="1"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button id="clear_gua_form" class="btn btn-outline-secondary" tabindex="1">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 overflow-x-cls">
                        <table id="guarantor_creation_table" class="custom-table">
                            <thead>
                                <tr>
                                    <th width="10">S.No.</th>
                                    <th>Name</th>
                                    <th>Relationship</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="1" onclick="getGuarantorInfoTable();updateFieldsVisibility()">Close</button>
            </div>
        </div>
    </div>
</div>

<!--Guarantor Modal End-->