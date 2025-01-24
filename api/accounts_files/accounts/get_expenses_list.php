<?php
require "../../../ajaxconfig.php";
$current_date = date('Y-m-d');
@session_start();
$user_id = $_SESSION['user_id'];

$exp_cat = ["1" => 'Pooja', "2" => 'Vehicle', "3" => 'Fuel', "4" => 'Stationary', "5" => 'Press', "6" => 'Food', "7" => 'Rent', "8" => 'EB', "9" => 'Mobile bill', "10" => 'Office Maintenance', "11" => 'Salary', "12" => 'Tax & Auditor', "13" => 'Int Less', "14" => 'Agent Incentive', "15" => 'Common', "16" => 'Other'];
$cash_type = ["1" => 'Hand Cash', "2" => 'Bank Cash'];
$exp_list_arr = array();
$qry = $pdo->query("SELECT e.*, bc.branch_name, b.bank_name FROM expenses e LEFT JOIN branch_creation bc ON e.branch = bc.id LEFT JOIN bank_creation b ON e.bank_id = b.id WHERE e.insert_login_id = '$user_id' AND DATE(e.created_on) = '$current_date' ");
if ($qry->rowCount() > 0) {
    while ($result = $qry->fetch()) {
        $result['branch'] = $result['branch_name'];
        $result['expenses_category'] = $exp_cat[$result['expenses_category']];
        $result['amount'] = moneyFormatIndia($result['amount']);
        $result['action'] = "<span class='icon-trash-2 expDeleteBtn' value='" . $result['id'] . "'></span>";
        $exp_list_arr[] = $result;
    }
}

echo json_encode($exp_list_arr);

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
