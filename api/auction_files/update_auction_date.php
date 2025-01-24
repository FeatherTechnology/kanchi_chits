<?php
require '../../ajaxconfig.php';

$response = ['status' => 'error', 'message' => 'Failed to update the date'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_id = $_POST['group_id'];
    $new_date = $_POST['new_date'];
    $auction_month = $_POST['auction_month'];
    
    if (isset($group_id) && isset($new_date) && isset($auction_month)) {
        try {
            // Extract year and month from the new date
            // $year = date('Y', strtotime($new_date));
            // $month = date('m', strtotime($new_date));
            $new_date = date('Y-m-d', strtotime($new_date));
            
            $query = "UPDATE auction_details SET date = ? WHERE group_id = ? AND auction_month = ?";
            $statement = $pdo->prepare($query);
            $updated = $statement->execute([$new_date, $group_id, $auction_month]);
          
        } catch (PDOException $e) {
            $response['message'] = 'Database error: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'Invalid input';
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>

