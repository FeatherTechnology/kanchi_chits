<?php
require "../../ajaxconfig.php";

// Get the auction_id and cus_id from POST data
$auction_id = isset($_POST['auction_id']) ? $_POST['auction_id'] : null;
$cus_id = isset($_POST['cus_id']) ? $_POST['cus_id'] : null;

if ($auction_id !== null) {
    // Construct the SQL query directly with the variables
    $qry = "SELECT 
                si.id, 
                si.den_upload 
            FROM 
                settlement_info si
            LEFT JOIN auction_details ad ON si.auction_id = ad.id
            LEFT JOIN customer_creation cc ON si.cus_name = cc.id
            WHERE 
                 si.auction_id = '$auction_id' AND cc.cus_id = '$cus_id' AND si.settle_type=1 ";

    // Execute the query
    $result = $pdo->query($qry);

    // Check if any rows are returned
    if ($result->rowCount() > 0) {
        $uploads = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // Generate the upload link for each den_upload
            $uploads[] = "<a href='uploads/denomination_upload/{$row['den_upload']}' target='_blank' style='color: blue;'>{$row['den_upload']}</a>";
        }

        // Join the links with commas and send back the response
        echo implode(", ", $uploads);
    } else {
        echo 'No uploads found.';
    }
} else {
    echo 'Invalid auction ID.';
}

$pdo = null;
?>

