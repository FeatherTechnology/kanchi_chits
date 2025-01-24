<?php
require '../../ajaxconfig.php';

$response = array();

if (isset($_POST['cus_id'])) {
    $property_holder_id = $_POST['cus_id'];

    $stmt = $pdo->prepare("SELECT cus_id FROM customer_creation WHERE id = ?");
    $stmt->execute([$property_holder_id]);

    // Fetch the result
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $response['ref_cus_id'] = $row['cus_id'];
    } else {
        $response['ref_cus_id'] = ''; // No relationship found
    }
} else {
    $response['ref_cus_id'] = ''; // No property holder ID provided
}

$pdo = null; // Close the connection

echo json_encode($response);
?>
