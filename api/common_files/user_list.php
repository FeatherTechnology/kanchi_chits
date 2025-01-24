<?php
require "../../ajaxconfig.php";

$result = array();

$qry = $pdo->query("SELECT id, name FROM users ");
if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$pdo = null; //close connection.
echo json_encode($result);
