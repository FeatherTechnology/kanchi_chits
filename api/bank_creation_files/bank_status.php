<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];

// Fetch current status
$qry = $pdo->prepare("SELECT status FROM bank_creation WHERE id = ?");
$qry->execute([$id]);
$row = $qry->fetch(PDO::FETCH_ASSOC);
$current_status = $row['status'];

// Toggle status
$new_status = $current_status == '1' ? '0' : '1';

// Update status
$update_qry = $pdo->prepare("UPDATE bank_creation SET status = ? WHERE id = ?");
$success = $update_qry->execute([$new_status, $id]);

echo json_encode(['success' => $success, 'new_status' => $new_status]);
