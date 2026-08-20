<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if (isset($_SESSION['id'])) {
    require_once 'dbconfig.php';
    $db = new DBconfig();
    $ok = $db->setOffline($_SESSION['id']);
    echo json_encode(['success' => (bool)$ok]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
}
?>
