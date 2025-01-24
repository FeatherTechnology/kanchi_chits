<?php
require "../../ajaxconfig.php";
$result =array();
$qry=$pdo->query("SELECT id, branch_name FROM branch_creation");
if($qry->rowCount()>0){
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}
$pdo=null; //Close Connection.

echo json_encode($result);
?>