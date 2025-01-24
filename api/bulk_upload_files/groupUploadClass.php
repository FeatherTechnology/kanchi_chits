<?php
require '../../ajaxconfig.php';
@session_start();

class groupUploadClass
{
    public function uploadFiletoFolder()
    {
        $excel = $_FILES['excelFile']['name'];
        $excel_temp = $_FILES['excelFile']['tmp_name'];
        $excelfolder = "../../uploads/excel_format/group_format/" . $excel;

        $fileExtension = pathinfo($excelfolder, PATHINFO_EXTENSION); //get the file extension

        $excel = uniqid() . '.' . $fileExtension;
        while (file_exists("../../uploads/excel_format/group_format/" . $excel)) {
            // this loop will continue until it generates a unique file name
            $excel = uniqid() . '.' . $fileExtension;
        }
        $excelfolder = "../../uploads/excel_format/group_format/" . $excel;
        move_uploaded_file($excel_temp, $excelfolder);
        return $excelfolder;
    }

    public function fetchAllRowData($Row)
    {
        $dataArray = array(
            'grp_name' => isset($Row[1]) ? $Row[1] : "",
            'chit_value' => isset($Row[2]) ? $Row[2] : "",
            'date' => isset($Row[3]) ? $Row[3] : "",
            'hours' => isset($Row[4]) ? $Row[4] : "",
            'minutes' => isset($Row[5]) ? $Row[5] : "",
            'ampm' => isset($Row[6]) ? $Row[6] : "",
            'commision' => isset($Row[7]) ? $Row[7] : "",
            'total_members' => isset($Row[8]) ? $Row[8] : "",
            'total_months' => isset($Row[9]) ? $Row[9] : "",
            'start_month' => isset($Row[10]) ? $Row[10] : "",
            'end_month' => isset($Row[11]) ? $Row[11] : "",
            'branch' => isset($Row[12]) ? $Row[12] : "",
            'grace_period' => isset($Row[13]) ? $Row[13] : "",
        );

        return $dataArray;
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

    function getGroupCode($pdo, $id)
    {
        if (!isset($id) || $id == '') {
            $qry = $pdo->query("SELECT grp_id FROM group_creation WHERE grp_id != '' ORDER BY id DESC LIMIT 1");

            if ($qry->rowCount() > 0) {
                $qry_info = $qry->fetch();
                $l_no = ltrim(strstr($qry_info['grp_id'], '-'), '-');
                $l_no = $l_no + 1;
                $loan_ID_final = "G-" . "$l_no";
            } else {
                $loan_ID_final = "G-101";
            }
        } else {
            $stmt = $pdo->prepare("SELECT grp_id FROM group_creation WHERE id = :id");
            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() > 0) {
                $qry_info = $stmt->fetch();
                $loan_ID_final = $qry_info['grp_id'];
            } else {
                $loan_ID_final = "G-101"; // Default value if not found
            }
        }

        return $loan_ID_final;
    }



    function getBranchId($pdo, $branch)
    {
        $stmt = $pdo->query("SELECT b.id
FROM `branch_creation` b
LEFT JOIN `group_creation` gc ON FIND_IN_SET(b.id, gc.branch)
WHERE LOWER(REPLACE(TRIM(b.branch_name), ' ', ''))  = LOWER(REPLACE(TRIM('$branch'), ' ', ''))");

        if ($stmt->rowCount() > 0) {
            $branch_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        } else {
            $branch_id = 'Not Found'; // Return 'Not Found' if branch does not exist
        }
        return $branch_id;
    }

    function GroupTable($pdo, $data)
    {
        $user_id = $_SESSION['user_id'];
        // Check if the place already exists (case-insensitive and ignoring spaces)
        $check_querys = "SELECT grp_name FROM group_creation 
  WHERE LOWER(REPLACE(TRIM(grp_name), ' ', '')) = LOWER(REPLACE(TRIM('" . $data['grp_name'] . "'), ' ', ''))";
        // Execute the query
        $result1 = $pdo->query($check_querys);

        // If the place does not exist, insert it
        if ($result1->rowCount() == 0) {
            $insert_query = "INSERT INTO `group_creation` (
            `grp_id`, `grp_name`, `chit_value`, `date`, `commission`, `hours`, `minutes`, `ampm`, `total_members`, 
            `total_months`, `start_month`, `end_month`, `branch`, `grace_period`, `status`, `insert_login_id`, `created_on`
        ) VALUES (
            '" . strip_tags($data['grp_id']) . "', 
            '" . strip_tags($data['grp_name']) . "', 
            '" . strip_tags($data['chit_value']) . "', 
            '" . strip_tags($data['date']) . "', 
            '" . strip_tags($data['commision']) . "', 
            '" . strip_tags($data['hours']) . "', 
            '" . strip_tags($data['minutes']) . "', 
            '" . strip_tags($data['ampm']) . "', 
            '" . strip_tags($data['total_members']) . "', 
            '" . strip_tags($data['total_months']) . "', 
            '" . strip_tags($data['start_month']) . "', 
            '" . strip_tags($data['end_month']) . "', 
            '" . strip_tags($data['branch_id']) . "', 
            '" . strip_tags($data['grace_period']) . "', 
            1, 
            '" . $user_id . "', 
            NOW()
        );";

            $pdo->query($insert_query);
        }
    }

    function handleError($data)
    {
        $errcolumns = array();

        if ($data['grp_name'] == '') {
            $errcolumns[] = 'Group Name';
        }

        if (!preg_match('/^[0-9]+$/', $data['chit_value'])) {
            $errcolumns[] = 'Chit Value';
        }

        if (!preg_match('/^[0-9]+$/', $data['date'])) {
            $errcolumns[] = 'Date';
        }
        if (!preg_match('/^[0-9]+$/', $data['hours'])) {
            $errcolumns[] = 'Hours';
        }
        if (!preg_match('/^[0-9]+$/', $data['minutes'])) {
            $errcolumns[] = 'Minutes';
        }
        if (!preg_match('/^[0-9]+(\.[0-9]+)?$/', $data['commision'])) {
            $errcolumns[] = 'Commision';
        } 
        if ($data['ampm'] == '') {
            $errcolumns[] = ' AM/PM';
        }
        if (!preg_match('/^[0-9]+$/', $data['total_members'])) {
            $errcolumns[] = 'Total Members';
        }
        if (!preg_match('/^[0-9]+$/', $data['total_months'])) {
            $errcolumns[] = 'Total Month';
        }
        if ($data['branch_id'] == 'Not Found') {
            $errcolumns[] = 'Branch ID';
        }
        if (!preg_match('/^\d{4}-\d{2}$/', $data['start_month'])) {
            $errcolumns[] = 'Start Month';
        }
        if (!preg_match('/^\d{4}-\d{2}$/', $data['end_month'])) {
            $errcolumns[] = 'End Month';
        }

        if (!preg_match('/^[0-9]+$/', $data['grace_period'])) {
            $errcolumns[] = 'Grace Period';
        }
        return $errcolumns;
    }
}
