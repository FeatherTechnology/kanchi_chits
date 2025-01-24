<?php
require '../../ajaxconfig.php';

$response =array();
$cus_id = $_POST['cus_id'];
$grp_id = $_POST['grp_id'];

$qry = $pdo->query("SELECT 
        ad.id,
        ad.auction_month
    FROM 
        auction_details ad
    LEFT JOIN 
        group_creation gc ON ad.group_id = gc.grp_id
   LEFT JOIN group_share gs ON
    ad.cus_name = gs.cus_mapping_id
LEFT JOIN customer_creation cc ON
    gs.cus_id = cc.id
WHERE
    cc.cus_id = '$cus_id' AND  ad.cus_name = gs.cus_mapping_id AND ad.group_id='$grp_id' Group BY ad.id");
if ($qry->rowCount() > 0) {
    $response = $qry->fetchAll(PDO::FETCH_ASSOC);
}
$pdo = null; //Close Connection

echo json_encode($response);