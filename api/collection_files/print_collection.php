<?php
require '../../ajaxconfig.php';

$due_list_arr = array();
$coll_id = $_POST["coll_id"];
$i = 0;

// Fetch auction details for the given group and customer mapping ID
$qry = $pdo->query("SELECT c.group_id, gc.grp_name AS group_name, CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name,
                           c.auction_month, c.chit_amount, c.payable, c.collection_date, c.collection_amount, 
                           (c.payable - c.collection_amount) AS pending
                    FROM collection c
                    JOIN customer_creation cc ON c.cus_id = cc.cus_id
                    JOIN group_creation gc ON c.group_id = gc.grp_id
                    WHERE c.id = '$coll_id'");

if ($qry->rowCount() > 0) {
    while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {

        // Ensure pending is not negative
        $row['pending'] = max($row['pending'], 0);

        // Convert and format collection_date to dd-mm-yyyy
        $date = new DateTime($row['collection_date']);
        $row['collection_date'] = $date->format('d-m-Y');

        $due_list_arr[$i] = $row;
        $i++;
    }
} 

echo json_encode($due_list_arr);
$pdo = null; // Close Connection
?>
