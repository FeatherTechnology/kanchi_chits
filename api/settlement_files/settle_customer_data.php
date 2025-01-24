<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];
$auction_id = $_POST['auction_id'];
$reference_type = [1 => "Promotion", 2 => "Customer", 3 => "Well Known Person"];

$qry = $pdo->query("SELECT 
    cc.id,
    cc.cus_id,
    pl.place,
    cc.mobile1,
    (
    SELECT
        GROUP_CONCAT(sc.occupation SEPARATOR ', ')
    FROM SOURCE
        sc
    WHERE
        sc.cus_id = cc.cus_id
) AS occupations,
cc.reference_type,
cc.pic,
gcm.map_id
FROM
    auction_details ad
LEFT JOIN group_share gs ON
    ad.cus_name = gs.cus_mapping_id
    LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
LEFT JOIN customer_creation cc ON
    gs.cus_id = cc.id
JOIN place pl ON
    cc.place = pl.id
WHERE
    cc.id = '$id' AND ad.id ='$auction_id'");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as &$row) {
        // Handle undefined or null reference_type
        if (!empty($row['reference_type']) && isset($reference_type[$row['reference_type']])) {
            $row['reference_type'] = $reference_type[$row['reference_type']];
        } else {
            $row['reference_type'] = ''; // Default value if reference_type is not valid
        }
    }
}

$pdo = null; // Close connection.
echo json_encode($result);
?>
