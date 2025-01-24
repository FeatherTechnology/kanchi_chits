<?php
require '../../ajaxconfig.php';

$bank_list_arr = array();
$i = 0;
$status_arr = ['Inactive', 'Active']; // Ensure 'Inactive' and 'Active' are correctly indexed

$qry = $pdo->prepare("SELECT id, bank_name, account_number, branch_name, status FROM bank_creation");
if ($qry->execute()) {
    while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
        // Debug status value
        error_log('Status Value: ' . $row['status']); // Log the status value for debugging

        $bank_list_arr[$i]['id'] = $row['id'];
        $bank_list_arr[$i]['bank_name'] = $row['bank_name'];
        $bank_list_arr[$i]['account_number'] = $row['account_number'];
        $bank_list_arr[$i]['branch_name'] = $row['branch_name'];
        
        // Check if status index exists in the status array
        if (isset($status_arr[$row['status']])) {
            $bank_list_arr[$i]['status'] = $status_arr[$row['status']];
        } else {
            $bank_list_arr[$i]['status'] = 'Unknown'; // Fallback status
        }

        $action_buttons = "<span class='icon-border_color bankActionBtn' data-value='" . $row['id'] . "'></span>";
        $action_buttons .= "&nbsp;&nbsp;&nbsp;<span class='icon-delete bankDeleteBtn' data-value='" . $row['id'] . "'></span>";
        $action_buttons .= "&nbsp;&nbsp;&nbsp;<span class='icon-check1 bankActiveBtn' data-value='" . $row['id'] . "'></span>";
        
        $bank_list_arr[$i]['action'] = $action_buttons;
        $i++;
    }
}

echo json_encode($bank_list_arr);
$pdo = null; // Close Connection
?>



