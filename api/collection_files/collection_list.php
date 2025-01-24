<?php
@session_start();
require '../../ajaxconfig.php';
$currentMonth = date('m');
$currentYear = date('Y');
$user_id = $_SESSION['user_id'];
include './collection_list_sts.php';
include './grace_period_list.php';

$collectionSts = new CollectStsClass($pdo);
$graceperiodSts = new GraceperiodClass($pdo);

$column = array(
    'cc.id',
    'cc.cus_id',
    'cus_name',
    'cc.mobile1',
    'pl.place',
    'occupations',
    'cc.id',
    'gc.grace_period',
    'cc.id'
);

$query = "SELECT
    cc.id, 
    cc.cus_id,
    ad.id AS auction_id,
    ad.group_id,
    CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name,
    cc.mobile1,
    pl.place,
    (
        SELECT
            GROUP_CONCAT(sc.occupation SEPARATOR ', ')
        FROM 
            source sc
        WHERE
            sc.cus_id = cc.cus_id
    ) AS occupations,
    gcm.id AS cus_mapping_id,
    gc.chit_value,
    gc.grace_period,
    ad.date,
    ad.chit_amount,
    ad.auction_month
FROM
    auction_details ad
LEFT JOIN group_share gs ON
    ad.group_id = gs.grp_creation_id
    LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
LEFT JOIN customer_creation cc ON
    gs.cus_id = cc.id
LEFT JOIN place pl ON cc.place = pl.id
LEFT JOIN group_creation gc ON ad.group_id = gc.grp_id
    JOIN 
        branch_creation bc ON gc.branch = bc.id
    JOIN 
        users us ON FIND_IN_SET(gc.branch, us.branch) > 0
WHERE
    gc.status BETWEEN 3 AND 4 AND gs.coll_status !='Paid' AND us.id = '$user_id'";
// Add search condition
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND (cc.cus_id LIKE '%" . $search . "%'
                      OR CONCAT(cc.first_name, ' ', cc.last_name) LIKE '%" . $search . "%'
                      OR cc.mobile1 LIKE '%" . $search . "%'
                      OR pl.place LIKE '%" . $search . "%'
                      OR (SELECT GROUP_CONCAT(sc.occupation SEPARATOR ', ') 
                          FROM source sc 
                          WHERE sc.cus_id = cc.cus_id
                      ) LIKE '%" . $search . "%')";
}

$query .= "
   GROUP BY
    cc.cus_id";
// Add sorting condition
if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order'][0]['column']] . ' ' . $_POST['order'][0]['dir'];
} else {
    // Default sorting if no order specified
    $query .= " ORDER BY cc.cus_id"; // Default sorting
}

// Handle pagination
$query1 = '';
if (isset($_POST['length']) && $_POST['length'] != -1) {
    $query1 = ' LIMIT ' . intval($_POST['start']) . ', ' . intval($_POST['length']);
}

// Prepare and execute the statement
$statement = $pdo->prepare($query . $query1);
$statement->execute();
$result = $statement->fetchAll();

// Get the number of filtered rows
$number_filter_row = $statement->rowCount();


$statement = $pdo->prepare($query);
$statement->execute();
$number_filter_row = $statement->rowCount();



$sno = isset($_POST['start']) ? $_POST['start'] + 1 : 1;
$data = [];
foreach ($result as $row) {
   $status = $collectionSts->updateCollectStatus($row['cus_id'], $row['id']);
    $grace_status = $graceperiodSts->updateGraceStatus($row['cus_id'],$row['id']);

    // Exclude customers with a 'Paid' status
  
        $sub_array = array();
        $sub_array[] = $sno++; // Increment the serial number only for 'Payable' customers
        $sub_array[] = isset($row['cus_id']) ? $row['cus_id'] : '';
        $sub_array[] = isset($row['cus_name']) ? $row['cus_name'] : '';
        $sub_array[] = isset($row['mobile1']) ? $row['mobile1'] : '';
        $sub_array[] = isset($row['place']) ? $row['place'] : '';
        $sub_array[] = isset($row['occupations']) ? $row['occupations'] : '';
        $sub_array[] = $status;
        // Determine the status color
        if ($grace_status === 'orange') {
            $status_color = 'orange';
        } elseif ($grace_status === 'red') {
            $status_color = 'red';
        } else {
            $status_color = 'grey'; // Default color for 'Payable'
        }

        $sub_array[] = "<span style='display: inline-block; width: 20px; height: 20px; border-radius: 4px; background-color: $status_color;'></span>";

        $action = "<button class='btn btn-primary collectionListBtn' value='" . $row['id'] . "'>&nbsp;View</button>";
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
?>
