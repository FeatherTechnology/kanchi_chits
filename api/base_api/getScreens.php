<?PHP
session_start();
$user_id = $_SESSION["user_id"];

include('../../ajaxconfig.php');

$search_content = $_POST["search_input"];
$response = array();

$sql = $pdo->prepare("SELECT sl.id, sl.sub_menu, sl.link FROM sub_menu_list sl INNER JOIN users u ON FIND_IN_SET(sl.id, u.screens) WHERE sl.sub_menu LIKE :search_content AND u.id = :user_id");
$sql->execute(array(':search_content' => "%$search_content%", ':user_id' => $user_id));

if ($sql->rowCount() > 0) {
    while ($row = $sql->fetch()) {
        $response[] = array(
            'display_name' => $row['sub_menu'],
            'module_name' => $row['link']
        );
    }
}

echo json_encode($response);
?>