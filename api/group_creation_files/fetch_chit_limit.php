<?php
require "../../ajaxconfig.php";

// Check if cus_name and group_id are passed in the request
if (isset($_POST['cus_name']) && isset($_POST['group_id'])) {
    $cus_name = $_POST['cus_name'];
    $group_id = $_POST['group_id'];

    // Ensure cus_name is a numeric value
    if (is_numeric($cus_name)) {
        $cus_name = (int)$cus_name;  // Casting to integer for safety

        // Fetch chit limit for the customer
        $cusStmt = $pdo->query("SELECT chit_limit FROM customer_creation WHERE id = $cus_name");
        $cusChitLimit = $cusStmt->fetchColumn();
        
        if ($cusChitLimit === false) {
            echo json_encode(['error' => 'Customer not found.']);
            exit;
        }
        
        // Check how many times the customer is already added to the same group
        $existingGroupsStmt = $pdo->query("SELECT COUNT(*) FROM group_share WHERE cus_id = $cus_name AND grp_creation_id = '$group_id'");
        $existingGroupsCount = $existingGroupsStmt->fetchColumn();

        // Calculate the total chit value of all groups the customer is currently in
        $shareValueSum = 0;
        $existingGroupsStmt = $pdo->query("SELECT share_value FROM group_share WHERE cus_id = $cus_name");
        $existingGroups = $existingGroupsStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($existingGroups as $grp) {
            $shareValueSum += $grp['share_value'];
        }

        // Add the chit value of the new group, adjusted if the customer is already in the same group
        $newShareValue = isset($_POST['new_share_value']) ? $_POST['new_share_value'] : 0;
        $shareValueSum += $newShareValue;  // Add new share value for the current group

        // Check if the total share value exceeds chit limit
        if ($shareValueSum > $cusChitLimit) {
            echo json_encode(['chit_limit' => $cusChitLimit, 'share_value_sum' => $shareValueSum, 'warning' => 'Share value exceeds chit limit.']);
        } else {
            echo json_encode(['chit_limit' => $cusChitLimit, 'share_value_sum' => $shareValueSum]);
        }
    } else {
        echo json_encode(['error' => 'Please Choose the Customer Name']);
    }
} else {
    echo json_encode(['error' => 'Customer name or group ID not provided.']);
}
?>
