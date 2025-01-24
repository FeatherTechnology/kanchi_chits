<div class="col-12 text-right">
    <button class="btn btn-primary back_btn" style="display: none;"><span class="icon-arrow-left"></span>&nbsp;Back</button>
</div></br>
<!----------------------------- CARD START  Settlement TABLE ------------------------------>
<div class="card settlement_table_content">
    <div class="card-header">
        <div class="card-title">Settlement List</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table id="settlement_list_table" class="table custom-table">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Group ID</th>
                            <th>Group Name</th>
                            <th>Chit Value</th>
                            <th>Chit Date</th>
                            <th>Total Members</th>
                            <th>Total Month</th>
                            <th>Auction Month</th>
                            <th>Customer</th>
                            <th>Settlement Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!----------------------------- CARD END-Settlement TABLE ------------------------------>
<div id="settlement_content" style="display: none;">
    <form id="settlement_screen" name="settlement_screen" method="post" enctype="multipart/form-data">
        <input type="hidden" id="groupid">
        <input type="hidden" id="hand_cash">
        <input type="hidden" id="bank_cash">
        <input type="hidden" id="auction_date">
        <input type="hidden" id="set_amount">
        <!-- Row start -->
        <div class="row gutters">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Group Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Fields -->
                            <div class="col-4 col-sm-4 col-md-4	col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="group_id">Group ID</label>
                                    <input type="text" class="form-control" id="group_id" name="group_id" readonly tabindex="1" readonly>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4	col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="group_name">Group Name</label>
                                    <input type="text" class="form-control" id="group_name" name="group_name" tabindex="2" readonly>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4	col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="chit_value">Chit Value</label>
                                    <input type="text" class="form-control" id="chit_value" name="chit_value" tabindex="3" readonly>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4	col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="commission">Commission</label>
                                    <input type="text" class="form-control" id="commission" name="commission" tabindex="4" readonly>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4	col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="total_members">Total Members</label>
                                    <input type="number" class="form-control" id="total_members" name="total_members" tabindex="5" readonly>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4	col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="total_month">Total Month</label>
                                    <input type="number" class="form-control" id="total_month" name="total_month" tabindex="6" readonly>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4	col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="start_month">Start Month</label>
                                    <input type="month" class="form-control" id="start_month" name="start_month" tabindex="7" readonly>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4	col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="end_month">End Month</label>
                                    <input type="month" class="form-control" id="end_month" name="end_month" tabindex="8" readonly>
                                </div>
                            </div>
                            <input type="hidden" id="grp_month">
                        </div>
                    </div>
                </div>
                <!-------------------------------------------------Group card------------------------------------>
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
                                            <label for="customer_name">Customer Name</label><span class="text-danger">*</span>
                                            <select class="form-control" id="customer_name" name="customer_name" tabindex="9">
                                                <option value="">Select Customer Name</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="map_id"> Mapping ID</label>
                                            <input type="text" class="form-control " id="map_id" name="map_id" readonly tabindex="10">
                                            <input type="hidden" id="map_id_upd" name="map_id_upd">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="cus_id"> Customer ID</label>
                                            <input type="text" class="form-control " id="cus_id" name="cus_id" readonly tabindex="11">
                                            <input type="hidden" id="cus_id_upd" name="cus_id_upd">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="place">Place</label>
                                            <input type="text" class="form-control " id="place" name="place" readonly tabindex="12">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mobile1">Mobile</label>
                                            <input type="number" class="form-control" id="mobile1" name="mobile1" readonly onKeyPress="if(this.value.length==10) return false;" tabindex="13">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="occupation">Occupation</label>
                                            <input type="text" class="form-control " id="occupation" name="occupation" readonly tabindex="14">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="referred_by"> Referred By </label>
                                            <input type="text" class="form-control " id="referred_by" name="referred_by" readonly tabindex="15">
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
                                            <!-- <input type="file" class="form-control  personal_info_disble" id="pic" name="pic" tabindex="18">
                                            <input type="hidden" class="personal_info_disble" id="per_pic"> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!------------------------------------------------------Customer card End--------------------------->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Settlement Info
                            <button type="button" class="btn btn-primary" style="padding: 5px 35px; float: right;" id="auction_info" tabindex='15'>Auction Info</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="settle_date">Settlement Date</label>
                                    <input type="text" class="form-control" id="settle_date" name="settle_date" readonly tabindex="15">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="settle_amount">Settlement Amount</label>
                                    <input type="text" class="form-control" id="settle_amount" name="settle_amount" tabindex="16" readonly>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="settle_balance">Settlement Balance</label>
                                    <input type="text" class="form-control" id="settle_balance" name="settle_balance" tabindex="17" readonly>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="payment_type">Payment Type</label><span class="text-danger">*</span>
                                    <select class="form-control" id="payment_type" name="payment_type" tabindex="18">
                                        <option value="">Select Payment Type</option>
                                        <option value="1">Split Payment</option>
                                        <option value="2">Single Payment</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4" id="settle_type_container">
                                <div class="form-group">
                                    <label for="settle_type">Settlement Type</label><span class="text-danger">*</span>
                                    <select class="form-control" id="settle_type" name="settle_type" tabindex="19">
                                        <option value="">Select Settlement Type</option>
                                        <option value="1">Cash</option>
                                        <option value="2">Cheque</option>
                                        <option value="3">Bank Transfer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" id="bank_container" style="display: none;">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label><span class="text-danger">*</span>
                                    <select class="form-control" id="bank_name" name="bank_name" tabindex="20">
                                        <option value="">Select Bank</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" id="cash_container" style="display: none;">
                                <div class="form-group">
                                    <label for="settle_cash">Cash</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="settle_cash" name="settle_cash" tabindex="21">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 " id="cash_denom" style="margin-top: 18px; display: none;">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="add_grup" name="add_grup"
                                        data-toggle="modal" data-target="#denomination"
                                        onclick="calDenomination()"
                                        style="padding: 3px 20px; font-size: 14px;" tabindex='30'>
                                        <span class="icon-add"></span>&nbsp;Denomination
                                    </button>

                                    <!-- <button type="button" class="btn btn-primary" id="denomination" name="denomination" tabindex='16'><span class="icon-add"></span>&nbsp;Denomination</button> -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4" id="cheque_no_container" style="display: none;">
                                <div class="form-group">
                                    <label for="cheque_no">Cheque Number</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" id="cheque_no" name="cheque_no" tabindex="22">
                                </div>
                            </div>
                            <div class="col-4" id="cheque_val_container" style="display: none;">
                                <div class="form-group">
                                    <label for="cheque_val">Cheque Value</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="cheque_val" name="cheque_val" tabindex="23">
                                </div>
                            </div>
                            <div class="col-4" id="cheque_remark_container" style="display: none;">
                                <div class="form-group">
                                    <label for="cheque_remark">Cheque Remark</label>
                                    <input type="text" class="form-control" id="cheque_remark" name="cheque_remark" tabindex="24">
                                </div>
                            </div>
                            <div class="col-4" id="transaction_id_container" style="display: none;">
                                <div class="form-group">
                                    <label for="transaction_id">Transaction ID</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" id="transaction_id" name="transaction_id" tabindex="24">
                                </div>
                            </div>
                            <div class="col-4" id="transaction_val_container" style="display: none;">
                                <div class="form-group">
                                    <label for="transaction_val">Transaction Value</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="transaction_val" name="transaction_val" tabindex="25">
                                </div>
                            </div>
                            <div class="col-4" id="transaction_remark_container" style="display: none;">
                                <div class="form-group">
                                    <label for="transaction_remark">Transaction Remark</label>
                                    <input type="text" class="form-control" id="transaction_remark" name="transaction_remark" tabindex="26">
                                </div>
                            </div>
                            <div class="col-4" id="balance_remark_container" style="display: none;">
                                <div class="form-group">
                                    <label for="balance_amount">Balance Amount</label>
                                    <input type="text" class="form-control" id="balance_amount" name="balance_amount" readonly tabindex="27">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-----------------------------------------------------Settlement Card End-------------------------->
                <!--- -------------------------------------- Document Info START ------------------------------- -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Document Info
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_doc_info_modal" onclick="getDocGuarantor();getDocCreationTable();groupData();" style="padding: 5px 35px; float: right;" tabindex='29'><span class="icon-add"></span></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <table id="document_info" class="table custom-table">
                                        <thead>
                                            <tr>
                                                <th width="20">S.NO</th>
                                                <th>Group Name</th>
                                                <th>Group ID</th>
                                                <th>Auction Month</th>
                                                <th>Document Name</th>
                                                <th>Document Type</th>
                                                <th>Holder Name</th>
                                                <th>Relationship</th>
                                                <th>Remarks</th>
                                                <th>Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--- -------------------------------------- Document Info END ------------------------------- -->

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Cash Acknowledgement</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Fields -->
                            <div class="col-4 col-sm-4 col-md-4	col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="gua_name">Name</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="gua_name" name="gua_name" tabindex="28">
                                        <option value="">Select Guarantor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4	col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="gua_relationship">Relationship</label>
                                    <input type="text" class="form-control" id="gua_relationship" name="gua_relationship" tabindex="29" readonly>
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4" id="deno_upload_cont" style="display:none">
                                <div class="form-group">
                                    <label for="den_upload">Denomination Upload</label><span class="text-danger">*</span>
                                    <span id="denom_data"></span> <!-- This will contain the links -->
                                    <input type="file" class="form-control" name="den_upload" id="den_upload" tabindex='9'>
                                    <input type="hidden" name="den_upload_edit" id="den_upload_edit">
                                </div>
                            </div>
                            <div class="col-12">
                                <table id="guarantor_table" class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Settlement Date</th>
                                            <th>Settlement Amount</th>
                                            <th>Name</th>
                                            <th>Relationship</th>
                                            <th>Upload</th>
                                        </tr>
                                    </thead>
                                    <tbody> </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!------------------------------------------------------Cash AcknowledgCard End------------------->
            </div>
        </div>

        <div class="col-12 mt-3 text-right">
            <button name="submit_settle_info" id="submit_settle_info" class="btn btn-primary" tabindex="19"><span class="icon-check"></span>&nbsp;Submit</button>
            <button type="reset" id="reset_clear" class="btn btn-outline-secondary" tabindex="20">Clear</button>
        </div>
    </form>
