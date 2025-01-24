<?php
include('../../ajaxconfig.php');

$user_id = $_POST['user_id'];
$response = array();
// this will get the screens allocated to the user
$qry = $pdo->query("SELECT m.link AS main_menu_link, s.id AS sub_menu_id, s.link as sub_menu_link
FROM users u 
LEFT JOIN sub_menu_list s ON FIND_IN_SET(s.id, u.screens)
LEFT JOIN menu_list m ON m.id = s.main_menu
WHERE u.id = '$user_id' ");
if ($qry->rowCount() > 0) {
    $response = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$pdo = null; //close connection
echo json_encode($response);