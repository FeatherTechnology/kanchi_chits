<?php
require '../../../ajaxconfig.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$group_id = isset($_POST['group_id']) ? $_POST['group_id'] : null;
$group_mem = isset($_POST['group_mem']) ? $_POST['group_mem'] : null;
$auction_month = isset($_POST['auction_month']) ? $_POST['auction_month'] : null;

$result = 2; // Default failure response

    // Proceed with the deletion only if all required values are provided
    $qry = $pdo->query("DELETE FROM `other_transaction` WHERE id='$id'");
    $qry1 = $pdo->query("DELETE FROM `settlement_info` WHERE group_id='$group_id' AND cus_name='$group_mem' AND auction_month='$auction_month'");
    $qry2 = $pdo->query("UPDATE `group_share` 
    SET `settle_status` = NULL 
    WHERE `grp_creation_id` = '$group_id' 
    AND `cus_id` = '$group_mem' 
    AND `settle_status`='Yes' 
    LIMIT 1");
    if ($qry && $qry1 && $qry2) {
        $result = 1; // Success response
    }


$pdo = null; // Close connection.

echo json_encode($result);
