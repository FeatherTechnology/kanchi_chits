<?php
require '../../ajaxconfig.php';

$group_id = $_POST['group_id'];
$cusMappingID = $_POST['cus_mapping_id'];
$share_id = $_POST['share_id'];
$currentMonth = date('m'); // Get the current month
$currentYear = date('Y'); // Get the current year

// Fetch start month from the group_creation table
$start_month_query = "SELECT start_month, end_month FROM group_creation WHERE grp_id = '$group_id'";
$start_month_result = $pdo->query($start_month_query);
$start_month_row = $start_month_result->fetch(PDO::FETCH_ASSOC);
$start_month = $start_month_row['start_month'];
$end_month = $start_month_row['end_month'];

// Extract year and month from start_month and end_month
$startYear = substr($start_month, 0, 4);
$startMonth = substr($start_month, 5, 2);
$endYear = substr($end_month, 0, 4);
$endMonth = substr($end_month, 5, 2);

// Calculate total months since year 0
$start_total_months = ($startYear * 12) + $startMonth;
$current_total_months = ($currentYear * 12) + $currentMonth;
$end_total_months = ($endYear * 12) + $endMonth;

// Calculate the auction month
$auction_month_current = $current_total_months - $start_total_months + 1;

// Check if the current month is within the range
if ($current_total_months <= $end_total_months && $current_total_months >= $start_total_months) {
    // Current month is within the range, auction month is correct
    $auction_month_current = $current_total_months - $start_total_months + 1;
} else {
    // Adjust the auction month if outside the range by fetching the max auction_month
    $current_query = "SELECT MAX(auction_month) AS max_auction_month FROM auction_details WHERE group_id = '$group_id'";
    $curr_statement = $pdo->query($current_query);
    // Fetch the result and assign to auction_month_current
    $current_row = $curr_statement->fetch(PDO::FETCH_ASSOC);
    $auction_month_current = $current_row['max_auction_month'];
}

// Fetch current auction details including collections
$current_auction_query = "SELECT
    gc.grp_name,
    ad.auction_month,
    ad.date,
    gc.chit_value,
    ad.chit_amount,
    gs.share_percent,
    (ad.chit_amount * gs.share_percent / 100) AS chit_share,
    COALESCE(SUM(cl.collection_amount),
    0) AS collection_amount
FROM
    group_creation gc
LEFT JOIN auction_details ad ON
   gc.grp_id = ad.group_id AND
ad.group_id = '$group_id' AND ad.auction_month = $auction_month_current
LEFT JOIN group_share gs ON
     gc.grp_id = gs.grp_creation_id
    LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
       LEFT JOIN collection cl ON ad.group_id = cl.group_id AND ad.auction_month = cl.auction_month AND cl.cus_mapping_id = '$cusMappingID' AND cl.share_id ='$share_id'
WHERE
 gs.id ='$share_id'
GROUP BY
    gc.grp_id";

// Fetch previous auction details to calculate pending amount
$previous_auction_query = "SELECT
ad.date,
    ad.auction_month,
    ad.chit_amount,
     (ad.chit_amount * gs.share_percent / 100) AS chit_share,
    COALESCE(SUM(cl.collection_amount), 0) AS collection_amount
FROM
    auction_details ad
    LEFT JOIN group_share gs ON
    ad.group_id = gs.grp_creation_id
    LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
LEFT JOIN
    collection cl ON ad.group_id = cl.group_id
    AND ad.auction_month = cl.auction_month
    AND cl.cus_mapping_id = '$cusMappingID'AND cl.share_id ='$share_id'
WHERE
    ad.group_id = '$group_id'
    AND ad.auction_month IN (
        SELECT auction_month
        FROM auction_details
        WHERE group_id = '$group_id'
        AND auction_month < $auction_month_current
        ORDER BY auction_month DESC
    )AND gs.id ='$share_id'
GROUP BY
    ad.auction_month
