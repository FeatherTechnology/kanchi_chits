<?php

require '../../ajaxconfig.php';

$response = array();
$qry = $pdo->query("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM customer_creation WHERE reference = 1");

if ($qry->rowCount() > 0) {
    $response = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$pdo = null; // Close Connection

echo json_encode($response);
?>
