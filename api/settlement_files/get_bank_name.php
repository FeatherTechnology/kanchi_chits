<?php
require '../../ajaxconfig.php';

$response = array();
$qry = $pdo->query("SELECT id,bank_name FROM bank_creation WHERE status = 1");
if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
       
        $response[] = $row;
    }
}
$pdo = null; // Close Connection

echo json_encode($response);
?>