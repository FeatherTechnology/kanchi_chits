<?php
require '../../ajaxconfig.php';


$id = $_POST['id'];
if ($id != '0' && $id != '') {
    $qry = $pdo->query("SELECT cus_id FROM customer_creation WHERE id = '$id'");
    $qry_info = $qry->fetch();
    $cus_ID_final = $qry_info['cus_id'];
} else {

    $qry = $pdo->query("SELECT cus_id FROM customer_creation WHERE cus_id !='' ORDER BY id DESC ");
    if ($qry->rowCount() > 0) {
        $qry_info = $qry->fetch(); //LID-101
        $l_no = ltrim(strstr($qry_info['cus_id'], '-'), '-'); 
        $l_no = $l_no+1;
        $cus_ID_final = "C-"."$l_no";
    } else {
        $cus_ID_final = "C-" . "101";
    }
}
echo json_encode($cus_ID_final);
?>
