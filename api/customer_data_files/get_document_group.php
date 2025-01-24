
<?php
require '../../ajaxconfig.php';

$response = array();
$cus_id = $_POST['cus_id'];

// Query to fetch unique group information

$qry = $pdo->query("
    SELECT
    gc.id,
    gc.grp_id,
    gc.grp_name,
    ad.id AS auction_id,
    ad.auction_month
FROM
    auction_details ad
LEFT JOIN group_creation gc ON
    ad.group_id = gc.grp_id
LEFT JOIN group_share gs ON
    ad.cus_name = gs.cus_mapping_id
LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
LEFT JOIN customer_creation cc ON
    gs.cus_id = cc.id
WHERE
    cc.cus_id = '$cus_id' AND  gs.cus_id = cc.id AND gc.status NOT IN(4, 5)
GROUP BY
    ad.id
");

// Initialize an array to hold the results
$groupedResponse = array();

if ($qry->rowCount() > 0) {
    // Fetch all results
    while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
        $grpId = $row['grp_id'];
        // Initialize the group entry if it doesn't exist
        if (!isset($groupedResponse[$grpId])) {
            $groupedResponse[$grpId] = [
                'grp_id' => $grpId,
                'grp_name' => $row['grp_name'],
                'auctions' => []
            ];
        }
        
    }
}

// Convert the grouped response to a simple indexed array
$response = array_values($groupedResponse);

$pdo = null; // Close connection

echo json_encode($response);
?>
