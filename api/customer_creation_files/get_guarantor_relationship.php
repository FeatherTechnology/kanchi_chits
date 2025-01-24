<?php
require '../../ajaxconfig.php';

$response = array();
$cus_id = $_POST['cus_id'];
//$relationship = [1 => 'Father', 2 => 'Mother', 3 => 'Spouse', 4 => 'Son', 5 => 'Daughter', 6 => 'Brother', 7 => 'Sister', 8 => 'Other'];
$qry = $pdo->query("SELECT id, fam_name FROM family_info WHERE cus_id = '$cus_id'");
if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
       // $row['fam_relationship'] = $relationship[$row['fam_relationship']];
        $response[] = $row;
    }
}
$pdo = null; // Close Connection

echo json_encode($response);
?>
