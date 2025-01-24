<?php
require '../../ajaxconfig.php';
@session_start();

class GroupStsClass
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function updateGroupStatus($share_id,$cus_mapping_id, $group_id, $cus_id, $auction_month)
    {
        $coll_status = 'Unpaid';
 
        // Query to fetch the latest collection record for the same cus_mapping_id
        $qury = "SELECT 
                    collection_amount, 
                    collection_date, 
                    payable, 
                    IFNULL(collection_amount, 0) AS amount_collected,
                    IFNULL(payable, 0) AS amount_payable
                  FROM collection 
                  WHERE share_id = '$share_id' AND cus_mapping_id = '$cus_mapping_id' 
                  AND group_id = '$group_id' 
                  AND cus_id = '$cus_id' 
                  AND auction_month ='$auction_month'
                  ORDER BY created_on DESC
                  LIMIT 1";
        $stmt = $this->pdo->query($qury);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $amount_collected = $row['amount_collected'];
            $amount_payable = $row['amount_payable'];

            if ($amount_collected >= $amount_payable) {
                $coll_status = 'Paid';
            } else {
                $due_date = date('Y-m-t', strtotime($auction_month . '-01'));
                $current_date = date('Y-m-d');
                if ($current_date > $due_date) {
                    $coll_status = 'Unpaid';
                }
            }
        } else {
            $due_date = date('Y-m-t', strtotime($auction_month . '-01'));
            $current_date = date('Y-m-d');

            if ($current_date > $due_date) {
                $coll_status = 'Unpaid';
            }
        }

        return $coll_status; // Return the status for use in your table
    }
}
?>
