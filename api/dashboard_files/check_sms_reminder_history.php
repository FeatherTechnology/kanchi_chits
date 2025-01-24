<?php
include "../../ajaxconfig.php";

$qry = $pdo->query("SELECT * FROM sms_remider_history WHERE created_on = CURDATE() ");
if($qry->rowCount() > 0){
    $return_data = 1;
}else{
    $return_data = 2;
}

echo json_encode($return_data);
?>

