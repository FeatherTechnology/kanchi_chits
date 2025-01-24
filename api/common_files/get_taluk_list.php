<?php
require '../../ajaxconfig.php';

$district_id = $_POST['district_id'];

$qry = $pdo->query("SELECT id, taluk_name FROM taluks WHERE district_id='$district_id' AND status = 1 ");
if ($qry->rowCount() > 0) {
    $response = $qry->fetchAll(PDO::FETCH_ASSOC);
}
$pdo = null; //Close Connection

echo json_encode($response);
