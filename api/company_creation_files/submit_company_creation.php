<?php
require '../../ajaxconfig.php';
@session_start();

$company_name = $_POST['company_name'];
$address = $_POST['address'];
$state = $_POST['state'];
$district = $_POST['district'];
$taluk = $_POST['taluk'];
$place = $_POST['place'];
$pincode = $_POST['pincode'];
$website = $_POST['website'];
$mailid = $_POST['mailid'];
$mobile = $_POST['mobile'];
$whatsapp = $_POST['whatsapp'];
$landline_code = $_POST['landline_code'];
$landline = $_POST['landline'];
$user_id = $_SESSION['user_id'];
$companyid = $_POST['companyid'];

if($companyid !=''){
    $qry = $pdo->query("UPDATE `company_creation` SET `company_name`='$company_name',`address`='$address',`state`='$state',`district`='$district',`taluk`='$taluk',`place`='$place',`pincode`='$pincode',`website`='$website',`mailid`='$mailid',`mobile`='$mobile',`whatsapp`='$whatsapp',`landline_code`='$landline_code',`landline`='$landline',`status`='1',`update_user_id`='$user_id',`updated_date`=now() WHERE `id`='$companyid'");
    $result = 0; //update

}else{
    $qry = $pdo->query("INSERT INTO `company_creation`(`company_name`, `address`, `state`, `district`, `taluk`, `place`, `pincode`, `website`, `mailid`, `mobile`, `whatsapp`, `landline_code`, `landline`, `insert_user_id`, `created_date`) VALUES ('$company_name','$address','$state','$district','$taluk','$place','$pincode','$website','$mailid','$mobile','$whatsapp','$landline_code', '$landline','$user_id', now())");
    $result = 1; //Insert
}

echo json_encode($result);
?>