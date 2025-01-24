<?php
require '../../ajaxconfig.php';

$company_list_arr = array();
$qry = $pdo->query("SELECT cc.id, cc.company_name, cc.place, dt.district_name, cc.mobile FROM company_creation cc LEFT JOIN districts dt ON cc.district = dt.id LEFT JOIN taluks tk ON cc.taluk = tk.id WHERE cc.status=1");
if ($qry->rowCount() > 0) {
    while ($companyInfo = $qry->fetch(PDO::FETCH_ASSOC)) {
        $companyInfo['action'] = "<span class='icon-border_color companyActionBtn' value='" . $companyInfo['id'] . "'></span>";
        $company_list_arr[] = $companyInfo; // Append to the array
    }
}

$pdo = null; //Close Connection.

echo json_encode($company_list_arr);
