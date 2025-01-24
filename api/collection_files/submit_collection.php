<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$group_id = $_POST['group_id'];
$cus_id = $_POST['cus_id'];
$auction_id = $_POST['auction_id'];
$cus_mapping_id = $_POST['cus_mapping_id'];
$share_id = $_POST['share_id'];
$auction_month = $_POST['auction_month'];  // Assuming this is provided as a number (e.g., 1 for Aug 2024, 6 for Jan 2025)
$chit_value = $_POST['chit_value'];
$chit_amount = $_POST['chit_amount'];
$pending = $_POST['pending_amt'];
$payable = $_POST['payable_amnt'];
$collection_amount = $_POST['collection_amount'];
$collection_date = date('Y-m-d', strtotime($_POST['collection_date']));
$coll_mode = $_POST['coll_mode'];
$transaction_id = $_POST['transaction_id'];
$bank_name = $_POST['bank_name'];
if ($collection_amount >= $payable) {
    $status = 'Paid';
} else {
    $status = 'Payable';
}
if($chit_amount ==0){
    $auction_id-=1;
    $auction_month -=1;
    }
/// Insert the collection record
$qry = $pdo->query("INSERT INTO collection (cus_mapping_id,share_id,auction_id, group_id, cus_id, auction_month, chit_value, chit_amount, pending, payable, coll_status, collection_date, coll_mode, transaction_id, bank_id, collection_amount, insert_login_id, created_on) 
VALUES ('$cus_mapping_id','$share_id','$auction_id', '$group_id', '$cus_id', '$auction_month', '$chit_value', '$chit_amount', '$pending', '$payable', '$status', '$collection_date " . date(' H:i:s') . "', '$coll_mode', '$transaction_id', '$bank_name', '$collection_amount', '$user_id', CURRENT_TIMESTAMP())");

if ($qry) {
    // Fetch the last inserted collection ID
    $coll_id = $pdo->lastInsertId();
    $update_query = $pdo->query("UPDATE group_share SET 
    coll_status = '$status'
    WHERE id = '$share_id' AND cus_mapping_id = '$cus_mapping_id'");
    // Fetch the group information
    $groupInfo = $pdo->query("SELECT start_month, end_month FROM group_creation WHERE grp_id = '$group_id' AND end_month <= DATE_FORMAT(CURDATE(), '%Y-%m')")->fetch(PDO::FETCH_ASSOC);

    if ($groupInfo) {
        $start_month = $groupInfo['start_month']; 
        $end_month = $groupInfo['end_month'];

        // Calculate the month difference
        $startDate = new DateTime($start_month . '-01');
        $endDate = new DateTime($end_month . '-01');
        $interval = $startDate->diff($endDate);
        $totalMonths = $interval->y * 12 + $interval->m + 1;

        // Count paid customers for all months in the group
        $paidCustomersCount = $pdo->query("SELECT COUNT(share_id) as paid_count 
                                           FROM collection 
                                           WHERE group_id = '$group_id' 
                                           AND coll_status = 'Paid' 
                                           AND auction_month BETWEEN 1 AND $totalMonths")->fetch(PDO::FETCH_ASSOC)['paid_count'];

        // Get the total number of customers in the group
        $totalCustomersCount = $pdo->query("SELECT COUNT(DISTINCT id) as total_count 
                                            FROM group_share 
                                            WHERE grp_creation_id = '$group_id'")->fetch(PDO::FETCH_ASSOC)['total_count'];

        if ($paidCustomersCount == $totalCustomersCount * $totalMonths) {
            // Update group status to 5
            $updateQry = $pdo->query("UPDATE group_creation SET status = 5 WHERE grp_id = '$group_id'");
            $result = $updateQry ? 1 : 0;
        } else {
            $result = 1; // Collection inserted but group status not updated
        }
    } else {
        $result = 1; // Collection inserted but group info not matching
    }

    echo json_encode(['result' => $result, 'coll_id' => $coll_id]); // Return collection ID in response
} else {
    echo json_encode(['result' => 0]);
}

// $qry = $pdo->query("SELECT CONCAT( `first_name`,' ', `last_name`) AS customer_name, `mobile1` FROM `customer_creation` WHERE cus_id = '$cus_id' ");
// $row = $qry->fetch();
// $customer_name = $row['customer_name'];
// $cus_mobile1 = $row['mobile1'];

// $message = "";
// $templateid	= ''; //FROM DLT PORTAL.
// // Account details
// $apiKey = '';
// // Message details
// $sender = '';
// // Prepare data for POST request
// $data = 'access_token='.$apiKey.'&to='.$cus_mobile1.'&message='.$message.'&service=T&sender='.$sender.'&template_id='.$templateid;
// // Send the GET request with cURL
// $url = 'https://sms.messagewall.in/api/v2/sms/send?'.$data; 
// $response = file_get_contents($url);  
// // Process your response here
// return $response; 

?>
