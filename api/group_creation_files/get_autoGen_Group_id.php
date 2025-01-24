
<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];
if ($id != '0' && $id != '') {
    $qry = $pdo->query("SELECT grp_id FROM group_creation WHERE id = '$id'");
    $qry_info = $qry->fetch();
    $cus_ID_final = $qry_info['grp_id'];
} else {
    $qry = $pdo->query("SELECT grp_id FROM group_creation WHERE grp_id != '' ORDER BY id DESC LIMIT 1");
    if ($qry->rowCount() > 0) {
        $qry_info = $qry->fetch(); // G-101
        $l_no = ltrim(strstr($qry_info['grp_id'], '-'), '-');
        $l_no = $l_no + 1;
        $cus_ID_final = "G-" . $l_no;
    } else {
        $cus_ID_final = "G-101";
    }
}
echo json_encode($cus_ID_final);
?>
