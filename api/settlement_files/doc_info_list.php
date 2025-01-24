<?php
require "../../ajaxconfig.php";

$doc_info_arr = array();
$cus_id = $_POST['cus_id'];
$auction_id = $_POST['auction_id'];
$qry = $pdo->query("SELECT
    gc.grp_name,
    ad.group_id,
    ad.auction_month,
    di.id AS d_id,
    di.*,
    IFNULL(
        gi.`guarantor_name`,
        CONCAT(cc.first_name, ' ', cc.last_name)
    ) AS guarantor_name,
    gi.guarantor_relationship
FROM
    document_info di
LEFT JOIN guarantor_info gi ON
    di.holder_name = gi.id
LEFT JOIN customer_creation cc ON
    di.cus_id = cc.cus_id
    LEFT JOIN auction_details ad ON di.auction_id = ad.id
LEFT JOIN group_creation gc ON ad.group_id=gc.grp_id WHERE di.cus_id = '$cus_id' AND di.auction_id ='$auction_id' ");
if ($qry->rowCount() > 0) {
    while ($doc_info = $qry->fetch(PDO::FETCH_ASSOC)) {
        $doc_info['doc_type'] = ($doc_info['doc_type'] == '1') ? 'Original' : 'Xerox';
        $doc_info['upload'] = "<a href='uploads/doc_info/".$doc_info['upload']."' target='_blank'>".$doc_info['upload']."</a>";
        $doc_info['action'] = "<span class='icon-border_color docActionBtn' value='" . $doc_info['d_id'] . "'></span> <span class='icon-trash-2 docDeleteBtn' value='" . $doc_info['d_id'] . "'></span>";
        $doc_info_arr[] = $doc_info;
    }
}
$pdo = null; //Connection Close.
echo json_encode($doc_info_arr);