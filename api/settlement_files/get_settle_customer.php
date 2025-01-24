<?php
require '../../ajaxconfig.php';

$id = $_POST['id']; // Ensure proper sanitization
$result = [];

// Check if `$id` is safe to use directly (basic validation).
if (is_numeric($id)) {
    $qry = $pdo->query("
        SELECT 
            cc.id,
            CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name,
            gs.share_percent
        FROM 
            auction_details ad
        LEFT JOIN group_share gs ON ad.cus_name = gs.cus_mapping_id 
        LEFT JOIN customer_creation cc ON gs.cus_id = cc.id 
        WHERE 
            ad.id = '$id'
        GROUP BY 
            cc.id
    ");

    if ($qry->rowCount() > 0) {
        $result = $qry->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Close the database connection.
$pdo = null;

// Output the result as JSON.
echo json_encode($result);
