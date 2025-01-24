<?php
require "../../../ajaxconfig.php";

$trans_cat = $_POST['trans_cat'];
$transcat = ["1" => 'DEP', "2" => 'INV', "3" => 'EL', "4" => 'EXC', "5" => 'BDEP', "6" => 'BWDL', "7" => 'ADV', "8" => 'INC', "9" => 'UBL'];
$trans = $transcat[$trans_cat];

$qry = $pdo->query("SELECT id,ref_id FROM other_transaction WHERE trans_cat ='$trans_cat' AND ref_id !='' ORDER BY id DESC LIMIT 1");
if ($qry->rowCount() > 0) {
    $last_ref_id = $qry->fetch()['ref_id'];
    $ref_s = ltrim(strstr($last_ref_id, '-'), '-');
    $ref = $ref_s + 1;
    $ref_id = $trans . '-' . $ref;
} else {
    $ref_id = $trans . '-101';
}
echo json_encode($ref_id);