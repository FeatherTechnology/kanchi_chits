<?php
require '../../ajaxconfig.php';
@session_start();

class customerUploadClass
{
    public function uploadFiletoFolder()
    {
        $excel = $_FILES['excelFile']['name'];
        $excel_temp = $_FILES['excelFile']['tmp_name'];
        $excelfolder = "../../uploads/excel_format/customer_format/" . $excel;

        $fileExtension = pathinfo($excelfolder, PATHINFO_EXTENSION); //get the file extension

        $excel = uniqid() . '.' . $fileExtension;
        while (file_exists("../../uploads/excel_format/customer_format/" . $excel)) {
            // this loop will continue until it generates a unique file name
            $excel = uniqid() . '.' . $fileExtension;
        }
        $excelfolder = "../../uploads/excel_format/customer_format/" . $excel;
        move_uploaded_file($excel_temp, $excelfolder);
        return $excelfolder;
    }

    public function fetchAllRowData($Row)
    {
        $dataArray = array(
            'first_name' => isset($Row[1]) ? $Row[1] : "",
            'last_name' => isset($Row[2]) ? $Row[2] : "",
            'aadhar_number' => isset($Row[3]) ? $Row[3] : "",
            'place' => isset($Row[4]) ? $Row[4] : "",
            'mobile1' => isset($Row[5]) ? $Row[5] : "",
            'address' => isset($Row[6]) ? $Row[6] : "",
            'occupation' => isset($Row[7]) ? $Row[7] : "",
            'occ_detail' => isset($Row[8]) ? $Row[8] : "",
            'income' => isset($Row[9]) ? $Row[9] : "",
            'chit_limit' => isset($Row[10]) ? $Row[10] : "",
            'reference' => isset($Row[11]) ? $Row[11] : "",
            'fam_name' => isset($Row[12]) ? $Row[12] : "",
            'fam_relationship' => isset($Row[13]) ? $Row[13] : "",
            'fam_aadhar' => isset($Row[14]) ? $Row[14] : "",
            'fam_mobile' => isset($Row[15]) ? $Row[15] : "",
            'guarantor_aadhar' => isset($Row[16]) ? $Row[16] : "",
            'grp_name' => isset($Row[17]) ? $Row[17] : "",
            'joining_month' => isset($Row[18]) ? $Row[18] : "",
            'mapping_id' => isset($Row[19]) ? $Row[19] : "",
            'share_value' => isset($Row[20]) ? $Row[20] : "",
            'share_percent' => isset($Row[21]) ? $Row[21] : "",
            
        );

        $dataArray['guarantor_aadhar'] = strlen($dataArray['guarantor_aadhar']) == 12 ? $dataArray['guarantor_aadhar'] : 'Invalid';

        $dataArray['mobile1'] = strlen($dataArray['mobile1']) == 10 ? $dataArray['mobile1'] : 'Invalid';
        $dataArray['fam_aadhar'] = strlen($dataArray['fam_aadhar']) == 12 ? $dataArray['fam_aadhar'] : 'Invalid';
        $dataArray['aadhar_number'] = strlen($dataArray['aadhar_number']) == 12 ? $dataArray['aadhar_number'] : 'Invalid';
        $guarantor_relationshipArray = ['Father' => 'Father', 'Mother' => 'Mother', 'Spouse' => 'Spouse', 'Sister' => 'Sister', 'Brother' => 'Brother', 'Son' => 'Son', 'Daughter' => 'Daughter','Other'=>'Other'];
        $dataArray['fam_relationship'] = $this->arrayItemChecker($guarantor_relationshipArray, $dataArray['fam_relationship']);
        $dataArray['fam_mobile'] = strlen($dataArray['fam_mobile']) == 10 ? $dataArray['fam_mobile'] : 'Invalid';
        $referred_typeArray = ['Yes' => '1', 'No' => '2'];
        $dataArray['reference'] = $this->arrayItemChecker($referred_typeArray, $dataArray['reference']);
        return $dataArray;
    }
    function dateFormatChecker($checkdate)
    {
        // Attempt to create a DateTime object from the provided date
        $dateTime = DateTime::createFromFormat('Y-m-d', $checkdate);

        // Check if the date is in the correct format
        if ($dateTime && $dateTime->format('Y-m-d') === $checkdate) {
            // Date is in the correct format, no need to change anything
            return $checkdate;
        }
        return 'Invalid Date';
    }
    function arrayItemChecker($arrayList, $arrayItem)
    {
        if (array_key_exists($arrayItem, $arrayList)) {
            $arrayItem = $arrayList[$arrayItem];
        } else {
            $arrayItem = 'Not Found';
        }
        return $arrayItem;
    }

