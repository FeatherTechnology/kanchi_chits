<?php
require '../../ajaxconfig.php';
include 'auctionUploadClass.php';
require_once('../../vendor/csvreader/php-excel-reader/excel_reader2.php');
require_once('../../vendor/csvreader/SpreadsheetReader_XLSX.php');


$obj = new auctionUploadClass();

$allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'text/csv', 'text/xml', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
if (in_array($_FILES["excelFile"]["type"], $allowedFileType)) {

    $excelfolder = $obj->uploadFiletoFolder();

    $Reader = new SpreadsheetReader_XLSX($excelfolder);
    $sheetCount = count($Reader->sheets());

    for ($i = 0; $i < $sheetCount; $i++) {

        $Reader->ChangeSheet($i);
        $rowChange = 0;
        foreach ($Reader as $Row) {
            if ($rowChange != 0) { // omitted 0

                $data = $obj->fetchAllRowData($Row);
                $grp_id = $obj->groupName($pdo, $data['grp_name']);
                $data['grp_id'] = $grp_id;
                $cust_id = $obj->getcustomerId($pdo,  $data['aadhar_number']);
                $data['cust_id'] = $cust_id;
                $map_id = $obj->MappingID($pdo,$data['mapping_id']);
                $data['map_id'] = $map_id;
                $data['bank_id'] = $obj->getBankId($pdo, $data['bank_name']);
                $gur_id = $obj->guarantorName($pdo,$data['guarantor_aadhar']);
                $data['gur_id'] = $gur_id;
                $err_columns = $obj->handleError($data);
                if (empty($err_columns)) {
                    // Call FamilyTable function
                    $obj->AuctionTable($pdo, $data);
                
                   
                    $auction_id = $obj->getAuctionId($pdo,$data['date'],$data['grp_id']);
                    $data['auction_id'] = $auction_id;
                
                    $obj-> auctionviewTable($pdo, $data);
                    $obj-> settlementTable($pdo, $data);
                }
                 else {
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
