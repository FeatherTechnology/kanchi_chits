<?php
require "../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$user_code = $_POST['user_code'];
$role = $_POST['role'];
$address = $_POST['address'];
$place = $_POST['place'];
$email = $_POST['email'];
$mobile_no = $_POST['mobile_no'];
$user_name = $_POST['user_name'];
$password = $_POST['password'];
$branch_name = implode(',', $_POST['branch_name']);
$designation = $_POST['designation'];
$occ_detail = $_POST['occ_detail'];
$collection_access = $_POST['collection_access'];
$submenus = implode(',', $_POST['submenus']);
$id = $_POST['id'];

$qry = $pdo->query("SELECT * FROM users WHERE REPLACE(TRIM(user_name), ' ', '') = REPLACE(TRIM('$user_name'), ' ', '') AND `user_code` !='$user_code' ");
if ($qry->rowCount() > 0) {
    $status ='3';
    $last_id = '0'; //Already exists.
} else {
    if ($id != '0' && $id != '') {
        $qry = $pdo->query("UPDATE `users` SET `name`='$name',`user_code`='$user_code',`role`='$role',`address`='$address',`place`='$place',`email`='$email',`mobile`='$mobile_no',`user_name`='$user_name',`password`='$password',`branch`='$branch_name',`designation`='$designation',`occ_detail`='$occ_detail',`collection_access`='$collection_access',`screens`='$submenus',`update_login_id`='$user_id',`updated_on`=now() WHERE `id`='$id'");
        if ($qry) {
            $status = '1';
            $last_id = $id;
        }
    } else {
        $qry = $pdo->query("INSERT INTO `users`(`name`, `user_code`, `role`, `address`, `place`, `email`, `mobile`, `user_name`, `password`, `branch`, `designation`,`occ_detail`,`collection_access`,`screens`, `insert_login_id`, `created_on`) VALUES ('$name','$user_code','$role','$address','$place','$email','$mobile_no','$user_name','$password','$branch_name','$designation','$occ_detail','$collection_access','$submenus','$user_id',now())");
        if ($qry) {
            $status ='2'; 
            $last_id = $pdo->lastInsertId();
        }
    }
}
$result = array('status'=>$status, 'last_id'=> $last_id);
echo json_encode($result);