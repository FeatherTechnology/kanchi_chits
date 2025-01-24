<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];
$result = 0;
$cnt = '0';

// Check if the document is used in the 'noc' table
$qry = $pdo->query("SELECT * FROM `noc` WHERE doc_id='$id'");
if ($qry->rowCount() > 0) {
    $cnt = '1';
}

if ($cnt == '1') {
    $result = '2'; // Document is used in 'noc', cannot delete.
} else {
    // Fetch the uploaded file's path from the 'document_info' table
    $qry = $pdo->query("SELECT upload FROM `document_info` WHERE id='$id'");
    if ($qry->rowCount() > 0) {
        $row = $qry->fetch();
        $filePath = "../../uploads/doc_info/" . $row['upload'];

        // Check if the file exists and is a valid file before attempting to delete
        if (!empty($row['upload']) && file_exists($filePath) && is_file($filePath)) {
            unlink($filePath); // Delete the file
        }

        // Delete the document info record from the database
        $qry = $pdo->query("DELETE FROM `document_info` WHERE id='$id'");
        if ($qry) {
            $result = 1; // Deletion successful
        } else {
            $result = 3; // Error during deletion
        }
    } else {
        $result = 3; // No document found with the given ID
    }
}

$pdo = null; // Close connection.

echo json_encode($result);
?>
