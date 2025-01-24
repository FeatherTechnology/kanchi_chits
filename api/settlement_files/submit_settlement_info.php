<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$auction_id = $_POST['auction_id'];
$group_id = $_POST['group_id'];
$cus_id = $_POST['cus_id'];
$settle_date = $_POST['settle_date'];
$settle_amount = $_POST['settle_amount'];
$set_amount = $_POST['set_amount'];
$settle_balance = $_POST['settle_balance'];
$payment_type = $_POST['payment_type'];
$settle_type = $_POST['settle_type'];
$bank_name = $_POST['bank_name'];
$settle_cash = $_POST['settle_cash'];
$cheque_no = $_POST['cheque_no'];
$cheque_val = $_POST['cheque_val'];
$cheque_remark = $_POST['cheque_remark'];
$transaction_id = $_POST['transaction_id'];
$transaction_val = $_POST['transaction_val'];
$transaction_remark = $_POST['transaction_remark'];
$balance_amount = !empty($_POST['balance_amount']) ? $_POST['balance_amount'] : 0;
$gua_name = $_POST['gua_name'];
$gua_relationship = $_POST['gua_relationship'];

// Handle file upload
if (!empty($_FILES['den_upload']['name'])) {
    $path = "../../uploads/denomination_upload/";
    $picture = $_FILES['den_upload']['name'];
    $pic_temp = $_FILES['den_upload']['tmp_name'];
    $fileExtension = pathinfo($picture, PATHINFO_EXTENSION);
    $picture = uniqid() . '.' . $fileExtension;
    while (file_exists($path . $picture)) {
        $picture = uniqid() . '.' . $fileExtension;
    }
    move_uploaded_file($pic_temp, $path . $picture);
} else {
    $picture = isset($_POST['den_upload_edit']) ? $_POST['den_upload_edit'] : '';
}

// Format the date
$date = DateTime::createFromFormat('d-m-Y', $settle_date); 
$settle_date_formatted = $date->format('Y-m-d');

// Fetch customer name from `customer_creation`
$qry1 = $pdo->query("SELECT id as cus_name FROM customer_creation WHERE cus_id = '$cus_id'");
$cus_name = $qry1->fetchColumn();

// Fetch customer mapping ID from `auction_details`
$qry3 = $pdo->query("SELECT cus_name as cus_mapping_id FROM auction_details WHERE id = '$auction_id'");
$cus_mapping_id = $qry3->fetchColumn();

// Insert into `settlement_info`
$qry = $pdo->query("INSERT INTO settlement_info (
    auction_id, settle_date, group_id, cus_name, settle_amount, settle_balance, payment_type, settle_type, bank_id, settle_cash, cheque_no, cheque_val, cheque_remark, transaction_id, transaction_val, transaction_remark, balance_amount, guarantor_name, guarantor_relationship, den_upload, insert_login_id, created_on
) VALUES (
    '$auction_id', '$settle_date_formatted', '$group_id', '$cus_name', '$settle_amount', '$settle_balance', '$payment_type', '$settle_type', '$bank_name', '$settle_cash', '$cheque_no', '$cheque_val', '$cheque_remark', '$transaction_id', '$transaction_val', '$transaction_remark', '$balance_amount', '$gua_name', '$gua_relationship', '$picture', '$user_id', NOW()
)");

if ($payment_type == "1") {
    // Check if balance_amount is zero
    if ($balance_amount == 0) {
        // Update auction_details status and group_share settle_status
        $qry2 = $pdo->query("UPDATE `group_share` 
            SET `settle_status` = 'Yes' 
            WHERE `grp_creation_id` = '$group_id' 
            AND `cus_id` = '$cus_name' 
            AND `cus_mapping_id` = '$cus_mapping_id'
            AND `settle_status` IS NULL 
            LIMIT 1");

        if ($qry && $qry2) {
            $result = 1;
        } else {
            $result = 0;
        }
    } else {
        $result = $qry ? 1 : 0;
    }
} else if ($payment_type == "2") {
    // Update group_share settle_status
    $qry2 = $pdo->query("UPDATE `group_share` 
        SET `settle_status` = 'Yes' 
        WHERE `grp_creation_id` = '$group_id' 
        AND `cus_id` = '$cus_name' 
        AND `cus_mapping_id` = '$cus_mapping_id'
        AND `settle_status` IS NULL 
        LIMIT 1");

    $result = ($qry && $qry2) ? 1 : 0;
} else {
    $result = 0;
}

// Check and update auction_details based on total settlement
$qry4 = $pdo->query("SELECT COALESCE(SUM(settle_cash) + SUM(cheque_val) + SUM(transaction_val), 0) as total_amount 
    FROM settlement_info 
    WHERE auction_id = '$auction_id'");

$total_amount = $qry4->fetchColumn();
if ($set_amount == $total_amount) {
    $update_query = $pdo->query("UPDATE auction_details 
        SET status = '3', update_login_id = '$user_id', updated_on = NOW() 
        WHERE id = '$auction_id'");
}

// Return result
echo json_encode($result);

// Close the connection
$pdo = null;
?>
