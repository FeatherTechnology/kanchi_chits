<?php
require "../../../ajaxconfig.php";
$current_date = date('Y-m-d');
$collection_list_arr = array();
$cash_type = $_POST['cash_type'];
$bank_id = $_POST['bank_id'];

// Determine condition based on cash_type
if ($cash_type == '1') {
    $cndtn = "coll_mode = '1'";
} elseif ($cash_type == '2') {
    $cndtn = "coll_mode != '1' AND bank_id = '$bank_id'";
}

$qry = $pdo->query("WITH first_query AS (
    SELECT
        u.id AS userid,
        u.name,
        GROUP_CONCAT(
            DISTINCT bc.branch_name
            ORDER BY bc.branch_name SEPARATOR ', '
        ) AS branch_name,
        (
            SELECT COUNT(*)
            FROM collection nbc
            WHERE
                $cndtn 
                AND nbc.insert_login_id = u.id 
                AND nbc.collection_date > COALESCE(
                    (
                        SELECT created_on
                        FROM accounts_collect_entry
                        WHERE user_id = u.id AND $cndtn
                        ORDER BY id DESC
                        LIMIT 1
                    ), '1970-01-01 00:00:00'
                ) 
                AND nbc.collection_date <= NOW()
        ) AS no_of_customers,
        SUM(c.collection_amount) AS total_amount,
        '1' AS TYPE
    FROM
        collection c
    LEFT JOIN users u ON c.insert_login_id = u.id
    LEFT JOIN group_creation gc ON c.group_id = gc.grp_id
    LEFT JOIN branch_creation bc ON FIND_IN_SET(bc.id, gc.branch)
    LEFT JOIN (
        SELECT ace.user_id, ace.collection_amnt
        FROM accounts_collect_entry ace
        ORDER BY id DESC
        LIMIT 1
    ) AS last_collection ON c.insert_login_id = last_collection.user_id
    WHERE
        $cndtn
        AND c.collection_date > COALESCE(
            (
                SELECT created_on
                FROM accounts_collect_entry
                WHERE user_id = u.id AND $cndtn
                ORDER BY id DESC
                LIMIT 1
            ), '1970-01-01 00:00:00'
        ) 
        AND c.collection_date <= NOW()
        AND c.insert_login_id = u.id
    GROUP BY u.id
),
second_query AS (
    SELECT 
        us.id AS userid, 
        us.name, 
        ac.branch, 
        SUM(ac.no_of_customers) AS no_of_customers,  
        SUM(ac.collection_amnt) AS total_amount,
        '2' AS type
    FROM 
        accounts_collect_entry ac
    JOIN users us ON ac.user_id = us.id
    WHERE 
        $cndtn 
        AND DATE(ac.created_on) = CURDATE() 
        AND ac.user_id NOT IN (
            SELECT userid 
            FROM first_query
        )
    GROUP BY us.id
)
SELECT userid, name, branch_name, no_of_customers, total_amount, type
FROM (
    SELECT * FROM first_query
    UNION ALL
    SELECT * FROM second_query
) AS subqry 
ORDER BY userid ASC;
");

if ($qry->rowCount() > 0) {
    while ($data = $qry->fetch(PDO::FETCH_ASSOC)) {
        $disabled = ($data['type'] == 2) ? 'disabled' : ''; // 1 - enabled; 2 - disabled
        $data['total_amount'] = moneyFormatIndia($data['total_amount']);
        $data['action'] = "<a href='#' class='collect-money' value='" . $data['userid'] . "'><button class='btn btn-primary' " . $disabled . ">Collect</button></a> ";
        $collection_list_arr[] = $data;
    }
}

echo json_encode($collection_list_arr);

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
