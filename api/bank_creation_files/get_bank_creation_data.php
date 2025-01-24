
<?php
require '../../ajaxconfig.php';

$id = isset($_POST['id']) ? $_POST['id'] : null; // Check if id is set

$response = array();

if ($id !== null) {
    $qry = $pdo->prepare("SELECT * FROM `bank_creation` WHERE id=:id");
    $qry->bindParam(':id', $id, PDO::PARAM_INT);
    $qry->execute();

    if ($qry->rowCount() > 0) {
        $response = $qry->fetchAll(PDO::FETCH_ASSOC);
    }
}

$pdo = null; // Close connection.

echo json_encode($response);
?>
