<?php
require "../../ajaxconfig.php";
$id = $_POST['id'];
$qry = $pdo->query("DELETE FROM `enquiry_creation` WHERE id = '$id' ");
if($qry){
    $result = 1;
}
else{
    $result= 0;
}


echo json_encode($result);
?>