    function getcusId($pdo, $id)
    {
        if (!isset($id) || $id == '') {
            $qry = $pdo->query("SELECT cus_id FROM customer_creation WHERE cus_id != '' ORDER BY id DESC LIMIT 1");

            if ($qry->rowCount() > 0) {
                $qry_info = $qry->fetch();
                $l_no = ltrim(strstr($qry_info['cus_id'], '-'), '-');
                $l_no = $l_no + 1;
                $cus_ID_final = "C-" . "$l_no";
            } else {
                $cus_ID_final = "C-101";
            }
        } else {
            $stmt = $pdo->prepare("SELECT cus_id FROM customer_creation WHERE id = :id");
            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() > 0) {
                $qry_info = $stmt->fetch();
                $cus_ID_final = $qry_info['cus_id'];
            } else {
                $cus_ID_final = "C-101"; // Default value if not found
            }
        }

        return $cus_ID_final;
    }
    function groupName($pdo, $grp_name)
    {
        // Use a direct query (ensure $grp_name is properly sanitized before using)
        $stmt = $pdo->query("SELECT grp_id FROM group_creation WHERE grp_name = '$grp_name'AND status <=3");

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $grp_id = $row['grp_id']; // Fetch the 'grp_id' column
        } else {
            $grp_id = 'Not Found'; // Return null if no result is found
        }

        return $grp_id;
    }
    function MappingID($pdo, $mapping_id)
    {
        // Use a direct query (ensure $grp_name is properly sanitized before using)
        $stmt = $pdo->query("SELECT id FROM group_cus_mapping WHERE map_id = '$mapping_id'");

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $map_id = $row['id']; // Fetch the 'grp_id' column
        } else {
            $map_id = 'Not Found'; // Return null if no result is found
        }

        return $map_id;
    }
    function getCustomerId($pdo,$aadhar_number)
    {
        $stmt = $pdo->query("SELECT id FROM  customer_creation WHERE aadhar_number = '$aadhar_number'");
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $cust_id = $row["id"];
        } else {
            $cust_id = 'Not Found'; // Return null if no result is found
        }


        return $cust_id;
    }
  
    function guarantorName($pdo, $guarantor_aadhar, $aadhar_number,$fam_name) {
        // Check if the customer exists based on aadhar_number
        $check_queryss = "SELECT cus_id FROM customer_creation WHERE aadhar_number = '$aadhar_number'";
    
        // Execute the query and fetch the cus_id
        $result1 = $pdo->query($check_queryss);
        $cusData = $result1->fetch(PDO::FETCH_ASSOC);
    
        // Initialize gur_id variable
        $gur_id = 'Not Found'; // Default value if no record is found
    
        // If customer exists, proceed to check for guarantor details
        if ($cusData) {
            $cus_id = $cusData['cus_id']; // Get cus_id from query result
    
            // Check if family info exists for the given guarantor_aadhar and cus_id
            $stmt = $pdo->query("SELECT id, fam_name FROM family_info WHERE fam_aadhar = '$guarantor_aadhar' AND cus_id = '$cus_id' AND fam_name ='$fam_name'");
    
            // If a family record is found, return the id (gur_id)
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $gur_id = $row["id"];
            }
        } 
        return $gur_id; // Return gur_id, which will be 'Not Found' if no record exists
    }
    
    
    function placeName($pdo, $place)
    {
        $stmt = $pdo->query("SELECT id, place FROM  place WHERE LOWER(REPLACE(TRIM(place),' ' ,'')) = LOWER(REPLACE(TRIM('$place'),' ' ,''))");
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $pl_id = $row["id"];
        } else {
            $pl_id = 'Not Found'; // Return null if no result is found
        }
        return $pl_id;
    }


    function FamilyTable($pdo, $data)
    {
        $user_id = $_SESSION['user_id'];
    
        // Check if the customer exists based on aadhar_number
        $check_queryss = "SELECT cus_id FROM customer_creation WHERE aadhar_number = '" . strip_tags($data['aadhar_number']) . "'";
    
        // Execute the query and fetch the cus_id
        $result1 = $pdo->query($check_queryss);
        $cusData = $result1->fetch(PDO::FETCH_ASSOC);
    
        // If cus_id is found, proceed with further checks
        if ($cusData) {
            $cus_ids = $cusData['cus_id']; // Get cus_id from query result
            
            // Check if family member already exists based on the provided details
            $check_query3 = "SELECT cus_id 
                             FROM family_info 
                             WHERE cus_id = '" . strip_tags($cus_ids) . "' 
                             AND fam_name = '" . strip_tags($data['fam_name']) . "' 
                             AND fam_relationship = '" . strip_tags($data['fam_relationship']) . "' 
                             AND fam_aadhar = '" . strip_tags($data['fam_aadhar']) . "' 
                             AND fam_mobile = '" . strip_tags($data['fam_mobile']) . "'";
    
            $result2 = $pdo->query($check_query3);
    
            // If no family member exists, insert new family member info
            if ($result2->rowCount() == 0) {
                // Prepare the insert query for family_info
                $insert_query1 = "INSERT INTO family_info (cus_id, fam_name, fam_relationship, fam_aadhar, fam_mobile, insert_login_id, created_on) 
                                  VALUES (
                                      '" . strip_tags($cus_ids) . "',
                                      '" . strip_tags($data['fam_name']) . "',
                                      '" . strip_tags($data['fam_relationship']) . "',
                                      '" . strip_tags($data['fam_aadhar']) . "',
                                      '" . strip_tags($data['fam_mobile']) . "',
                                      '" . $user_id . "',
                                      NOW()
                                  )";
    
                // Execute the insert query
                $pdo->query($insert_query1);
            }
        } else {
            // Handle case where aadhar_number is not found in customer_creation
            echo "Error: Customer with the given Aadhar number not found.";
        }
    }
    
    
    function PlaceTable($pdo, $data)
    {
        $user_id = $_SESSION['user_id'];

        // Check if the place already exists (case-insensitive and ignoring spaces)
        $check_querys = "SELECT id FROM place 
        WHERE LOWER(REPLACE(TRIM(place), ' ', '')) = LOWER(REPLACE(TRIM('" . $data['place'] . "'), ' ', ''))";
        // Execute the query
        $result1 = $pdo->query($check_querys);

        // If the place does not exist, insert it
        if ($result1->rowCount() == 0) {
            $insert_query2 = "INSERT INTO place (place, insert_login_id, created_on) 
                             VALUES ('" . strip_tags($data['place']) . "', '$user_id', NOW())";

            // Execute the insert query
            $pdo->query($insert_query2);
        }
    }
    function guarantorTable($pdo, $data)
    {
        $user_id = $_SESSION['user_id'];
    
        // Check if the customer exists based on aadhar_number
        $check_queryss = "SELECT cus_id FROM customer_creation WHERE aadhar_number = '" . strip_tags($data['aadhar_number']) . "'";
        
        // Execute the query and fetch the cus_id
        $result1 = $pdo->query($check_queryss);
        $cusData = $result1->fetch(PDO::FETCH_ASSOC);
    
        // If cus_id is found, proceed with further checks
        if ($cusData) {
            $cus_id = $cusData['cus_id']; // Get cus_id from query result
    
            // Check if guarantor already exists based on the provided details
            $check_query4 = "SELECT cus_id 
                             FROM guarantor_info 
                             WHERE cus_id = '" . strip_tags($cus_id) . "' 
                             AND guarantor_name = '" . strip_tags($data['fam_name']) . "' 
                             AND family_id = '" . strip_tags($data['gur_id']) . "' 
                             AND guarantor_relationship = '" . strip_tags($data['fam_relationship']) . "'";
    
            $result2 = $pdo->query($check_query4);
    
            // If no guarantor exists, insert new guarantor info
            if ($result2->rowCount() == 0) {
                // Prepare the insert query for guarantor_info
                $insert_query4 = "INSERT INTO guarantor_info (cus_id, relationship_type, guarantor_name, family_id, guarantor_relationship, insert_login_id, created_on) 
                                  VALUES (
                                      '" . strip_tags($cus_id) . "',
                                      '3',  
                                      '" . strip_tags($data['fam_name']) . "',
                                      '" . strip_tags($data['gur_id']) . "',
                                      '" . strip_tags($data['fam_relationship']) . "',
                                      '" . $user_id . "',
                                      NOW()
                                  )";
    
                // Execute the insert query
                $pdo->exec($insert_query4);
            }
        } else {
            // Handle case where aadhar_number is not found in customer_creation
            echo "Error: Customer with the given Aadhar number not found.";
        }
    }
    
    

    function customerEntryTables($pdo, $data)
    {
        // Print or log $data to see what values are being passed
        $user_id = $_SESSION['user_id'];
        $che_query = "SELECT id FROM customer_creation WHERE  aadhar_number = '" . $data['aadhar_number'] . "'";
        $result2 = $pdo->query($che_query);
        if ($result2->rowCount() == 0) {
            $insertQuery = "INSERT INTO `customer_creation` (
             `cus_id`,
            `first_name`, 
            `last_name`, 
            `aadhar_number`, 
            `place`, 
            `mobile1`, 
            `address`, 
            `tot_income`,
            `chit_limit`, 
            `reference`, 
            `insert_login_id`, `created_on`
        ) VALUES (
        '" . strip_tags($data['cus_id']) . "',
            '" . strip_tags($data['first_name']) . "',
            '" . strip_tags($data['last_name']) . "',
            '" . strip_tags($data['aadhar_number']) . "',
            '" . strip_tags($data['pl_id']) . "',
            '" . strip_tags($data['mobile1']) . "',
            '" . strip_tags($data['address']) . "',
             '" . strip_tags($data['income']) . "',
            '" . strip_tags($data['chit_limit']) . "',
            '" . strip_tags($data['reference']) . "',
               '" . $user_id . "', 
            NOW()
        )";
        $pdo->query($insertQuery);
        }
    }
    
    function sourceTable($pdo, $data)
    {
        $user_id = $_SESSION['user_id'];

        // Check if the place already exists (case-insensitive and ignoring spaces)
        $check_queryss = "SELECT cus_id FROM customer_creation WHERE cus_id = '" . $data['cus_id'] . "'";

        // Execute the query
        $result1 = $pdo->query($check_queryss);


        // If the place does not exist, insert it
        if ($result1->rowCount() > 0) {
            $insert_query3 = "INSERT INTO source (cus_id,occupation,occ_detail,income,insert_login_id,created_on) 
                             VALUES ( '" . strip_tags($data['cus_id']) . "','" . strip_tags($data['occupation']) . "',
            '" . strip_tags($data['occ_detail']) . "',
            '" . strip_tags($data['income']) . "', '$user_id', NOW())";

            // Execute the insert query
            $pdo->query($insert_query3);
        }
    }
    function cusMappingTable($pdo, $data)
    {
        $user_id = $_SESSION['user_id'];
    
        // Fetch current customer count in the group
        $stmt = $pdo->query("SELECT COUNT(*) FROM group_cus_mapping WHERE grp_creation_id = '" . $data['grp_id'] . "'");
        $current_count = $stmt->fetchColumn();
    
        // Fetch total members allowed in the group
        $stmt2 = $pdo->query("SELECT total_members FROM group_creation WHERE grp_id = '" . $data['grp_id'] . "'");
        $total_members = $stmt2->fetchColumn();
    
        // Initialize a response message variable
        $responseMessage = '';
    
        // Check if the map_id already exists
        $check_query = "SELECT map_id FROM group_cus_mapping WHERE map_id = '" . $data['mapping_id'] . "'";
        $resultCheck = $pdo->query($check_query);
    
        // If the map_id does not exist, insert it
        if ($resultCheck->rowCount() == 0) {
            // Check if the current group count is less than the total allowed members
            if ($current_count < $total_members) {
               $insert_query = "INSERT INTO group_cus_mapping (map_id, grp_creation_id, joining_month, insert_login_id, created_on) 
                                 VALUES ('" . strip_tags($data['mapping_id']) . "', '" . strip_tags($data['grp_id']) . "', '" . strip_tags($data['joining_month']) . "', '$user_id', NOW())";
                
                $pdo->query($insert_query);
    
                if ($pdo->lastInsertId()) {
                    // Check if the count now equals the total members allowed
                    $stmt = $pdo->query("SELECT COUNT(*) FROM group_cus_mapping WHERE grp_creation_id = '" . $data['grp_id'] . "'");
                         $current_count = $stmt->fetchColumn();
    
                    if ($current_count == $total_members) {
                        // Update the status in the group_creation table to indicate the group is full
                        $pdo->query("UPDATE group_creation SET status = '2', update_login_id = '$user_id', updated_on = NOW() WHERE grp_id = '" . $data['grp_id'] . "'");
                        $responseMessage = "Customer successfully added. The group is now full.";
                    } else {
                        $responseMessage = "Customer successfully added.";
                    }
                } else {
                    $responseMessage = "Error inserting new customer to group.";
                }
            } else {
                $responseMessage = "Customer Mapping Limit is Exceeded"; // Show error if the count exceeds the limit
            }
        } else {
            $responseMessage = "Mapping ID already exists.";
        }
    
        // Return response message
        return $responseMessage;
    }
    
