<!-- Enquiry Creation List Start -->
<div class="text-right">
    <button type="button" class="btn btn-primary " id="add_enquiry"><span class="fa fa-plus"></span>&nbsp; Add Enquiry</button>
    <button type="button" class="btn btn-primary" id="back_btn" style="display:none;"><span class="icon-arrow-left"></span>&nbsp; Back</button>
</div>
<br>
<div class="card enquiry_table_content">
    <div class="card-body">
        <div class="col-12">
            <table id="enquiry_create" class="table custom-table">
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Chit Value</th>
                        <th>Total Month</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<!-- Enquiry Creation List End -->

<!-- Enquiry Creation-->
<div id="enquiry_creation_content" style="display: none;">
    <form id="enquiry_creation" name="enquiry_creation" method="post" enctype="multipart/form-data">
        <input type="hidden" id="enquiryid" value="0">
        <!-- Row start -->
        <div class="row gutters">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Chit Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="chitvalue">Chit Value</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="chitvalue" name="chitvalue" placeholder="Enter Chit Value" tabindex="1">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="chitmonth">Total Month</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="chitmonth" name="chitmonth" placeholder="Enter Total Month" tabindex="2">
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" name="submit_enquiry_creation" id="submit_enquiry_creation" class="btn btn-primary" value="Submit" tabindex="3"><span class="icon-check"></span>&nbsp;Submit</button>
                        </div>
                    </div>
                </div>

                <div class="card mt-3 " id="basicinfocard" style="display:none;">
                    <input type="hidden" id="customerid" value="0">
                    <div class="card-header">
                        <h5 class="card-title">Basic Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="name">Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Customer Name" tabindex="4">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="customerstatus">Customer Status</label><span class="text-danger">*</span>
                                    <select class="form-control" id="customerstatus" tabindex="5">
                                        <option value="">Select Customer Status</option>
                                        <option value="1">New</option>
                                        <option value="2">Existing</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="mobilenumber">Mobile Number</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" id="mobilenumber" name="mobilenumber" placeholder="Enter Mobile Number" onKeyPress="if(this.value.length==10) return false;" tabindex="6">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="place">Place</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="place" name="place" placeholder="Enter Costumer Place" tabindex="7">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="remarks">Remarks</label><span class="text-danger">*</span>
                                    <textarea name="remarks" id="remarks" class="form-control" tabindex="8"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <button type="submit" name="addcustomer" id="addcustomer"
                                        class="btn btn-primary ec-addbtn" value="Submit" tabindex="9">Add</button>
                                </div>
                            </div>
                            <div class="col-12">
                                <table id="customer_create" class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th width="25">S.NO</th>
                                            <th>Name</th>
                                            <th>Customer Status</th>
                                            <th>Mobile Number</th>
                                            <th>Place</th>
                                            <th>Remark</th>
                                            <th>Action</th>
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
    </form>
</div>