<?php
require "../../ajaxconfig.php";
$result =array();
$qry=$pdo->query("SELECT cc.id,cc.cus_id, cc.first_name, cc.last_name ,pl.place FROM customer_creation cc JOIN place pl ON cc.place = pl.id order by cc.id desc ");
if($qry->rowCount()>0){
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}
$pdo=null; //Close Connection.

echo json_encode($result);
?>