<?php
require '../../ajaxconfig.php';
@session_start();

$cus_id = $_POST['cus_id']; // Add this line to get the customer ID
$occupation = $_POST['occupation'];
$occ_detail = $_POST['occ_detail'];
$occ_place = $_POST['occ_place'];
$source = $_POST['source'];
$income = $_POST['income'];
$user_id = $_SESSION['user_id']; // Corrected session variable name

    $qry = $pdo->query("INSERT INTO `source`(`cus_id`, `occupation`, `occ_detail`,`occ_place`, `source`, `income`, `insert_login_id`, `created_on`) VALUES ('$cus_id', '$occupation', '$occ_detail', '$occ_place','$source', '$income','$user_id', now())");
    $result = 1; // Insert


echo json_encode($result);
?>