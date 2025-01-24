<?php
require '../../ajaxconfig.php';
@session_start();

if (!empty($_FILES['gu_pic']['name'])) {
    $paths= "../../uploads/customer_creation/gu_pic/";
    $gpicture = $_FILES['gu_pic']['name'];
    $pic_temp = $_FILES['gu_pic']['tmp_name'];
    $fileExtension = pathinfo($gpicture, PATHINFO_EXTENSION);
    $gpicture = uniqid() . '.' . $fileExtension;
    while (file_exists($paths . $gpicture)) {
        $gpicture = uniqid() . '.' . $fileExtension;
    }
    move_uploaded_file($pic_temp, $paths . $gpicture);
} else {
    $gpicture = $_POST['gur_pic'];
}

$cus_id = $_POST['cus_id'];
$relationship_type = $_POST['relationship_type'];
$guarantor_name = $_POST['guarantor_name'];
$existing_cus_id = isset($_POST['existing_cus_id']) ? $_POST['existing_cus_id'] : null;
$family_id = isset($_POST['family_id']) ? $_POST['family_id'] : null;
$guarantor_relationship = $_POST['guarantor_relationship'];
$details = $_POST['details'];
$user_id = $_SESSION['user_id'];
$guarantor_id = $_POST['guarantor_id'];

if ($guarantor_id != '') {
    // Update existing record
    $qry = $pdo->prepare("UPDATE `guarantor_info` SET `cus_id` = :cus_id, `relationship_type` = :relationship_type, `guarantor_name` = :guarantor_name, `existing_cus_id` = :existing_cus_id, `family_id` = :family_id, `guarantor_relationship` = :guarantor_relationship, `details` = :details, `gu_pic` = :gpicture, `update_login_id` = :user_id, `updated_on` = now() WHERE `id` = :guarantor_id");
    $qry->execute([
        'cus_id' => $cus_id,
        'relationship_type' => $relationship_type,
        'guarantor_name' => $guarantor_name,
        'existing_cus_id' => $existing_cus_id,
        'family_id' => $family_id,
        'guarantor_relationship' => $guarantor_relationship,
        'details' => $details,
        'gpicture' => $gpicture,
        'user_id' => $user_id,
        'guarantor_id' => $guarantor_id
    ]);
    $result = $qry->rowCount() > 0 ? 'Success' : 'Failed';
} else {
    // Insert new record
    $qry = $pdo->prepare("INSERT INTO `guarantor_info`(`cus_id`, `relationship_type`, `guarantor_name`, `existing_cus_id`, `family_id`, `guarantor_relationship`, `details`, `gu_pic`, `insert_login_id`, `created_on`) VALUES (:cus_id, :relationship_type, :guarantor_name, :existing_cus_id, :family_id, :guarantor_relationship, :details, :gpicture, :user_id, now())");
    $qry->execute([
        'cus_id' => $cus_id,
        'relationship_type' => $relationship_type,
        'guarantor_name' => $guarantor_name,
        'existing_cus_id' => $existing_cus_id,
        'family_id' => $family_id,
        'guarantor_relationship' => $guarantor_relationship,
        'details' => $details,
        'gpicture' => $gpicture,
        'user_id' => $user_id
    ]);
    $result = $qry->rowCount() > 0 ? 'Success' : 'Failed';
}

echo json_encode($result);
$pdo = null;
?>
