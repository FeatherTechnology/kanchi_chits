<?php
require "../../../ajaxconfig.php";
date_default_timezone_set('Asia/Kolkata'); // Set timezone

$today = date('Y-m-d'); // Get today's date
$query = "SELECT amount, quantity, (amount * quantity) AS total_value FROM denom_refer_table WHERE DATE(created_on) = '$today'";
$result = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
?>
