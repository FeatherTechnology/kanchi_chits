<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];
$current_date = date('Y-m-d');

// Use a default value for $branchId if it's not set
$branchId = isset($_POST['branchId']) ? $_POST['branchId'] : null;
$response = array();

// Initialize the SQL for total paid
$month_paid = "SELECT COALESCE(SUM(c.collection_amount), 0) AS month_paid 
                FROM collection c 
                JOIN group_creation gc ON c.group_id = gc.grp_id 
                WHERE MONTH(c.collection_date) = MONTH('$current_date') AND YEAR(c.collection_date) = YEAR('$current_date') ";

// Add conditions based on branchId
if ($branchId !== null && $branchId !== '' && $branchId !== '0') {
    $month_paid .= " AND gc.branch = '$branchId' ";
} 
$month_paid .= "GROUP BY gc.grp_id";

// Initialize the SQL for unpaid amount calculation
$month_unpaid = "SELECT 
    ((ad.chit_amount * gc.total_members) - COALESCE(
        SUM(
            CASE
                -- Check if chit_amount > 0
                WHEN c.chit_amount > 0 THEN
                    -- Case 1: c.pending = 0, include the collection amount
                    CASE 
                        WHEN c.pending = 0 THEN c.collection_amount
                        
                        -- Case 2: c.payable == c.pending, include the collection amount
                        WHEN c.payable = c.pending THEN c.collection_amount
                        
                        -- Case 3: c.payable != c.pending, check further
                        WHEN c.payable != c.pending THEN 
                            CASE
                                -- If collection_amount > pending, include the difference
                                WHEN c.collection_amount > c.pending THEN (c.collection_amount - c.pending)
                                
                                -- If collection_amount <= pending, do not include it (set to 0)
                                ELSE 0
                            END
                    END
                -- If chit_amount <= 0, do not include any collection amount (set to 0)
                ELSE 0
            END
        ), 0
    )) AS unpaid_amount
FROM 
    group_share gs
JOIN auction_details ad 
    ON gs.grp_creation_id = ad.group_id
JOIN group_creation gc 
    ON gs.grp_creation_id = gc.grp_id
LEFT JOIN collection c 
    ON ad.id = c.auction_id AND gs.id = c.share_id
WHERE 
    MONTH(ad.date) = MONTH('$current_date') 
    AND YEAR(ad.date) = YEAR('$current_date')
    AND ad.status IN (2, 3) ";

// Add conditions based on branchId for unpaid amount
if ($branchId !== null && $branchId !== '' && $branchId !== '0') {
    $month_unpaid .= " AND gc.branch = '$branchId'";
} 
$month_unpaid .= " GROUP BY gs.grp_creation_id";

$prev_pen_amount  = "SELECT (
                        (SELECT SUM(COALESCE(ad.chit_amount, 0) * gc_sub.total_members)
                        FROM auction_details ad
                        JOIN group_creation gc_sub ON ad.group_id = gc_sub.grp_id
                        WHERE ad.group_id = gc.grp_id
                        AND (YEAR(ad.date) < YEAR('$current_date') 
                            OR (YEAR(ad.date) = YEAR('$current_date') 
                                AND MONTH(ad.date) < MONTH('$current_date')))
                        AND ad.status IN (2, 3)
                        ) - 
                        (SELECT COALESCE(SUM(c.collection_amount), 0)
                        FROM collection c
                        LEFT JOIN auction_details ad ON c.auction_id = ad.id
                        WHERE c.group_id = gc.grp_id
                        AND (YEAR(ad.date) < YEAR('$current_date') 
                            OR (YEAR(ad.date) = YEAR('$current_date') 
                                AND MONTH(ad.date) < MONTH('$current_date')))
                            AND ad.status IN (2, 3)
                        )
                    ) AS pending_amount
                    FROM group_creation gc ";

// Add conditions based on branchId for unpaid amount
if ($branchId !== null && $branchId !== '' && $branchId !== '0') {
    $prev_pen_amount .= "WHERE gc.branch = '$branchId'";
} 
$qryCount = "SELECT
    gs.id, 
    COALESCE((la.chit_amount * gs.share_percent / 100), 0) AS total_chit_amount,
    la.last_auction_month
FROM
    group_share gs
JOIN (
    SELECT
        ad.group_id,
        COALESCE(SUM(ad.chit_amount), 0) AS chit_amount,
        MAX(ad.auction_month) AS last_auction_month
    FROM
        auction_details ad
    WHERE (
            YEAR(ad.date) < YEAR('$current_date')  
            OR (
                YEAR(ad.date) = YEAR('$current_date')  
                AND MONTH(ad.date) < MONTH('$current_date')  
            )
    )
    GROUP BY
        ad.group_id
) la ON gs.grp_creation_id = la.group_id
LEFT JOIN collection c ON gs.id = c.share_id 
    AND c.auction_month = la.last_auction_month 
    AND c.created_on = (
        SELECT MAX(created_on)
        FROM collection 
        WHERE share_id = gs.id 
        AND auction_month = la.last_auction_month
    )   
