<?php
require '../../ajaxconfig.php';
@session_start();
if (!empty($_FILES['pic']['name'])) {
    $path = "../../uploads/customer_creation/cus_pic/";
    $picture = $_FILES['pic']['name'];
    $pic_temp = $_FILES['pic']['tmp_name'];
    $picfolder = $path . $picture;
    $fileExtension = pathinfo($picfolder, PATHINFO_EXTENSION); //get the file extention
    $picture = uniqid() . '.' . $fileExtension;
    while (file_exists($path . $picture)) {
        //this loop will continue until it generates a unique file name
        $picture = uniqid() . '.' . $fileExtension;
    }
    move_uploaded_file($pic_temp, $path . $picture);

  
} else {
    $picture = $_POST['per_pic'];
}
$cus_id = $_POST['cus_id'];
$reference_type = $_POST['reference_type'];
$cus_name = $_POST['cus_name'];
$ref_cus_id=$_POST['ref_cus_id'];
$name = $_POST['name'];
$mobile = $_POST['mobile'];
$declaration = $_POST['declaration'];
$aadhar_number = $_POST['aadhar_number'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$dob = ($_POST['dob'] !='') ? $_POST['dob'] : '0000-00-00';
$age = ($_POST['age'] !='') ? $_POST['age'] : '0';
$place = $_POST['place'];
$mobile1 = $_POST['mobile1'];
$mobile2 = $_POST['mobile2'];
$whatsapp = $_POST['whatsapp'];
$address = $_POST['address'];
$native_address = $_POST['native_address'];
$tot_income = $_POST['tot_income'];
$chit_limit = $_POST['chit_limit'];
$reference = $_POST['reference'];
$customer_id = $_POST['customer_id'];
$user_id = $_SESSION['user_id'];

if ($customer_id != '') {
    $qry = $pdo->query("UPDATE `customer_creation` SET `reference_type`='$reference_type', `cus_name`='$cus_name',`ref_cus_id`='$ref_cus_id',`name`='$name', `mobile`='$mobile', `declaration`='$declaration',`cus_id`='$cus_id', `aadhar_number`='$aadhar_number', `first_name`='$first_name', `last_name`='$last_name', `dob`='$dob',`age`='$age',`place`='$place', `mobile1`='$mobile1', `mobile2`='$mobile2', `whatsapp`='$whatsapp', `address`='$address', `native_address`='$native_address',`pic`='$picture',`tot_income`='$tot_income', `chit_limit`='$chit_limit', `reference`='$reference', `update_login_id`='$user_id', updated_on = now() WHERE `id`='$customer_id'");
    $result = 0; // Update
} else {
    $qry = $pdo->query("INSERT INTO `customer_creation`(`reference_type`, `cus_name`,`ref_cus_id`, `name`, `mobile`, `declaration`, `cus_id`,`aadhar_number`, `first_name`, `last_name`,`dob`,`age`,`place`, `mobile1`, `mobile2`, `whatsapp`, `address`, `native_address`,`pic`, `tot_income`, `chit_limit`, `reference`, `insert_login_id`, `created_on`) VALUES ('$reference_type', '$cus_name','$ref_cus_id', '$name', '$mobile', '$declaration','$cus_id','$aadhar_number', '$first_name', '$last_name','$dob','$age','$place', '$mobile1', '$mobile2', '$whatsapp', '$address', '$native_address', '$picture','$tot_income', '$chit_limit', '$reference', '$user_id', now())");
    $result = 1; // Insert
}

echo json_encode($result);
?>
