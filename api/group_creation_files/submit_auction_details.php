<?php
require '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id'];
$groupId = $_POST['group_id'];
$grpDate = $_POST['grp_date'];

$auctionDetails = $_POST['auction_details'];

$result = 0;

try {
    if ($groupId != '') {
        // First delete existing records for this group
        $deleteQry = $pdo->query("DELETE FROM auction_details WHERE group_id='$groupId'");

        // Insert new records
        foreach ($auctionDetails as $detail) {
            $auctionMonth = $detail['auction_month'];
            $monthName = $detail['month_name'];
            $lowValue = $detail['low_value'];
            $highValue = $detail['high_value'];

            // Format the date as "User-entered date-Month-Year"
            $formattedDate = $grpDate . '-' . $monthName;
            $formatDate = new DateTime($formattedDate);
            $formattedDate = $formatDate->format('Y-m-d');

            $insertQry = $pdo->query("INSERT INTO auction_details (group_id, date, auction_month, low_value, high_value, status, insert_login_id, created_on) VALUES ('$groupId','$formattedDate', '$auctionMonth', '$lowValue','$highValue', 1, '$user_id', NOW())");
 
        }

        $result = 1; // Success
    }
} catch (Exception $e) {
    error_log('Error in auction detail submission: ' . $e->getMessage());
}

echo json_encode($result);
?>


