<?php
include "../../ajaxconfig.php";
@session_start();
$branch_id = $_SESSION['branch_id'];
$user_id = $_SESSION['user_id'];

// Primary query
$mainQry = $pdo->query("SELECT 
        gc.grp_id
    FROM 
        group_creation gc
    LEFT JOIN 
        auction_details ad ON gc.grp_id = ad.group_id
    WHERE 
        ad.date = CURDATE() AND 
        gc.branch IN ($branch_id) AND
        gc.status BETWEEN 2 AND 3 ");

$hasData = false;

while($result = $mainQry->fetch()){
    $grp_id = $result['grp_id'];
    
    // Secondary query
    $subQry = $pdo->query("SELECT CONCAT(cc.first_name,' ',cc.last_name) AS customer_name, cc.mobile1 
        FROM group_cus_mapping gcm 
        JOIN customer_creation cc ON gcm.cus_id = cc.id 
        WHERE gcm.grp_creation_id ='$grp_id' 
        GROUP BY cc.id;");
    $data = $subQry->fetchAll();

    if (!empty($data)) {
        $hasData = true; // Set flag if any data is found
    }

    foreach($data as $row){
        $customer_name = $row['customer_name'];
        $cus_mobile1 = $row['mobile1'];

        // Example of SMS API integration - uncomment to use
        // $message = "";
        // $templateid = ''; // FROM DLT PORTAL
        // $apiKey = ''; // Your API Key
        // $sender = ''; // Your Sender ID

        // $data = 'access_token='.$apiKey.'&to='.$cus_mobile1.'&message='.$message.'&service=T&sender='.$sender.'&template_id='.$templateid;
        // $url = 'https://sms.messagewall.in/api/v2/sms/send?'.$data;
        // $response = file_get_contents($url);
        // Process your response here if needed
    }

    $pdo->query("INSERT INTO `sms_remider_history`(`grp_id`, `insert_login_id`, `created_on`) VALUES ('$grp_id','$user_id',now() ) ");
}

$return_data = $hasData ? 1 : 2;
echo json_encode($return_data);
?>
