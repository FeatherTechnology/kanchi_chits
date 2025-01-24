<?php
require '../../ajaxconfig.php';
$id=$_POST["params"];
$auction_status = [1 => 'New', 2 => 'Existing'];
$column = array(
    'bc.id',
    'bc.enquiry_creation_id',
    'bc.cus_name',
    'bc.cus_status',
    'bc.mobile_number',
    'bc.place',
    'bc.remarks'
);
$query="SELECT bc.id,bc.enquiry_creation_id,bc.cus_name, bc.cus_status,bc.mobile_number,bc.place,bc.remarks FROM enquiry_creation_customer bc WHERE bc.enquiry_creation_id='$id' ";
if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $search = $_POST['search'];
        $query .= " AND (bc.id LIKE '" . $search . "%'
                    OR bc.enquiry_creation_id LIKE '" . $search . "%'
                      OR bc.cus_name LIKE '%" . $search . "%'
                       OR bc.cus_status LIKE '%" . $search . "%'
                        OR bc.mobile_number LIKE '%" . $search . "%'
                         OR bc.place LIKE '%" . $search . "%'
                          OR bc.remarks LIKE '%" . $search . "%'";
    }
}
if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}
$query1="";
if (isset($_POST['length']) && $_POST['length'] != -1) {
    $query1 = ' LIMIT ' . intval($_POST['start']) . ', ' . intval($_POST['length']);
}
$statement = $pdo->prepare($query);
// echo $query;
$statement->execute();
$number_filter_row = $statement->rowCount();

$statement = $pdo->prepare($query . $query1);

$statement->execute();
$result = $statement->fetchAll();
$sno = isset($_POST['start']) ? $_POST['start'] + 1 : 1;
$data = [];
foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $sno++;
    $sub_array[] = isset($row['cus_name']) ? $row['cus_name'] : '';
    $sub_array[] = isset($row['cus_status']) ? $auction_status[ $row['cus_status'] ]: '';
    $sub_array[] = isset($row['mobile_number']) ? $row['mobile_number'] : '';
    $sub_array[] = isset($row['place']) ? $row['place'] : '';
    $sub_array[] = isset($row['remarks']) ? $row['remarks'] : '';
    $action = "<span class='icon-border_color customerActionBtn' value='" . $row['id'] . "'></span>&nbsp;&nbsp;&nbsp;<span class='icon-delete customerDeleteBtn' value='" . $row['id'] . "'></span>";
    $sub_array[] = $action;
    $data[] = $sub_array;
   

}
function count_all_data($pdo)
{
    $query = "SELECT COUNT(*) FROM enquiry_creation_customer";
    $statement = $pdo->prepare($query);
    $statement->execute();
    return $statement->fetchColumn();
}

$output = array(
    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    'recordsTotal' => count_all_data($pdo),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);