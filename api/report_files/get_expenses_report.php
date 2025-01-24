<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$from_date = $_POST['params']['from_date'];
$to_date = $_POST['params']['to_date'];
$exp_cat = [
    "1" => 'Pooja', 
    "2" => 'Vehicle', 
    "3" => 'Fuel', 
    "4" => 'Stationary', 
    "5" => 'Press', 
    "6" => 'Food', 
    "7" => 'Rent', 
    "8" => 'EB', 
    "9" => 'Mobile bill', 
    "10" => 'Office Maintenance', 
    "11" => 'Salary', 
    "12" => 'Tax & Auditor', 
    "13" => 'Int Less', 
    "14" => 'Agent Incentive', 
    "15" => 'Common', 
    "16" => 'Other'
];

$cash_type = ["1" => 'Hand Cash', "2" => 'Bank Cash'];

$column = array(
    'e.id',
    'e.invoice_id',
    'bc.branch_name',
    'e.expenses_category',
    'e.description',
    'e.amount'
);

$query = "SELECT e.id,e.invoice_id,e.expenses_category,e.description,e.amount, bc.branch_name, b.bank_name 
          FROM expenses e 
          LEFT JOIN branch_creation bc ON e.branch = bc.id 
          LEFT JOIN bank_creation b ON e.bank_id = b.id 
          WHERE e.insert_login_id = '$user_id' 
          AND DATE(e.created_on) BETWEEN '$from_date' AND '$to_date'";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND (e.id LIKE '%" . $search . "%'
                      OR e.invoice_id LIKE '%" . $search . "%'
                      OR bc.branch_name LIKE '%" . $search . "%'
                      OR e.expenses_category LIKE '%" . $search . "%'
                      OR e.description LIKE '%" . $search . "%'
                      OR e.amount LIKE '%" . $search . "%'
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
    $sub_array[] = isset($row['invoice_id']) ? $row['invoice_id'] : '';
    $sub_array[] = isset($row['branch_name']) ? $row['branch_name'] : '';
    $category_key = isset($row['expenses_category']) ? $row['expenses_category'] : '';
    $sub_array[] = isset($exp_cat[$category_key]) ? $exp_cat[$category_key] : '';
    $sub_array[] = isset($row['description']) ? $row['description'] : '';
    $sub_array[] = isset($row['amount']) ? moneyFormatIndia($row['amount']) : '';

    $data[] = $sub_array;
}

function count_all_data($pdo) {
    $query = "SELECT COUNT(*) FROM expenses";
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
