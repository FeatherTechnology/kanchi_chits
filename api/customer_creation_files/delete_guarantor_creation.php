<?php
require '../../ajaxconfig.php';

$id = $_POST['id'] ?? null;
$cus_id = $_POST['cus_id'] ?? null;
$customer_id = $_POST['customer_id'] ?? null;

if (!$id || !$cus_id) {
    echo json_encode('2'); // Missing ID or Customer ID
    exit;
}

try {
    // Check if there's only one guarantor and if deletion is restricted
    $checkQry = $pdo->prepare("SELECT COUNT(*) FROM guarantor_info WHERE cus_id = :cus_id");
    $checkQry->bindParam(':cus_id', $cus_id, PDO::PARAM_INT);
    $checkQry->execute();
    $count = $checkQry->fetchColumn();

    if ($count == 1) {
        echo json_encode('0'); // Restriction: Only one guarantor
        exit;
    }

    // Fetch the file path for the guarantor picture
    $qry = $pdo->prepare("SELECT gu_pic FROM guarantor_info WHERE id = :id");
    $qry->bindParam(':id', $id, PDO::PARAM_INT);
    $qry->execute();
    $row = $qry->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $filePath = "../../uploads/customer_creation/gu_pic/" . $row['gu_pic'];

        // Delete the file if it exists
        if (is_file($filePath)) {
            unlink($filePath);
        }

        // Delete the guarantor info from the database
        $deleteQry = $pdo->prepare("DELETE FROM guarantor_info WHERE id = :id");
        $deleteQry->bindParam(':id', $id, PDO::PARAM_INT);
        if ($deleteQry->execute()) {
            echo json_encode('1'); // Success
        } else {
            echo json_encode('2'); // Failure to delete record
        }
    } else {
        echo json_encode('2'); // Guarantor not found
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode('2'); // Exception error
}
