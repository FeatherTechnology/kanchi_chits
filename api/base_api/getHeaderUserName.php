<?PHP
session_start();

include('../../ajaxconfig.php');

$user_id = $_SESSION["user_id"];
$response = array();

$qry = $pdo->prepare("SELECT name FROM users where `id` = ?");
$qry->execute(array($user_id));

if ($qry->rowCount() > 0) {
    while ($row = $qry->fetch()) {
        $response['user_name'] = $row['name'];
    }
}

echo json_encode($response);