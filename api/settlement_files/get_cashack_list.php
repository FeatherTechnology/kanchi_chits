<?php
require "../../ajaxconfig.php";

// Get the auction_id from POST data
$auction_id = isset($_POST['auction_id']) ? $_POST['auction_id'] : null;
$cus_id = isset($_POST['cus_id']) ? $_POST['cus_id'] : null;

if ($auction_id !== null) {
    // Directly embed the auction_id into the query
    $qry = $pdo->query("SELECT 
            si.id, 
            si.settle_date, 
            si.settle_cash, 
            si.cheque_val, 
            si.transaction_val, 
            gi.guarantor_name, 
            si.guarantor_relationship, 
            CONCAT(cc.first_name, ' ', cc.last_name) AS cus_name,
             CASE 
            WHEN si.settle_type = 1 THEN si.den_upload 
            ELSE NULL 
        END AS den_upload
        FROM 
            settlement_info si
        LEFT JOIN 
            guarantor_info gi ON si.guarantor_name = gi.id  
        LEFT JOIN auction_details ad ON si.auction_id = ad.id
           LEFT JOIN customer_creation cc ON si.cus_name = cc.id
        WHERE 
            si.auction_id = '$auction_id' AND cc.cus_id='$cus_id'
    ");

    // Check if any rows are returned
    if ($qry->rowCount() > 0) {
        $result = [];
        while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
            // Convert settle_date to dd-mm-yyyy format
            if (!empty($row['settle_date'])) {
                $date = new DateTime($row['settle_date']);
                $row['settle_date'] = $date->format('d-m-Y');
            }

            // Calculate total amount
            $total_amount = (
                ($row['settle_cash'] ? (float)$row['settle_cash'] : 0) +
                ($row['cheque_val'] ? (float)$row['cheque_val'] : 0) +
                ($row['transaction_val'] ? (float)$row['transaction_val'] : 0)
            );
            $row['balance_amount'] = moneyFormatIndia($total_amount);

            // Check if guarantor_name is null or -1
            if ($row['guarantor_name'] === null || $row['guarantor_name'] == -1) {
                $row['guarantor_name'] = $row['cus_name'];
            }
            if (!empty($row['den_upload'])) {
                $row['upload'] = "<a href='uploads/denomination_upload/{$row['den_upload']}' target='_blank'>
                                    <button type='button' class='btn btn-primary'>
                                        View
                                    </button>
                                  </a>";
            } else {
                $row['upload'] = ''; // No button if den_upload is empty or null
            }

            $result[] = $row;
        }
        // Output the result as JSON
        echo json_encode($result);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$pdo = null;
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


