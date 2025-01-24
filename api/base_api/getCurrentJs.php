<?php
session_start();
$user_id = $_SESSION["user_id"];


include('../../ajaxconfig.php');

$current_page = $_POST['current_page'];

$response = '';

if ($current_page != '') {
    $qry = $pdo->prepare("SELECT sl.id, sl.sub_menu, sl.link FROM sub_menu_list sl INNER JOIN users u ON FIND_IN_SET(sl.id, u.screens) WHERE sl.link = :current_page AND u.id = :user_id");
    $qry->execute(array(':current_page' => $current_page, ':user_id' => $user_id));
    $count = $qry->fetchColumn();

    if ($count > 0) {
        $response = 'js/' .$current_page . '.js';
    }
}

echo $response;
