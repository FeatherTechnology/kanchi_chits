<?php
require '../../ajaxconfig.php';

$groupId = $_POST['group_id'];
$status = 0;

if ($groupId != '') {
    $stmt = $pdo->prepare("SELECT status FROM group_creation WHERE grp_id = ?");
    $stmt->execute([$groupId]);
    $status = $stmt->fetchColumn();
}

echo json_encode($status);
?>
