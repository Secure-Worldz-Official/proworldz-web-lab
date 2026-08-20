<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'dbconfig.php';
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$db = new DBconfig();
echo json_encode($db->getOnlineUserIds());
?>
