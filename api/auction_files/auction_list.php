<?php
require '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id'];
$auction_status = [1 => 'In Auction', 2 => 'Finished', 3 => 'Finished'];

$column = array(
    'gc.id',
    'gc.grp_id',
    'gc.grp_name',
    'gc.chit_value',
    'gc.total_months',
    'gc.date',
    'gc.hours',
    'ad.auction_month',
    'bc.branch_name',
    'ad.status',
    'gc.id'
);
$type = $_POST['params']['type'] ?? ''; // Get the type from POST data

// Adjusted query to get the last record for each group with row numbering
$query = "SELECT * FROM (
    SELECT 
        gc.id,
        gc.grp_id,
        gc.grp_name,
        gc.chit_value,
        gc.total_months,
        gc.date,
        gc.hours,
        gc.minutes,
        gc.ampm,
        ad.auction_month,
        bc.branch_name,
        ad.status,
        ROW_NUMBER() OVER (PARTITION BY gc.grp_id ORDER BY gc.date DESC, ad.auction_month DESC) AS row_num
    FROM 
        group_creation gc
    LEFT JOIN 
        auction_details ad ON gc.grp_id = ad.group_id
    JOIN 
        branch_creation bc ON gc.branch = bc.id
    JOIN 
        users us ON FIND_IN_SET(gc.branch, us.branch) > 0
    WHERE 
        gc.status BETWEEN 2 AND 4 
        AND us.id = '$user_id'
";

// Additional conditions based on the type
if ($type == 'month') {
    $query .= " AND MONTH(ad.date) = MONTH(CURDATE()) 
                AND YEAR(ad.date) = YEAR(CURDATE()) ";
} else if ($type == 'today') {
    $query .= " AND ad.date = CURDATE() ";
} else {
    $query .= " AND (
        MONTH(ad.date) = MONTH(CURDATE()) 
        AND YEAR(ad.date) = YEAR(CURDATE()) 
        OR (ad.date <= DATE_ADD(CURDATE(), INTERVAL 2 DAY))
    )";
}

// Close the subquery and filter for row_num
$query .= ") AS subquery WHERE row_num = 1";

// Search functionality
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND (grp_id LIKE '%" . $search . "%'
                  OR grp_name LIKE '%" . $search . "%'
                  OR chit_value LIKE '%" . $search . "%'
                  OR total_months LIKE '%" . $search . "%'
                  OR date LIKE '%" . $search . "%'
                  OR auction_month LIKE '%" . $search . "%'
                  OR branch_name LIKE '%" . $search . "%'
                  OR status LIKE '%" . $search . "%')";
}

// Order by status and id
if (isset($_POST['order'])) {
    $column = ['id', 'grp_id', 'grp_name', 'chit_value', 'total_months', 'date','hours','minutes','ampm', 'auction_month', 'branch_name', 'status','id'];
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    // Default ordering by status and id
    $query .= " ORDER BY 
    CASE 
        WHEN status = 1 THEN 0
        WHEN status = 2 THEN 1
        ELSE 2
    END, 
    -- First, sort by AM/PM (AM before PM)
     date ASC,
    CASE ampm 
        WHEN 'AM' THEN 0
        WHEN 'PM' THEN 1
        ELSE 0
    END ASC,
    CASE 
        WHEN hours = '12' AND ampm = 'AM' THEN 0
        WHEN hours = '12' AND ampm = 'PM' THEN 12
        ELSE hours
    END ASC,
    -- Then, sort by minutes
    LPAD(minutes, 2, '0') ASC, 
    -- Finally, order by id
    id ASC";

}

// Pagination
$query1 = '';
if (isset($_POST['length']) && $_POST['length'] != -1) {
    $query1 = ' LIMIT ' . intval($_POST['start']) . ', ' . intval($_POST['length']);
}

// Execute the query
$statement = $pdo->prepare($query);
$statement->execute();
$number_filter_row = $statement->rowCount();

// Fetch paginated results
$statement = $pdo->prepare($query . $query1);
$statement->execute();
$result = $statement->fetchAll();

$sno = isset($_POST['start']) ? $_POST['start'] + 1 : 1;
$data = [];
foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $sno++;
    $sub_array[] = isset($row['grp_id']) ? $row['grp_id'] : '';
    $sub_array[] = isset($row['grp_name']) ? $row['grp_name'] : '';
    $sub_array[] = isset($row['chit_value']) ? moneyFormatIndia($row['chit_value']) : ''; // Apply formatting here
    $sub_array[] = isset($row['total_months']) ? $row['total_months'] : '';
    $sub_array[] = isset($row['date']) ? $row['date'] : '';
    $formattedTime = '';
     if (isset($row['hours']) && isset($row['minutes']) && isset($row['ampm'])) {
         $formattedTime = sprintf("%02d:%02d %s", (int)$row['hours'], (int)$row['minutes'], strtoupper($row['ampm']));
     }
     $sub_array[] = $formattedTime;
    $sub_array[] = isset($row['auction_month']) ? $row['auction_month'] : '';
    $sub_array[] = isset($row['branch_name']) ? $row['branch_name'] : '';

    // Set the status with color and bold text based on its value
    if (isset($row['status'])) {
        $status_text = $auction_status[$row['status']];
        if ($row['status'] == 1) { // In Auction
            $sub_array[] = "<span style='color: red;'><strong>$status_text</strong></span>";
        } elseif ($row['status'] == 2 || $row['status'] == 3) { // Finished
            $sub_array[] = "<span style='color: green;'><strong>$status_text</strong></span>";
        } else {
            $sub_array[] = "<strong>$status_text</strong>"; // Default case
        }
    } else {
        $sub_array[] = '';
    }

    $unique = $row['grp_id'] . '_' . $row['grp_name'] . '_' . $row['chit_value'];
    $action = "<button class='btn btn-primary auctionListBtn' data-grpid='" . $row['grp_id'] . "' data-grpname='" . $row['grp_name'] . "' data-chitval='" . $row['chit_value'] . "'>&nbsp;View</button>";
   
    $sub_array[] = $action;
    $data[] = $sub_array;
}

// Count total records
function count_all_data($pdo) {
    $query = "SELECT COUNT(*) FROM group_creation";
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

// Output the result as JSON
echo json_encode($output);

// Money formatting function
function moneyFormatIndia($num) {
    $explrestunits = "";
    if(strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; 
        $expunit = str_split($restunits, 2);
        for($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2 unit pairs, adds a comma
            if($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ","; // if first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}
?>
