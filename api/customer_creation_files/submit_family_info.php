<?php
require '../../ajaxconfig.php';
@session_start();

$cus_id = $_POST['cus_id']; // Add this line to get the customer ID
$fam_name = $_POST['fam_name'];
$fam_relationship = $_POST['fam_relationship'];
$fam_age = $_POST['fam_age'];
$fam_live = $_POST['fam_live'];
$fam_occupation = $_POST['fam_occupation'];
$fam_aadhar = $_POST['fam_aadhar'];
$fam_mobile = $_POST['fam_mobile'];
$user_id = $_SESSION['user_id']; // Corrected session variable name
$family_id = $_POST['family_id'];

if ($family_id != '') {
    $qry = $pdo->query("UPDATE `family_info` SET `cus_id`='$cus_id', `fam_name`='$fam_name', `fam_relationship`='$fam_relationship', `fam_age`='$fam_age', `fam_live`='$fam_live', `fam_occupation`='$fam_occupation', `fam_aadhar`='$fam_aadhar', `fam_mobile`='$fam_mobile', `update_login_id`='$user_id', updated_on = now() WHERE `id`='$family_id'");
    $result = 0; // Update
} else {
    $qry = $pdo->query("INSERT INTO `family_info`(`cus_id`, `fam_name`, `fam_relationship`, `fam_age`, `fam_live`, `fam_occupation`, `fam_aadhar`, `fam_mobile`, `insert_login_id`, `created_on`) VALUES ('$cus_id', '$fam_name', '$fam_relationship', '$fam_age', '$fam_live', '$fam_occupation', '$fam_aadhar', '$fam_mobile', '$user_id', now())");
    $result = 1; // Insert
}

echo json_encode($result);
?>
