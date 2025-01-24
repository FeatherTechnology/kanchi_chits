<?php
require '../../ajaxconfig.php';

$response = array();
include 'grp_col_status.php';

$collectionSts = new GroupStsClass($pdo);

// Check if required POST data is set
if (isset($_POST['group_id']) && isset($_POST['auction_month'])) {
    $group_id = $_POST['group_id'];
    $auction_month = $_POST['auction_month'];

    try {
        // Main query to fetch customer details
        $qry = "SELECT 
        cc.id,
            ad.group_id,
            cc.cus_id,
            CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name,
            cc.mobile1,
            pl.place,
            (
                SELECT GROUP_CONCAT(sc.occupation SEPARATOR ', ')
                FROM source sc
                WHERE sc.cus_id = cc.cus_id
            ) AS occupations,
            gcm.id AS cus_mapping_id,
            gs.id AS share_id,
            gs.settle_status,
            ad.auction_month
        FROM
            auction_details ad
      LEFT JOIN group_share gs ON
    ad.group_id = gs.grp_creation_id
    LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
LEFT JOIN customer_creation cc ON
    gs.cus_id = cc.id
        LEFT JOIN place pl ON cc.place = pl.id
        LEFT JOIN group_creation gc ON ad.group_id = gc.grp_id
        JOIN users us ON FIND_IN_SET(gc.branch, us.branch)
        WHERE
            gc.grp_id = :group_id 
            AND ad.auction_month = :auction_month  
        GROUP BY gs.id
        ORDER BY cc.cus_id";

        // Execute the main query
        $stmt = $pdo->prepare($qry);
        $stmt->execute([':group_id' => $group_id, ':auction_month' => $auction_month]);

        // Check if any data is returned
        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Query to fetch settle status
            $settleStatusQuery = "SELECT
                gs.cus_id AS customer_id
            FROM
                auction_details ad
LEFT JOIN group_share gs ON ad.cus_name = gs.cus_mapping_id 
LEFT JOIN customer_creation cc ON gs.cus_id = cc.id 
            WHERE
                ad.auction_month <= :auction_month 
                AND ad.group_id = :group_id AND gs.settle_status ='Yes'";

            // Execute the settleStatusQuery
            $settleStmt = $pdo->prepare($settleStatusQuery);
            $settleStmt->execute([':auction_month' => $auction_month, ':group_id' => $group_id]);
            $settleData = $settleStmt->fetchAll(PDO::FETCH_ASSOC);

            // Count occurrences of cus_name from settle query result
            $cusNameCounts = array_count_values(array_column($settleData, 'customer_id'));

            // Loop through the original $data and update the settle_status based on cus_name counts
            foreach ($data as &$row) {
                // Safely handle undefined array keys with null coalescing operator
                $cus_id = $row['cus_id'] ?? '';
                $cus_mapping_id = $row['cus_mapping_id'] ?? '';

                // Update group status
                $status = $collectionSts->updateGroupStatus($row['share_id'], $cus_mapping_id,$row['group_id'],$cus_id,$row['auction_month']);
                $row['action'] = $status;

                // Handle settle_status based on the count of cus_name occurrences
                $customer_id = $row['id'] ?? ''; // Corrected customer_id reference to cus_name

                if (isset($cusNameCounts[$customer_id]) && $cusNameCounts[$customer_id] == 1) {
                    // If count is 1, set settle_status to 'Yes'
                    if ($cusNameCounts[$customer_id] == 1) {
                        // Only update 'Yes' for the first occurrence of cus_mapping_id for the customer
                        if ($row['settle_status'] !== 'Yes') {
                            $row['settle_status'] = 'Yes';
                        }
                    } else {
                        $row['settle_status'] = ''; // or 'No'
                    }
                    $cusNameCounts[$customer_id]--; // Decrement count
                } elseif (isset($cusNameCounts[$customer_id]) && $cusNameCounts[$customer_id] > 1) {
                    // If there are duplicates, set settle_status to 'Yes' for one of the occurrences
                    if ($cusNameCounts[$customer_id] > 1) {
                        // Only update 'Yes' for the first occurrence of cus_mapping_id for the customer
                        if ($row['settle_status'] !== 'Yes') {
                            $row['settle_status'] = 'Yes';
                        }
                    } else {
                        $row['settle_status'] = ''; // or 'No'
                    }
                    $cusNameCounts[$customer_id]--; // Decrement count
                } else {
                    $row['settle_status'] = ''; // Default to empty if no match
                }

                // Determine the action color based on status
                if ($status === 'Paid') {
                    $row['action'] = '<span style="color: green;"><strong>' . $row['action'] . '</strong></span>';
                } elseif ($status === 'Unpaid') {
                    $row['action'] = '<span style="color: red;"><strong>' . $row['action'] . '</strong></span>';
                } else {
                    $row['action'] = '';
                }
            }

            // Return the data as JSON
            echo json_encode($data);
        } else {
            echo json_encode([]); // No data found
        }
    } catch (PDOException $e) {
        // Return any errors encountered
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // If POST data is missing
    echo json_encode(['error' => 'Missing group_id or auction_month']);
}

// Close the PDO connection
$pdo = null;
