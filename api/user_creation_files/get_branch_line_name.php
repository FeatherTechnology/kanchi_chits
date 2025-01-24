<?php
require "../../ajaxconfig.php";
$branch_id = $_POST['branchId'];
$response =array();
$qry = $pdo->query("SELECT lnc.id, lnc.linename FROM `area_creation` ac LEFT JOIN line_name_creation lnc ON ac.line_id = lnc.id WHERE FIND_IN_SET(ac.branch_id, '$branch_id')");
if ($qry->rowCount() > 0) {
    $response = $qry->fetchAll(PDO::FETCH_ASSOC);
}
$pdo = null;
echo json_encode($response);