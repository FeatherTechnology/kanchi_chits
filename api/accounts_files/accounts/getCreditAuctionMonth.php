<?php
require "../../../ajaxconfig.php";

// Fetch the group ID, category type, and group member ID from POST data
$group_id = $_POST['group_id'] ?? '';
$category_type = $_POST['category_type'] ?? '';
$group_mem_id = $_POST['group_mem_id'] ?? '';

$result = [];

if ($group_id && $category_type && $group_mem_id) {
    // Corrected SQL query to properly use the category_type variable
    $qry = $pdo->query("SELECT max(auction_month) as auction_month FROM other_transaction 
                        WHERE group_mem = '$group_mem_id' AND group_id = '$group_id' 
                        AND type != '$category_type'");
    
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