</div>
<!-- ------------------------------------------------------------ Document Info Modal START --------------------------------------------------------------- -->
<div class="modal fade" id="add_doc_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Document Info</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="getDocInfoTable();refreshDocModal();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="doc_info_form">
                        <input type="hidden" name="doc_info_id" id='doc_info_id'>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="grp_name">Group Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="grp_name" id="grp_name" tabindex="2" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="grp_id">Group ID</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="grp_id" id="grp_id" tabindex="1" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="auction_month">Auction Month</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="auction_month" id="auction_month" tabindex="3" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_name">Document Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="doc_name" id="doc_name" tabindex="5" placeholder="Enter Document Name">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_type">Document Type</label><span class="text-danger">*</span>
                                    <select class="form-control" name="doc_type" id="doc_type" tabindex="5">
                                        <option value="">Select Document Type</option>
                                        <option value="1">Original</option>
                                        <option value="2">Xerox</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_holder_name">Holder Name</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="doc_holder_name" name="doc_holder_name" tabindex="6">
                                        <option value="">Select Holder Name</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_relationship">Relationship</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="doc_relationship" id="doc_relationship" tabindex="7" placeholder="Relationship" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="remarks">Remark</label>
                                    <textarea class="form-control" name="remarks" id="remarks" placeholder="Enter Remarks" tabindex="8"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_upload"> Upload</label>
                                    <input type="file" class="form-control" name="doc_upload" id="doc_upload" tabindex='9'>
                                    <input type="hidden" name="doc_upload_edit" id="doc_upload_edit">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <button name="submit_doc_info" id="submit_doc_info" class="btn btn-primary" tabindex="10" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button id="clear_doc_form" class="btn btn-outline-secondary" style="margin-top: 18px;" tabindex="8">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-12 overflow-x-cls">
                        <table id="doc_creation_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.No.</th>
                                    <th>Group Name</th>
                                    <th>Group ID</th>
                                    <th>Auction Month</th>
                                    <th>Document Name</th>
                                    <th>Document Type</th>
                                    <th>Holder Name</th>
                                    <th>Relationship</th>
                                    <th>Remarks</th>
                                    <th>Upload</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="getDocInfoTable();refreshDocModal()" tabindex="8">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- ------------------------------------------------------------ Document Info Modal END --------------------------------------------------------------- -->
