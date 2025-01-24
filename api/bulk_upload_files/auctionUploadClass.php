<?php
require '../../ajaxconfig.php';
@session_start();

class auctionUploadClass
{
    public function uploadFiletoFolder()
    {
        $excel = $_FILES['excelFile']['name'];
        $excel_temp = $_FILES['excelFile']['tmp_name'];
        $excelfolder = "../../uploads/excel_format/settlement_format/" . $excel;

        $fileExtension = pathinfo($excelfolder, PATHINFO_EXTENSION); //get the file extension

        $excel = uniqid() . '.' . $fileExtension;
        while (file_exists("../../uploads/excel_format/settlement_format/" . $excel)) {
            // this loop will continue until it generates a unique file name
            $excel = uniqid() . '.' . $fileExtension;
        }
        $excelfolder = "../../uploads/excel_format/settlement_format/" . $excel;
        move_uploaded_file($excel_temp, $excelfolder);
        return $excelfolder;
    }

    public function fetchAllRowData($Row)
    {
        $dataArray = array(
            'grp_name' => isset($Row[1]) ? $Row[1] : "",
            'mapping_id' => isset($Row[2]) ? $Row[2] : "",
            'date' => isset($Row[3]) ? $Row[3] : "",
            'auction_month' => isset($Row[4]) ? $Row[4] : "",
            'low_value' => isset($Row[5]) ? $Row[5] : "",
            'high_value' => isset($Row[6]) ? $Row[6] : "",
            'cus_name' => isset($Row[7]) ? $Row[7] : "",
            'aadhar_number' => isset($Row[8]) ? $Row[8] : "",
            'auction_value' => isset($Row[9]) ? $Row[9] : "",
            'settle_date' => isset($Row[10]) ? $Row[10] : "",
            'settle_amount' => isset($Row[11]) ? $Row[11] : "",
            'payment_type' => isset($Row[12]) ? $Row[12] : "",
            'settle_type' => isset($Row[13]) ? $Row[13] : "",
            'settle_cash' => isset($Row[14]) ? $Row[14] : "",
            'bank_name' => isset($Row[15]) ? $Row[15] : "",
            'cheque_no' => isset($Row[16]) ? $Row[16] : "",
            'cheque_val' => isset($Row[17]) ? $Row[17] : "",
            'transaction_id' => isset($Row[18]) ? $Row[18] : "",
            'transaction_val' => isset($Row[19]) ? $Row[19] : "",
            'guarantor_aadhar' => isset($Row[20]) ? $Row[20] : "",
            'relationship' => isset($Row[21]) ? $Row[21] : "",
        );

        // Only check if the value is not empty
        if (!empty($dataArray['guarantor_aadhar'])) {
            $dataArray['guarantor_aadhar'] = strlen($dataArray['guarantor_aadhar']) == 12 ? $dataArray['guarantor_aadhar'] : 'Invalid';
        }

        if (!empty($dataArray['aadhar_number'])) {
            $dataArray['aadhar_number'] = strlen($dataArray['aadhar_number']) == 12 ? $dataArray['aadhar_number'] : 'Invalid';
        }

        if (!empty($dataArray['date'])) {
            $dataArray['date'] = $this->dateFormatChecker($dataArray['date']);
        }

        if (!empty($dataArray['settle_date'])) {
            $dataArray['settle_date'] = $this->dateFormatChecker($dataArray['settle_date']);
        }

        // Check only if 'relationship' field is not empty
        if (!empty($dataArray['relationship'])) {
            $guarantor_relationshipArray = ['Father' => 'Father', 'Mother' => 'Mother', 'Spouse' => 'Spouse', 'Sister' => 'Sister', 'Brother' => 'Brother', 'Son' => 'Son', 'Daughter' => 'Daughter', 'Other' => 'Other', 'Customer' => 'Customer'];
            $dataArray['relationship'] = $this->arrayItemChecker($guarantor_relationshipArray, $dataArray['relationship']);
        }

        // Check only if 'payment_type' field is not empty
        if (!empty($dataArray['payment_type'])) {
            $refer_typeArray = ['Split' => '1', 'Single' => '2'];
            $dataArray['payment_type'] = $this->arrayItemChecker($refer_typeArray, $dataArray['payment_type']);
        }

        // Check only if 'settle_type' field is not empty
        if (!empty($dataArray['settle_type'])) {
            $referred_typeArray = ['Cash' => '1', 'Cheque' => '2', 'Bank Transfer' => '3'];
            $dataArray['settle_type'] = $this->arrayItemChecker($referred_typeArray, $dataArray['settle_type']);
        }

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


    function groupName($pdo, $grp_name)
    {
        // Use a direct query (ensure $grp_name is properly sanitized before using)
        $stmt = $pdo->query("SELECT grp_id FROM group_creation WHERE grp_name = '$grp_name' AND status <=3");

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $grp_id = $row['grp_id']; // Fetch the 'grp_id' column
        } else {
            $grp_id = 'Not Found'; // Return null if no result is found
        }

        return $grp_id;
    }

