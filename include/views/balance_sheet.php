<div class="row gutters">

    <div class="toggle-container dwnmrgn col-12">
        <input type="button" class="toggle-button" data-toggle='modal' data-target='#dayModal' value='Day Wise'>
        <input type="button" class="toggle-button" value='Today'>
        <input type="button" class="toggle-button" data-toggle='modal' data-target='#monthModal' value='Month Wise'>
        <select type="text" class="toggle-button" id='by_user' name='by_user'>
            <option value=''>Select User</option>
        </select>
    </div> <br>

    <!----------------------------- CARD START Balance Sheet ------------------------------>
    <div class="col-6">
        <div class="card balance_sheet">
            <div class="card-header">
                <h5 class="card-title">Balance Sheet</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <table id="balance_sheet_table" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th><b>Credit</b></th>
                                        <th><b>Debit</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>Opening Balance</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Collection Amount</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Investment</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Deposit</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>EL</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Exchange</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Contra</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Other Income</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Total Settlement Amount</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Total Expenses</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Closing Balance</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Total</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!----------------------------- CARD END Balance Sheet ------------------------------>

    <!----------------------------- CARD START Net Benefit ------------------------------>
    <div class="col-6">
        <div class="card net_benefit">
            <div class="card-header">
                <h5 class="card-title">Net Benefit</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <table id="net_benefit_table" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th><b>Credit</b></th>
                                        <th><b>Debit</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Benefit Amount</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Other Income</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Expenses</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Total</b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>Total Benefit</b></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!----------------------------- CARD END Net Benefit ------------------------------>

</div>

<!-- Modal for Day Choose -->
<div class="modal fade" id="dayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Day Wise</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row container">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
						<label for="to_date">From Date</label>
						<input type="date" name="from_date" id="from_date" class='form-control'>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
						<label for="to_date">To Date</label>
						<input type="date" name="to_date" id="to_date" class='form-control'>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id='submitDaywise'>Submit</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal for Month Choose -->
<div class="modal fade" id="monthModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content" style="background-color: white">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Month Wise</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row container">
					<div class="col-12">
						<label for="for_month">Month of</label>
						<input type="month" name="for_month" id="for_month" class='form-control'>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id='submitMonthwise'>Submit</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>