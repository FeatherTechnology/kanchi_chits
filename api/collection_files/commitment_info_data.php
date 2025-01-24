<?php
require '../../ajaxconfig.php';

$property_list_arr = array();
$cusMappingID = $_POST['cus_mapping_id'];
$share_id = $_POST['share_id'];
$groupId =$_POST['group_id'];
$i = 0;
$qry = $pdo->query("SELECT id, label,commitment_date, remark,created_on
FROM commitment_info
 WHERE cus_mapping_id = '$cusMappingID' AND group_id='$groupId' AND share_id = '$share_id'");

if ($qry->rowCount() > 0) {
    while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
        $row['created_on'] = date('d-m-Y', strtotime($row['created_on']));
        $row['commitment_date'] = date('d-m-Y', strtotime($row['commitment_date']));
        $row['action'] = "<span class='icon-delete commitDeleteBtn' value='" . $row['id'] . "'></span>";

        $property_list_arr[$i] = $row; // Append to the array
        $i++;
    }
}

echo json_encode($property_list_arr);
$pdo = null; // Close Connection