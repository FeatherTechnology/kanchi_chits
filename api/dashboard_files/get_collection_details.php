<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];
$branchId = $_POST['branchId'];
$response = array();

//Total Paid
$tot_paid = "SELECT COALESCE(SUM(collection_amount),0) AS total_paid FROM `collection` c JOIN group_creation gc ON c.group_id = gc.grp_id WHERE MONTH(c.collection_date) = MONTH(CURDATE()) 
AND YEAR(c.collection_date) = YEAR(CURDATE())  ";

//Today Paid
$today_paid = "SELECT COALESCE(SUM(collection_amount),0) AS today_paid FROM `collection` c JOIN group_creation gc ON c.group_id = gc.grp_id WHERE DATE(c.created_on) = CURDATE() ";

if ($branchId != '' && $branchId != '0') {
    $tot_paid .= " AND gc.branch = $branchId  AND  c.insert_login_id = '$user_id'";
    $today_paid .= " AND gc.branch = $branchId  AND  c.insert_login_id = '$user_id'";
} else {
    $tot_paid .= "AND c.insert_login_id = '$user_id'";
    $today_paid .= " AND c.insert_login_id = '$user_id'";
}

$qry = $pdo->query($tot_paid);
$response['total_paid'] = $qry->fetch()['total_paid'];
$qry = $pdo->query($today_paid);
$response['today_paid'] = $qry->fetch()['today_paid'];

echo json_encode($response);
