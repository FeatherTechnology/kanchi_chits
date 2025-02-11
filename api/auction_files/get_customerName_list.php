<?php
require '../../ajaxconfig.php';
@session_start();

$group_id = $_POST['group_id'];
$auction_month = $_POST['auction_month'];

if (isset($group_id) && !empty($group_id) && isset($auction_month) && !empty($auction_month)) {
    $customer_list_arr = array();

    // Get the list of customer names who have taken the auction in any previous month for the same group
    $taken_auction_qry = "
        SELECT gs.cus_id
        FROM auction_details ad
        LEFT JOIN group_share gs ON ad.cus_name = gs.cus_mapping_id
        WHERE group_id = '$group_id' 
        AND auction_month < '$auction_month'
    ";
    
    $taken_customers = $pdo->query($taken_auction_qry)->fetchAll(PDO::FETCH_COLUMN);

    // Get the list of customer names already in other_transaction for this group
    $transaction_qry = "
    SELECT group_mem 
    FROM other_transaction 
    WHERE group_id = '$group_id' AND type =2;
";
    $transaction_customers = $pdo->query($transaction_qry)->fetchAll(PDO::FETCH_COLUMN);

    // Get eligible customers for the current auction month
     $qry = "
    (
        SELECT 
            gcm.id,
            gs.cus_id as cust_id,
            cc.cus_id, 
            GROUP_CONCAT(DISTINCT cc.first_name SEPARATOR ' - ') AS cus_name,
            pl.place,
            COUNT(DISTINCT CASE 
                            WHEN (SELECT COUNT(*) 
                                  FROM group_share gs_check 
                                  WHERE gs_check.cus_mapping_id = gs.cus_mapping_id AND gs_check.share_percent = 100) = 1 
                            THEN gs.cus_mapping_id
                            ELSE NULL 
                          END) AS chit_count
        FROM 
            group_share gs
        JOIN 
            customer_creation cc ON gs.cus_id = cc.id
        JOIN 
            place pl ON cc.place = pl.id 
        JOIN 
            group_cus_mapping gcm ON gs.cus_mapping_id = gcm.id
        WHERE 
            gs.grp_creation_id = '$group_id' 
            AND gcm.joining_month <= '$auction_month' AND gs.share_percent = 100
        GROUP BY 
            cc.cus_id
        HAVING 
            chit_count > 0
    )
    UNION ALL
    (
        SELECT 
            gcm.id,
            GROUP_CONCAT(DISTINCT gs.cus_id) AS cust_id,
            '' AS cus_id, 
            GROUP_CONCAT(cc.first_name SEPARATOR ' - ') AS cus_name,
            '' AS place,
            (SELECT COUNT(*) 
             FROM group_share gs_sub
             WHERE gs_sub.id = gs.id AND gs_sub.grp_creation_id = '$group_id') AS chit_count
        FROM 
            group_share gs
        JOIN 
            customer_creation cc ON gs.cus_id = cc.id
        JOIN 
            group_cus_mapping gcm ON gs.cus_mapping_id = gcm.id
        WHERE 
            gs.grp_creation_id = '$group_id' 
            AND gcm.joining_month <= '$auction_month'
        GROUP BY 
            gcm.id
        HAVING 
            COUNT(*) > 1
    );
";
    $customers = $pdo->query($qry)->fetchAll(PDO::FETCH_ASSOC);

    // Filter customers based on their chit count, auction participation, and transactions in the other_transaction table
    foreach ($customers as $customer) {
        $customer_ids = explode(',', $customer['cust_id']); // Split combined customer IDs
        $chit_count = $customer['chit_count'];

        // Loop through each customer ID and process
        foreach ($customer_ids as $customer_id) {
            $auction_taken_count = count(array_filter($taken_customers, fn($id) => $id == $customer_id));
        //    echo "Customer ID: $customer_id | Auction Taken Count: $auction_taken_count | $chit_count <br>";
            
            // Count how many transactions this customer has in other_transaction
            $transaction_taken_count = count(array_filter($transaction_customers, fn($id) => $id == $customer_id));
        }

            // Check eligibility: customer can participate if their combined auction and transaction counts are less than or equal to their chit count
            if (($auction_taken_count + $transaction_taken_count) < $chit_count) {
                // Add to the list
                $customer_list_arr[] = $customer;
              //  print_r($customer_list_arr);
            }
        
    }

    $pdo = null; // Close Connection.
    echo json_encode($customer_list_arr);
} else {
    echo json_encode([]);
}
