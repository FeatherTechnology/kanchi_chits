<?php
require '../../ajaxconfig.php';
$column = array(
    'bc.id',
    'bc.chit_value',
    'bc.total_month'
);
$query="SELECT bc.id,bc.chit_value, bc.total_month FROM enquiry_creation bc WHERE 1";
if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $search = $_POST['search'];
        $query .= " AND (bc.chit_value LIKE '" . $search . "%'
                      OR bc.total_month LIKE '%" . $search . "%'";
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
    $sub_array[] = isset($row['chit_value']) ? moneyFormatIndia($row['chit_value']): '';
    $sub_array[] = isset($row['total_month']) ? $row['total_month'] : '';
    $action = "<span class='icon-border_color enquiryActionBtn' value='" . $row['id'] . "'></span>&nbsp;&nbsp;&nbsp;<span class='icon-delete enquiryDeleteBtn' value='" . $row['id'] . "'></span>";
    $sub_array[] = $action;
    $data[] = $sub_array;
   

}
function count_all_data($pdo)
{
    $query = "SELECT COUNT(*) FROM enquiry_creation";
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


function moneyFormatIndia($num) {
    $explrestunits = "";
    if(strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; 
        $expunit = str_split($restunits, 2);
        for($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2 unit pairs, adds a comma
            if($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ","; // if first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}
?>
