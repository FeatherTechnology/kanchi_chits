<?php
require '../../ajaxconfig.php';
@session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group_id = $_POST['group_id'];
    $grp_date = $_POST['grp_date'];
    $start_month = $_POST['start_month'];
    $total_months = intval($_POST['total_month']);

    // Assuming $grp_date is the day of the month and $start_month is in yyyy-mm format
    $formatted_date = $start_month . '-' . str_pad($grp_date, 2, '0', STR_PAD_LEFT); // yyyy-mm-dd

    try {
        $format_date = new DateTime($formatted_date);
        $formatted_date = $format_date->format('Y-m-d');

        // Subquery to get the count of auction details
        $subQuery = "SELECT COUNT(*) AS total FROM auction_details WHERE group_id = '$group_id'";

        // Main query to get the earliest date and total count from auction_details
        $stmt = $pdo->query("SELECT 
                (SELECT date FROM auction_details WHERE group_id = '$group_id' ORDER BY date ASC LIMIT 1) AS first_date,
                ($subQuery) AS total
            FROM auction_details
            WHERE group_id = '$group_id'
            LIMIT 1");

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $first_date = new DateTime($result['first_date']);
                $total = $result['total'];

                // Check if the formatted_date matches the first_date and total matches $total_months
                if ($formatted_date == $first_date->format('Y-m-d') && $total == $total_months) {
                    
                    // Fetch data from auction_details
                    $stmt = $pdo->query("SELECT auction_month, date, low_value, high_value FROM auction_details WHERE group_id = '$group_id'");
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Format dates to "Aug 2024"
                    foreach ($data as &$row) {
                        $date = new DateTime($row['date']);
                        $row['date'] = $date->format('M Y');
                    }

                    echo json_encode([
                        'result' => 1, // Data found
                        'data' => $data
                    ]);
                    exit();
                }
            }
        }

        // If conditions are not satisfied, calculate start and end month
        $startDate = new DateTime($start_month . "-01");
        $endDate = clone $startDate;
        $endDate->modify('+' . ($total_months - 1) . ' months');
        $endMonth = $endDate->format('Y-m');

        echo json_encode([
            'result' => 0, // Generate new rows
            'start_month' => $start_month,
            'end_month' => $endMonth
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'result' => 2, // Failure
            'error_message' => $e->getMessage()
        ]);
    }
}
?>
