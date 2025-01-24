<?php
require "../../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$coll_mode = $_POST['coll_mode'];
$bank_id = $_POST['bank_id'];
$invoice_id = $_POST['invoice_id'];
$branch_name = $_POST['branch_name'];
$expenses_category = $_POST['expenses_category'];
$description = $_POST['description'];
$expenses_amnt = $_POST['expenses_amnt'];
$expenses_trans_id = $_POST['expenses_trans_id'];

$qry = $pdo->query("INSERT INTO `expenses`(`coll_mode`, `bank_id`, `invoice_id`, `branch`, `expenses_category`, `description`, `amount`, `trans_id`, `insert_login_id`, `created_on`) VALUES ('$coll_mode','$bank_id','$invoice_id','$branch_name','$expenses_category','$description','$expenses_amnt', '$expenses_trans_id','$user_id',now() )");

if ($qry) {
    $result = 1;
} else {
    $result = 2;
}

echo json_encode($result);
