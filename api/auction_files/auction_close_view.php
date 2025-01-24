<?php
require '../../ajaxconfig.php';
@session_start();

$group_id = $_POST['group_id']; // Get group_id from POST request
$date = $_POST['date']; // Get date from POST request

$response = array();

try {
    // Format the date into 'Y-m-d'
    $date = date('Y-m-d', strtotime($date));

    // Create the SQL query with FIND_IN_SET and handling cus_name = -1 (Company)
    $qry = "
          SELECT 
    al.value,
    gs.cus_mapping_id,
    GROUP_CONCAT(
        CASE 
            WHEN al.cus_name = '-1' THEN 'Company' 
            ELSE cc.first_name
        END 
        ORDER BY cc.first_name ASC SEPARATOR ' - '
    ) AS customer_name
FROM 
    auction_modal al
LEFT JOIN 
    group_share gs ON al.cus_name = gs.cus_mapping_id
LEFT JOIN 
    customer_creation cc ON FIND_IN_SET(cc.id, gs.cus_id) > 0
WHERE 
    al.group_id = '$group_id'
    AND al.date = '$date'
GROUP BY 
    al.value, gs.cus_mapping_id
ORDER BY 
    al.id, al.value ASC;
    ";
    
    // Execute the SQL query
    $result = $pdo->query($qry);

    // Fetch all results
    $data = $result->fetchAll(PDO::FETCH_ASSOC);

    // Check if results are found
    if ($data) {
        $response['status'] = 'success';
        $response['data'] = $data;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No data found for the specified group ID and date.';
    }
} catch (PDOException $e) {
    $response['status'] = 'error';
    $response['message'] = 'Database error: ' . $e->getMessage();
}

$pdo = null; // Close the database connection
echo json_encode($response);
?>
