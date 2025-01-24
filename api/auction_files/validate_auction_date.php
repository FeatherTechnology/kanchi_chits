<?php
require '../../ajaxconfig.php';

$group_id = $_POST['group_id'];
$auction_month = $_POST['auction_month'];

// Fetch auction details from auction_details table
$auction_qry = "SELECT * FROM auction_details WHERE group_id = '$group_id' AND auction_month = '$auction_month'";
$auction_result = $pdo->query($auction_qry);
$auction_detail = $auction_result->fetch(PDO::FETCH_ASSOC); // Fetch the auction details

// Fetch group details from group_creation table
$group_qry = "SELECT gc.hours, gc.minutes, gc.ampm, gc.grp_name, gc.chit_value, bc.branch_name 
              FROM group_creation gc 
              JOIN branch_creation bc ON gc.branch = bc.id 
              WHERE grp_id = '$group_id'";
$group_result = $pdo->query($group_qry);
$group_time = $group_result->fetch(PDO::FETCH_ASSOC); // Fetch group creation details

// Combine auction and group details into one array
$response = [
    'is_valid' => true, // Always return true for validity, as per your requirement
    'auction_detail' => $auction_detail ? array_merge($auction_detail, $group_time) : null // Merge auction and group details
];

// Ensure group name, chit value, and branch name are included in the response
if ($group_time) {
    $response['auction_detail']['group_name'] = $group_time['grp_name']; // Group name
    $response['auction_detail']['chit_value'] = $group_time['chit_value']; // Chit value
    $response['auction_detail']['branch_name'] = $group_time['branch_name']; // Branch name
    $response['auction_detail']['auction_time'] = "{$group_time['hours']}:{$group_time['minutes']} {$group_time['ampm']}"; // Auction time formatted
}

// Return the JSON response
echo json_encode($response);
?>
