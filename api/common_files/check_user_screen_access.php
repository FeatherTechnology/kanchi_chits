<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$result =array();
$qry = $pdo->query("SELECT screens FROM `users` WHERE id = '$user_id' ");
if($qry->rowCount()>0){
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($result);
