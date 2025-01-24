<?php
session_start();
$user_id = $_SESSION["user_id"];


include('../../ajaxconfig.php');

$current_page = $_POST['current_page'];

$response = '';
ob_start(); // Start output buffering
if ($current_page != '') {
    $qry = $pdo->prepare("SELECT sl.id, sl.sub_menu, sl.link FROM sub_menu_list sl INNER JOIN users u ON FIND_IN_SET(sl.id, u.screens) WHERE sl.link = :current_page AND u.id = :user_id");
    $qry->execute(array(':current_page' => $current_page, ':user_id' => $user_id));
    $count = $qry->fetchColumn();

    if ($count > 0) {
        try {
            // Set custom error handler to convert errors to exceptions
            set_error_handler(function ($errno, $errstr, $errfile, $errline) {
                throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
            });

            include '../../include/views/' . $current_page . '.php';

            // Restore the default error handler
            restore_error_handler();
        } catch (Exception $e) {
            include '../../include/views/404.php';
        }
    } else {
        include '../../include/views/404.php';
    }
} elseif ($current_page == '' or $current_page == 'index.php') {
    include '../../index.php';
}
$response = ob_get_clean(); // Get the output buffer content and clear it
echo $response;
