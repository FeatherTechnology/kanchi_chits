<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];

$qry = $pdo->query("SELECT  * FROM guarantor_info  WHERE id='$id'");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}
$pdo = null; 
echo json_encode($result);
// Close connection
?>

