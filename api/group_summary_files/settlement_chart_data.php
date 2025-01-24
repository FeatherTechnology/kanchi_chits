<?php
require "../../ajaxconfig.php";

// Get the auction_id from POST data
$group_id = $_POST['group_id'];
$currentMonth = date('m'); // Get the current month
$currentYear = date('Y'); // Get the current year

$qry = $pdo->query("SELECT ad.auction_month, ad.group_id, gc.grp_name, cc.cus_id, si.cus_name, si.id AS settlement_id, si.settle_date, si.settle_cash, si.cheque_val, si.transaction_val, gi.guarantor_name, si.guarantor_relationship 
    FROM settlement_info si
    LEFT JOIN auction_details ad ON si.auction_id = ad.id
    LEFT JOIN guarantor_info gi ON si.guarantor_name = gi.id
    LEFT JOIN group_creation gc ON ad.group_id = gc.grp_id
    LEFT JOIN customer_creation cc ON si.cus_name = cc.id
    WHERE ad.group_id = '$group_id' AND (
    YEAR(ad.date) < $currentYear
    OR (YEAR(ad.date) = $currentYear AND MONTH(ad.date) <= $currentMonth)
)
ORDER BY ad.auction_month ASC, si.settle_date ASC");

// Initialize an empty array to hold the results
$result = [];
$previousMonth = null;
$previousCusId = null; // Initialize variable to track previous customer ID

// Process each row
while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
    // Convert settle_date to dd-mm-yyyy format
    if (!empty($row['settle_date'])) {
        $date = new DateTime($row['settle_date']);
        $row['settle_date'] = $date->format('d-m-Y');
    }

    // Calculate total amount
    $total_amount = (
        ($row['settle_cash'] ? (float)$row['settle_cash'] : 0) +
        ($row['cheque_val'] ? (float)$row['cheque_val'] : 0) +
        ($row['transaction_val'] ? (float)$row['transaction_val'] : 0)
    );
    $row['balance_amount'] = $total_amount;

    // Check if guarantor_name is -1 or null
    if ($row['guarantor_name'] === null || $row['guarantor_name'] == -1 || $row['guarantor_name'] == 0) {
        $row['guarantor_name'] = 'Customer';

        // Fetch the corresponding customer name for the current auction_month
        $customerQuery = $pdo->query("SELECT CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name 
            FROM settlement_info si 
            JOIN customer_creation cc ON si.cus_name = cc.id  
            LEFT JOIN auction_details ad ON si.auction_id = ad.id
            WHERE ad.group_id = '$group_id' 
            AND ad.auction_month = '{$row['auction_month']}' AND cc.id = '{$row['cus_name']}'
            AND (
                YEAR(ad.date) < $currentYear
                OR (YEAR(ad.date) = $currentYear AND MONTH(ad.date) <= $currentMonth)
            )
            ORDER BY ad.auction_month ASC");
        
        if ($customerQuery->rowCount() > 0) {
            $customerRow = $customerQuery->fetch(PDO::FETCH_ASSOC);
            $row['cus_name'] = $customerRow['cus_name'];
        } else {
            $row['cus_name'] = ''; // Default to empty if no customer found
        }
    } else {
        $row['cus_name'] = null; // Clear cus_name if not applicable
    }

    // Handle rows with the same auction month and customer ID
    if ($row['auction_month'] == $previousMonth && $row['cus_id'] == $previousCusId) {
        // Clear values only if both auction_month and cus_id are the same
        $row['group_id'] = '';
        $row['grp_name'] = '';
        $row['cus_id'] = '';
        $row['auction_month'] = '';
    } else {
        // Update previousMonth and previousCusId
        $previousMonth = $row['auction_month'];
        $previousCusId = $row['cus_id'];
    }

    // Add the row to the result
    $result[] = $row;
}

// Output the result as JSON
echo json_encode($result);

$pdo = null; // Close the connection
?>
