<div class="row gutters">
    <div class="col-12">
        <div class="col-12 text-right">
            <button class="btn btn-primary addcompanyBtn" id="add_company"><span class="icon-add"></span> Add Company Creation</button>
            <button class="btn btn-primary backBtn" style="display: none;"><span class="icon-arrow-left"></span> Back</button>
        </div></br>
        <!----------------------------- CARD START  COMPANY CREATION TABLE------------------------------>
        <div class="card wow company_table_content">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table id="company_creation_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th width="50">S.No.</th>
                                    <th>Company Name</th>
                                    <th>Place</th>
                                    <th>District</th>
                                    <th>Mobile No</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!----------------------------- CARD END  COMPANY CREATION TABLE------------------------------>


        <!----------------------------- CARD START  COMPANY CREATION FORM------------------------------>
        <div id="company_creation_content" style="display:none;">
            <form id="company_creation" name="company_creation" method="post" enctype="multipart/form-data">
                <input type="hidden" id="companyid">
                <!-- Row start -->
                <div class="row gutters">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">General Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Fields -->
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="company_name">Company Name</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="company_name" name="company_name" pattern="[a-zA-Z\s]+" placeholder="Enter Company Name" tabindex="1">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="address">Address</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" tabindex="2">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="state">State</label><span class="text-danger">*</span>
                                            <select class="form-control" id="state" name="state" tabindex="3">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="district">District</label><span class="text-danger">*</span>
                                            <select class="form-control" id="district" name="district" tabindex="4">
                                                <option value="">Select District</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="taluk">Taluk</label><span class="text-danger">*</span>
                                            <select class="form-control" id="taluk" name="taluk" tabindex="5">
                                                <option value="">Select Taluk</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="place">Place</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="place" name="place" pattern="[a-zA-Z\s]+" placeholder="Enter Place" tabindex="6">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="pincode">Pincode</label><span class="text-danger">*</span>
                                            <input type="number" class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode" onKeyPress="if(this.value.length==6) return false;" tabindex="7">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">Communication Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Fields -->
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="website">Website</label>
                                            <input type="text" class="form-control" id="website" name="website" placeholder="Enter Website Name" tabindex="8">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="mailid">Mail ID</label>
                                            <input type="email" class="form-control" id="mailid" name="mailid" placeholder="Enter Mail ID" tabindex="9">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="mobile">Mobile No.</label>
                                            <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile Number" onKeyPress="if(this.value.length==10) return false;" tabindex="10">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="whatsapp">Whatsapp No.</label>
                                            <input type="number" class="form-control" id="whatsapp" name="whatsapp" placeholder="Enter Whatsapp Number" onKeyPress="if(this.value.length==10) return false;" tabindex="11">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="landline">Landline Number</label>
                                            <div class="input-group" style="gap:12px">
                                                <input type="number" class="form-control" id="landline_code" name="landline_code" onKeyPress="if(this.value.length==5) return false;" tabindex="12" placeholder="Enter Code" style="max-width: 95px;">
                                                <input type="number" class="form-control" id="landline" name="landline" onKeyPress="if(this.value.length==8) return false;" placeholder="Enter Landline Number" tabindex="13">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-3 text-right">
                            <button name="submit_company_creation" id="submit_company_creation" class="btn btn-primary" tabindex="14"><span class="icon-check"></span>&nbsp;Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" tabindex="15">Clear</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        <!----------------------------- CARD END  COMPANY CREATION FORM------------------------------>

    </div>
</div>