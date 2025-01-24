<?php
require '../../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['user_id'];

$doc_name = $_POST['doc_name'];
$doc_type = $_POST['doc_type'];
$doc_holder_name = $_POST['doc_holder_name'];
$doc_relationship = $_POST['doc_relationship'];
$remarks = $_POST['remarks'];
$cus_id = $_POST['cus_id'];
$auction_id = $_POST['groupid'];
$id = $_POST['id'];


if (!empty($_FILES['doc_upload']['name'])) {
    $path = "../../uploads/doc_info/";
    $picture = $_FILES['doc_upload']['name'];
    $pic_temp = $_FILES['doc_upload']['tmp_name'];
    $picfolder = $path . $picture;
    $fileExtension = pathinfo($picfolder, PATHINFO_EXTENSION); //get the file extention
    $picture = uniqid() . '.' . $fileExtension;
    while (file_exists($path . $picture)) {
        //this loop will continue until it generates a unique file name
        $picture = uniqid() . '.' . $fileExtension;
    }
    move_uploaded_file($pic_temp, $path . $picture);
} else {
    $picture = (isset($_POST['doc_upload_edit'])) ? $_POST['doc_upload_edit'] : '';
}

$status = 0;
if ($id != '') {
    $qry = $pdo->query("UPDATE `document_info` SET `cus_id`='$cus_id',`auction_id`='$auction_id',`doc_name`='$doc_name',`doc_type`='$doc_type',`holder_name`='$doc_holder_name',`relationship`='$doc_relationship',`remarks`='$remarks',`upload`='$picture',`update_login_id`='$user_id',`updated_on`=now() WHERE `id`='$id' ");
    $status = 1; //update
} else {
    $qry = $pdo->query("INSERT INTO `document_info`(`cus_id`,`auction_id`,`doc_name`, `doc_type`, `holder_name`, `relationship`, `remarks`,`upload`, `insert_login_id`, `created_on`) VALUES ('$cus_id','$auction_id','$doc_name','$doc_type','$doc_holder_name','$doc_relationship','$remarks','$picture','$user_id',now())");
    $status = 2; //Insert
}

echo json_encode($status);
