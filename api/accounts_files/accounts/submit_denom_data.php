<?php
require "../../../ajaxconfig.php";
@session_start();

// Get user ID from the session
$user_id = $_SESSION['user_id'];

// Get the data from the AJAX request
$denominationData = $_POST['denominationData'];
$totalAmount = $_POST['totalAmount'];
$closingBalance = $_POST['closingBalance'];
$handCash = $_POST['handCash'];

// Insert into denomination_table
$insertDenomQuery = "INSERT INTO denomination_table (closing_balance, hand_cash, created_on, inserted_login_id) VALUES ('$closingBalance', '$handCash', NOW(), '$user_id')";
$pdo->query($insertDenomQuery);

// Get the last inserted ID to use in the denom_refer_table
$denom_id = $pdo->lastInsertId();

// Insert each denomination detail into denom_refer_table
foreach ($denominationData as $denom) {
    $amount = floatval($denom['denomination']); // Ensure amount is treated as a float
    $quantity = floatval($denom['quantity']); // Ensure quantity is treated as a float
    $value = floatval($denom['totalValue']); // Ensure value is treated as a float
    
    // Calculate total_amount
    $total_amount = $amount * $quantity;

    // Check if quantity is greater than 0 before inserting
    if ($quantity > 0) {
        $insertReferQuery = "INSERT INTO denom_refer_table (denom_id, amount, quantity, value, total_amount, insert_login_id, created_on) 
                             VALUES ('$denom_id', '$amount', '$quantity', '$value', '$total_amount', '$user_id', NOW())";

        $pdo->query($insertReferQuery);
    } else {
        // Optional: Handle cases where quantity is 0
        // You might want to log or do something else here
    }
}

// Return success response
echo json_encode(['status' => 'success', 'message' => 'Data submitted successfully!']);
?>
