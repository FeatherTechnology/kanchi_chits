<?php
require '../../ajaxconfig.php';

$id = $_POST['id']; // Sanitize input
$reference_type = [1 => "Promotion", 2 => "Customer", 3 => "Well Known Person"];

$statement = $pdo->query("SELECT 
            ad.id,
            cc.cus_id,
            CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name,
            pl.place,
            cc.mobile1,
            (SELECT GROUP_CONCAT(sc.occupation SEPARATOR ', ')
             FROM source sc 
             WHERE sc.cus_id = cc.cus_id
            ) AS occupations,
            cc.reference_type,
            cc.pic
        FROM 
            auction_details ad
 LEFT JOIN group_share gs ON
    ad.group_id = gs.grp_creation_id
LEFT JOIN customer_creation cc ON
    gs.cus_id = cc.id 
        LEFT JOIN 
            place pl ON cc.place = pl.id
        WHERE 
            cc.id = '$id'
        LIMIT 1
");

$result = $statement->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $result['reference_type'] = isset($reference_type[$result['reference_type']]) ? $reference_type[$result['reference_type']] : '';
    echo json_encode([$result]); // Wrap result in an array
} else {
    echo json_encode([]); // Return an empty array if no result
}

$pdo = null; // Close connection
?>
