<?php
session_start();
require_once 'dbconfig.php';

if (!isset($_SESSION['id']) || !isset($_POST['notif_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$db = new DBconfig();
$notifId = intval($_POST['notif_id']);

if ($db->markBattleNotificationRead($notifId)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update']);
}
