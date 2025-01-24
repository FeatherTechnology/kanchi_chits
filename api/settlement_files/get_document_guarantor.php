<?php
require '../../ajaxconfig.php';

$cus_id = $_POST['cus_id'];

$qry = $pdo->query("
    SELECT 
        gi.id,
        gi.guarantor_name,
        CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name
    FROM 
        customer_creation cc 
    LEFT JOIN 
        guarantor_info gi ON cc.cus_id = gi.cus_id
    WHERE 
        cc.cus_id = '$cus_id'
    GROUP BY 
        cc.id, gi.id
");

$result = [];
$customerAdded = false;  // Flag to track if the customer name is added

if ($qry->rowCount() > 0) {
    $guarantorResults = $qry->fetchAll(PDO::FETCH_ASSOC);  // Fetch the results
    
    foreach ($guarantorResults as $row) {
        // Push the customer name only once
        if (!$customerAdded) {
            $result[] = [
                'id' => null, // Assuming customer doesn't have an id in this context
                'name' => $row['cus_name'],
            ];
            $customerAdded = true;  // Set flag to true after adding the customer
        }

        // Push the guarantor name (if available)
        if (!empty($row['guarantor_name'])) {
            $result[] = [
                'id' => $row['id'],
                'name' => $row['guarantor_name'],
            ];
        }
    }
}

$pdo = null;  // Close connection
echo json_encode($result);  // Corrected: encode and return $result
?>