<!--Print Info Modal-->
<div class="modal fade" id="denomination" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cash Denomination</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetDenominationTable()" tabindex="1">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-end">
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary" onclick="printDenomination();">Print</button>
                    </div>
                </div>
                <div id="denominationContent">
                    <form id="family_form">
                        <div class="row">
                            <input type="hidden" name="family_id" id='family_id'>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="grop_id">Group ID</label>
                                    <input type="text" class="form-control" name="grop_id" id="grop_id" tabindex="1" readonly>
                                    <input type="hidden" id="addfam_name_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="denon_name">Group Name</label>
                                    <input type="text" class="form-control" name="denon_name" id="denon_name" tabindex="1" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="cht_value">Chit Value</label>
                                    <input type="text" class="form-control" name="cht_value" id="cht_value" tabindex="1" readonly>
                                    <input type="hidden" id="addoccupation_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="auct_date">Date</label>
                                    <input type="text" class="form-control" name="auct_date" id="auct_date" tabindex="1" readonly>
                                    <input type="hidden" id="addaadhar_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="auc_month">Auction Month</label>
                                    <input type="text" class="form-control" name="auc_month" id="auc_month" tabindex="1" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="act_val">Auction Value</label>
                                    <input type="text" class="form-control" name="act_val" id="act_val" tabindex="1" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="cht_com">Commission</label>
                                    <input type="text" class="form-control" name="cht_com" id="cht_com" tabindex="1" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="total_val">Total Amount</label>
                                    <input type="text" class="form-control" name="total_val" id="total_val" tabindex="1" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="set_val">Settlement Amount</label>
                                    <input type="text" class="form-control" name="set_val" id="set_val" tabindex="1" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <table class="table table-bordered" id="denominationTable">
                                <thead>
                                    <tr>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Total Value</th>
                                    </tr>
                                </thead>
                                <tbody id="denominationTableBody">
                                    <tr>
                                        <td>500</td>
                                        <td><input type="number" class="form-control " value="" min="0"></td>
                                        <td><input type="text" class="form-control" value="0" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>200</td>
                                        <td><input type="number" class="form-control" value="" min="0"></td>
                                        <td><input type="text" class="form-control" value="0" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>100</td>
                                        <td><input type="number" class="form-control" value="" min="0"></td>
                                        <td><input type="text" class="form-control" value="0" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>50</td>
                                        <td><input type="number" class="form-control" value="" min="0"></td>
                                        <td><input type="text" class="form-control" value="0" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>20</td>
                                        <td><input type="number" class="form-control" value="" min="0"></td>
                                        <td><input type="text" class="form-control" value="0" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td><input type="number" class="form-control" value="" min="0"></td>
                                        <td><input type="text" class="form-control" value="0" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td><input type="number" class="form-control" value="" min="0"></td>
                                        <td><input type="text" class="form-control" value="0" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="number" class="form-control" value="" min="0"></td>
                                        <td><input type="text" class="form-control" value="0" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="number" class="form-control" value="" min="0"></td>
                                        <td><input type="text" class="form-control" value="0" readonly></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Total</b></td>
                                        <td><input type="text" class="form-control" id="totalAmount" value="0" readonly></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row" style="margin-top: 50px;">
                                <div class="col-md-6">
                                    <h5>Manager's Signature</h5>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h5>Customer's Signature</h5>
                                </div>
                            </div>
                        </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="resetDenominationTable()" tabindex="1">Close</button>
            </div>
        </div>
    </div>