    function getCustomerId($pdo, $aadhar_number)
    {
        $stmt = $pdo->query("SELECT id,cus_id FROM  customer_creation WHERE  aadhar_number = '$aadhar_number'");
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $cust_id = $row["id"];
        } else {
            $cust_id = 'Not Found'; // Return null if no result is found
        }


        return $cust_id;
    }
    function getAuctionId($pdo, $date, $grp_id)
    {
        $stmt = $pdo->query("SELECT id FROM  auction_details WHERE  date = '$date' AND group_id = '$grp_id'");
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $auction_id = $row["id"];
        } else {
            $auction_id = 'Not Found'; // Return null if no result is found
        }


        return $auction_id;
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
    function guarantorName($pdo, $guarantor_aadhar)
    {
        // Query to fetch family info using guarantor's Aadhar number
        $stmt = $pdo->query("SELECT id, fam_name FROM family_info WHERE fam_aadhar = '" . strip_tags($guarantor_aadhar) . "'");

        if ($stmt->rowCount() > 0) {
            // Fetch the family ID if a record is found
            $family_info = $stmt->fetch(PDO::FETCH_ASSOC);
            $family_id = $family_info['id']; // Correctly reference the 'id' from family_info

            // Query to check the guarantor_info using the fetched family ID
            $stmt3 = $pdo->query("SELECT id FROM guarantor_info WHERE family_id = '" . strip_tags($family_id) . "'");

            // If a record is found, fetch the guarantor ID
            if ($stmt3->rowCount() > 0) {
                $guarantor_info = $stmt3->fetch(PDO::FETCH_ASSOC);
                $gur_id = $guarantor_info['id'];
            } else {
                $gur_id = 'Not Found'; // Return 'Not Found' if no guarantor info is found
            }
        } else {
            $gur_id = 'Not Found'; // Return 'Not Found' if no family info is found
        }

        return $gur_id; // Return the guarantor ID or 'Not Found'
    }


    function getBankId($pdo, $bank_name)
    {
        $stmt = $pdo->query("SELECT b.id
    FROM `settlement_info` si 
    JOIN bank_creation b ON FIND_IN_SET(b.id, si.bank_id)
    WHERE LOWER(REPLACE(TRIM(b.bank_name), ' ', '')) = LOWER(REPLACE(TRIM('$bank_name'), ' ', ''))");

        if ($stmt->rowCount() > 0) {
            $bank_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        } else {
            $bank_id = ''; // Return 'Not Found' if branch does not exist
        }
        return $bank_id;
    }


    function AuctionTable($pdo, $data)
    {

            // Fetch relevant group details
            $smt2 = $pdo->query("SELECT chit_value, total_members, commission, end_month FROM group_creation WHERE grp_id = '" . strip_tags($data['grp_id']) . "'");
            $groupData = $smt2->fetch(PDO::FETCH_ASSOC);

            // Check if the query returned valid data
            if ($groupData) {
                // Extract values
                $chit_value = $groupData['chit_value'];
                $total_members = $groupData['total_members'];
                $commission = $groupData['commission'];
                $end_month = $groupData['end_month'];
                $auction_value = floatval(strip_tags($data['auction_value']));

                // Calculate chit_amount if auction_value is provided and greater than zero
                if ($auction_value > 0) {
                    $chit_amount = ($chit_value + ($chit_value * ($commission / 100)) - $auction_value) / $total_members;
                } else {
                    $chit_amount = null; // Set to null or handle accordingly
                }

                // Determine customer ID based on aadhar_number
                if (!empty($auction_value) && $auction_value > 0) {
                $customer_id = !empty($data['aadhar_number']) ? strip_tags($data['map_id']) : -1;
                }
                // Get the user ID from the session
                $user_id = $_SESSION['user_id'];

                // Fetch chit_value, total_members, and commission from group_creation table
                $check_query = "SELECT id FROM auction_details WHERE group_id = '" . $data['grp_id'] . "' AND date = '" . $data['date'] . "'";
                $resultCheck = $pdo->query($check_query);

                // If the record does not exist, insert it
                if ($resultCheck->rowCount() == 0) {

                // Prepare and execute the insert query
                $insert_query1 = "INSERT INTO auction_details (group_id, date, auction_month, low_value, high_value, status, cus_name, auction_value, chit_amount, insert_login_id, created_on) 
                                  VALUES (
                                      '" . strip_tags($data['grp_id']) . "',
                                      '" . strip_tags($data['date']) . "',
                                      '" . strip_tags($data['auction_month']) . "',
                                      '" . strip_tags($data['low_value']) . "',
                                      '" . strip_tags($data['high_value']) . "',
                                      1,
                                      '" . $customer_id . "',
                                      '" . $auction_value . "',
                                      '" . $chit_amount . "',
                                      '" . $user_id . "',
                                      '" . strip_tags($data['date']) . "'
                                  )";
                }else{
                  $insert_query1 = "UPDATE `auction_details` SET `cus_name` = '$customer_id',`auction_value` = '$auction_value',`chit_amount` = '$chit_amount',`update_login_id` = '$user_id',`updated_on` = '" . strip_tags($data['date']) . "' WHERE group_id = '" . strip_tags($data['grp_id']) . "' AND date = '" . strip_tags($data['date']) . "' "; 
                }
                // Execute the insert query
                $pdo->query($insert_query1);

                // Check if the insert was successful
                if ($pdo->lastInsertId()) {
                    // Update the status in the group_creation table to indicate the group is in auction (status 3)
                    $pdo->query("UPDATE group_creation SET status = '3', update_login_id = '$user_id', updated_on = NOW() WHERE grp_id = '" . strip_tags($data['grp_id']) . "'");

                    // Check if auction_value is not empty before proceeding
                    if (!empty($auction_value)) {
                        // Update the status in auction_details to '2'
                        $pdo->query("UPDATE auction_details 
                                     SET status = '2', update_login_id = '$user_id', updated_on = NOW() 
                                     WHERE group_id = '" . strip_tags($data['grp_id']) . "' 
                                     AND date = '" . strip_tags($data['date']) . "'");

                        // Extract year and month from the date provided in $data
                        $auction_date = DateTime::createFromFormat('Y-m-d', strip_tags($data['date']));
                        $auction_year_month = $auction_date->format('Y-m'); // Get yyyy-mm format

                        // Compare auction_date (yyyy-mm) with end_month
                        if ($auction_year_month == $end_month) {
                            // Update the status in the group_creation table to indicate the group is completed (status 4)
                            $pdo->query("UPDATE group_creation SET status = '4', update_login_id = '$user_id', updated_on = NOW() WHERE grp_id = '" . strip_tags($data['grp_id']) . "'");
                        }
                    }
                }
            } else {
                // Handle case where group data is not found
                echo "Error: Group not found or invalid group ID.";
            }
    }



    function auctionviewTable($pdo, $data)
    {
        $user_id = $_SESSION['user_id']; // Retrieve the user ID from session

        // Corrected insert query with proper syntax and variable names
        $check_query1 = "SELECT id FROM auction_modal WHERE group_id = '" . $data['grp_id'] . "' AND date = '" . $data['date'] . "'";
        $resultCheck1 = $pdo->query($check_query1);

        // If the record does not exist, insert it
        if ($resultCheck1->rowCount() == 0) {
            $auction_value = floatval(strip_tags($data['auction_value']));
            if (!empty($auction_value) && $auction_value > 0) {
                $customer_id = !empty($data['aadhar_number']) ? strip_tags($data['map_id']) : -1;
                $insert_query2 = "INSERT INTO auction_modal 
                      (auction_id, group_id, date, cus_name, value, inserted_login_id, created_on) 
                      VALUES (
                          '" . strip_tags($data['auction_id']) . "',
                          '" . strip_tags($data['grp_id']) . "',
                          '" . strip_tags($data['date']) . "',
                          '" .  $customer_id . "',  
                          '" . strip_tags($data['auction_value']) . "',
                          '" . $user_id . "',  
                          NOW()
                      )";

                // Execute the query
                $pdo->query($insert_query2);
            }
        }
    }
    function settlementTable($pdo, $data)
    {
        // Fetch chit_value from group_creation table
        // Get user ID from session
        $user_id = $_SESSION['user_id'];
    
        // Assign auction_value earlier to avoid undefined variable
        $auction_value = floatval(strip_tags($data['auction_value']));
    
        // Ensure auction_value, settle_date, and settle_amount are valid
        if ($auction_value > 0 && !empty($data['settle_date']) && !empty($data['settle_amount'])) {
            // Prepare the insert query for settlement_info table
            $insert_query2 = "
                INSERT INTO settlement_info 
                (auction_id, settle_date, group_id, cus_name, settle_amount, settle_balance, payment_type, settle_type, bank_id, settle_cash, cheque_no, cheque_val, transaction_id, transaction_val, guarantor_name, guarantor_relationship, insert_login_id, created_on) 
                VALUES (
                    '" . strip_tags($data['auction_id']) . "',
                    '" . strip_tags($data['settle_date']) . "',
                    '" . strip_tags($data['grp_id']) . "',
                    '" . strip_tags($data['cust_id']) . "',
                    '" . strip_tags($data['settle_amount']) . "',
                    '" . strip_tags($data['settle_amount']) . "', -- Settle balance same as settle amount
                    '" . strip_tags($data['payment_type']) . "',
                    '" . strip_tags($data['settle_type']) . "',
                    '" . strip_tags($data['bank_id']) . "',
                    '" . strip_tags($data['settle_cash']) . "',
                    '" . strip_tags($data['cheque_no']) . "',
                    '" . strip_tags($data['cheque_val']) . "',
                    '" . strip_tags($data['transaction_id']) . "',
                    '" . strip_tags($data['transaction_val']) . "',
                    '" . strip_tags($data['gur_id']) . "', -- Guarantor ID
                    '" . strip_tags($data['relationship']) . "',
                    '" . $user_id . "',
                   '" . strip_tags($data['settle_date']) . "'
                )";
    
            // Execute the insert query for settlement_info and handle errors
            try {
                $pdo->query($insert_query2);
            } catch (PDOException $e) {
                echo "Error inserting settlement info: " . $e->getMessage();
            }
        }
    
        // Update auction_details only if auction_value is valid
        if ($auction_value > 0) {
            try {
                // Prepare and execute the auction_details update query
                $auction_update = "
                    UPDATE auction_details 
                    SET status = '3', update_login_id = '$user_id', updated_on = NOW() 
                    WHERE group_id = '" . strip_tags($data['grp_id']) . "' 
                    AND date = '" . strip_tags($data['date']) . "'
                ";
                $pdo->query($auction_update);
            } catch (PDOException $e) {
                echo "Error updating auction details: " . $e->getMessage();
            }
    
            // Update group_share settle_status if settle_date is provided
            if (!empty($data['settle_date'])) {
                try {
                    $group_update = "
                        UPDATE group_share 
                        SET settle_status = 'Yes' 
                        WHERE grp_creation_id = '" . strip_tags($data['grp_id']) . "' 
                        AND cus_id = '" . strip_tags($data['cust_id']) . "' 
                        AND cus_mapping_id = '" . strip_tags($data['map_id']) . "'  
                        LIMIT 1
                    ";
                    $pdo->query($group_update);
                } catch (PDOException $e) {
                    echo "Error updating group share: " . $e->getMessage();
                }
            }
        } else {
            // Log or handle case where auction_value is invalid
            echo "Auction value is zero or invalid.";
        }
    }
    


    function handleError($data)
    {
        $errcolumns = array();
        if ($data['grp_name'] == '') {
            $errcolumns[] = 'Group Name';
        }
        if ($data['date'] == 'Invalid Date') {
            $errcolumns[] = 'Auction Date';
        }
        if (!empty($data['mapping_id'])) {
            if ($data['mapping_id'] == '') {
                $errcolumns[] = 'Mapping ID';
            }
        }
        if (!empty($data['settle_date'])) {
            if ($data['settle_date'] == 'Invalid Date') {
                $errcolumns[] = 'Settlement Date';
            }
        }
        if ($data['aadhar_number'] == 'Invalid') {
            $errcolumns[] = 'Customer Aadhar Number';
        }
        if (!empty($data['auction_value'])) {
            if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['auction_value'])) {
                $errcolumns[] = 'Auction Value';
            }
        }
        if (!empty($data['settle_cash'])) {
            if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['settle_cash'])) {
                $errcolumns[] = 'Cash';
            }
        }
        if (!empty($data['guarantor_aadhar'])) {
            if ($data['guarantor_aadhar'] == 'Invalid') {
                $errcolumns[] = 'Guarantor Aadhar';
            }
        }

        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['high_value'])) {
            $errcolumns[] = 'High Value';
        }
        if (!preg_match('/^\d+(\.\d{1,2})?$/', $data['low_value'])) {
            $errcolumns[] = 'Low Value';
        }
        if (!preg_match('/^[0-9]+$/', $data['auction_month'])) {
            $errcolumns[] = ' Auction Month';
        }
        if (!empty($data['settle_type'])) {
            if ($data['settle_type'] != 'Not Found') {
                // Subcondition 7.1
                if ($data['settle_type'] == '1') {
                    if (
                        $data['settle_cash'] == ''
                    ) {
                        $errcolumns[] = 'Settle Cash';
                    }
                }
                // Subcondition 7.2
                if ($data['settle_type'] == '2') {
                    if ($data['cheque_no'] == '' || $data['cheque_val'] == '' || $data['bank_name'] == '') {
                        $errcolumns[] = 'Cheque Number or Cheque Value or Bank Name';
                    }
                }
                if ($data['settle_type'] == '3') {
                    if ($data['transaction_id'] == '' || $data['transaction_val'] == '' || $data['bank_name'] == '') {
                        $errcolumns[] = 'Transaction ID or Transaction Value or Bank Name';
                    }
                }
            } else {
                $errcolumns[] = 'Settle Type';
            }
        }
        if (!empty($data['payment_type'])) {
            if ($data['payment_type'] == 'Not Found') {
                $errcolumns[] = 'Payment Type';
            }
        }
        if (!empty($data['grp_id'])) {
            if ($data['grp_id'] == 'Not Found') {
                $errcolumns[] = 'Group ID';
            }
        }
        if (!empty($data['relationship'])) {
            if ($data['relationship'] == 'Not Found') {
                $errcolumns[] = 'Relationship';
            }
        }
        return $errcolumns;
    }
}
