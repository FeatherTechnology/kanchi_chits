<?php
require '../../ajaxconfig.php';
@session_start();

$response = array();
$user_id = $_SESSION['user_id'];

// Retrieve data from request
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is valid
if (isset($data['data']) && is_array($data['data'])) {
    $tableData = $data['data'];

    foreach ($tableData as $entry) {
        // Format date
        $formattedDate = date('Y-m-d', strtotime($entry['date']));

        // Use cus_id as cus_name
        $cusName = $entry['cus_id']; // Store cus_id in cus_name

        // Insert auction details into auction_modal
        $insertQuery = "INSERT INTO auction_modal (auction_id, group_id, date, cus_name, value, inserted_login_id, created_on) 
                        VALUES ('" . strip_tags($entry['id']) . "', '" . strip_tags($entry['group_id']) . "', '$formattedDate', 
                        '" . strip_tags($cusName) . "', '" . strip_tags($entry['value']) . "', '$user_id', NOW())";

        if (!$pdo->query($insertQuery)) {
            echo json_encode(['success' => false, 'message' => 'Failed to insert data into auction_modal.']);
            exit;
        }
    }

    // Find the highest value (treating company like any other customer)
    $group_id = $tableData[0]['group_id']; // Assuming all entries have the same group_id
    $date = $formattedDate; // Assuming all entries have the same date

    // Query to find the maximum value, without special priority for 'Company' (cus_name = -1)
    $maxQuery = "
        SELECT cus_name, value 
        FROM auction_modal 
        WHERE group_id = '$group_id' AND date = '$date' 
        ORDER BY value DESC 
        LIMIT 1";

    $maxResult = $pdo->query($maxQuery)->fetch(PDO::FETCH_ASSOC);

    if ($maxResult) {
        $max_value = $maxResult['value'];
        $cus_name = $maxResult['cus_name'];

        // Set status uniformly for all customers (including company)
        $status = ($cus_name == -1) ? 3 : 2;

        // Update the auction_details table
        $updateDetailsQuery = "UPDATE auction_details SET auction_value = '$max_value', cus_name = '$cus_name', 
                               status = '$status', update_login_id = '$user_id', updated_on = NOW() 
                               WHERE group_id = '$group_id' AND date = '$date'";
        if (!$pdo->query($updateDetailsQuery)) {
            echo json_encode(['success' => false, 'message' => 'Failed to update auction_details table.']);
            exit;
        }

        // Update the group_creation table
        $updateGroupQuery = "UPDATE group_creation SET status = 3, update_login_id = '$user_id', updated_on = NOW() 
                             WHERE grp_id = '$group_id'";
        if (!$pdo->query($updateGroupQuery)) {
            echo json_encode(['success' => false, 'message' => 'Failed to update group_creation table.']);
            exit;
        }

        // Update the group_share table
        $updateCusMappingQuery = "UPDATE group_share SET coll_status = 'Payable' 
                                  WHERE grp_creation_id = '$group_id'";
        if (!$pdo->query($updateCusMappingQuery)) {
            echo json_encode(['success' => false, 'message' => 'Failed to update group_cus_mapping_table.']);
            exit;
        }

        // Fetch date and end_month from group_creation table
        $groupCreationQuery = "SELECT date, end_month FROM group_creation WHERE grp_id = '$group_id'";
        $groupCreationResult = $pdo->query($groupCreationQuery)->fetch(PDO::FETCH_ASSOC);

        if ($groupCreationResult) {
            $endMonth = $groupCreationResult['end_month']; // Format: 'yyyy-mm'
            $currentMonthYear = date('Y-m'); // Current month in 'yyyy-mm' format

            // If the end month matches the current month, update status to 4
            if ($endMonth == $currentMonthYear) {
                $updateStatusQuery = "UPDATE group_creation SET status = 4, update_login_id = '$user_id', updated_on = NOW() 
                                      WHERE grp_id = '$group_id'";
                if (!$pdo->query($updateStatusQuery)) {
                    echo json_encode(['success' => false, 'message' => 'Failed to update group_creation status to 4.']);
                    exit;
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No group creation details found.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No auction details found.']);
        exit;
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
}
