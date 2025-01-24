<?php
include('../../ajaxconfig.php');

$response = array();
$current_page = $_POST['current_page'];

if($current_page != ''){
    $qry = $pdo->query("SELECT sub_menu FROM sub_menu_list where link LIKE '%$current_page%' LIMIT 1");
    if ($qry->rowCount() > 0) {
        $row = $qry->fetch();
        $response['sub_menu'] = $row['sub_menu'];
    }
}

$pdo = null; //close connection

echo json_encode($response);
