<?php
require "../../ajaxconfig.php";

$cus_map_arr = array();
$group_id = $_POST['group_id'];
$qry = $pdo->query("SELECT
    gs.id,
    gcm.id as cus_map_id,
    gcm.map_id,
    cc.cus_id,
    CONCAT(cc.first_name, ' ', cc.last_name) AS name,
    pl.place,
    (
    SELECT
        GROUP_CONCAT(occupation SEPARATOR ', ')
    FROM SOURCE
WHERE
    cus_id = cc.cus_id
) AS occ,
gcm.joining_month,
gs.share_value,
gs.share_percent
FROM
    `group_cus_mapping` gcm
JOIN group_share gs ON
    gs.cus_mapping_id = gcm.id
JOIN customer_creation cc ON
    gs.cus_id = cc.id
JOIN place pl ON
    cc.place = pl.id
WHERE
    gcm.grp_creation_id = '$group_id' order by map_id asc");
if ($qry->rowCount() > 0) {
    while ($gcm_info = $qry->fetch(PDO::FETCH_ASSOC)) {
        $gcm_info['action'] = "<span class='icon-trash-2 cusMapDeleteBtn' value='" . $gcm_info['id'] . "-" . $gcm_info['cus_map_id'] . "'></span>";
        $cus_map_arr[] = $gcm_info;
    }
}
$pdo = null; //Connection Close.
echo json_encode($cus_map_arr);