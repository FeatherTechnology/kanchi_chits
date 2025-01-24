<!----------------------------- CARD END-Collection TABLE ------------------------------>
<div class="row gutters">
    <div class="col-12">
        <div class="card" id="collection_list">
            <div class="card-header">
                <h5 class="card-title">Collection List</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table id="collection_list_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <!-- <th>Group ID</th> -->
                                    <th>Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>Mobile No</th>
                                    <th>Place</th>
                                    <th>Occupation</th>
                                    <th>Status</th>
                                    <th>Grace Period</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3 text-right" style="margin-bottom:20px">
            <button class="btn btn-primary" id="back_to_coll_list" style="display: none;"><span class="icon-arrow-left"></span> Back</button>
            <button class="btn btn-primary" id="back_to_pay_list" style="display: none;"><span class="icon-cancel"></span> Back</button>
        </div>

        <div id="coll_main_container" style="display:none">
            <!-- Row start -->
            <div class="row gutters colls-cntnr">
                <input type="hidden" id="group_id">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Customer Info</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="cus_id">Customer ID</label>
                                                <input type="text" class="form-control" id="cus_id" name="cus_id" tabindex="1" disabled>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="cus_name">Customer Name</label>
                                                <input type="text" class="form-control" id="cus_name" name="cus_name" pattern="[a-zA-Z\s]+" tabindex="2" disabled>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="place">Place</label>
                                                <input type="text" class="form-control " id="place" name="place" readonly tabindex="3">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="mobile1">Mobile</label>
                                                <input type="number" class="form-control" id="mobile1" name="mobile1" readonly onKeyPress="if(this.value.length==10) return false;" tabindex="4">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="occupation">Occupation</label>
                                                <input type="text" class="form-control " id="occupation" name="occupation" readonly tabindex="5">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="referred_by"> Referred By </label>
                                                <input type="text" class="form-control " id="referred_by" name="referred_by" readonly tabindex="6">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Group List</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <table id="group_list_table" class=" table custom-table">
                                        <thead>
                                            <tr>
                                                <th width="50">S.No.</th>
                                                <th>Group ID</th>
                                                <th>Group Name</th>
                                                <th>Chit Value</th>
                                                <th>Chit Amount</th>
                                                <th>Settlement</th>
                                                <th>Status</th>
                                                <th>Grace Period</th>
                                                <th>Charts</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /////////////////////////////////////////////////// Collection Info  START ///////////////////////////////////////// -->
            <div class="card coll_details" style="display: none;">
                <div class="card-header">
                    <h5 class="card-title">Collection Info</h5>
                </div>

                <input type="hidden" name="auction_id" id="auction_id">
                <input type="hidden" name="cus_mapping_id" id="cus_mapping_id">
                <input type="hidden" name="status" id="status">
                <input type="hidden" name="sub_status" id="sub_status">

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Group Name</label>&nbsp;<span class="text-danger totspan">*</span>
                                        <input type="text" class="form-control" readonly id="group_name" name="group_name" value='' tabindex='1'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Auction Month</label>&nbsp;<span class="text-danger paidspan">*</span>
                                        <input type="text" class="form-control" readonly id="auction_month" name="auction_month" value='' tabindex='2'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Date</label>&nbsp;<span class="text-danger balspan">*</span>
                                        <input type="text" class="form-control" readonly id="date" name="date" value='' tabindex='3'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Chit Value</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="text" class="form-control" readonly id="chit_value" name="chit_value" value='' tabindex='4'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Chit Amount</label>&nbsp;<span class="text-danger pendingspan">*</span>
                                        <input type="text" class="form-control" readonly id="chit_amt" name="chit_amt" value='' tabindex='5'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Pending</label>&nbsp;<span class="text-danger payablespan">*</span>
                                        <input type="text" class="form-control" readonly id="pending_amt" name="pending_amt" value='' tabindex='6'>
                                        <input type="hidden" class="form-control" readonly id="pend_amt" name="pend_amt">
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 till-date-int">
                                    <div class="form-group">
                                        <label for="disabledInput">Payable</label>&nbsp;<span class="text-danger ">*</span>
                                        <input type="text" class="form-control" readonly id="payable_amnt" name="payable_amnt" value='' tabindex='7'>
                                        <input type="hidden" class="form-control" readonly id="payableAmount" name="payableAmount">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /////////////////////////////////////////////////// Collection Info END ///////////////////////////////////////// -->

            <!-- /////////////////////////////////////////////////// Collection Track START ///////////////////////////////////////// -->
            <div class="card coll_details" style="display: none;">
                <div class="card-header">
                    <div class="card-title">Collection Track</div>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <!--Fields -->
                        <div class="col-md-12 ">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="collection_date">Collection Date</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="text" class="form-control" id="collection_date" name="collection_date" readonly tabindex='8'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="coll_mode">Collection Mode</label><span class="text-danger">*</span>
                                        <select type="text" class="form-control" id="coll_mode" name="coll_mode" tabindex="9">
                                            <option value="">Select Collection Mode</option>
                                            <option value="1">Cash</option>
                                            <option value="2">Bank Transfer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" id="bank_container" style="display: none;">
                                    <div class="form-group">
                                        <label for="bank_name">Bank Name</label>
                                        <select class="form-control" id="bank_name" name="bank_name" tabindex="10">
                                            <option value="">Select Bank</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 " id="transaction_container" style="display:none;">
                                    <div class="form-group">
                                        <label for="transaction_id">Transaction ID</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="number" class="form-control" id="transaction_id" name="transaction_id" value='' placeholder='Enter Transaction ID' tabindex='11'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 intLoanDiv">
                                    <div class="form-group">
                                        <label for="collection_amount">Collection Amount</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="number" class="form-control clearFields" id="collection_amount" name="collection_amount" value='' placeholder='Enter Collection Amount' tabindex='12'>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /////////////////////////////////////////////////// Collection Track END ///////////////////////////////////////// -->

            <!-- Submit Button Start -->
            <div class="col-md-12 coll_details" style="display: none;">
                <div class="text-right">
                    <button type="submit" name="submit_collection" id="submit_collection" class="btn btn-primary" value="Submit" tabindex='10'><span class="icon-check"></span>&nbsp;Submit</button>
                </div>
            </div>
            <!-- Submit Button End -->

        </div>

    </div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Commitment Add Modal START ////////////////////////////////////////////////////////////// -->
