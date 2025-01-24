<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$from_date = $_POST['params']['from_date'];
$to_date = $_POST['params']['to_date'];

$trans_cat = ["1" => 'Deposit', "2" => 'Investment', "3" => 'EL', "4" => 'Exchange', "5" => 'Bank Deposit', "6" => 'Bank Withdrawal', "7" => 'Chit Advance', "8" => 'Other Income', "9" => 'Bank Unbilled'];
$cash_type = ["1" => 'Hand Cash', "2" => 'Bank Cash'];
$crdr = ["1" => 'Credit', "2" => 'Debit'];

$column = array(
    'a.id',
    'a.trans_cat',
    'gc.grp_id',
    ' b.name',
    'cc.cus_name',
    ' e.bank_name',
    'a.auction_month',
    'a.ref_id',
    'a.trans_id',
    'a.type',
    'a.amount',
    'a.remark',
);

$query = "SELECT 
        a.*, 
        b.name AS transname,
        CONCAT(gc.grp_id, '-', gc.grp_name) AS group_id, 
        d.name as username,  
        CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name,
        e.bank_name as bank_namecash,
        a.auction_month 
    FROM `other_transaction` a 
    LEFT JOIN other_trans_name b ON a.name = b.id 
    LEFT JOIN group_creation gc ON a.group_id = gc.grp_id 
    LEFT JOIN users d ON a.user_name = d.id 
    LEFT JOIN bank_creation e ON a.bank_id = e.id 
    LEFT JOIN customer_creation cc ON a.group_mem = cc.id 
    WHERE a.insert_login_id = '$user_id' 
    AND DATE(a.created_on) BETWEEN '$from_date' AND '$to_date'";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND (a.trans_cat LIKE '%" . $search . "%'
                      OR group_id LIKE '%" . $search . "%'
                      OR  b.name LIKE '%" . $search . "%'
                      OR cus_name LIKE '%" . $search . "%'
                      OR  e.bank_name LIKE '%" . $search . "%'
                      OR a.auction_month  LIKE '%" . $search . "%'
                      OR a.ref_id LIKE '%" . $search . "%'
                      OR a.trans_id LIKE '%" . $search . "%'
                      OR a.trans_id LIKE '%" . $search . "%'
                      OR a.type LIKE '%" . $search . "%'
                      OR a.amount LIKE '%" . $search . "%'
                      OR a.remark LIKE '%" . $search . "%'
                  )";
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}

$query1 = '';
if (isset($_POST['length']) && $_POST['length'] != -1) {
    $query1 = ' LIMIT ' . intval($_POST['start']) . ', ' . intval($_POST['length']);
}

$statement = $pdo->prepare($query);
$statement->execute();

$number_filter_row = $statement->rowCount();

// Close the cursor before executing a new query
$statement->closeCursor();

// Prepare and execute the second query
$statement = $pdo->prepare($query . $query1);
$statement->execute();

$result = $statement->fetchAll();
$sno = isset($_POST['start']) ? $_POST['start'] + 1 : 1;
$data = [];

foreach ($result as $row) {
    $sub_array = [];

    $sub_array[] = $sno++;
    
    $sub_array[] = isset($trans_cat[$row['trans_cat']]) ? $trans_cat[$row['trans_cat'] ]: '';
    $sub_array[] = isset($row['group_id']) ? $row['group_id'] : '';
    $sub_array[] = isset($row['transname']) ? $row['transname'] : '';
    $sub_array[] = isset($row['cus_name']) ? $row['cus_name'] : '';
    $sub_array[] = isset($crdr[$row['type']]) ? $crdr[$row['type']]: '';
    $sub_array[] = isset($row['bank_namecash']) ? $row['bank_namecash'] : '';
    $sub_array[] = isset($row['ref_id']) ? $row['ref_id'] : '';
    $sub_array[] = isset($row['trans_id']) ? $row['trans_id'] : '';
// Check if the amount is a valid numeric value before applying abs() or money formatting
$sub_array[] = isset($row['amount']) && is_numeric($row['amount']) ? moneyFormatIndia(abs($row['amount'])) : '';
    $sub_array[] = isset($row['auction_month']) ?($row['auction_month']) : '';
    $sub_array[] = isset($row['remark']) ?($row['remark']) : '';

    $data[] = $sub_array;
}

function count_all_data($pdo) {
    $query = "SELECT COUNT(*) FROM other_transaction";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchColumn();
}


$output = array(
    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    'recordsTotal' => count_all_data($pdo),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

function moneyFormatIndia($num)
{
    $isNegative = false;
    if ($num < 0) {
        $isNegative = true;
        $num = abs($num);
    }

    $explrestunits = "";
    if (strlen((string)$num) > 3) {
        $lastthree = substr((string)$num, -3);
        $restunits = substr((string)$num, 0, -3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        foreach ($expunit as $index => $value) {
            if ($index == 0) {
                $explrestunits .= (int)$value . ",";
            } else {
                $explrestunits .= $value . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    $thecash = $isNegative ? "-" . $thecash : $thecash;
    $thecash = $thecash == 0 ? "" : $thecash;
    return $thecash;
}