</div>
<!--Print Modal End-->

<!---------------------------------------------------------CalCulation Modal Start--------------------------------------------------------------------------->
<div class="modal fade" id="add_Calculation_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Calculation</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="closeChartsModal()" tabindex="1" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <button name="print_cal" id="print_cal" class="btn btn-primary" tabindex="2" style="margin-left: 650px;"><span class="icon-download"></span>&nbsp;Download</button>
                    <form id="Calculation_form">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="calc_group_name">Group Name</label>
                                    <input class="form-control" name="calc_group_name" id="calc_group_name" tabindex="3" placeholder="Enter Group Name" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="calc_auction_month">Auction Month</label>
                                    <input class="form-control" name="calc_auction_month" id="calc_auction_month" tabindex="4" placeholder="Enter Auction Month" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="cal_date">Date</label>
                                    <input class="form-control" name="cal_date" id="cal_date" tabindex="5" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="calc_chit_value">Chit Value</label>
                                    <input class="form-control" name="calc_chit_value" id="calc_chit_value" tabindex="6" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="calc_auction_value">Auction Value</label>
                                    <input class="form-control" name="calc_auction_value" id="calc_auction_value" tabindex="7" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="calc_commission">Commission</label>
                                    <input class="form-control" name="calc_commission" id="calc_commission" tabindex="8" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="calc_total_value">Total Value</label>
                                    <input class="form-control" name="calc_total_value" id="calc_total_value" tabindex="9" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="calc_chit_amount">Chit Amount</label>
                                    <input class="form-control" name="calc_chit_amount" id="calc_chit_amount" tabindex="10" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="11">Close</button>
            </div>
        </div>
    </div>
</div>
<!------------------------------------------------------------Calculation Modal End------------------------------------------------------------------------>