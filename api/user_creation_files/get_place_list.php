<?php
require "../../ajaxconfig.php";

$role_arr = array();
$qry = $pdo->query("SELECT id,place FROM place");
if ($qry->rowCount() > 0) {
    while ($role_info = $qry->fetch(PDO::FETCH_ASSOC)) {
        $role_info['action'] = "<span class='icon-border_color placeActionBtn' value='" . $role_info['id'] . "'></span>  <span class='icon-trash-2 placeDeleteBtn' value='" . $role_info['id'] . "'></span>";
        $role_arr[] = $role_info;
    }
}
$pdo = null; //Connection Close.
echo json_encode($role_arr);