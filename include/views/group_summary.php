<?php
//Format number in Indian Format
function moneyFormatIndia($num1)
{
    if ($num1 < 0) {
        $num = str_replace("-", "", $num1);
    } else {
        $num = $num1;
    }
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    if ($num1 < 0 && $num1 != '') {
        $thecash = "-" . $thecash;
    }

    return $thecash;
}
?>

<div class="radio-container" id="curr_closed">
    <div class="selector">
        <div class="selector-item">
            <input type="radio" id="group_current" name="customer_data_type" class="selector-item_radio" value="cus_profile" checked>
            <label for="group_current" class="selector-item_label">Current</label>
        </div>
        <div class="selector-item">
            <input type="radio" id="group_closed" name="customer_data_type" class="selector-item_radio" value="cus_summary">
            <label for="group_closed" class="selector-item_label">Closed</label>
        </div>
    </div>
</div>
<br>
<div class="text-right">
    <button type="button" class="btn btn-primary" id="back_btn" style="display:none;"><span class="icon-arrow-left"></span>&nbsp; Back </button>
</div>
<br>
<!----------------Group Table---------------------------->
<div class="card wow group_table_content">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table id="group_creation_table" class="table custom-table">
                    <thead>
                        <tr>
                            <th width="50">S.No.</th>
                            <th>Group ID</th>
                            <th>Group Name</th>
                            <th>Chit Value</th>
                            <th>Date</th>
                            <th>Branch</th>
                            <th>Group Status</th>
                            <th>Collection Status</th>
                            <th>Charts</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--------------Group table end-------------------------->
<!----------------------------- CARD Start-Auction  Detail TABLE ------------------------------>
<div class="card auction_detail_content" style="display: none;">
    <div class="card-header">
        <div class="card-title">Auction List</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table id="auction_table" class="table custom-table">
                    <thead>
                        <tr>
                            <th>Auction Month</th>
                            <th>Date</th>
                            <th>Auction Value</th>
                            <th>Customer</th>
                            <th>Auction Status</th>
                            <th>Group status</th>
                            <th>Collection status</th>
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
<!-----------------------------CARD END - Auction Detail TABLE --------------------------------->

<!----------------------------- CARD Start- Ledger View Chart ------------------------------>
<div class="card ledger_view_chart_model" style="display: none;">
    <div class="card-header">
        <div class="card-title">Ledger View</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12" id="ledger_view_table_div" style="overflow: auto;">
                
            </div>
        </div>
    </div>
</div>
<!-----------------------------CARD END - Ledger View Chart --------------------------------->

<!-- /////////////////////////////////////////////////////////////////// Auction Chart Modal Start ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade bd-example-modal-lg" id="auction_chart_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document" style="max-width: 70% !important">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="dueChartTitle">Auction Chart</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" id="due_chart_table_div">
                    <table id="auction_chart_table" class="table custom-table">
                        <thead>
                            <th>Auction Month</th>
                            <th>Date</th>
                            <th>Auction Value</th>
                            <th>Commision</th>
                            <th>Total amount</th>
                            <th>Chit Amount</th>
                            <th>Customer Name</th>
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
<!-- /////////////////////////////////////////////////////////////////// Auction Chart Modal END ////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// Settlement Chart Modal Start ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade bd-example-modal-lg" id="settlement_chart_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document" style="max-width: 70% !important">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="dueChartTitle">Settlement Chart</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" id="settle_chart_table_div">
                    <table id="settle_chart_table" class="table custom-table">
                        <thead>
                            <th>Auction Month</th>
                            <th>Group ID</th>
                            <th>Group Name</th>
                            <th>Customer ID</th>
                            <th>Customer Name</th>
                            <th>Settlement Date</th>
                            <th>Settlement Amount</th>
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
<!-- /////////////////////////////////////////////////////////////////// Settlement Chart Modal END ////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// Collection Chart Modal Start /////////////////////////////////////////////////////////////// -->
<div class="modal fade bd-example-modal-lg" id="collection_chart_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document" style="max-width: 70% !important">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="dueChartTitle">Collection Chart - Paid : <span id="paidValue">0</span> UnPaid : <span id="unpaidValue">0</span> Pending : <span id="pendingValue">0</span></h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="month_paid">
                <input type="hidden" id="month_unpaid">
                <input type="hidden" id="month_pending">
                <div class="container-fluid" id="collect_chart_table_div">
                    <table id="collect_chart_table" class="table custom-table">
                        <thead>
                            <th>SI.NO</th>
                            <th>Cus ID</th>
                            <th>Cus Name</th>
                            <th>Place</th>
                            <th>Occupation</th>
                            <th>Mobile</th>
                            <th>Action</th>
                            <th>Settlement</th>
                        </thead>
                        <tbody>
                            <!-- Dynamic table data will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="4">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- /////////////////////////////////////////////////////////////////// Collection Chart Modal END ////////////////////////////////////////////////////////////// -->

    <!-- /////////////////////////////////////////////////////////////////// Due Chart Modal Start ////////////////////////////////////////////////////////////////////// -->
<div class="modal fade bd-example-modal-lg" id="due_chart_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document" style="max-width: 70% !important">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="dueChartTitle">Due Chart - <span id="due_cus_info"> </span> </h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="closeDueChartModal()">
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
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeDueChartModal()" tabindex="4">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /////////////////////////////////////////////////////////////////// Due Chart Modal END ////////////////////////////////////////////////////////////////////// -->