ORDER BY
    ad.auction_month ASC";

// Fetch collections for the current auction month if necessary
$collections_query = '';


// Execute queries
$current_statement = $pdo->query($current_auction_query);
$previous_statement = $pdo->query($previous_auction_query);

$response = [];
$pending = 0; // Initialize pending amount
$auction_month = 0;
$previous_auction_month = 0;
$prev_auction_date = '';

if ($current_statement->rowCount() > 0) {
    $current_row = $current_statement->fetch(PDO::FETCH_ASSOC);

    // Loop through the previous auction data to calculate pending amount
    while ($previous_row = $previous_statement->fetch(PDO::FETCH_ASSOC)) {
        $previous_collection_amount = (int)$previous_row['collection_amount'];
        $previous_chit_amount = (int)$previous_row['chit_share'];
        $pending += max(0, $previous_chit_amount - $previous_collection_amount);
        // $previous_auction_month = max(0,(int)$previous_row['auction_month']);
        $previous_auction_month = max($auction_month, (int)$previous_row['auction_month']);
        $prev_auction_date = date('d-m-Y', strtotime($previous_row['date']));
    }
    $current_auction_month = ($current_row['auction_month'] !== null) ? $current_row['auction_month'] : 0;
    $current_auction_date = (date('d-m-Y', strtotime($current_row['date'])) !== null) ? date('d-m-Y', strtotime($current_row['date'])) : 0;
    $auction_month = max($current_auction_month, $previous_auction_month);

    if ($current_auction_date && strtotime($current_auction_date) >= strtotime($prev_auction_date)) {
        $auction_date = $current_auction_date;
    } else {
        $auction_date = $prev_auction_date;
    }

    $total_collected = (int)$current_row['collection_amount'];

    // Initial payable amount for the current month is chit_amount + pending amount (if any)
    $initial_payable_amnt = (int)$current_row['chit_share'] + $pending;

    // Calculate the remaining balance for the current auction month
    $remaining_balance = $initial_payable_amnt - $total_collected;

    // Set the payable amount for the current month
    $payable_amnt = max(0, $remaining_balance);
    if ($auction_month_current != 1) {
        $collections_query = "SELECT 
            ad.chit_amount,
            (ad.chit_amount * gs.share_percent / 100) AS chit_share,
            c.payable,
            c.collection_date, 
            c.collection_amount, 
            c.id as coll_id
        FROM 
            auction_details ad
             LEFT JOIN group_share gs ON
        ad.group_id = gs.grp_creation_id
        LEFT JOIN group_cus_mapping gcm ON
        gs.cus_mapping_id = gcm.id
        LEFT JOIN 
            collection c ON ad.group_id = c.group_id 
                         AND c.cus_mapping_id = '$cusMappingID'
                         AND ad.auction_month = c.auction_month AND c.share_id ='$share_id'
        WHERE 
            c.group_id = '$group_id'
            AND c.cus_mapping_id = '$cusMappingID'
            AND c.auction_month = $auction_month AND c.share_id ='$share_id'
        ORDER BY c.id";
    }
    // Fetch collections and recalculate pending amount if necessary
    if ($collections_query) {
        $collections_statement = $pdo->query($collections_query);
        while ($collection_row = $collections_statement->fetch(PDO::FETCH_ASSOC)) {
            $collection_amount = (int)$collection_row['collection_amount'];
            $payable = (int)$collection_row['payable'];
            $pending = max(0, $payable - $collection_amount);
        }
    }

    $response = [
        'success' => true,
        'group_name' => $current_row['grp_name'],
        'auction_month' => $auction_month,
        'date' => $auction_date,
        'chit_value' => $current_row['chit_value'],
        'chit_amount' => (int)$current_row['chit_share'],
        'pending_amt' => $pending,
        'payable_amnt' => $payable_amnt,
        'payableAmount' => $initial_payable_amnt
    ];
} else {
    $response = ['success' => false];
}

echo json_encode($response);
