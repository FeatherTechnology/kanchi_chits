<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$id = $_POST['groupid'];
$group_id = $_POST['group_id'];
$group_name = $_POST['group_name'];
$chit_value = $_POST['chit_value'];
$grp_date = $_POST['grp_date'];
$commission = $_POST['commission'];
$hours = $_POST['hours'];
$minutes = $_POST['minutes'];
$ampm = $_POST['ampm'];
$total_members = $_POST['total_members'];
$total_month = $_POST['total_month'];
$start_month = $_POST['start_month'];
$end_month = $_POST['end_month'];
$branch = $_POST['branch'];
$grace_period = $_POST['grace_period'];

// Format date for comparison
$formatted_date = $start_month . '-' . str_pad($grp_date, 2, '0', STR_PAD_LEFT);
try {
    $format_date = new DateTime($formatted_date);
    $formatted_date = $format_date->format('Y-m-d');
} catch (Exception $e) {
    $formatted_date = null; // Invalid date
}

// Check Auction Details
$subQuery = "SELECT COUNT(*) AS total FROM auction_details WHERE group_id = '$group_id'";
$stmt = $pdo->query("SELECT 
    (SELECT date FROM auction_details WHERE group_id = '$group_id' ORDER BY date ASC LIMIT 1) AS first_date,
    ($subQuery) AS total,
    (SELECT COUNT(*) FROM auction_details WHERE group_id = '$group_id') AS auction_count
FROM auction_details
WHERE group_id = '$group_id'
LIMIT 1");

$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if data was fetched successfully
if (!$data) {
    echo json_encode(['result' => 4, 'message' => 'Auction details not found']);
    exit;
}

$first_date = $data['first_date'];
$total = $data['total'];
$auction_count = $data['auction_count'];

if ($formatted_date !== $first_date || $total != $total_month) {
    echo json_encode(['result' => 4, 'message' => 'Fill the Auction Details']);
    exit;
}

// Check Customer Mapping
$mappingCountStmt = $pdo->query("SELECT COUNT(*) FROM group_cus_mapping WHERE grp_creation_id = '$group_id'");
$current_mapping_count = $mappingCountStmt->fetchColumn();

if ($current_mapping_count > $total_members) {
    echo json_encode(['result' => 5, 'message' => 'Remove The Customer Mapping Details']);
    exit;
}

// Insert or Update Group Creation
if ($id == '') {
    // Insert
    $qry = $pdo->query("INSERT INTO `group_creation`(
        `grp_id`, `grp_name`, `chit_value`, `date`, `commission`, `hours`, `minutes`, `ampm`, `total_members`, `total_months`, `start_month`, `end_month`, `branch`, `grace_period`, `insert_login_id`, `created_on`, `status`
    ) VALUES (
        '$group_id', '$group_name', '$chit_value', '$grp_date', '$commission', '$hours', '$minutes', '$ampm', '$total_members', '$total_month', '$start_month', '$end_month', '$branch', '$grace_period', '$user_id', NOW(), '1'
    )");

    if ($qry) {
        $result = 1; // Success
        $last_id = $pdo->lastInsertId();
    } else {
        $result = 0; // Failure
        $last_id = '';
    }
} else {
    // Update
    $qry = $pdo->query("UPDATE `group_creation` SET
        `grp_id` = '$group_id',
        `grp_name` = '$group_name',
        `chit_value` = '$chit_value',
        `date` = '$grp_date',
        `commission` = '$commission',
        `hours` = '$hours',
        `minutes` = '$minutes',
        `ampm` = '$ampm',
        `total_members` = '$total_members',
        `total_months` = '$total_month',
        `start_month` = '$start_month',
        `end_month` = '$end_month',
        `branch` = '$branch',
        `grace_period` = '$grace_period',
        `update_login_id` = '$user_id',
        `updated_on` = NOW()
    WHERE `id` = '$id'");

    if ($qry) {
        $result = 1; // Success
        $last_id = $id;
    } else {
        $result = 0; // Failure
        $last_id = '';
    }
}

// Check if the query was successful
if ($result == 1) {
    // Check customer mapping and update status
    $mappingCountStmt = $pdo->query("SELECT COUNT(*) FROM group_cus_mapping WHERE grp_creation_id = '$group_id'");
    $current_mapping_count = $mappingCountStmt->fetchColumn();

    // Check if the total_month matches auction_count
    if ($current_mapping_count == $total_members && $total_month == $auction_count) {
        // Update status in group_creation table
        $statusUpdateStmt = $pdo->query("UPDATE group_creation SET status = '2' WHERE grp_id = '$group_id'");
        if ($statusUpdateStmt) {
            $result = 1;
        } else {
            $result = 0;
        }
    } else {
        $result = 1; // Success, but mapping count or auction count not yet full
    }
} else {
    $result = 0; // Failure
    $last_id = '';
}

echo json_encode(['result' => $result, 'last_id' => $last_id]);
?>