function cusShareTable($pdo, $data)
{
    $user_id = $_SESSION['user_id'];
      
    // Add the new customer to the group mapping

        $insert_query6 = "INSERT INTO group_share (cus_mapping_id,cus_id,grp_creation_id, share_value,share_percent, insert_login_id, created_on) 
                          VALUES ('" . strip_tags($data['map_id']) . "','" . strip_tags($data['cust_id']) . "', '" . strip_tags($data['grp_id']) . "', '" . strip_tags($data['share_value']) . "','" . strip_tags($data['share_percent']) . "', '$user_id', NOW())";

        $pdo->query($insert_query6);
}
    
    function handleError($data)
    {
        $errcolumns = array();


        if ($data['first_name'] == '') {
            $errcolumns[] = 'First Name';
        }
    
        if ($data['address'] == '') {
            $errcolumns[] = 'Address';
        }

        if ($data['aadhar_number'] == 'Invalid') {
            $errcolumns[] = 'Customer Aadhar Number';
        }
        if ($data['mobile1'] == 'Invalid') {
            $errcolumns[] = 'Mobile Number';
        }

        if ($data['fam_name'] == '') {
            $errcolumns[] = 'Family Name';
        }

        if ($data['fam_aadhar'] == 'Invalid') {
            $errcolumns[] = 'Family Aadhar';
        }

        if ($data['fam_mobile'] == 'Invalid') {
            $errcolumns[] = 'Family Mobile Number';
        }

        if ($data['guarantor_aadhar'] == 'Invalid') {
            $errcolumns[] = 'Guarantor Aadhar';
        }
        if ($data['grp_id'] == 'Not Found') {
            $errcolumns[] = 'Group ID';
        }
        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['chit_limit'])) {
            $errcolumns[] = 'Chit Limit';
        }
        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['income'])) {
            $errcolumns[] = 'Income';
        }
        if (!preg_match('/^[0-9]+$/', $data['joining_month'])) {
            $errcolumns[] = 'Customer Auction Start Month';
        }
        if ($data['occupation'] == '') {
            $errcolumns[] = 'Occupation';
        }
        if ($data['occ_detail'] == '') {
            $errcolumns[] = 'Occupation Detail';
        }
        if ($data['grp_name'] == 'Not Found') {
            $errcolumns[] = 'Group Name';
        }
        if ($data['reference'] == 'Not Found') {
            $errcolumns[] = 'Reference';
        }
        if ($data['fam_relationship'] == 'Not Found') {
            $errcolumns[] = 'Relationship';
        }
        if ($data['share_value'] == '') {
            $errcolumns[] = 'Share Value';
        }
        if ($data['share_percent'] == '') {
            $errcolumns[] = 'Share Percent';
        }
        if ($data['mapping_id'] == '') {
            $errcolumns[] = 'Mapping ID';
        }
        return $errcolumns;
    }
}
