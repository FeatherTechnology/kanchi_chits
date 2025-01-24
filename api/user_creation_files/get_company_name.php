<?php
//Also Using in User creation.
require '../../ajaxconfig.php';

$response = array();

try {
    $qry =  $pdo->query("SELECT company_name FROM company_creation");
    if ($qry->rowCount() > 0) {
        $row = $qry->fetch(PDO::FETCH_ASSOC);
        $response['company_name'] = $row['company_name'];
    }
} catch (PDOException $e) {
    $response['error'] = $e->getMessage();
}

$pdo = null; // Close Connection
echo json_encode($response);
