<?php
require '../../ajaxconfig.php';

$due_list_arr = array();
$cusMappingID = $_POST['cus_mapping_id'];
$groupId = $_POST['group_id'];
$share_id = $_POST['share_id'];

// Fetch auction details for the given group and customer mapping ID
$qry1 = $pdo->query("SELECT 
    ad.auction_month, 
    ad.date AS auction_date,
    ad.chit_amount,
    (ad.chit_amount * gs.share_percent / 100) AS chit_share
FROM 
    auction_details ad
 JOIN group_share gs ON
    ad.group_id = gs.grp_creation_id
    LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
WHERE 
    ad.group_id = '$groupId'
    AND gs.cus_mapping_id = '$cusMappingID' AND gs.id ='$share_id'");

$auctionData = $qry1->fetchAll(PDO::FETCH_ASSOC);
// Fetch start month
$start_month_query = "SELECT start_month FROM group_creation WHERE grp_id = '$groupId'";
$start_month_result = $pdo->query($start_month_query);
$start_month_row = $start_month_result->fetch(PDO::FETCH_ASSOC);
$start_month = $start_month_row['start_month'];

$currentYear = date('Y');
$currentMonth = date('m');
$auction_month_current = ($currentYear * 12 + $currentMonth) - (substr($start_month, 0, 4) * 12 + substr($start_month, 5, 2)) + 1;

$previous_auction_query = "SELECT
    ad.auction_month,
    ad.chit_amount,
    (ad.chit_amount * gs.share_percent / 100) AS chit_share,
    COALESCE(SUM(cl.collection_amount), 0) AS collection_amount
FROM
    auction_details ad
    JOIN group_share gs ON
    ad.group_id = gs.grp_creation_id
    LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
LEFT JOIN
    collection cl ON ad.group_id = cl.group_id
    AND ad.auction_month = cl.auction_month
    AND cl.cus_mapping_id = '$cusMappingID' AND cl.share_id = '$share_id'
WHERE
    ad.group_id = '$groupId' AND gs.id = '$share_id'
    AND ad.auction_month < $auction_month_current AND ad.status IN (2, 3) 
GROUP BY
    ad.auction_month
ORDER BY
    ad.auction_month DESC";

$previous_statement = $pdo->query($previous_auction_query);

// Initialize pending amount
$pending_amount = 0;

// Calculate pending amount
while ($previous_row = $previous_statement->fetch(PDO::FETCH_ASSOC)) {
    $previous_collection_amount = (int)$previous_row['collection_amount'];
    $previous_chit_amount = (int)$previous_row['chit_share'];
    $pending_amount += max(0, $previous_chit_amount - $previous_collection_amount);
}
$lastAuctionMonth = null;
$lastAuctionDate = null;

foreach ($auctionData as $auction) {
    $auction_date = $auction['auction_date'];
    if ($auction_date) {
        $auction_date = new DateTime($auction_date);
        if ($lastAuctionMonth === null || 
            $auction['auction_month'] > $lastAuctionMonth || 
            ($auction['auction_month'] == $lastAuctionMonth && $auction_date > $lastAuctionDate)) {
            $lastAuctionMonth = $auction['auction_month'];
            $lastAuctionDate = $auction_date;
        }
    }
}

// Helper array to keep track of auction months that have been used
$auctionMonthUsed = array();

$i = 0;
$previous_payable_amount = 0; // Initialize a variable to track previous month's payable amount

foreach ($auctionData as $auctionDetails) {
    $auction_month = $auctionDetails['auction_month'];
    $auction_date = $auctionDetails['auction_date'];
    $chit_share = (int)$auctionDetails['chit_share'];
    // Format the auction_date to dd-mm-yyyy
    if (!empty($auction_date)) {
        $date = new DateTime($auction_date);
        $auction_date = $date->format('d-m-Y');
    }

    if ($auction_month == $auction_month_current) {
        // If chit amount > 0, add pending amount
        if ($chit_share > 0) {
            $initial_payable_amount = $chit_share + $pending_amount;
           
        } 
        // If chit amount is 0 and pending amount > 0, use previous month's payable amount
        else if ($pending_amount > 0) {
            $initial_payable_amount = '';
        } 
        // If chit amount is 0 and no pending amount, set payable to 0
        else {
            $initial_payable_amount = '';
        }
    } else {
        // For previous months, just use the chit amount and store it as the previous payable amount
        $initial_payable_amount = $chit_share;
        $previous_payable_amount = $initial_payable_amount;  // Store this for future use
    }

    // Fetch collection details for the auction month
    $qry2 = $pdo->query("SELECT 
        c.chit_amount as chit_share,
        c.auction_month,
        c.payable,
        c.collection_date, 
        c.collection_amount, 
        c.id as coll_id
    FROM 
        auction_details ad
    LEFT JOIN 
        collection c ON ad.group_id = c.group_id 
                     AND c.cus_mapping_id = '$cusMappingID'
                     AND c.share_id ='$share_id'
                     AND ad.auction_month = c.auction_month
    WHERE 
        c.group_id = '$groupId' AND
        c.cus_mapping_id = '$cusMappingID' AND  c.share_id ='$share_id'
        AND c.auction_month = '$auction_month' ORDER BY c.id");

    if ($qry2->rowCount() > 0) {
        while ($row = $qry2->fetch(PDO::FETCH_ASSOC)) {
            if (!empty($row['collection_date'])) {
                $collection_date = new DateTime($row['collection_date']);
                $row['collection_date'] = $collection_date->format('d-m-Y');
            }
            $payable = (int)$row['payable'];
            $collection_amount = (int)$row['collection_amount'];
            $pending = $payable - $collection_amount;
            $initial_payable_amount =$payable;
            if ($row['chit_share'] == 0 ) {
                $row['chit_share'] = $chit_share;
            }else{
                $row['chit_share'] = $chit_share;
            }
            // Ensure pending is not negative
            $pending = max($pending, 0);
            // Add auction_month and auction_date to the row
            $row['auction_month'] = !in_array($auction_month, $auctionMonthUsed) ? $auction_month : '';
            $row['auction_date'] = $auction_date;
            $row['initial_payable_amount'] = $initial_payable_amount;
            $row['pending'] = $pending;
            $row['action'] = "<a class='print_due_coll' id='" . $row['coll_id'] . "'> <i class='fa fa-print' aria-hidden='true'></i> </a>";

            $due_list_arr[$i] = $row;
            $i++;

            // Mark this auction month as used
            $auctionMonthUsed[] = $auction_month;
        }
    } else {
        // No data found, create a default entry 
        $due_list_arr[$i] = array(
            'auction_month' => !in_array($auction_month, $auctionMonthUsed) ? $auction_month : '',
            'auction_date' => $auction_date,
            'chit_share' => $chit_share,
            'collection_date' => '',
            'collection_amount' => '',
            'initial_payable_amount' => $initial_payable_amount,
            'pending' => '',
            'id' => '',
            'action' => ''
        );
        $i++;
    }
}
$collectionDatesQuery = "SELECT collection_date FROM collection WHERE collection_date > '" . $lastAuctionDate->format('Y-m-d') . "' AND group_id ='$groupId' AND cus_mapping_id= '$cusMappingID' AND share_id = '$share_id'";
$collectionDatesStmt = $pdo->query($collectionDatesQuery);
$collectionDates = $collectionDatesStmt->fetchAll(PDO::FETCH_ASSOC);

// Add future collection dates to $due_list_arr
foreach ($collectionDates as $collectionDate) {
    $collectionDateFormatted = date('d-m-Y', strtotime($collectionDate['collection_date']));
    $auction_month_future = date('m', strtotime($collectionDate['collection_date']));

    // Only add if the auction month is not already used
    if (!in_array($auction_month_future, $auctionMonthUsed)) {
        $due_list_arr[] = array(
            'auction_month' => $auction_month_future,
            'auction_date' => $collectionDateFormatted,
            'chit_share' => '',
            'collection_date' => '',
            'payable' => '',
            'pending' => '',
            'id' => '',
            'action' => ''
        );
        $auctionMonthUsed[] = $auction_month_future; // Mark this future auction month as used
    }
}

echo json_encode($due_list_arr);
$pdo = null; // Close Connection
?>
