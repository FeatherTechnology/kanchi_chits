<?php
require '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id'];
$currentMonth = date('m'); // Get the current month
$currentYear = date('Y');
$column = array(
    'ad.id',
    'ad.group_id',
    'gc.grp_name',
    'gc.chit_value',
    'gc.total_members',
    'gc.total_months',
    'ad.auction_month',
    'cus_name',
    'ad.auction_value',
    'ad.id'
);

$query = "SELECT 
            ad.id,
            ad.group_id,
            gc.grp_name,
            gc.chit_value,
            gc.date,
            gc.total_members,
            gc.total_months,
            ad.auction_month,
            GROUP_CONCAT(
                CASE 
                    WHEN ad.cus_name = '-1' THEN 'Company' 
                    ELSE COALESCE(cc.first_name, '') 
                END 
                SEPARATOR ' - '
            ) AS cus_name, 
            (gc.chit_value - ad.auction_value) AS settlement_amount 
        FROM 
            auction_details ad
        LEFT JOIN 
            group_creation gc ON ad.group_id = gc.grp_id
        LEFT JOIN 
            group_share gs ON ad.cus_name = gs.cus_mapping_id 
        LEFT JOIN 
            customer_creation cc ON gs.cus_id = cc.id 
        JOIN 
            branch_creation bc ON gc.branch = bc.id
        JOIN 
            users us ON FIND_IN_SET(gc.branch, us.branch) > 0
        WHERE 
            ad.status = 2  AND us.id = '$user_id' ";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND (ad.group_id LIKE '%" . $search . "%'
                      OR gc.grp_name LIKE '%" . $search . "%'
                      OR gc.chit_value LIKE '%" . $search . "%'
                      OR gc.total_months LIKE '%" . $search . "%'
                      OR gc.total_members LIKE '%" . $search . "%'
                      OR ad.auction_month LIKE '%" . $search . "%'
                      OR CONCAT(cc.first_name, ' ', cc.last_name) LIKE '%" . $search . "%'
                      OR ad.auction_value LIKE '%" . $search . "%')";
}
$query .= " GROUP BY ad.id ";
if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= " ORDER BY ad.id DESC";
}

$query1 = '';
if (isset($_POST['length']) && $_POST['length'] != -1) {
    $query1 = ' LIMIT ' . intval($_POST['start']) . ', ' . intval($_POST['length']);
}

$statement = $pdo->prepare($query);
$statement->execute();
$number_filter_row = $statement->rowCount();

$statement = $pdo->prepare($query . $query1);
$statement->execute();
$result = $statement->fetchAll();

$sno = isset($_POST['start']) ? $_POST['start'] + 1 : 1;
$data = [];
foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $sno++;
    $sub_array[] = isset($row['group_id']) ? $row['group_id'] : '';
    $sub_array[] = isset($row['grp_name']) ? $row['grp_name'] : '';
    $sub_array[] = isset($row['chit_value']) ? moneyFormatIndia($row['chit_value']) : '';
    $sub_array[] = isset($row['date']) ? $row['date'] : '';
    $sub_array[] = isset($row['total_members']) ? $row['total_members'] : '';
    $sub_array[] = isset($row['total_months']) ? $row['total_months'] : '';
    $sub_array[] = isset($row['auction_month']) ? $row['auction_month'] : '';
    $sub_array[] = isset($row['cus_name']) ? $row['cus_name'] : '';
    $sub_array[] = isset($row['settlement_amount']) ? moneyFormatIndia($row['settlement_amount']) : '';
    $action = "<button class='btn btn-primary settleListBtn' value='" . $row['id'] . "'>&nbsp;View</button>";
    $sub_array[] = $action;
    $data[] = $sub_array;
}

function count_all_data($pdo)
{
    $query = "SELECT COUNT(*) FROM auction_details";
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
    return $thecash;
}
