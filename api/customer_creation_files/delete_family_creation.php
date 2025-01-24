<?php
require "../../ajaxconfig.php";

$id = $_POST['id'];
$cus_id = $_POST['cus_id'];
$customer_id = $_POST['customer_id'];

try {
    $qry = $pdo->query("SELECT * FROM family_info WHERE cus_id = '$cus_id' ");
    if ($qry->rowCount() == 1 && $customer_id != '') { //If Only one count of kyc for the customer then restrict to delete.
        $result = '0';
    } else {
        $qry = $pdo->prepare("DELETE FROM `family_info` WHERE `id` = :id");
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        if ($qry->execute()) {
            $result = 1; // Deleted.
        } else {
            throw new Exception();
        }
    }
} catch (Exception $e) {
    $result = 2; // Handle general exceptions
}

echo json_encode($result);
