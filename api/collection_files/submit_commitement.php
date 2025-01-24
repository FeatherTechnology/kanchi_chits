<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$group_id = $_POST['group_id'];
$cus_mapping_id=$_POST['cus_mapping_id'];
$share_id=$_POST['share_id'];
$label = $_POST['label'];
$remark = $_POST['remark'];
$commitment_date = $_POST['commitment_date'];

// Build the SQL query
$qry = "INSERT INTO commitment_info (cus_mapping_id,share_id,
     group_id, label, remark,commitment_date,insert_login_id, created_on
) VALUES ('$cus_mapping_id','$share_id',
     '$group_id', '$label', '$remark' ,'$commitment_date','$user_id', NOW()
)";

if ($pdo->query($qry)) {
    $result = 1;
} else {
    $result = 0;
}   
echo json_encode($result);

?>
