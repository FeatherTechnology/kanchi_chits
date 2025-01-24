<!-- Branch Creation List Start -->
<div class="text-right">
    <button type="button" class="btn btn-primary" id="add_branch"><span class="fa fa-plus"></span>&nbsp; Add Branch Creation</button>
    <button type="button" class="btn btn-primary" id="back_btn"  style="display:none;"><span class="icon-arrow-left"></span>&nbsp; Back </button>
</div>
 <br>
<div class="card branch_table_content">
    <div class="card-body">
        <div class="col-12">

            <table id="branch_create" class="table custom-table dtable">
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Branch Code</th>
                        <th>Company Name</th>
                        <th>Branch Name</th>
                        <th>Place</th>
                        <th>State</th>
                        <th>District</th>
                        <th>Mobile Number</th>
                        <th>Email ID</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Branch Creation List End-->
<!-- Branch Creation-->
<div id="branch_creation_content" style="display:none;">
    <form id="branch_creation" name="branch_creation" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" id="branchid" value="0">
        <div class="row gutters">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">General Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="company_name"> Company Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="company_name" name="company_name" pattern="[a-zA-Z\s]+" placeholder="Enter Company Name" disabled tabindex="1">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="branch_code"> Branch Code</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="branch_code" name="branch_code" placeholder="Enter Branch Code" disabled tabindex="2">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="branch_name"> Branch Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="branch_name" name="branch_name" pattern="[a-zA-Z\s]+" placeholder="Enter Branch Name" tabindex="3">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="address"> Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" tabindex="4">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="state">State</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="state" name="state" tabindex="5">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="district">District</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="district" name="district" tabindex="6">
                                        <option value="">Select District</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="taluk">Taluk</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="taluk" name="taluk" tabindex="7">
                                        <option value="">Select Taluk</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="place"> Place</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="place" name="place" pattern="[a-zA-Z\s]+" placeholder="Enter Place" tabindex="8">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="pincode"> Pincode</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" id="pincode" name="pincode" maxlength="6" pattern="\d{6}" onKeyPress="if(this.value.length==6) return false;" placeholder="Enter Pincode" tabindex="9">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Communication Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="email_id"> E-Mail Id</label>
                                    <input type="email" class="form-control" id="email_id" name="email_id" placeholder="Enter E-Mail Id" tabindex="10">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number</label>
                                    <input type="number" class="form-control" id="mobile_number" name="mobile_number" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Mobile Number" tabindex="11">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="whatsapp">WhatsApp Number</label>
                                    <input type="number" class="form-control" id="whatsapp" name="whatsapp" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter WhatsApp Number" tabindex="12">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="landline">Landline Number</label>
                                    <div class="input-group" style="gap:12px">
                                        <input type="number" class="form-control" id="landline_code" name="landline_code" onKeyPress="if(this.value.length==5) return false;" tabindex="13" placeholder="Enter Code" style="max-width: 95px;">
                                        <input type="number" class="form-control" id="landline" name="landline" onKeyPress="if(this.value.length==8) return false;" placeholder="Enter Landline Number" tabindex="13">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="col-md-12 ">
                <div class="text-right">

                    <button type="submit" name="submit_branch_creation" id="submit_branch_creation" class="btn btn-primary" value="Submit" tabindex="14"><span class="icon-check"></span>&nbsp;Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" tabindex="15">Clear</button>
                </div>
            </div>

        </div>
    </form>
</div>