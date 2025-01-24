<?php
require "../../../ajaxconfig.php";

$other_trans_name = isset($_POST['other_trans_name']) ? $_POST['other_trans_name'] : '';
$group_mem = isset($_POST['group_mem']) ? $_POST['group_mem'] : '';
$group_id= isset($_POST['group_id']) ? $_POST['group_id'] : '';
$result = [];
$total_type_1_amount = 0; // Credit amount (type=1)
$total_type_2_amount = 0; // Debit amount (type=2)

if (!empty($other_trans_name) && $other_trans_name != '0') {
    $qry = $pdo->query("SELECT type, amount FROM other_transaction WHERE name = '$other_trans_name'");
} else {
    $qry = $pdo->query("SELECT type, amount FROM other_transaction WHERE group_mem = '$group_mem' AND group_id = '$group_id'");
}

if ($qry->rowCount() > 0) {
    $transactions = $qry->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($transactions as $transaction) {
        // Ensure that amount is treated as a float
        $amount = (float)$transaction['amount'];

        // Check if type is 1 (credit) and sum the amount
        if ($transaction['type'] == '1') {
            $total_type_1_amount += $amount;
        }
        // Check if type is 2 (debit) and sum the amount
        if ($transaction['type'] == '2') {
            $total_type_2_amount += $amount;
        }
    }

    // Add the credit and debit totals to the result
    $result['transactions'] = $transactions;
    $result['total_type_1_amount'] = $total_type_1_amount;
    $result['total_type_2_amount'] = $total_type_2_amount;
} else {
    $result['error'] = "No transactions found.";
}

$pdo = null; // Close connection.
echo json_encode($result);
?>
