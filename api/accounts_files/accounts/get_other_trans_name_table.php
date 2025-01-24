<?php
require "../../../ajaxconfig.php";
@session_start();
$user_id = $_SESSION['user_id'];

$transCat = $_POST['transCat'];
$trans_cat = ["1"=>'Deposit', "2"=>'Investment', "3"=>'EL', "4"=>'Exchange', "5"=>'Bank Deposit', "6"=>'Bank Withdrawal', "7"=>'Chit Advance', "8"=>'Other Income', "9"=> 'Bank Unbilled'];
$name_list_arr = array();
$qry = $pdo->query("SELECT * FROM other_trans_name WHERE trans_cat = '$transCat' ");
if($qry->rowCount()>0){
    while($result = $qry->fetch()){
        $result['trans_cat'] = $trans_cat[$result['trans_cat']];
        $name_list_arr[] = $result;
    }
}

echo json_encode($name_list_arr);
?>
