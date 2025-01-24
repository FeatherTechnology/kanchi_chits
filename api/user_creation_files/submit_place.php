<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];
$place = $_POST['place'];
$id = $_POST['id'];

$qry = $pdo->query("SELECT * FROM `place` WHERE REPLACE(TRIM(place), ' ', '') = REPLACE(TRIM('$place'), ' ', '') ");
if ($qry->rowCount() > 0) {
    $result = 0; //already Exists.

} else {
    if ($id != '0' && $id != '') {
        $pdo->query("UPDATE `place` SET `place`='$place',`update_login_id`='$user_id',`updated_on`=now() WHERE `id`='$id' ");
        $result = 1; //update

    } else {
        $pdo->query("INSERT INTO `place`(`place`, `insert_login_id`, `created_on`) VALUES ('$place','$user_id', now())");
        $result = 2; //Insert
    }
}

echo json_encode($result);
