<?php
require '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id']; 

$chit_value = $_POST['chitvalue'];
$chit_month=$_POST['chitmonth'];
$enquiry_id=$_POST['enquiryid'];
$result=0;
if($enquiry_id !='0' && $enquiry_id!=''){
    $sql = $pdo->query("UPDATE `enquiry_creation` SET `chit_value`='$chit_value',`total_month`='$chit_month' WHERE `id`='$enquiry_id' ");
    if($sql){
        $result = 1; 
    }
    $last_id= $enquiry_id;
}
else{
    $sql = $pdo->query("INSERT INTO `enquiry_creation`(`chit_value`, `total_month`,`insert_login_id`,`created_on`) VALUES ('$chit_value','$chit_month','$user_id',now())");
    if($sql){
        $result = 2; 
    }
    $last_id= $pdo->lastInsertId();
}
echo json_encode(array('result'=>$result,'lastid'=>$last_id));



?>