<?php
require '../../ajaxconfig.php';

$state_id = $_POST['state_id'];

$qry = $pdo->query("SELECT id, district_name FROM districts WHERE state_id='$state_id' AND status = 1 ");
if ($qry->rowCount() > 0) {
    $response = $qry->fetchAll(PDO::FETCH_ASSOC);
}
$pdo = null; //Close Connection

echo json_encode($response);
