
<?php
require '../../ajaxconfig.php';

$column = array(
    'bc.id',
    'bc.branch_code',
    'bc.company_name',
    'bc.branch_name',
    'bc.place',
    'st.state_name',
    'dt.district_name',
    'bc.mobile_number',
    'bc.email_id',
    'bc.id'
);
$query = "SELECT bc.id, bc.branch_code, bc.company_name, bc.branch_name, bc.place, st.state_name, dt.district_name, bc.mobile_number, bc.email_id  FROM branch_creation bc LEFT JOIN States st ON bc.state = st.id LEFT JOIN districts dt ON bc.district = dt.id LEFT JOIN taluks tk ON bc.taluk = tk.id WHERE 1 ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $search = $_POST['search'];
        $query .= " AND (bc.branch_code LIKE '" . $search . "%'
                      OR bc.branch_name LIKE '%" . $search . "%'
                      OR bc.company_name LIKE '%" . $search . "%'
                      OR bc.place LIKE '%" . $search . "%'
                      OR bc.mobile_number LIKE '%" . $search . "%'
                      OR bc.email_id LIKE '%" . $search . "%')";
    }
}
if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}
$query1 = '';
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
    $sub_array[] = isset($row['branch_code']) ? $row['branch_code'] : '';
    $sub_array[] = isset($row['company_name']) ? $row['company_name'] : '';
    $sub_array[] = isset($row['branch_name']) ? $row['branch_name'] : '';
    $sub_array[] = isset($row['place']) ? $row['place'] : '';
    $sub_array[] = isset($row['state_name']) ? $row['state_name'] : '';
    $sub_array[] = isset($row['district_name']) ? $row['district_name'] : '';
    $sub_array[] = isset($row['mobile_number']) ? $row['mobile_number'] : '';
    $sub_array[] = isset($row['email_id']) ? $row['email_id'] : '';

    $action = "<span class='icon-border_color branchActionBtn' value='" . $row['id'] . "'></span>&nbsp;&nbsp;&nbsp;<span class='icon-delete branchDeleteBtn' value='" . $row['id'] . "'></span>";
    $sub_array[] = $action;

    $data[] = $sub_array;
}

function count_all_data($pdo)
{
    $query = "SELECT COUNT(*) FROM branch_creation";
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
?>


