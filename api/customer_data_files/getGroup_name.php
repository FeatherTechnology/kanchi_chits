<?php
require '../../ajaxconfig.php';

$response = array();

if (isset($_POST['id'])) {
    $guarantor_id = $_POST['id'];

    $stmt = $pdo->prepare("SELECT grp_id FROM group_creation WHERE grp_name = ?");
    $stmt->execute([$guarantor_id]);

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $response['grp_id'] = $row['grp_id'];
    } else {
        $response['grp_id'] = ''; // No relationship found
    }
} else {
    $response['grp_id'] = ''; // No guarantor ID provided
}

$pdo = null; // Close the connection

echo json_encode($response);
?>
