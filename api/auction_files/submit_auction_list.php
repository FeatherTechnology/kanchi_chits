<?php
require '../../ajaxconfig.php';
@session_start();

$cus_name = $_POST['cus_name'];
$group_id = $_POST['group_id'];
$date = $_POST['date'];
$user_id = $_SESSION['user_id'];
$response = ['status' => 'error', 'message' => ''];

try {
    // Fetch the auction_id from auction_details
    $date = date('Y-m-d', strtotime($date));
    $sql = "SELECT id FROM auction_details WHERE group_id = :group_id AND date = :date";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':group_id' => $group_id,
        ':date' => $date
    ]);

    $auction = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($auction) {
        $auction_id = $auction['id'];

        // Prepare the SQL statement to insert into auction_modal
        $sql = "INSERT INTO auction_modal (auction_id, group_id, date, cus_name, inserted_login_id,created_on) VALUES (:auction_id, :group_id, :date, :cus_name, :insert_login_id,now())";
        $stmt = $pdo->prepare($sql);
        
        // Execute the SQL statement
        $stmt->execute([
            ':auction_id' => $auction_id,
            ':group_id' => $group_id,
            ':date' => $date,
            ':cus_name' => $cus_name,
            ':insert_login_id' => $user_id // Adjusted parameter name
        ]);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            $response['status'] = 'success';
            $response['message'] = 'Customer mapping inserted successfully.';
        } else {
            $response['message'] = 'Failed to insert customer mapping.';
        }
    } else {
        $response['message'] = 'No matching auction found.';
    }
} catch (PDOException $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
$pdo = null; // Close Connection
?>
