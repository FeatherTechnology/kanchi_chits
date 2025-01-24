<?php
require "../../../ajaxconfig.php";
$current_date = date('Y-m-d');
@session_start();
$user_id = $_SESSION['user_id'];

$trans_cat = ["1" => 'Deposit', "2" => 'Investment', "3" => 'EL', "4" => 'Exchange', "5" => 'Bank Deposit', "6" => 'Bank Withdrawal', "7" => 'Chit Advance', "8" => 'Other Income', "9" => 'Bank Unbilled'];
$cash_type = ["1" => 'Hand Cash', "2" => 'Bank Cash'];
$crdr = ["1" => 'Credit', "2" => 'Debit'];
$trans_list_arr = array();
$qry = $pdo->query("
    SELECT 
        a.*, 
        b.name AS transname,
        gc.grp_id, -- Ensure gc.grp_id is retrieved directly
        CONCAT(gc.grp_id, '-', gc.grp_name) AS group_id, 
        d.name as username,  
        CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name,
        e.bank_name as bank_namecash,
        a.auction_month 
    FROM `other_transaction` a 
    LEFT JOIN other_trans_name b ON a.name = b.id 
    LEFT JOIN group_creation gc ON a.group_id = gc.grp_id 
    LEFT JOIN users d ON a.user_name = d.id 
    LEFT JOIN bank_creation e ON a.bank_id = e.id 
    LEFT JOIN customer_creation cc ON a.group_mem = cc.id 
    WHERE a.insert_login_id = '$user_id' 
    AND DATE(a.created_on) = '$current_date'
");
if ($qry->rowCount() > 0) {
    while ($result = $qry->fetch()) {
        $result['trans_cat'] = $trans_cat[$result['trans_cat']];
        $result['name'] = $result['transname'];
        $result['type'] = $crdr[$result['type']];
        $result['amount'] = moneyFormatIndia($result['amount']);
        // Ensure no empty values in the unique string
        $id = !empty($result['id']) ? $result['id'] : '0';
        $grp_id = !empty($result['grp_id']) ? $result['grp_id'] : '0';
        $group_mem = !empty($result['group_mem']) ? $result['group_mem'] : '0';
        $auction_month = !empty($result['auction_month']) ? $result['auction_month'] : '';

        $unique = $id . '_' . $grp_id . '_' . $group_mem . '_' . $auction_month;
        $result['action'] = "<span class='icon-trash-2 transDeleteBtn' data-value='" . $unique . "'></span>";

        $trans_list_arr[] = $result;
    }
}
echo json_encode($trans_list_arr);

//Format number in Indian Format
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
