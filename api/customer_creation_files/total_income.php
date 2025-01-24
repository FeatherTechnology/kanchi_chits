<?php
require '../../ajaxconfig.php';

// Fetching and sanitizing input
$cus_id = isset($_POST['cus_id']) ? $_POST['cus_id'] : '';

if ($cus_id) {
    // Use prepared statements to prevent SQL injection
    $stmt = $pdo->prepare("SELECT SUM(income) AS total_income FROM `source` WHERE cus_id = :cus_id");
    $stmt->execute(['cus_id' => $cus_id]);

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if result is empty and handle accordingly
    $total_income = $result['total_income'] ?? 0;

    // Return the result as JSON
    echo json_encode(['total_income' => $total_income]);
} else {
    echo json_encode(['total_income' => 0]);
}

$pdo = null; // Close connection
?>
