<?php
require "../../../ajaxconfig.php";

$group_mem_id = $_POST['group_mem_id'];  // Get group member ID from the request
$group_id = $_POST['group_id'];  // Get group member ID from the request

// Query to get the customer's chit count and check if they have a transaction in the other_transaction table
$qry = "SELECT 
        gs.cus_id, 
        gs.grp_creation_id, 
        COUNT(gs.cus_id) AS chit_count,
        (SELECT COUNT(*) FROM other_transaction WHERE group_mem = gs.cus_id AND type = 2 AND group_id='$group_id' ) AS transaction_count
        FROM group_share gs 
        WHERE gs.grp_creation_id = '$group_id' AND gs.cus_id = '$group_mem_id' ";

$result = $pdo->query($qry)->fetch(PDO::FETCH_ASSOC);

// Determine the cat_type based on the chit count and transaction count
$cat_type = '';
if ($result['transaction_count'] == 0 && $result['chit_count'] > 0) {
    // If the member has no transactions and chit count > 1, show Debit (2)
    $cat_type = 2;
} else if ($result['transaction_count'] == $result['chit_count']) {
    // If the member has transactions and chit count <= 1, show Credit (1)
    $cat_type = 1;
} else {
    // If no transactions and chit count <= 1, show Debit (2)
    $cat_type = 'both';
}

echo json_encode([
    'cat_type' => $cat_type
]);

$pdo = null; // Close the connection
?>
