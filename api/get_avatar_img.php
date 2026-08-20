<?php
require_once 'dbconfig.php';
require_once 'avatar_helper.php';

$db = new DBconfig();
$name = $_GET['name'] ?? '';

if (!empty($name)) {
    $userId = $db->getIdbyName($name);
    if ($userId) {
        $avatarPath = getActiveAvatarImage($db, $userId);
        $filePath = '../' . $avatarPath;
        if (file_exists($filePath)) {
            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
            if ($ext === 'jpg') $ext = 'jpeg';
            header("Content-Type: image/" . $ext);
            readfile($filePath);
            exit;
        }
    }
}

header("Content-Type: image/png");
if (file_exists("../image.webp")) {
    readfile("../image.webp");
} else {
    echo "";
}
?>
