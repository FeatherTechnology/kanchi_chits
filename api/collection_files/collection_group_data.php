<?php
require '../../ajaxconfig.php';
@session_start();
$id = $_POST['params']['id'];
$user_id = $_SESSION['user_id'];
$currentMonth = date('m');
$currentYear = date('Y');
include 'collectionStatus.php';
include './col_group_grace.php';
$currentDate = new DateTime();
$collectionSts = new CollectionStsClass($pdo);
$graceperiodSts = new GraceperiodClass($pdo);
$column = array(
    'cc.id',
    'gc.grp_id',
    'gc.grp_name',
    'gc.chit_value',
    'ad.chit_amount',
    'status',
    'gc.grace_period',
    'cc.id',
    'cc.id'
);

// First query
$query = "SELECT 
    COALESCE(ad.id, last_ad.id) AS auction_id,
    cc.id AS customer_id,
    gc.grp_id,
    gc.grp_name,
    gc.chit_value,
    COALESCE(ad.chit_amount, last_ad.chit_amount) AS chit_amount,
    (COALESCE(ad.chit_amount, last_ad.chit_amount) * gs.share_percent / 100) AS chit_share,
    COALESCE(ad.auction_month, last_ad.auction_month) AS auction_month,
    COALESCE(ad.date, last_ad.date) AS due_date,
    gcm.id AS cus_mapping_id,
    gs.id AS share_id,
    cc.cus_id,
    gc.grace_period,
    gs.settle_status
FROM 
    group_creation gc
    LEFT JOIN auction_details ad 
        ON ad.group_id = gc.grp_id 
        AND YEAR(ad.date) = '$currentYear' 
        AND MONTH(ad.date) = '$currentMonth'
    LEFT JOIN auction_details last_ad 
        ON last_ad.group_id = gc.grp_id 
        AND last_ad.date = (
            SELECT MAX(ad2.date) 
            FROM auction_details ad2 
            WHERE ad2.group_id = gc.grp_id
        )
    LEFT JOIN group_share gs 
        ON gc.grp_id = gs.grp_creation_id
    LEFT JOIN group_cus_mapping gcm 
        ON gs.cus_mapping_id = gcm.id
    LEFT JOIN customer_creation cc 
        ON gs.cus_id = cc.id
    JOIN branch_creation bc 
        ON gc.branch = bc.id
    JOIN users us 
        ON FIND_IN_SET(gc.branch, us.branch) > 0
WHERE
     gc.status IN (3, 4) -- Fetch only groups with status 4
    AND cc.id = '$id'
    AND us.id = '$user_id'";

// Handle search filter
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND (gc.grp_id LIKE '%$search%' OR gc.grp_name LIKE '%$search%' OR gc.chit_value LIKE '%$search%' OR ad.chit_amount LIKE '%$search%')";
}

$query .= " ORDER BY gc.grp_id";


// Prepare the statement for the main query
$statement = $pdo->prepare($query);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

// Count the number of filtered rows
$number_filter_row = $statement->rowCount(); // Get the number of rows returned by the main query

// Store settle statuses for all mappings in a separate query
$settleStatusQuery = "SELECT
    gs.id AS share_id,
    gs.settle_status
FROM
    group_share gs
JOIN
    auction_details ad ON gs.grp_creation_id = ad.group_id
WHERE
    gs.cus_id = (SELECT id FROM customer_creation WHERE id = '$id')"; // Assuming you're filtering by the same customer id

$settleStatusStatement = $pdo->prepare($settleStatusQuery);
$settleStatusStatement->execute();
$settleStatuses = $settleStatusStatement->fetchAll(PDO::FETCH_KEY_PAIR); // Fetch as key-value pairs

$sno = isset($_POST['start']) ? $_POST['start'] + 1 : 1;
$data = [];

foreach ($result as $row) {
    $sub_array = [];
    $sub_array[] = $sno++;
    $sub_array[] = $row['grp_id'];
    $sub_array[] = $row['grp_name'];
    $sub_array[] = moneyFormatIndia($row['chit_value']);

    $chit_share = isset($row['chit_share']) && is_numeric($row['chit_share']) ? floor($row['chit_share']) : 0;

    $sub_array[] = moneyFormatIndia($chit_share);


    // Get settle_status from the previously fetched settleStatuses array
    $settle_status = $settleStatuses[$row['share_id']] ?? ''; // Default to 'N/A' if not found
    $sub_array[] = $settle_status;

    // Update status logic
    $status = $collectionSts->updateCollectionStatus($row['share_id'], $row['grp_id']);
    $sub_array[] = $status;
    $grace_status = $graceperiodSts->updateGraceStatus($row['share_id'], $row['grp_id']);

    // Grace period calculation
    $auction_month = $row['auction_month'] ?? 0;
    $grace_period = $row['grace_period'] ?? 0;
    $due_date = isset($row['due_date']) ? date('Y-m-d', strtotime($row['due_date'])) : '';
    $grace_end_date = date('Y-m-d', strtotime($due_date . " + $grace_period days"));

    $current_date = date('Y-m-d');
    if ($status === "Paid") {
        $status_color = 'green';
    } elseif ($grace_status === 'orange') {
        $status_color = 'orange';
    } elseif ($grace_status === 'red') {
        $status_color = 'red';
    } else {
        $status_color = 'orange'; // Default color for 'Payable'
    }

    // if ($status === "Paid") {
    //     $status_color = 'green';
    // } elseif ($status === "Payable" && $grace_end_date >= $current_date) {
    //     $status_color = 'orange';
    // } else {
    //     $status_color = 'red';
    // }

    $sub_array[] = "<span style='display: inline-block; width: 20px; height: 20px; border-radius: 4px; background-color: $status_color;'></span>";

    // Action dropdowns
    $sub_array[] = "<div class='dropdown'>
                        <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
                        <div class='dropdown-content'>
                            <a href='#' class='add_due' data-value='{$row['grp_id']}_{$row['cus_mapping_id']}_{$row['auction_month']}_{$row['share_id']}'>Due Chart</a>
                            <a href='#' class='commitment_chart' data-value='{$row['grp_id']}_{$row['cus_mapping_id']}_{$row['share_id']}'>Commitment Chart</a>
                        </div>
                    </div>";

    $sub_array[] = "<div class='dropdown'>
                        <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
                        <div class='dropdown-content'>
                            <a href='#' class='add_pay' data-value='{$row['grp_id']}_{$row['cus_id']}_{$row['auction_id']}_{$row['cus_mapping_id']}_{$row['customer_id']}_{$row['share_id']}'> Pay</a>
                            <a href='#' class='add_commitment' data-value='{$row['grp_id']}_{$row['cus_mapping_id']}_{$row['share_id']}'>Commitment</a>
                        </div>
                    </div>";

    $data[] = $sub_array;
}

// Count function
function count_all_data($pdo)
{
    $query = "SELECT COUNT(*) FROM group_creation";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchColumn();
}

// Output results
$output = array(
    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    'recordsTotal' => count_all_data($pdo),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);


// Money Format Function
function moneyFormatIndia($num1)
{
    $num = abs($num1); // Handle negatives
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
    return ($num1 < 0 ? "-" : "") . $thecash;
}
