<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
include 'templates/header.php';
?>
<div class="container mt-5">
    <h3>Developer Console</h3>
    <p>Your User ID: <code><?= htmlspecialchars($_SESSION['user_id']) ?></code></p>
    <p>Test the internal API: <a href="api.php?user_id=<?= htmlspecialchars($_SESSION['user_id']) ?>" target="_blank">api.php?user_id=<?= htmlspecialchars($_SESSION['user_id']) ?></a></p>
</div>
<?php include 'templates/footer.php'; ?>
