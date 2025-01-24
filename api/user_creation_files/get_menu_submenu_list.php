<?php
require "../../ajaxconfig.php";

$response = array();
//get the menu list
$qry = $pdo->query("SELECT m.menu AS main_menu, m.link AS main_menu_link, s.id AS sub_menu_id, s.sub_menu, s.link AS sub_menu_link FROM menu_list m LEFT JOIN sub_menu_list s ON m.id = s.main_menu ");
if ($qry->rowCount() > 0) {
    $response = $qry->fetchAll(PDO::FETCH_ASSOC);
}
$pdo = null; //close connection
echo json_encode($response);