JOIN group_creation gc ON gs.grp_creation_id = gc.grp_id  
WHERE
    c.share_id IS NULL  
    OR (c.payable != c.collection_amount)
";
if ($branchId !== null && $branchId !== '' && $branchId !== '0') {
    $qryCount .= " AND gc.branch = '$branchId' ";
} 
$qryCount .= "GROUP BY
gs.id
ORDER BY
gs.id";

$stmtCount = $pdo->query($qryCount);
$mappings = $stmtCount->fetchAll(PDO::FETCH_ASSOC);

// Initialize variable to hold the total pending amount
$total_amount = 0;

foreach ($mappings as $mapping) {
    $map_id = $mapping['id'];  // Corrected the key from 'cc_id' to 'id'
    $total_chit_amount = $mapping['total_chit_amount'];  // Assuming this value is already sanitized
    $last_month = $mapping['last_auction_month'];  // Assuming this value is already sanitized

    // Correct the query: 'pending_amount' should be 'total_amount'
    $qry1 = "SELECT COALESCE(SUM(c.collection_amount), 0) AS total_collection_amount
             FROM collection c
             WHERE c.share_id = '$map_id'
               AND c.auction_month <= '$last_month'";
    $stmt1 = $pdo->query($qry1);

    // Fetch the result
    $res = $stmt1->fetch(PDO::FETCH_ASSOC);  // Use fetch instead of fetchAll
    $total_collection_amount = $res['total_collection_amount'];  // Get the total collection amount
    $previous_amount = $total_chit_amount - $total_collection_amount;  // Calculate previous amount

    // Now, calculate the pending amount for the current date
    $qry2 = "
    SELECT 
        COALESCE(
            LEAST(
                $previous_amount, 
                COALESCE(SUM(IF(c.chit_amount = 0, 0, c.collection_amount)), 0)
            ), 
            0
        ) AS total_amount
    FROM 
        collection c
    WHERE 
        c.share_id = '$map_id'
        AND MONTH(c.collection_date) = MONTH('$current_date')
        AND YEAR(c.collection_date) = YEAR('$current_date');
    ";

    // Directly execute the query using query()
    $stmt2 = $pdo->query($qry2);

    // Fetch the result
    $result5 = $stmt2->fetch(PDO::FETCH_ASSOC);

    // Check if there is a result and add the total amount
    if ($result5 && isset($result5['total_amount'])) {
        $total_amount += $result5['total_amount'];  // Sum total amounts
    }
}
// Now, $total_pending_amount contains the total pending amount for all mappings
try {

    // Query for total paid
    $qry = $pdo->query($month_paid);
    $paid_results = $qry->fetchAll(PDO::FETCH_ASSOC);
    $total_paid_amount = 0; // Initialize total paid amount
    foreach ($paid_results as $result) {
        $total_paid_amount += $result['month_paid']; // Sum paid amounts
    }
    // Add total paid amount to the response
    $response['month_paid'] = $total_paid_amount;

    // Query for unpaid groups
    $qry = $pdo->query($month_unpaid);
    $unpaid_results = $qry->fetchAll(PDO::FETCH_ASSOC);
    $total_unpaid_amount = 0; // Initialize total unpaid amount
    foreach ($unpaid_results as $result) {
        $total_unpaid_amount += $result['unpaid_amount']; // Sum unpaid amounts
    }
    // Add total unpaid amount to the response
    $response['month_unpaid'] = $total_unpaid_amount;

    // Query for unpaid groups
    $qry = $pdo->query($prev_pen_amount);
    $pending_results = $qry->fetchAll(PDO::FETCH_ASSOC);
    $total_pending_amount = 0; // Initialize total unpaid amount
    foreach ($pending_results as $result) {
        $total_pending_amount += $result['pending_amount']; // Sum unpaid amounts
    }
    // Add total unpaid amount to the response
    $response['prev_pen_amount'] = $total_pending_amount - $total_amount;

    $total_outstanding = $total_unpaid_amount + $total_pending_amount - $total_amount;
    $response['total_outstanding'] = $total_outstanding;

    // Return response as JSON
    echo json_encode($response);
} catch (PDOException $e) {
    // Handle any errors
    echo json_encode(array('error' => $e->getMessage()));
}
