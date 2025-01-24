<?php
require "../../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

if (isset($_POST['IDEtype'])) {
    $IDEtype = $_POST['IDEtype'];
} else {
    $IDEtype = '';
} // investment or Deposit or EL
if (isset($_POST['IDEview_type'])) {
    $IDEview_type = $_POST['IDEview_type'];
} else {
    $IDEview_type = '';
} // overall or individual
if (isset($_POST['IDE_name_id'])) {
    $IDE_name_id = $_POST['IDE_name_id'];
} else {
    $IDE_name_id = '';
} // Name id for IDE

if ($IDEtype == 1 and $IDEview_type == 1 and $IDE_name_id == '') { //Deposit without name
    {
        $opening_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'
        ) AS opening
    ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];

        $closing_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS closing_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'
            
            UNION ALL
            
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'
            
            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'
        ) AS closing
    ");
        $closing_bal = $closing_qry->fetch()['closing_balance'];
    }
    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";


    $qry = $pdo->query("SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='1' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='1' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='1' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='1' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $pdo->query("SELECT bank_short_name,account_number from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['bank_short_name'] . ' - ' . substr($bnamerun['account_number'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='3'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Closing Balance</b></td><td colspan='2'>" . moneyFormatIndia($closing_bal) . "</td></tr>";

}else if ($IDEtype == 1 and $IDEview_type == 2 and $IDE_name_id != '') {//Deposit with name
    {
        $opening_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
        ) AS opening
    ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];

        $closing_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS closing_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
            
            UNION ALL
            
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='1' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
            
            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='1' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
        ) AS closing
    ");
        $closing_bal = $closing_qry->fetch()['closing_balance'];
    }
    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";


    $qry = $pdo->query("SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='1' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='1' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='1' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='1' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $pdo->query("SELECT bank_short_name,account_number from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['bank_short_name'] . ' - ' . substr($bnamerun['account_number'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='3'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Closing Balance</b></td><td colspan='2'>" . moneyFormatIndia($closing_bal) . "</td></tr>";

}else if ($IDEtype == 3 and $IDEview_type == 1 and $IDE_name_id == '') {//EL without name
    {
        $opening_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'
        ) AS opening
    ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];

        $closing_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS closing_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'
            
            UNION ALL
            
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'
            
            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'
        ) AS closing
    ");
        $closing_bal = $closing_qry->fetch()['closing_balance'];
    }
    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";


    $qry = $pdo->query("SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='3' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='3' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='3' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='3' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $pdo->query("SELECT bank_short_name,account_number from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['bank_short_name'] . ' - ' . substr($bnamerun['account_number'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='3'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Closing Balance</b></td><td colspan='2'>" . moneyFormatIndia($closing_bal) . "</td></tr>";

}else if ($IDEtype == 3 and $IDEview_type == 2 and $IDE_name_id != '') {//EL with name
    {
        $opening_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
        ) AS opening
    ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];

        $closing_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS closing_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
            
            UNION ALL
            
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='3' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
            
            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='3' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
        ) AS closing
    ");
        $closing_bal = $closing_qry->fetch()['closing_balance'];
    }
    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";


    $qry = $pdo->query("SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='3' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='3' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='3' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='3' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $pdo->query("SELECT bank_short_name,account_number from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['bank_short_name'] . ' - ' . substr($bnamerun['account_number'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='3'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Closing Balance</b></td><td colspan='2'>" . moneyFormatIndia($closing_bal) . "</td></tr>";

}else if ($IDEtype == 4 and $IDEview_type == 1 and $IDE_name_id == '') {//Exchange without name
    {
        $opening_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'
        ) AS opening
    ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];

        $closing_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS closing_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'
            
            UNION ALL
            
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'
            
            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'
        ) AS closing
    ");
        $closing_bal = $closing_qry->fetch()['closing_balance'];
    }
    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";


    $qry = $pdo->query("SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='4' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='4' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='4' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='4' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id'
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $pdo->query("SELECT bank_short_name,account_number from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['bank_short_name'] . ' - ' . substr($bnamerun['account_number'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='3'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Closing Balance</b></td><td colspan='2'>" . moneyFormatIndia($closing_bal) . "</td></tr>";

}else if ($IDEtype == 4 and $IDEview_type == 2 and $IDE_name_id != '') {//Exchange with name
    {
        $opening_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS opening_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
        ) AS opening
    ");
        $opening_bal = $opening_qry->fetch()['opening_balance'];

        $closing_qry = $pdo->query("SELECT
        IFNULL(SUM(Credit), 0) - IFNULL(SUM(Debit), 0) AS closing_balance
        FROM (
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
            
            UNION ALL
            
            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                created_on <= LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='4' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
            
            UNION ALL

            SELECT
                '' AS Credit,
                amount AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'

            UNION ALL

            SELECT
                amount AS Credit,
                '' AS Debit
            FROM other_transaction
            WHERE
                MONTH(created_on) = MONTH(CURRENT_DATE())
                AND YEAR(created_on) = YEAR(CURRENT_DATE())
                AND `trans_cat` ='4' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
        ) AS closing
    ");
        $closing_bal = $closing_qry->fetch()['closing_balance'];
    }
    $tableHeaders = "<th width='50'>S.No</th><th>Date</th><th>Cash Type</th><th>Credit</th><th>Debit</th>";


    $qry = $pdo->query("SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='4' AND coll_mode = 1 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, '' AS Credit, amount AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='4' AND coll_mode = 2 AND type = 2 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, 'Hand Cash' AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='4' AND coll_mode = 1 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    UNION ALL 
    
    SELECT DATE_FORMAT(`created_on`, '%d-%m-%Y') AS tdate, bank_id AS ctype, amount AS Credit, '' AS Debit, amount AS Amount 
    FROM other_transaction 
    WHERE MONTH(created_on) = MONTH(CURRENT_DATE()) AND YEAR(created_on) = YEAR(CURRENT_DATE()) AND `trans_cat` ='4' AND coll_mode = 2 AND type = 1 AND insert_login_id = '$user_id' AND name ='$IDE_name_id'
    
    ORDER BY 1
    ");

    $i = 1;
    $creditSum = 0;
    $debitSum = 0;

    $tabBody = '<tr>';

    if ($qry->rowCount() > 0) { //check wheather query returning values or not

        while ($row = $qry->fetch()) {
            $tabBody .= "<td>$i</td>";
            $tabBody .= "<td>" . date('d-m-Y', strtotime($row['tdate'])) . "</td>";

            if ($row['ctype'] != 'Hand Cash') {
                $bnameqry = $pdo->query("SELECT bank_short_name,account_number from bank_creation where id = '" . $row['ctype'] . "' ");
                $bnamerun = $bnameqry->fetch();
                $bname = $bnamerun['bank_short_name'] . ' - ' . substr($bnamerun['account_number'], -5);

                $tabBody .= "<td>" . $bname . "</td>";
            } else {
                $tabBody .= "<td>" . $row['ctype'] . "</td>";
            }

            $tabBody .= "<td>" . moneyFormatIndia($row['Credit']) . "</td>";
            $tabBody .= "<td>" . moneyFormatIndia($row['Debit']) . "</td>";
            $tabBody .= '</tr>';

            //Store credit and debit for total
            $creditSum = $creditSum + intVal($row['Credit']);
            $debitSum = $debitSum + intVal($row['Debit']);
            $i++;
        }
        $difference = $creditSum - $debitSum;
    } else {
        //if query not returning any values, set table body and footer empty. by default its not working
        $tabBody = '';
        $tabBodyEnd = '';
        $difference = 0;
    }

    $tabBodyEnd = "<tr><td colspan='3'><b>Total</b></td><td>" . moneyFormatIndia($creditSum) . "</td><td>" . moneyFormatIndia($debitSum) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Difference</b></td><td colspan='2'>" . moneyFormatIndia($difference) . "</td></tr>";
    $tabBodyEnd .= "<tr><td colspan='3'><b>Closing Balance</b></td><td colspan='2'>" . moneyFormatIndia($closing_bal) . "</td></tr>";

}

//Format number in Indian Format
function moneyFormatIndia($num1){
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
                $explrestunits .= (int) $expunit[$i] . ",";
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

if ($opening_bal != '') {
?>
    <div class="col-12" id="open_balance">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12"></div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                <div class="form-group">
                    <label for=''><b>Opening Balance: <?php echo $opening_bal; ?></b></label>
                </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12"></div>
        </div>
    </div>
<?php } ?>

<table class="table custom-table" id='blncSheetTable'>
    <thead>
        <tr>
            <?php echo $tableHeaders; ?>
        </tr>
    </thead>
    <tbody>
        <?php echo $tabBody; ?>
    </tbody>
    <tfoot>
        <?php echo $tabBodyEnd; ?>
    </tfoot>
</table>

<script type='text/javascript'>
    $(function() {
        $('#blncSheetTable').DataTable({
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ],
        });
    });
</script>