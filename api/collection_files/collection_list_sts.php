<?php
require '../../ajaxconfig.php';
@session_start();

class CollectStsClass
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function updateCollectStatus($cus_id, $id)
    {
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Fetch all groups for the customer
        $qry1 = "SELECT DISTINCT ad.group_id
             FROM auction_details ad
         LEFT JOIN group_share gs ON
    ad.group_id = gs.grp_creation_id
    LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
LEFT JOIN customer_creation cc ON
    gs.cus_id = cc.id
             WHERE cc.cus_id = '$cus_id'
               AND ad.status IN (2, 3) AND YEAR(ad.date) = '$currentYear'
                           AND MONTH(ad.date) = '$currentMonth' ";

        $statement = $this->pdo->query($qry1);

        $groups = $statement->fetchAll(PDO::FETCH_ASSOC);

        $overallStatus = 'Payable';

        foreach ($groups as $group) {
            $group_id = $group['group_id'];

            // Fetch count of customer mappings for this group
            $qryCount = "SELECT id as cc_id
                     FROM group_share
                     WHERE grp_creation_id = '$group_id'
                       AND cus_id = '$id'";

            $stmtCount = $this->pdo->query($qryCount);

            $mappings = $stmtCount->fetchAll(PDO::FETCH_ASSOC);

            // Check payment status for each mapping
            foreach ($mappings as $mapping) {
                $map_id = $mapping['cc_id'];

                $qry2 = "SELECT gs.coll_status
                     FROM group_share gs
                     WHERE gs.id =  '$map_id'
                       AND gs.grp_creation_id='$group_id'";

                $stmt2 = $this->pdo->query($qry2);

                $result = $stmt2->fetch(PDO::FETCH_ASSOC);


                $coll_status = $result['coll_status'] ?? 'Payable'; // Default to 'Payable' if no status is found

                // If any mapping is 'Payable', set overall status to 'Payable'
                if ($coll_status === 'Payable') {
                    $overallStatus = 'Payable';
                    break 2; // Exit both loops early as we found a 'Payable' status
                }
            }
        }

        return $overallStatus;
    }
}
