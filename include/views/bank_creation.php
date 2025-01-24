<!-- Bank Creation List Start -->
<div class="text-right">
    <button type="button" class="btn btn-primary " id="add_bank"><span class="fa fa-plus"></span>&nbsp; Add Bank Creation</button>
    <button type="button" class="btn btn-primary" id="back_btn" style="display:none;"><span class="icon-arrow-left"></span>&nbsp; Back </button>   
</div>
 <br>
<div class="card bank_table_content">
    <div class="card-body">
        <div class="col-12">

            <table id="bank_create" class="table custom-table">
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Bank Name</th>
                        <th>Account Number</th>
                        <th>Branch Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<!--Bank Creation List End-->
<!-- Bank Creation-->
<div id="bank_creation_content" style="display:none;">
    <form id="bank_creation" name="bank_creation" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" id="bank_id">

        <div class="row gutters">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Bank Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="bank_name"> Bank Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" pattern="[a-zA-Z\s]+" placeholder="Enter Bank Name" tabindex="1">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="bank_short_name"> Bank Short Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="bank_short_name" name="bank_Short_name" pattern="[a-zA-Z\s]+" placeholder="Enter Bank Short Name" tabindex="2">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="account_number"> Account Number</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" id="account_number" name="account_number" placeholder="Enter Account Number" tabindex="3">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="ifsc_code"> IFSC Code</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" pattern="[a-zA-Z\s]+" placeholder="Enter IFSC Code" tabindex="4">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="branch_name"> Branch Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="branch_name" name="branch_name" pattern="[a-zA-Z\s]+" placeholder="Enter Branch Name" tabindex="5">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="qr_code"> UPI QR Code</label>
                                    <input type="file" class="form-control" id="qr_code" name="qr_code" tabindex="6">
                                    <input type="hidden" id="inserted_qr_code">
                                    <div id="qr_code_filename"></div> 
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="gpay"> Gpay Number</label>
                                    <input type="number" class="form-control" id="gpay" name="gpay" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Gpay Number" tabindex="6">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="under_branch">Under Branch</label>&nbsp;<span class="text-danger">*</span>
                                    <input type="hidden" id="branch_name2">
                                    <select class="" id="under_branch" name="under_branch" tabindex='8' multiple>
                                        <option value=''>Select Branch name</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 ">
                <div class="text-right">

                    <button type="submit" name="submit_bank_creation" id="submit_bank_creation" class="btn btn-primary" value="Submit" tabindex="9"><span class="icon-check"></span>&nbsp;Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" tabindex="10">Clear</button>
                </div>
            </div>
        </div>
    </form>
</div>