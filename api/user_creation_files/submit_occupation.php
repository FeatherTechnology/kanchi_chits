<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];
$designation = $_POST['designation'];
$id = $_POST['id'];

$qry = $pdo->query("SELECT * FROM `designation` WHERE REPLACE(TRIM(designation), ' ', '') = REPLACE(TRIM('$designation'), ' ', '') ");
if ($qry->rowCount() > 0) {
    $result = 0; //already Exists.

} else {
    if ($id != '0' && $id != '') {
        $pdo->query("UPDATE `designation` SET `designation`='$designation',`update_login_id`='$user_id',`updated_on`=now() WHERE `id`='$id' ");
        $result = 1; //update

    } else {
        $pdo->query("INSERT INTO `designation`(`designation`, `insert_login_id`, `created_on`) VALUES ('$designation','$user_id', now())");
        $result = 2; //Insert
    }
}

echo json_encode($result);
