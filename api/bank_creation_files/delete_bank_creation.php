<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];

$qry = $pdo->prepare("SELECT qr_code FROM bank_creation WHERE id = $id ");
if ($qry->execute()) {
    $row = $qry->fetch(PDO::FETCH_ASSOC);

    if($row['qr_code'] !=''){
        unlink("../../uploads/bank_creation/qr_code/" . $row['qr_code']);
    }

    $qry = $pdo->query("DELETE FROM `bank_creation` WHERE `id` = '$id'");
}


if ($qry) {
    $result = '1'; // Success
} else {
    $result = '0'; //Failed
}

$pdo = null; // Close Connection

echo json_encode($result); // Failure
