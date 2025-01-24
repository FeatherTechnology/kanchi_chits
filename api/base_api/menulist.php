<?php
session_start();

include('../../ajaxconfig.php');

$user_id = $_SESSION['user_id'];

$response = array();

// this will get the screens allocated to the user
$qry = $pdo->prepare("SELECT screens from users where `id` = $user_id ");
$qry->execute();
$row = $qry->fetch();
$screens = $row['screens'];

//get the menu list allocated to the user
$qry = $pdo->query("SELECT m.menu AS main_menu, m.link AS main_menu_link, m.icon as main_menu_icon, s.sub_menu, s.link AS sub_menu_link, s.icon as sub_menu_icon FROM menu_list m LEFT JOIN sub_menu_list s ON m.id = s.main_menu WHERE s.id IN ($screens) ORDER BY s.id ASC ");
if ($qry->rowCount() > 0) {
    $response = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$pdo = null; //close connection

echo json_encode($response);
