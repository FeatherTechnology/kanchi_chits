<?php
require '../../ajaxconfig.php';
@session_start();

class CollectionStsClass
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function updateCollectionStatus($share_id,$group_id)
    {
        $coll_status = 'Payable'; // Default status

        // Directly interpolating variables into the SQL query
        $query = "SELECT gs.coll_status
                     FROM group_share gs 
                     WHERE gs.id = '$share_id'
                       AND gs.grp_creation_id = '$group_id'";
        
        $result = $this->pdo->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);

        // Check if any record is returned
        if ($row && isset($row['coll_status'])) {
            $coll_status = $row['coll_status']; // Set the status to the one fetched from the DB

            // If status is 'Paid', it remains 'Paid'. Otherwise, set to 'Payable'
            if ($coll_status !== 'Paid') {
                $coll_status = 'Payable';
            }
        }

        return $coll_status; // Return the status for use in your table
    }
}
?>
