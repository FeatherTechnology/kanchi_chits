<?php
require "../../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$trans_cat = $_POST['transCat'];
$name = $_POST['name'];

$qry = $pdo->query("INSERT INTO `other_trans_name`(`trans_cat`, `name`, `insert_login_id`, `created_on`) VALUES ('$trans_cat','$name','$user_id',now())");
if ($qry) {
    $result = 1;
} else {
    $result = 2;
}

echo json_encode($result);
