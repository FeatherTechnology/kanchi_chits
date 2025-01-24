<?php
require "../../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];
$userid = $_POST['id'];
$branch = $_POST['branch'];
$no_of_customers = $_POST['no_of_customers'];
$total_amount = str_replace(',', '', $_POST['total_amount']);
$cash_type = $_POST['cash_type'];
$bank_id = $_POST['bank_id'];

$qry = $pdo->query("INSERT INTO `accounts_collect_entry`( `user_id`, `branch`, `coll_mode`, `bank_id`, `no_of_customers`, `collection_amnt`, `insert_login_id`, `created_on`) VALUES ('$userid','$branch','$cash_type','$bank_id','$no_of_customers','$total_amount','$user_id',now())");

if ($qry) {
    $result = 1;
} else {
    $result = 2;
}

echo json_encode($result);
