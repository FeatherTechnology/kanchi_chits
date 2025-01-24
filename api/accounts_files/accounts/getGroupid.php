<?php
require "../../../ajaxconfig.php";

// First query to fetch group information
$qry = $pdo->query("
    SELECT
        id,
        grp_id,
        grp_name,
        total_members
    FROM
        group_creation
    WHERE
        STATUS >= 3 AND STATUS <= 5
");

$response = [];

while ($group = $qry->fetch(PDO::FETCH_ASSOC)) {
    $grp_id = $group['grp_id']; // Get the group ID from the first query
    $total_members = $group['total_members']; // Get the total_members from the first query
    
    // Second query to count distinct cus_mapping_id for each group
    $second_qry = $pdo->query("
        SELECT
            COUNT(DISTINCT cus_mapping_id) AS distinct_count
        FROM
            group_share
        WHERE
            grp_creation_id = '$grp_id'
            AND settle_status = 'Yes'
    ");
    
    $distinct_count = $second_qry->fetch(PDO::FETCH_ASSOC)['distinct_count'];

    // Third query to get credit amount
    $third_qry = $pdo->query("
        SELECT
            SUM(amount) AS credit_amount
        FROM
            other_transaction
        WHERE
            group_id = '$grp_id' AND type = 1
    ");
    $credit_amount = $third_qry->fetch(PDO::FETCH_ASSOC)['credit_amount'];

    // Fourth query to get debit amount
    $fourth_qry = $pdo->query("
        SELECT
            SUM(amount) AS debit_amount
        FROM
            other_transaction
        WHERE
            group_id = '$grp_id' AND type = 2
    ");
    $debit_amount = $fourth_qry->fetch(PDO::FETCH_ASSOC)['debit_amount'];

    // Check if total_members == distinct_count and debit_amount == credit_amount
    if ($total_members == $distinct_count && $debit_amount == $credit_amount) {
        // Skip this group if the condition is met
        continue;
    }

    // If the group does not meet the condition, add it to the response
    $response[] = $group;
}

$pdo = null; // Close the connection

// Return the filtered groups as JSON
echo json_encode($response);
?>
