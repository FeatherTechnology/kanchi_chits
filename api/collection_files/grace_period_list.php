<?php
require '../../ajaxconfig.php';
@session_start();

class GraceperiodClass
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function updateGraceStatus($cus_id, $id)
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $current_date = date('Y-m-d');
        $status_color = 'orange'; // Default to orange unless a red status is found

        // Step 1: Fetch all the group IDs for the given customer
        $qry = "SELECT DISTINCT ad.group_id
                FROM auction_details ad
                LEFT JOIN group_share gs ON
    ad.group_id = gs.grp_creation_id
    LEFT JOIN group_cus_mapping gcm ON
    gs.cus_mapping_id = gcm.id
LEFT JOIN customer_creation cc ON
    gs.cus_id = cc.id
                WHERE cc.cus_id = '$cus_id'
                  AND ad.status IN (2, 3)";
        $statement = $this->pdo->query($qry);
        $groups = $statement->fetchAll(PDO::FETCH_ASSOC);

        $found_unpaid = false;  // Flag to track if any unpaid amount is found

        // Step 2: Check unpaid amount for each group
        foreach ($groups as $group) {
            $group_id = $group['group_id'];

            $qryCount = "SELECT id as map_id
                         FROM group_share
                         WHERE grp_creation_id = '$group_id'
                           AND cus_id = '$id' AND coll_status='Payable'"; // Fetch all instances of the customer in the group

            $stmtCount = $this->pdo->query($qryCount);
            $mappings = $stmtCount->fetchAll(PDO::FETCH_ASSOC);

            // Check if there are any mappings; if not, skip the group
            if (count($mappings) > 0) {
                // Check payment status for each mapping
                foreach ($mappings as $mapping) {
                    $mapping_id = $mapping['map_id'];

                    // Query to fetch unpaid amounts (chit_amount - collection_amount)
                    $qry1 = "SELECT
   (COALESCE(SUM(ad.chit_amount),
    0)* gs.share_percent / 100) - COALESCE(
        (
        SELECT
            SUM(c.collection_amount)
        FROM
            collection c
        WHERE
            c.share_id = '$mapping_id' AND c.group_id = '$group_id' AND(
                c.collection_date <= NOW() OR c.collection_date IS NULL)
            ),
            0
    ) AS unpaid_amount
FROM
    auction_details ad
      JOIN group_share gs ON
    ad.group_id = gs.grp_creation_id
WHERE
    ad.group_id = '$group_id' AND  gs.id = '$mapping_id' AND ad.status IN(2, 3) AND(
        YEAR(ad.date) < YEAR(CURRENT_DATE) OR(
            YEAR(ad.date) = YEAR(CURRENT_DATE) AND MONTH(ad.date) < MONTH(CURRENT_DATE)
        )
    );
";

                    $result = $this->pdo->query($qry1)->fetch(PDO::FETCH_ASSOC);
                    $unpaid_amount = $result['unpaid_amount'] ?? 0;

                    // If any unpaid amount is found, set the flag and break the loop
                    if ($unpaid_amount > 0) {
                        $found_unpaid = true;
                        break; // No need to check further mappings in this group
                    }
                }
                // If any unpaid amount is found, return 'red'
                if ($found_unpaid) {
                    return 'red';
                }
                // Step 3: If no unpaid amount, check the grace period


                // Query to fetch grace period and auction date for the group
                $qry2 = "SELECT 
                                gc.grace_period, 
                                ad.date 
                            FROM auction_details ad 
                            LEFT JOIN group_creation gc ON ad.group_id = gc.grp_id
                            WHERE ad.group_id = '$group_id'
                              AND YEAR(ad.date) = '$currentYear'
                              AND MONTH(ad.date) = '$currentMonth'";

                $mapped = $this->pdo->query($qry2)->fetchAll(PDO::FETCH_ASSOC);

                // Check if there are any mappings; if not, skip the group
                if (count($mapped) > 0) {
                    foreach ($mapped as $row) {
                        $grace_period = $row['grace_period'] ?? 0;
                        $date = $row['date'] ?? '';

                        if (!empty($date)) {
                            $grace_end_date = date('Y-m-d', strtotime($date . ' + ' . $grace_period . ' days'));

                            // If the payment is missed after the grace period, return 'red'
                            if ($grace_end_date < $current_date) {
                                return 'red';
                            }
                        }
                    }
                }
            }
        }

        // If no group is in a red state, return orange (default)
        return $status_color;
    }
}
