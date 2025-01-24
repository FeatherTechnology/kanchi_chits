<?php
require '../../ajaxconfig.php';
include 'customerUploadClass.php';
require_once('../../vendor/csvreader/php-excel-reader/excel_reader2.php');
require_once('../../vendor/csvreader/SpreadsheetReader_XLSX.php');


$obj = new customerUploadClass();

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
                $data['cus_id'] = isset($data['cus_id']) ? $data['cus_id'] : '';
                if (isset($data['cus_id'])) {
                    $data['cus_id'] = $obj->getcusId($pdo, $data['cus_id']);
                }
                $grp_id = $obj->groupName($pdo, $data['grp_name']);
                $data['grp_id'] = $grp_id;

                $err_columns = $obj->handleError($data);
                if (empty($err_columns)) {
                    // Call FamilyTable function
                  
                
                    // Call PlaceTable function and retrieve place ID
                    $obj->PlaceTable($pdo, $data);
                    $pl_id = $obj->placeName($pdo, $data['place']);
                    $data['pl_id'] = $pl_id;
                
                    // Call remaining functions in sequence
                    
                    $obj->customerEntryTables($pdo, $data);
                    $cust_id = $obj->getcustomerId($pdo,$data['aadhar_number']);
                    $data['cust_id'] = $cust_id;
                    $obj->FamilyTable($pdo, $data);
                    $gur_id = $obj->guarantorName($pdo, $data['guarantor_aadhar'], $data['aadhar_number'],$data['fam_name']);
                    $data['gur_id'] = $gur_id; // Store the returned gur_id in the data array
                                     
                    $obj->guarantorTable($pdo, $data);
                    $obj->sourceTable($pdo, $data);
                    $obj->cusMappingTable($pdo, $data);
                    $map_id = $obj->MappingID($pdo,$data['mapping_id']);
                    $data['map_id'] = $map_id;
                    $obj->cusShareTable($pdo, $data);
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
