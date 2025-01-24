<?php
require "../../../ajaxconfig.php";

// Fetch the group ID, category type, and group member ID from POST data
$group_id = $_POST['group_id'] ?? '';
$auction_month = $_POST['auction_month'] ?? '';

$result = [];

if ($group_id && $auction_month) {
    // Corrected SQL query to properly use the category_type variable
    $qry = $pdo->query("SELECT high_value FROM auction_details 
                        WHERE group_id = '$group_id' 
                        AND auction_month = '$auction_month' AND status IN (2,3)");
    
    if ($qry->rowCount() > 0) {
        $result = $qry->fetchAll(PDO::FETCH_ASSOC);  // Fetch the data if found
    } else {
        $result['error'] = "No transactions found.";  // Return error if no data
    }
} else {
    $result['error'] = "Invalid input data.";  // Handle missing POST data
}

// Return the result as JSON
echo json_encode($result);
?>
