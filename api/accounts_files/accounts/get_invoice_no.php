<?php
require "../../../ajaxconfig.php";

$qry = $pdo->query("SELECT invoice_id FROM expenses WHERE invoice_id !='' ORDER BY id DESC ");
if ($qry->rowCount() > 0) {
    $qry_info = $qry->fetch(); //YYMM001 / 2407001
    $invoice_no_f = substr($qry_info['invoice_id'], 0, 4);
    $invoice_no_s = substr($qry_info['invoice_id'], 4, 3);
    $final_code = str_pad($invoice_no_s + 1, 3, 0, STR_PAD_LEFT);
    $invoice_no_final = $invoice_no_f . $final_code;
} else {
    $invoice_no_final = date('y') . date('m') . "001";
}
echo json_encode($invoice_no_final);
