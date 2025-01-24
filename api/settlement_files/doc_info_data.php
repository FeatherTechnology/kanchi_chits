<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];

// Modify the query to join the customer_creation table and fetch the customer name
$qry = $pdo->query("
    SELECT
    di.*,
    CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name,
    ad.group_id,
    gc.grp_name,
    gc.status
FROM
    `document_info` di
LEFT JOIN `customer_creation` cc ON
    di.cus_id = cc.cus_id
LEFT JOIN auction_details ad ON di.auction_id = ad.id
LEFT JOIN group_creation gc ON ad.group_id=gc.grp_id
    WHERE di.id = '$id'
");

$result = [];

if ($qry->rowCount() > 0) {
    $data = $qry->fetch(PDO::FETCH_ASSOC); // Fetch a single row

    // Check if holder_name is 0 and use cus_name instead
    if ($data['holder_name'] == 0) {
        $data['holder_name'] = $data['cus_name']; // Replace holder_name with cus_name
    }

    $result[] = $data; // Add modified data to result
}

$pdo = null; // Close connection

echo json_encode($result);
?>
