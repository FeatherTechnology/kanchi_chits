<?php
require('../../ajaxconfig.php');

$response = array();

$company_name = $_POST["company_name"];

try {

    $str = preg_replace('/\s+/', '', $company_name);
    $myStr = mb_substr($str, 0, 1);

    // Check if branch codes exist in the database
    $qry1 = $pdo->query("SELECT branch_code FROM branch_creation WHERE branch_code != '' ORDER BY branch_code DESC LIMIT 1");
    if ($qry1->rowCount() > 0) {
        // If branch codes exist, generate a new branch code
        $row = $qry1->fetch(PDO::FETCH_ASSOC);
        $ac2 = $row["branch_code"];
        $appno2 = ltrim(strstr($ac2, '-'), '-');
        $appno2 = $appno2 + 1;
        $branch_code = $myStr . "-" . $appno2;
    } else {
        // If no branch codes exist, set an initial one
        $initialapp = $myStr . "-101";
        $branch_code = $initialapp;
    }

    // Store the branch code in the response array
    $response['branch_code'] = $branch_code;

} catch (PDOException $e) {
    // Handle PDO exceptions
    $response['error'] = $e->getMessage();
}

    // Return the response as JSON
    echo json_encode($response);
?>



