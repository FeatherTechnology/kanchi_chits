<?php
require '../../ajaxconfig.php';

$doc_need_arr = array();
$cusProfileId = $_POST['cus_id'];
$qry = $pdo->query("SELECT id,occupation,occ_detail,occ_place,source,income FROM source where cus_id = '$cusProfileId' ");
if ($qry->rowCount() > 0) {
    while ($DocNeed_info = $qry->fetch(PDO::FETCH_ASSOC)) {
        $DocNeed_info['income'] = moneyFormatIndia($DocNeed_info['income']);
        $DocNeed_info['action'] = "<span class='icon-trash-2 sourceDeleteBtn' value='" . $DocNeed_info['id'] . "'></span>";
        $doc_need_arr[] = $DocNeed_info;
    }
}
$pdo = null; //Connection Close.
echo json_encode($doc_need_arr);
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
