<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];
$auction_id = $_POST['auction_id'];
$qry = $pdo->query("SELECT 
            ad.id,
            gc.chit_value,
            ad.auction_value,
            (gc.chit_value - ad.auction_value) AS settle_amount,
            gs.share_percent,
            (gc.chit_value - ad.auction_value)*(gs.share_percent / 100) as settlement_amount
        FROM 
            auction_details ad
        LEFT JOIN 
            group_creation gc ON ad.group_id = gc.grp_id 
            LEFT JOIN group_share gs ON
    ad.cus_name = gs.cus_mapping_id
    LEFT JOIN customer_creation cc ON
    gs.cus_id = cc.id
        WHERE cc.id = '$id' AND ad.id ='$auction_id'");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
} else {
    $result = []; // Return empty array if no data found
}

$pdo = null; // Close connection
echo json_encode($result);
?>
