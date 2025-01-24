<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];

$qry = $pdo->query("SELECT 
            ad.id,
            ad.group_id,
            gc.grp_name,
            gc.chit_value,
            (gc.chit_value * (gc.commission / 100)) AS commission,
            gc.total_members,
            gc.total_months,
            gc.start_month,
            gc.end_month,
            ad.auction_month,
            ad.date
        FROM 
            auction_details ad
        LEFT JOIN 
            group_creation gc ON ad.group_id = gc.grp_id WHERE ad.id='$id'");
if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
    function formatNumber($value) {
        if (is_numeric($value)) {
            // Convert to float and format to avoid scientific notation
            $formatted = number_format($value, 10, '.', ''); // Extra decimals to avoid rounding issues
            // Remove trailing zeros
            $formatted = rtrim(rtrim($formatted, '0'), '.');
            return $formatted;
        }
        return $value;
    }
    
    // Apply formatting to each row in the result set
    if (!empty($result)) {
        foreach ($result as &$row) {
            $row['commission'] = formatNumber($row['commission']);
            $row['chit_value'] = formatNumber($row['chit_value']);
        }
    }
}
$pdo = null; //Close connection.
echo json_encode($result);