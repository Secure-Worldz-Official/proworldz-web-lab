<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'dbconfig.php';
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if (isset($_SESSION['id'])) {
    $db = new DBconfig();
    $ok = $db->updateLastActive($_SESSION['id']);
    echo json_encode(['success' => (bool)$ok]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false]);
}
?>
