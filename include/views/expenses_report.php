<div class="row gutters">
    <div class="col-12">
        <div class="toggle-container col-12">
            <input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
            <input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
            <input type="button" id='expenses_report_btn' name='expenses_report_btn' class="toggle-button" style="background-color: #e2776f;color:white" value='Search'>
        </div> <br/>
        <!-- Loan Issue report Start -->
        <div class="card">
            <div class="card-body">
                <div class="col-12">
                <table id="accounts_expenses_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Invoice ID</th>
                                    <th>Branch</th>
                                    <th>Expense Category</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                </div>
            </div>
        </div>
        <!--Loan Issue report End-->
    </div>
</div>