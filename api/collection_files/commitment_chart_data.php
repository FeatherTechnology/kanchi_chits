<?php
require '../../ajaxconfig.php';

$property_list_arr = array();
$share_id =$_POST['share_id'];
$cusMappingID = $_POST['cus_mapping_id'];
$groupId =$_POST['group_id'];

$i = 0;
$qry = $pdo->query("SELECT id, created_on, label,commitment_date, remark FROM commitment_info WHERE cus_mapping_id = '$cusMappingID' AND group_id='$groupId' AND share_id='$share_id'");

if ($qry->rowCount() > 0) {
    while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
        
        // Format the created_on date to dd-mm-yyyy
        if (!empty($row['created_on'])) {
            $date = new DateTime($row['created_on']);
            $row['created_on'] = $date->format('d-m-Y'); // Format to dd-mm-yyyy
        }
        if (!empty($row['commitment_date'])) {
            $date = new DateTime($row['commitment_date']);
            $row['commitment_date'] = $date->format('d-m-Y'); // Format to dd-mm-yyyy
        }
        $property_list_arr[$i] = $row; // Append to the array
        $i++;
    }
}

echo json_encode($property_list_arr);
$pdo = null; // Close Connection
