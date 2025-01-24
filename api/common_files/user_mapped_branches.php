<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];
$result =array();
$qry=$pdo->query("SELECT bc.id, bc.branch_name FROM branch_creation bc JOIN users u ON FIND_IN_SET(bc.id, u.branch) WHERE u.id = '$user_id' ");
if($qry->rowCount()>0){
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}
$pdo=null; //Close Connection.

echo json_encode($result);
?>