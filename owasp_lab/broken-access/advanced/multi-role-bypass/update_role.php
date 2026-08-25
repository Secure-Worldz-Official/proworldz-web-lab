<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
if (!isset($_SESSION['uid'])) die("Unauthorized");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['role'])) {
        $_SESSION['role'] = $_POST['role'];
        $stmt = $con->prepare("UPDATE users_owasp SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $_POST['role'], $_SESSION['uid']);
        $stmt->execute();
    }
    header("Location: dashboard.php?message=Profile+Updated");
}
