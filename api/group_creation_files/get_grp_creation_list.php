<?php
require '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id'];

// Define status mapping array
$status_arr = [1 => 'Process', 2 => 'Created', 3 => 'Current', 4 => 'Closed'];

// Define column names for sorting
$column = array(
    'gc.id',
    'gc.grp_id',
    'gc.grp_name',
    'gc.chit_value',
    'gc.total_months',
    'gc.date',
    'gc.start_month',
    'gc.end_month',
    'gc.commission',
    'bc.branch_name',
    'gc.id',
    'gc.id'

);

// Base query with JOIN
$query = "SELECT gc.id, gc.grp_id, gc.grp_name, gc.chit_value, gc.total_months, gc.date,gc.start_month,gc.end_month, gc.commission, bc.branch_name,gc.status
          FROM group_creation gc 
          JOIN branch_creation bc ON gc.branch = bc.id 
          WHERE 1";

// Add search condition if search term is provided
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = '%' . $_POST['search'] . '%';
    $query .= " AND (gc.grp_id LIKE :search
                    OR gc.grp_name LIKE :search
                    OR gc.chit_value LIKE :search
                    OR gc.total_months LIKE :search
                    OR gc.date LIKE :search
                    OR gc.start_month LIKE :search
                    OR gc.end_month LIKE :search
                    OR gc.commission LIKE :search
                    OR bc.branch_name LIKE :search
                    OR gc.status LIKE :search)";
}

// Add ordering condition
if (isset($_POST['order'])) {
    // Order by column from DataTables request
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    // Default ordering by gc.grp_id in descending order
    $query .= " ORDER BY gc.grp_id DESC";
}

// Add pagination
$query1 = '';
if (isset($_POST['length']) && $_POST['length'] != -1) {
    $query1 = ' LIMIT ' . intval($_POST['start']) . ', ' . intval($_POST['length']);
}

// Prepare and execute the statement for counting filtered rows
$statement = $pdo->prepare($query);
if (isset($_POST['search']) && $_POST['search'] != "") {
    $statement->bindValue(':search', $search, PDO::PARAM_STR);
}
$statement->execute();
$number_filter_row = $statement->rowCount();

// Prepare and execute the statement for fetching paginated data
$statement = $pdo->prepare($query . $query1);
if (isset($_POST['search']) && $_POST['search'] != "") {
    $statement->bindValue(':search', $search, PDO::PARAM_STR);
}
$statement->execute();
$result = $statement->fetchAll();

// Prepare data for response
$sno = isset($_POST['start']) ? $_POST['start'] + 1 : 1;
$data = [];
foreach ($result as $row) {
    $sub_array = array(
        $sno++,
        isset($row['grp_id']) ? $row['grp_id'] : '',
        isset($row['grp_name']) ? $row['grp_name'] : '',
        isset($row['chit_value']) ? moneyFormatIndia($row['chit_value']) : '',
        isset($row['total_months']) ? $row['total_months'] : '',
        isset($row['date']) ? $row['date'] : '',
        // Convert start_month
        $start_month = isset($row['start_month']) ? DateTime::createFromFormat('Y-m', $row['start_month'])->format('F Y') : '',

        // Convert end_month
        $end_month = isset($row['end_month']) ? DateTime::createFromFormat('Y-m', $row['end_month'])->format('F Y') : '',

        isset($row['commission']) ? $row['commission'] : '',
        isset($row['branch_name']) ? $row['branch_name'] : '',
        isset($row['status']) ? $status_arr[$row['status']] : '', // Fix for status mapping
        "<a href='#' class='edit-group-creation' value='" . $row['id'] . "' title='Edit details'><span class='icon-border_color'></span></a>"
    );
    $data[] = $sub_array;
}

// Function to count all data
function count_all_data($pdo)
{
    $query = "SELECT COUNT(*) FROM group_creation";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchColumn();
}

// Prepare output for DataTables
$output = array(
    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    'recordsTotal' => count_all_data($pdo),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);
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
