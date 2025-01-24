<?php
require '../../ajaxconfig.php';

$auction_id = $_POST['auction_id'];
$cus_id = $_POST['cus_id'];
$qry = $pdo->query("
    SELECT si.balance_amount,si.settle_amount
    FROM settlement_info si
     LEFT JOIN customer_creation cc ON si.cus_name = cc.id
    WHERE si.auction_id = '$auction_id' AND cc.cus_id = '$cus_id'
    ORDER BY si.id DESC
    LIMIT 1
");

if ($qry->rowCount() > 0) {
    $result = $qry->fetch(PDO::FETCH_ASSOC);
} else {
    $result = ['balance_amount' => null]; // Default to 0 if no records found
}

$pdo = null; // Close connection
echo json_encode($result);
?>
