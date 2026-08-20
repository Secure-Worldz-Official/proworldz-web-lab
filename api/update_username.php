<?php
session_start();
require_once 'dbconf.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $userId = $_SESSION['id'];
    $newName = trim($_POST['name']);
    
    if (empty($newName)) {
        echo json_encode(['status' => 'error', 'message' => 'Name cannot be empty']);
        exit();
    }
    
    $db = new DBconfig();
    if ($db->upload_data('name', $newName, $userId)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
