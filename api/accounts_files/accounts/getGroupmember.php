<?php
require "../../../ajaxconfig.php";
@session_start();

$group_id = $_POST['group_id'];

if (isset($group_id) && !empty($group_id)) {
    $customer_list_arr = array();

    // Query to fetch auction details for customers who participated in past auctions
    $taken_auction_qry = "
      SELECT
  gs.cus_id
FROM
  auction_details ad
             LEFT JOIN group_share gs ON
    ad.cus_name = gs.cus_mapping_id
WHERE
  group_id = '$group_id' AND gs.settle_status ='Yes'
  AND (
    (MONTH(ad.date) <= MONTH(CURDATE()) AND YEAR(ad.date) = YEAR(CURDATE()))  
    OR YEAR(ad.date) = YEAR(CURDATE()) - 1  
  );
    ";
    $taken_customers = $pdo->query($taken_auction_qry)->fetchAll(PDO::FETCH_COLUMN);

    // Query to fetch customers who had transactions in other_transaction
    $transaction_qry = "
       SELECT group_mem, LEAST(COUNT(CASE WHEN TYPE = 1 THEN 1 END), COUNT(CASE WHEN TYPE = 2 THEN 1 END)) AS pair_count
       FROM other_transaction
       WHERE group_id = '$group_id'
       AND TYPE IN (1, 2)
       GROUP BY group_mem
       HAVING pair_count > 0;
    ";
    $transaction_customers = $pdo->query($transaction_qry)->fetchAll(PDO::FETCH_ASSOC);

    // Extract transaction customer IDs based on their pair counts (to appear multiple times if needed)
    $transaction_customers_expanded = [];
    foreach ($transaction_customers as $transaction) {
        $group_mem = $transaction['group_mem'];
        $pair_count = $transaction['pair_count'];

        // Repeat group_mem based on pair_count
        for ($i = 0; $i < $pair_count; $i++) {
            $transaction_customers_expanded[] = $group_mem;
        }
    }

    // Main query to fetch customers along with their chit count and auction month
 
 $qry = "SELECT
    cc.id,
    ad.group_id,
    cc.cus_id,
    CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name,
    cc.mobile1,
    pl.place,
    (
    SELECT
        GROUP_CONCAT(sc.occupation SEPARATOR ', ')
    FROM SOURCE
        sc
    WHERE
        sc.cus_id = cc.cus_id
) AS occupations,
gcm.id AS cus_mapping_id,
gs.id AS share_id,
gs.settle_status,
ad.auction_month,
COUNT(
    DISTINCT CASE WHEN(
    SELECT
        COUNT(*)
    FROM
        group_share gs_check
    WHERE
        gs_check.cus_mapping_id = gs.cus_mapping_id
)  THEN gs.cus_mapping_id ELSE 1
END
) AS chit_count
FROM
    auction_details ad
LEFT JOIN group_share gs ON
    ad.group_id = gs.grp_creation_id
LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
LEFT JOIN customer_creation cc ON
    gs.cus_id = cc.id
LEFT JOIN place pl ON
    cc.place = pl.id
LEFT JOIN group_creation gc ON
    ad.group_id = gc.grp_id
JOIN users us ON
    FIND_IN_SET(gc.branch, us.branch)
WHERE
    gc.grp_id = '$group_id'
GROUP BY
    cc.cus_id
ORDER BY cc.cus_id";
    $customers = $pdo->query($qry)->fetchAll(PDO::FETCH_ASSOC);

    // Filter customers based on their chit count, auction participation, and transactions in other_transaction
    foreach ($customers as $customer) {
        $customer_id = $customer['id'];
        $chit_count = $customer['chit_count'];

        // Count how many times this customer has taken part in auctions
        $auction_taken_count = count(array_filter($taken_customers, fn($id) => $id == $customer_id));

        // Count how many transactions this customer has in other_transaction
        $transaction_taken_count = count(array_filter($transaction_customers_expanded, fn($id) => $id == $customer_id));

        // Check eligibility: customer can participate if their combined auction and transaction counts are less than or equal to their chit count
        if (($auction_taken_count + $transaction_taken_count) < $chit_count) {
            $customer_list_arr[] = $customer;
        }
    }

    $pdo = null; // Close Connection.
    echo json_encode($customer_list_arr);
} else {
    echo json_encode([]);
}
