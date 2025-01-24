<?php
require '../../ajaxconfig.php';

    $cus_sts_id = $_POST['cus_sts_id'];
    $remark = $_POST['remark'];

 if ($cus_sts_id != ''){
        // Update customer_profile table
        $qry = $pdo->query("UPDATE customer_profile SET `remark` = '$remark' WHERE `id` = '$cus_sts_id'");
        $result = 0;
 }
       
echo json_encode($result);
?>


