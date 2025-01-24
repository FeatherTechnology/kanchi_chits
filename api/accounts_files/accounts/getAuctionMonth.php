<?php
require "../../../ajaxconfig.php";

// Fetch the group ID from POST data
$group_id = $_POST['group_id'] ?? '';

if (!empty($group_id)) {
    // First query to fetch auction month for the current month
    $taken_auction_qry = "
        SELECT
            ad.auction_month
        FROM
            auction_details ad
        WHERE
            ad.group_id = '$group_id'
            AND ad.auction_month = (
                SELECT
                    MAX(ad2.auction_month)
                FROM
                    auction_details ad2
                WHERE
                    ad2.group_id = '$group_id'
                    AND ad2.status IN (2, 3)
            )
    ";

    // Execute the query directly
    $stmt = $pdo->query($taken_auction_qry);
    $taken_customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode($taken_customers);
} 

// Close the PDO connection
$pdo = null;
?>
