<?php
require '../../ajaxconfig.php';
@session_start();

$name = $_POST['name']; 
$customerstatus = $_POST['customerstatus'];
$mobilenumber = $_POST['mobilenumber'];
$place = $_POST['place'];
$remarks= $_POST['remarks'];
$enquiry_id=$_POST['enquiryid'];
$user_id = $_SESSION['user_id']; 
$customerlistid=$_POST['customerlistid'];


    // $qry = $pdo->query("INSERT INTO `enquiry_creation_customer`( `enquiry_creation_id`, `cus_name`, `cus_status`, `mobile_number`, `place`, `remarks`, `insert_login_id`, `creater_on`) VALUES ('$enquiry_id','$name','$customerstatus','$mobilenumber','$place ','$remarks','$user_id',now())");
    // $result = 1; 
    $result=0;
    if($customerlistid !='0' && $customerlistid!=''){
        $sql = $pdo->query("UPDATE `enquiry_creation_customer` SET `enquiry_creation_id`='$enquiry_id',`cus_name`='$name',`cus_status`='$customerstatus',`mobile_number`='$mobilenumber',`place`='$place',`remarks`='$remarks',`insert_login_id`='$user_id' WHERE `id`='$customerlistid'");
        if($sql){
            $result = 1; 
        }
        
    }
    else{
        $sql = $pdo->query("INSERT INTO `enquiry_creation_customer`( `enquiry_creation_id`, `cus_name`, `cus_status`, `mobile_number`, `place`, `remarks`, `insert_login_id`, `creater_on`) VALUES ('$enquiry_id','$name','$customerstatus','$mobilenumber','$place ','$remarks','$user_id',now())");
        if($sql){
            $result = 2; 
        }
        
    }


echo json_encode(array('result'=>$result));
?>