<?php
require '../../ajaxconfig.php';

$group_id = $_POST['group_id'];
$auction_month = $_POST['auction_month'];

// Fetch auction details from auction_details table
$auction_qry = "SELECT * FROM auction_details WHERE group_id = '$group_id' AND auction_month = '$auction_month'";
$auction_result = $pdo->query($auction_qry);
$auction_detail = $auction_result->fetch(PDO::FETCH_ASSOC); // Use fetch with PDO::FETCH_ASSOC to get associative array

// Get current date and time
$current_date = date('Y-m-d');
$current_hour = date('h'); // 12-hour format
$current_minute = date('i');
$current_ampm = date('A'); // AM/PM

// Fetch group details from group_creation table to check time
$group_qry = "SELECT gc.hours, gc.minutes, gc.ampm, gc.grp_name, gc.chit_value,bc.branch_name FROM group_creation gc JOIN branch_creation bc ON gc.branch = bc.id WHERE grp_id = '$group_id'";
$group_result = $pdo->query($group_qry);
$group_time = $group_result->fetch(PDO::FETCH_ASSOC); // Use fetch with PDO::FETCH_ASSOC to get associative array

// Validate if auction date and time are valid
$is_valid = false;
if ($auction_detail && $group_time) {
    $auction_date = $auction_detail['date']; // Auction date from database
    $group_hour = $group_time['hours']; // Auction start hour
    $group_minute = str_pad($group_time['minutes'], 2, '0', STR_PAD_LEFT); // Ensure minute is two digits
    $group_ampm = $group_time['ampm']; // Auction start AM/PM

    $formatted_group_time = "$group_hour:$group_minute $group_ampm";
   
    // Check if the auction date is today or in the future
    if ($auction_date < $current_date) {
        $is_valid = true; // Future auction date, so it's valid
    } else if ($auction_date == $current_date) {
        // Get current time as a timestamp
        $current_time = strtotime("$current_hour:$current_minute $current_ampm");
        // Get auction time as a timestamp
        $auction_time = strtotime($formatted_group_time);
        if ($current_time >= $auction_time) {
            $is_valid = true;
        }
    }
}

// Send auction details even if is_valid is false
$response = [
    'is_valid' => $is_valid,
    'auction_detail' => $auction_detail ? array_merge($auction_detail, $group_time) : null // Merge date and time details
];
// Ensure group name is included in the response
if ($group_time) {
    $response['auction_detail']['group_name'] = $group_time['grp_name']; // Add group name to auction detail
    $response['auction_detail']['chit_value'] = $group_time['chit_value']; // Add chit value to auction detail
    $response['auction_detail']['branch_name'] = $group_time['branch_name'];
    $response['auction_detail']['auction_time'] = "{$group_time['hours']}:{$group_time['minutes']} {$group_time['ampm']}"; // Format auction time as 7:00 PM
}

echo json_encode($response);
