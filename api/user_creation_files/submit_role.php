<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];
$role = $_POST['role'];
$id = $_POST['id'];

$qry = $pdo->query("SELECT * FROM `role` WHERE REPLACE(TRIM(role), ' ', '') = REPLACE(TRIM('$role'), ' ', '') ");
if ($qry->rowCount() > 0) {
    $result = 0; //already Exists.

} else {
    if ($id != '0' && $id != '') {
        $pdo->query("UPDATE `role` SET `role`='$role',`update_login_id`='$user_id',`updated_on`=now() WHERE `id`='$id' ");
        $result = 1; //update

    } else {
        $pdo->query("INSERT INTO `role`(`role`, `insert_login_id`, `created_on`) VALUES ('$role','$user_id', now())");
        $result = 2; //Insert
    }
}

echo json_encode($result);
