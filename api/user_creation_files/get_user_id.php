<?php
require "../../ajaxconfig.php";

$user_creation_id = $_POST['id'];
if ($user_creation_id != '0' && $user_creation_id != '') {
    $qry = $pdo->query("SELECT user_code FROM users WHERE id = '$user_creation_id'");
    $qry_info = $qry->fetch();
    $user_code_final = $qry_info['user_code'];
} else {

    $qry = $pdo->query("SELECT user_code FROM users WHERE user_code !='' ORDER BY id DESC ");
    if ($qry->rowCount() > 0) {
        $qry_info = $qry->fetch(); //US-001
        $usr_code_f = substr($qry_info['user_code'], 0, 3);
        $usr_code_s = substr($qry_info['user_code'], 3, 5);
        $final_code = str_pad($usr_code_s + 1, 3, 0, STR_PAD_LEFT);
        $user_code_final = $usr_code_f.$final_code;
    } else {
        $user_code_final = "US-" . "001";
    }
}
echo json_encode($user_code_final);
?>
