<?php
require '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id'];

// Define status mapping array
$status_arr = [1 => 'Process', 2 => 'Created', 3 => 'Current',4=>'Closed',5 => 'Closed'];
$currentMonth = date('m'); // Get the current month
$currentYear = date('Y'); // Get the current year
// Define column names for sorting
$column = array(
    'gc.id',
    'gc.grp_id',
    'gc.grp_name',
    'gc.chit_value',
    'gc.date',
    'bc.branch_name',
    'gc.status'
);

// Base query with JOIN
 $query = "SELECT gc.id, gc.grp_id, gc.grp_name, gc.chit_value, gc.date, bc.branch_name, gc.status, ad.auction_month,ad.id as auction_id
          FROM group_creation gc 
          JOIN branch_creation bc ON gc.branch = bc.id 
          LEFT JOIN auction_details ad ON gc.grp_id = ad.group_id
           JOIN 
        users us ON FIND_IN_SET(gc.branch, us.branch) > 0
          WHERE gc.status BETWEEN 4 AND 5 AND us.id = '$user_id' group by gc.id";

// Add search condition if search term is provided
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = '%' . $_POST['search'] . '%';
    $query .= " AND (gc.grp_id LIKE :search
                    OR gc.grp_name LIKE :search
                    OR gc.chit_value LIKE :search
                    OR gc.date LIKE :search
                    OR bc.branch_name LIKE :search
                    OR gc.status LIKE :search)";
}

if (isset($_POST['order'])) {
    $columnIndex = $_POST['order'][0]['column'];  // Index of the column to be sorted
    $sortDirection = $_POST['order'][0]['dir'];  // Sort direction (asc/desc)
    
    if (isset($column[$columnIndex])) {
        // Apply sorting using the column and direction provided
        $query .= " ORDER BY " . $column[$columnIndex] . " " . $sortDirection;
    }
} else {
    // Default sorting (if no sorting is applied from frontend)
    $query .= " ORDER BY gc.grp_id"; // Order by group ID
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
    // Fetch all customer IDs mapped to the group
    $customer_mapping_query = "SELECT id FROM group_cus_mapping 
                               WHERE grp_creation_id = :grp_creation_id";
    $customer_mapping_stmt = $pdo->prepare($customer_mapping_query);
    $customer_mapping_stmt->execute([':grp_creation_id' => $row['grp_id']]);
    $customer_ids = $customer_mapping_stmt->fetchAll(PDO::FETCH_COLUMN);

    // Check if all customers have Paid status
    $all_paid = true;
    foreach ($customer_ids as $cus_id) {
        $payment_status_query = "SELECT coll_status FROM collection 
                                 WHERE group_id = :group_id 
                                 AND auction_month = :auction_month 
                                 AND cus_mapping_id = :cus_mapping_id 
                                 ORDER BY created_on DESC LIMIT 1";
        $payment_status_stmt = $pdo->prepare($payment_status_query);
        $payment_status_stmt->execute([
            ':group_id' => $row['grp_id'],
            ':auction_month' => $row['auction_month'],
            ':cus_mapping_id' => $cus_id
        ]);
        $payment_status = $payment_status_stmt->fetchColumn();
        if ($payment_status !== 'Paid') {
            $all_paid = false;
            break;
        }
    }

    // Determine the collection status
    $collection_status = $all_paid ? 'Completed' : 'In Collection';

    $sub_array = array(
        $sno++,
        isset($row['grp_id']) ? $row['grp_id'] : '',
        isset($row['grp_name']) ? $row['grp_name'] : '',
        isset($row['chit_value']) ? moneyFormatIndia($row['chit_value']) : '',
        isset($row['date']) ? $row['date'] : '',
        isset($row['branch_name']) ? $row['branch_name'] : '',
        isset($row['status']) ? $status_arr[$row['status']] : '',
        $collection_status // Add collection status to the array
    );
  // Charts dropdown
    $sub_array[] = "<div class='dropdown'>
        <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
        <div class='dropdown-content'>
            <a href='#' class='auction_chart' data-value='{$row['grp_id']}_{$row['auction_month']}'>Auction Chart</a>
            <a href='#' class='settle_chart' data-value='{$row['grp_id']}_{$row['auction_id']}'>Settlement Chart</a>
            <a href='#' class='ledger_view_chart' data-value='{$row['grp_id']}'>Ledger View Chart</a>
        </div>
    </div>";
    // Action button
    // $sub_array[] = "<button class='btn btn-primary customerActionBtn' value='" . $row['grp_id'] . "'>&nbsp;View</button>";

  

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

// Function to format money as per Indian convention
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
