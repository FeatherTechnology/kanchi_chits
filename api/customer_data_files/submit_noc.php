<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$doc_id = isset($_POST['docId']) ? $_POST['docId'] : [];  // This is an array
$date_of_noc = $_POST['date_of_noc'];
$noc_member = $_POST['noc_member'];
$noc_relation = $_POST['noc_relation'];
$grp_id = $_POST['grp_id'];
$cus_id = $_POST['cus_id'];
$doc_list_cnt = $_POST['doc_list_cnt'];

// Update document info for each doc_id in the array
foreach ($doc_id as $did) {
    $qry = $pdo->query("UPDATE `document_info` 
                        SET `noc_status`='1', `date_of_noc`='$date_of_noc', `noc_member`='$noc_member', `noc_relationship`='$noc_relation', `update_login_id`='$user_id', `updated_on`=now() 
                        WHERE `id`='$did'");
}

// Get document count
$d_qry = $pdo->query("SELECT * FROM document_info di 
                      JOIN auction_details ad ON di.auction_id = ad.id 
                      WHERE di.cus_id = '$cus_id' AND ad.group_id = '$grp_id' AND noc_status = '1'");
$doc_count = $d_qry->rowCount(); // Count of documents

// Determine document status
if($doc_count == $doc_list_cnt){
    $doc_sts = 2; // All documents submitted
} else if($doc_count < $doc_list_cnt && $doc_count != 0 ){
    $doc_sts = 1; // Some documents submitted
} else {
    $doc_sts = 0; // No documents submitted
}

// Set NOC status
$status = ($doc_sts == 2) ? '2' : '1';


$doc_ids_str = implode(',', $doc_id); // Convert array to comma-separated string

$qry2 = $pdo->query("SELECT * FROM `noc` WHERE `doc_id` IN ($doc_ids_str)");
if($qry2->rowCount() > 0){
    // If record exists, update it
    $qry = $pdo->query("UPDATE `noc` 
                        SET `cus_id`='$cus_id', `document_list`='$doc_sts', `noc_status`='$status', `update_login_id`='$user_id', `updated_on`=now() 
                        WHERE `doc_id` IN ($doc_ids_str)");
} else {
    // Insert a new `noc` record
    foreach ($doc_id as $did) { // Insert for each doc_id
        $qry = $pdo->query("INSERT INTO `noc`(`doc_id`, `cus_id`, `document_list`, `noc_status`, `insert_login_id`, `created_on`) 
                            VALUES ('$did', '$cus_id', '$doc_sts', '$status', '$user_id', now())");
        $last_id = $pdo->lastInsertId();

        // Insert into `noc_ref` table
        $pdo->query("INSERT INTO `noc_ref`(`noc_id`, `date_of_noc`, `noc_member`, `noc_relationship`, `created_on`) 
                     VALUES ('$last_id', '$date_of_noc', '$noc_member', '$noc_relation', now())");
    }
}

$result = 0;
if ($qry) {
    $result = 1;
}

echo json_encode($result);
