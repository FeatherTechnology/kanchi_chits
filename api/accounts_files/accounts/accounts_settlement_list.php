<?php
require "../../../ajaxconfig.php";
$current_date = date('Y-m-d');
$loan_issue_list_arr = array();
$cash_type = $_POST['cash_type'];
$bank_id = $_POST['bank_id'];

// Initialize bank_condition
$bank_condition = ''; 

// Initialize amount_column based on cash_type
$amount_column = '';

if ($cash_type == '1') {
    // Assuming '1' means cash
    $amount_column = "si.settle_cash";
} elseif ($cash_type == '2') {
    // Assuming '2' means bank (cheque and transaction)
    $amount_column = "COALESCE(si.cheque_val, 0) + COALESCE(si.transaction_val, 0)";
    $bank_condition = "AND si.bank_id = '$bank_id'";
}

// Constructing the query
$query = "WITH SettlementSummary AS (
    SELECT 
        si.insert_login_id,
        DATE_FORMAT(si.settle_date, '%d-%m-%Y') AS settle_date,
        COUNT(DISTINCT CASE WHEN $amount_column > 0 THEN si.auction_id END) AS no_of_customers,
        SUM(CASE WHEN $amount_column > 0 THEN $amount_column ELSE 0 END) AS amount
    FROM 
        settlement_info si
    WHERE 
        DATE(si.settle_date) = '$current_date'
        $bank_condition
    GROUP BY 
        si.insert_login_id, DATE_FORMAT(si.settle_date, '%d-%m-%Y')
)
SELECT
    a.name AS user_name,
    GROUP_CONCAT(DISTINCT bc.branch_name ORDER BY bc.branch_name SEPARATOR ', ') AS branch_names,
    ss.no_of_customers,
    ss.settle_date,
    COALESCE(ss.amount, 0) AS total_settlement_amount
FROM
    users a
INNER JOIN SettlementSummary ss ON ss.insert_login_id = a.id
LEFT JOIN branch_creation bc ON FIND_IN_SET(bc.id, a.branch)
GROUP BY
    a.name, ss.settle_date
ORDER BY
    a.name, ss.settle_date;";

$qry = $pdo->query($query);

if ($qry->rowCount() > 0) {
    while ($data = $qry->fetch(PDO::FETCH_ASSOC)) {
        $data['no_of_customers'] = ($data['no_of_customers']) ? $data['no_of_customers'] : 0;
        $data['total_settlement_amount'] = ($data['total_settlement_amount']) ? moneyFormatIndia($data['total_settlement_amount']) : 0;
        $loan_issue_list_arr[] = $data;
    }
}

echo json_encode($loan_issue_list_arr);

// Format number in Indian Format
function moneyFormatIndia($num1)
{
    if ($num1 < 0) {
        $num = str_replace("-", "", $num1);
    } else {
        $num = $num1;
    }
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    if ($num1 < 0 && $num1 != '') {
        $thecash = "-" . $thecash;
    }

    return $thecash;
}
?>
