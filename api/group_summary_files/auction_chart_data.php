<?php
require '../../ajaxconfig.php';

$response = array();

if (isset($_POST['group_id'])) { 
    $group_id = $_POST['group_id'];
    $currentMonth = date('m'); // Get the current month
    $currentYear = date('Y'); // Get the current year

    try {
        $qry = "SELECT 
                    ad.auction_month,
                    DATE_FORMAT(ad.date, '%d-%m-%Y') AS auction_date,
                    ad.auction_value,
                    (gc.chit_value * (gc.commission / 100)) AS commission,
                    (gc.chit_value + (gc.chit_value * (gc.commission / 100)) - ad.auction_value) AS total_value,
                    ad.chit_amount,
                    GROUP_CONCAT(
                        CASE 
                            WHEN ad.cus_name = '-1' THEN 'Company' 
                            ELSE CONCAT(cc.first_name,' ', cc.last_name) 
                        END 
                        SEPARATOR ' - '
                    ) AS cus_name
                FROM auction_details ad
                JOIN group_creation gc ON ad.group_id = gc.grp_id
                LEFT JOIN group_share gs ON ad.cus_name = gs.cus_mapping_id 
                LEFT JOIN customer_creation cc ON gs.cus_id = cc.id 
                WHERE ad.group_id = '$group_id' AND ad.status >=2
                AND ( YEAR(ad.date) < $currentYear OR (YEAR(ad.date) = $currentYear AND MONTH(ad.date) <=$currentMonth ))
                GROUP BY ad.id
                ORDER BY ad.auction_month ASC";
               
        // Execute the query
        $stmt = $pdo->query($qry);

        // Fetch the result
        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Function to format number without trailing zero decimals
            // function formatNumber($value) {
            //     if (is_numeric($value)) {
            //         $formatted = number_format($value, 10, '.', '');
            //         $formatted = rtrim(rtrim($formatted, '0'), '.');
            //         return $formatted;
            //     }
            //     return $value;
            // }

            // // Format the numbers
            // foreach ($data as &$row) {
            //     $row['commission'] = formatNumber($row['commission']);
            //     $row['total_value'] = formatNumber($row['total_value']);
            //     $row['chit_amount'] = formatNumber($row['chit_amount']);
            // }
            
            echo json_encode($data);
        } else {
            echo json_encode([]);
        }

    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

    // Close the PDO connection
    $pdo = null;
}
?>
