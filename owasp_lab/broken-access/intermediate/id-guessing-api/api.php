<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
header('Content-Type: application/json');

// VULNERABILITY: API does not check session or ownership.
$user_id = $_GET['user_id'] ?? 0;

$stmt = $con->prepare("SELECT id, username, secret_token FROM users_owasp WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if ($data) {
    echo json_encode(['status' => 'success', 'data' => $data]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
}
