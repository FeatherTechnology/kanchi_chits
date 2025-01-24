<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];

$qry = $pdo->query("SELECT 
            ad.id,
            cc.cus_id,
            gi.guarantor_name
        FROM 
            auction_details ad
        JOIN 
            customer_creation cc ON ad.cus_name = cc.id 
        JOIN 
            place pl ON cc.place = pl.id
        WHERE 
            ad.id = '$id'");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as &$row) {
        $row['reference_type'] = $reference_type[$row['reference_type']];
    }
}

$pdo = null; //Close connection.
echo json_encode($result);