<div class="modal fade" id="add_commitment_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Add Commitment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="form-group">
                            <label for="comm_date "> Date </label> 
                            <input type="text" class="form-control" id="comm_date" name="comm_date" tabindex='1' readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="form-group">
                            <label for="label "> Label </label> <span class="required">&nbsp;*</span>
                            <input type="hidden" class="form-control" id="comm_label" name="comm_label">
                            <input type="text" class="form-control" id="label" name="label" tabindex='1'>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="form-group">
                            <label for="commitment_date"> Commitment Date</label><span class="required">&nbsp;*</span>
                            <input type="date" class="form-control" id="commitment_date" name="commitment_date" placeholder="Enter Commitment Date" tabindex="1">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="form-group">
                            <label for="remark"> Remark </label> <span class="required">&nbsp;*</span>
                            <textarea class="form-control" name="remark" id="remark" placeholder="Enter Remark" tabindex="1"></textarea>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
                        <button type="button" tabindex="4" name="add_commit" id="add_commit" class="btn btn-primary" style="margin-top: 19px;">Add</button>
                    </div>
                </div>
                </br>
                <div>
                    <table id="commit_form_table" class="table custom-table">
                        <thead>
                            <tr>
                                <th width="15%"> S.No </th>
                                <th>Date</th>
                                <th> Label </th>
                                <th>Commitment Date</th>
                                <th> Remark </th>
                                <th> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="printcollection" style="display: none"></div>
<!-- /////////////////////////////////////////////////////////////////// Commitment Add Modal END ////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// Due Chart Modal Start ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade bd-example-modal-lg" id="due_chart_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document" style="max-width: 70% !important">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="dueChartTitle">Due Chart</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" id="due_chart_table_div">
                    <table id="due_chart_table" class="table custom-table">
                        <thead>
                            <th>Auction Month</th>
                            <th>Date</th>
                            <th>Chit Amount</th>
                            <th>Payable</th>
                            <th>Collection Date</th>
                            <th>Collection Amount</th>
                            <th>Pending</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="4">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Due Chart Modal END ////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// Commitement Chart Modal Start ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade bd-example-modal-lg" id="commitment_chart_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document" style="max-width: 70% !important">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="dueChartTitle">Commitement Chart</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" id="commitment_chart_table_div">
                    <table id='commitment_chart_table' class="table custom-table">
                        <thead>
                            <th width="20">S.No</th>
                            <th>Date</th>
                            <th>Label</th>
                            <th>Commitment Date</th>
                            <th>Remark</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="4">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Commitement Chart Modal END ////////////////////////////////////////////////////////////////////// -->