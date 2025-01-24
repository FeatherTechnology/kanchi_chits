<?php
require '../../ajaxconfig.php';

$response = array();
$auction_status = [1 => 'In Auction', 2 => 'Finished', 3 => 'Finished'];
$status_arr = [1 => 'Process', 2 => 'Created', 3 => 'Current', 4 => 'Closed',5 => 'Closed'];

if (isset($_POST['group_id'])) {
    $group_id = $_POST['group_id'];
    $currentMonth = date('m'); // Get the current month
    $currentYear = date('Y'); // Get the current year

    try {
        // Construct the query with the group_id, currentYear, and currentMonth directly embedded
        $qry = "SELECT 
                    ad.auction_month,
                    DATE_FORMAT(ad.date, '%d-%m-%Y') AS auction_date,
                    ad.auction_value,
                    GROUP_CONCAT(
    CASE 
        WHEN ad.cus_name = '-1' THEN 'Company' 
        ELSE COALESCE(cc.first_name, '') 
    END 
    SEPARATOR ' - '
) AS cus_name,
                    ad.status as auction_status,
                    gc.status as grp_status,
                    gc.grp_id  
                FROM auction_details ad
                 JOIN group_creation gc ON ad.group_id = gc.grp_id
              LEFT JOIN group_share gs ON ad.cus_name = gs.cus_mapping_id 
LEFT JOIN customer_creation cc ON gs.cus_id = cc.id 
                WHERE ad.group_id = '$group_id' 
                AND  ad.date <= CURDATE() AND ad.status IN (2, 3)
                  GROUP BY ad.id, ad.group_id,gs.cus_mapping_id
                ORDER BY ad.auction_month DESC";

        // Execute the query
        $stmt = $pdo->query($qry);

        // Fetch the result
        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

         
            foreach ($data as &$row) {
                // Fetch customer mapping IDs
                $customer_mapping_query = "SELECT id FROM group_share WHERE grp_creation_id = '{$group_id}'";
                $customer_mapping_result = $pdo->query($customer_mapping_query);
                $customer_ids = $customer_mapping_result->fetchAll(PDO::FETCH_COLUMN);

                // Check if all customers have Paid status
                $all_paid = true;
                foreach ($customer_ids as $cus_id) {
                    $payment_status_query = "SELECT coll_status FROM collection 
                        WHERE group_id = '{$group_id}' 
                        AND auction_month = '{$row['auction_month']}' 
                        AND share_id = '{$cus_id}'
                        ORDER BY created_on DESC LIMIT 1";
                    $payment_status_result = $pdo->query($payment_status_query);
                    $payment_status = $payment_status_result->fetchColumn();
                    if ($payment_status !== 'Paid') {
                        $all_paid = false;
                        break;
                    }
                }

                // Add collection status to the row
                $row['collection_status'] = $all_paid ? 'Completed' : 'In Collection';
                $row['auction_status'] = $auction_status[$row['auction_status']] ?? '';
                $row['grp_status'] = $status_arr[$row['grp_status']] ?? '';

                // Add the action button HTML to the row
                $row['action'] = "<button class='btn btn-primary collectionActionBtn' data-value='{$row['grp_id']}_{$row['auction_month']}'>&nbsp;Collection Chart</button>";
            }

            // Return the processed data
            echo json_encode($data);
        } else {
            echo json_encode([]);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

    // Close the PDO connection
    $pdo = null;
}
