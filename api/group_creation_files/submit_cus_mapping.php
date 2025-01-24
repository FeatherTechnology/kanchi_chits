<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];
$total_members = intval($_POST['total_members']);
$group_id = $pdo->quote($_POST['group_id']);
$map_id = $pdo->quote($_POST['map_id']);
$cus_name = isset($_POST['cus_name']) ? $_POST['cus_name'] : [];
$chit_value = intval($_POST['chit_value']);
$joining_month = intval($_POST['joining_month']);
$share_value = isset($_POST['share_value']) ? $_POST['share_value'] : [];
$share_percent = isset($_POST['share_percent']) ? $_POST['share_percent'] : [];

$response = ['result' => 2]; // Default to failure

// Check the current count of customer mappings for the group
$stmt = $pdo->query("SELECT COUNT(*) FROM group_cus_mapping WHERE grp_creation_id = $group_id");
$current_count = $stmt->fetchColumn();

// Add the new group to the mapping if the customer mapping limit is not exceeded
if ($current_count < $total_members) {
    // Insert into group_cus_mapping only if it doesn't exist
    $checkQuery = "SELECT map_id FROM group_cus_mapping WHERE map_id = $map_id AND grp_creation_id = $group_id";
    $existingMapping = $pdo->query($checkQuery)->fetchColumn(); // Fetch the map_id if exists

    if (!$existingMapping) {
        $insertQuery = "INSERT INTO group_cus_mapping (map_id, grp_creation_id, joining_month, insert_login_id, created_on) 
                        VALUES ($map_id, $group_id, $joining_month, $user_id, NOW())";
        $pdo->query($insertQuery);
        $cus_mapping_id = $pdo->lastInsertId(); // Get the last inserted ID from group_cus_mapping
    } else {
        // If the mapping already exists, use the existing cus_mapping_id
        $cus_mapping_id = $pdo->query("SELECT id FROM group_cus_mapping WHERE map_id = $map_id AND grp_creation_id = $group_id")->fetchColumn();
    }

    // Insert into group_share table for each customer
    for ($i = 0; $i < count($cus_name); $i++) {
        $cus_id = intval($cus_name[$i]);
        $share_value_item = floatval($share_value[$i]);
        $share_percent_item = floatval($share_percent[$i]);

        if ($cus_mapping_id) {
            // Insert into group_share table
            $insertShareQuery = "INSERT INTO group_share (cus_mapping_id, cus_id, grp_creation_id, share_value, share_percent, created_on, insert_login_id) 
                                 VALUES ($cus_mapping_id, $cus_id, $group_id, $share_value_item, $share_percent_item, NOW(), $user_id)";
            $pdo->query($insertShareQuery);

            $response['result'] = 1; // Success
        } else {
            $response = ['result' => 3, 'message' => 'Customer Mapping Limit is Exceeded'];
            break; // Exit loop if insertion fails
        }
    }

    // Check if the customer count now equals the total members and update status in group_creation
    $stmt = $pdo->query("SELECT COUNT(*) FROM group_cus_mapping WHERE grp_creation_id = $group_id");
    $current_count = $stmt->fetchColumn();

    if ($current_count == $total_members) {
        // Update the status in the group_creation table
        $update_stmt = $pdo->query("UPDATE group_creation SET status = '2' WHERE grp_id = $group_id");
        if ($update_stmt) {
            $response['result'] = 1; // Success
        } else {
            $response['result'] = 2; // Failure
        }
    } else {
        $response['result'] = 1; // Success, but not yet full
    }

    echo json_encode($response);
} else {
    // Customer mapping limit exceeded
    $response = ['result' => 3, 'message' => 'Customer Mapping Limit is Exceeded'];
    echo json_encode($response);
}
?>
