<?php
require '../../ajaxconfig.php';
@session_start();

$group_id = $_POST['group_id']; // Get group_id from POST request
$date = $_POST['date']; // Get date from POST request

$response = array();

try {
    // Prepare the SQL statement
    $date = date('Y-m-d', strtotime($date));
    $qry = "SELECT
                al.id,
                cc.first_name,
                cc.last_name,
                al.value
            FROM 
                auction_modal al 
            JOIN 
                customer_creation cc 
            ON 
                cc.id = al.cus_name
            WHERE 
                al.group_id = :group_id
                AND al.date = :date";
    
    $stmt = $pdo->prepare($qry);
    
    // Execute the SQL statement
    $stmt->execute([
        ':group_id' => $group_id,
        ':date' => $date
    ]);
    
    // Fetch all results
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        $response['status'] = 'success';
        $response['data'] = $result;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No data found for the specified group ID and date.';
    }
} catch (PDOException $e) {
    $response['status'] = 'error';
    $response['message'] = 'Database error: ' . $e->getMessage();
}

$pdo = null; // Close Connection
echo json_encode($response);
?>
