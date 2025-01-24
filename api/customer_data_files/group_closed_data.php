<?php
require '../../ajaxconfig.php';
@session_start();

$id = $_POST['id'];
include '../collection_files/collectionStatus.php';
$collectionSts = new CollectionStsClass($pdo);
$group_status = [4 => 'Closed',5=> 'Closed'];

$query = "SELECT
    ad.id AS auction_id,
    cc.id AS customer_id,
    gc.grp_id,
    gc.grp_name,
    gc.chit_value,
    gc.status as grp_status,
    ad.chit_amount,
    ad.auction_month,
    ad.date AS due_date,
    gcm.id AS cus_mapping_id,
    gs.id AS share_id,
   gs.settle_status,
    cc.cus_id,
    gc.grace_period
FROM
    auction_details ad
LEFT JOIN group_creation gc ON
    ad.group_id = gc.grp_id
LEFT JOIN group_share gs ON
    ad.group_id = gs.grp_creation_id
  LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
LEFT JOIN customer_creation cc ON
    gs.cus_id = cc.id
WHERE
     gc.status BETWEEN 4 AND 5
    AND cc.id = '$id' GROUP BY gs.id";

$result = [];
$statement = $pdo->prepare($query); // Use query instead of prepare + execute
$statement->execute();

if ($statement->rowCount() > 0) {

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $sub_array = [];

        // Grace Period Calculation
        $chit_amount = $row['chit_amount'] ?? 0;
        $auction_month = $row['auction_month'] ?? 0;
        $status = $collectionSts->updateCollectionStatus($row['share_id'], $row['grp_id']);
        $sub_array['status'] = $status;

        // Check payment status for all customers in the group
        $customer_mapping_query = "SELECT id FROM group_share 
                                   WHERE grp_creation_id = '{$row['grp_id']}'";
        $customer_mapping_stmt = $pdo->query($customer_mapping_query);
        $customer_ids = $customer_mapping_stmt->fetchAll(PDO::FETCH_COLUMN);

        $all_paid = true;
        foreach ($customer_ids as $cus_id) {
            $payment_status_query = "SELECT coll_status FROM  group_share
                                     WHERE grp_creation_id = '{$row['grp_id']}'
                                     AND id = '$cus_id'";
            $payment_status_stmt = $pdo->query($payment_status_query);
            $payment_status = $payment_status_stmt->fetchColumn();
            if ($payment_status !== 'Paid') {
                $all_paid = false;
                break;
            }
        }

        // Update the group's collection status based on payments
        if ($all_paid) {
            $sub_array['collection_status'] = 'Completed';
        } else {
            $sub_array['collection_status'] = 'In Collection';
        }

        // Add other relevant data to sub_array
        $sub_array['id'] = $row['auction_id'];
        $sub_array['cus_mapping_id'] = $row['cus_mapping_id'];
        $sub_array['customer_id'] = $row['customer_id'];
        $sub_array['grp_id'] = $row['grp_id'];
        $sub_array['grp_name'] = $row['grp_name'];
        $sub_array['chit_value'] = moneyFormatIndia($row['chit_value']);
        $sub_array['grp_status'] = $group_status[$row['grp_status']];
        $sub_array['settle_status'] = $row['settle_status'];
        $sub_array['charts'] = "<div class='dropdown'>
                                    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
                                    <div class='dropdown-content'>
                                        <a href='#' class='add_due' data-value='{$row['grp_id']}_{$row['cus_mapping_id']}_{$row['auction_month']}_{$row['share_id']}'>Due Chart</a>
                                        <a href='#' class='commitment_chart' data-value='{$row['grp_id']}_{$row['cus_mapping_id']}_{$row['share_id']}'>Commitment Chart</a>
                                    </div>
                                </div>";

        $result[] = $sub_array;
    }
}

echo json_encode($result);


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
