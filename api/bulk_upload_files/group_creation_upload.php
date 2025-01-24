<?php
require '../../ajaxconfig.php';
include 'groupUploadClass.php';
require_once('../../vendor/csvreader/php-excel-reader/excel_reader2.php');
require_once('../../vendor/csvreader/SpreadsheetReader_XLSX.php');


$obj = new groupUploadClass();

$allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'text/csv', 'text/xml', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
if (in_array($_FILES["excelFile"]["type"], $allowedFileType)) {

    $excelfolder = $obj->uploadFiletoFolder();

    $Reader = new SpreadsheetReader_XLSX($excelfolder);
    $sheetCount = count($Reader->sheets());

    for ($i = 0; $i < $sheetCount; $i++) {

        $Reader->ChangeSheet($i);
        $rowChange = 0;
        foreach ($Reader as $Row) {
            if ($rowChange != 0) { // omitted 0,1 to avoid headers

                $data = $obj->fetchAllRowData($Row);
                $data['grp_id'] = isset($data['grp_id']) ? $data['grp_id'] : '';
                if (isset($data['grp_id'])) {
                    $data['grp_id'] = $obj->getGroupCode($pdo, $data['grp_id']);
                }

                
                $data['branch_id'] = $obj->getBranchId($pdo, $data['branch']);

                $err_columns = $obj->handleError($data);
                if (empty($err_columns)) {
                    // Call LoanEntryTables function
                    $obj->GroupTable($pdo, $data);
                } else {
                    $errtxt = "Please Check the input given in Serial No: " . ($rowChange) . " on below. <br><br>";
                    $errtxt .= "<ul>";
                    foreach ($err_columns as $columns) {
                        $errtxt .= "<li>$columns</li>";
                    }
                    $errtxt .= "</ul><br>";
                    $errtxt .= "Insertion completed till Serial No: " . ($rowChange - 1);
                    echo $errtxt;
                    exit();
                }
            }

            $rowChange++;
        }
    }
    $message = 'Bulk Upload Completed.';
} else {
    $message = 'File is not in Excel Format.';
}

echo $message;
