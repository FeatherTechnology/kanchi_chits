<div class="row gutters">
    <div class="col-12">
        <div class="branch-div">
            <button class='btn btn-primary' name='auction_reminder_smsbtn' id='auction_reminder_smsbtn' title='Reminder for today auction group customer'> Send Reminder SMS </button>

            <select name="branch_id" id="branch_id" class="branch-dropdown">
                <option value="">Choose Branch</option>
            </select>
        </div></br>
        
        <!----------------------------- CARD START Group List ------------------------------>
        <div class="card group-list-card" style="display: none;">
            <div class="card-header" id="group_list_title">
                <div class="card-title dashboard-count-header">Group List</div>
            </div>
            <div class="card-body" id="group_list_body" style="display: none;">
                <div class="row card-row">
                    <div class="col-12">
                        <table id="group_creation_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th width="50">S.No.</th>
                                    <th>Group ID</th>
                                    <th>Group Name</th>
                                    <th>Chit Value</th>
                                    <th>Total Month</th>
                                    <th>Date</th>
                                    <th>Start Month</th>
                                    <th>End Month</th>
                                    <th>Commission(%)</th>
                                    <th>Branch</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!----------------------------- CARD END Group List ------------------------------>

        <!----------------------------- CARD START Auction List ------------------------------>
        <div class="card auction-list-card" style="display: none;">
            <div class="card-header" id="auction_list_title">
                <div class="card-title dashboard-count-header">Auction List</div>
            </div>
            <div class="card-body" id="auction_list_body" style="display: none;">
                <div class="row card-row">
                    <div class="col-12">
                        <table id="auction_list_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th width=50>S.No.</th>
                                    <th>Group ID</th>
                                    <th>Group Name</th>
                                    <th>Chit Value</th>
                                    <th>Total Month</th>
                                    <th>Date</th>
                                    <th>Auction Time</th>
                                    <th>Auction Month</th>
                                    <th>Branch</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!----------------------------- CARD END Auction List ------------------------------>

        <!----------------------------- CARD START Collection ------------------------------>
        <div class="card collection-card" style="display: none;">
            <div class="card-header" id="collection_title">
                <div class="card-title dashboard-count-header">Collection</div>
            </div>
            <div class="card-body" id="collection_body" style="display: none;">
                <div class="row card-row">
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <p class="count-head">Total Collection Amount</p>
                                    <p class="cnt-value-p" id="tot_paid">0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <p class="count-head">Today Collection Amount</p>
                                    <p class="cnt-value-p" id="today_paid">0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!----------------------------- CARD END Collection ------------------------------>

        <!----------------------------- CARD START Settlement ------------------------------>
        <div class="card settlement-card" style="display: none;">
            <div class="card-header" id="settlement_title">
                <div class="card-title dashboard-count-header">Settlement</div>
            </div>
            <div class="card-body" id="settlement_body" style="display: none;">
                <div class="row card-row">
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <p class="count-head">Total Settlement</p>
                                    <p class="cnt-value-p" id="tot_settle">0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <p class="count-head">Today Settlement</p>
                                    <p class="cnt-value-p" id="today_settle">0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!----------------------------- CARD END Settlement ------------------------------>
        <!----------------------------- CARD START Collection Summary ------------------------------>
        <div class="card collection-summary-card" style="display: none;">
            <div class="card-header" id="coll_summary_title" style="display: none;">
                <div class="card-title dashboard-count-header">Collection Summary</div>
            </div>
            <div class="card-body" id="coll_summ_body" style="display: none;">
                <div class="row card-row">
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <p class="count-head">Current Month Paid</p>
                                    <p class="cnt-value-p" id="month_paid">0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <p class="count-head">Current Month Unpaid</p>
                                    <p class="cnt-value-p" id="month_unpaid">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <p class="count-head">Previous Pending Amount</p>
                                    <p class="cnt-value-p" id="prev_pen_amount">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <p class="count-head">Total Outstanding</p>
                                    <p class="cnt-value-p" id="total_outstanding">0</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!----------------------------- CARD END Collection Summary ------------------------------>
    </div>
</div>