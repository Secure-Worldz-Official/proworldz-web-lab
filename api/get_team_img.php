<?php
require_once 'dbconfig.php';
$db = new DBconfig();
if (isset($_GET['id'])) {
    $img = $db->getTeamProfile($_GET['id']);
    if ($img) {
        header("Content-Type: image/png");
        echo $img;
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}
?>
