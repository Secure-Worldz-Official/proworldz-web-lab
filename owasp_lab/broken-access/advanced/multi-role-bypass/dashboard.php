<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }
include 'templates/header.php';
?>
<h3>Role Dashboard</h3>
<p>Logged in as: <strong><?= htmlspecialchars($_SESSION['user'] ?? '') ?></strong> | Role: <strong><?= htmlspecialchars($_SESSION['role'] ?? '') ?></strong></p>
<!-- VULNERABILITY: Role is included in the hidden parameters and processed by the server -->
<form method="POST" action="update_role.php">
    <input type="hidden" name="role" value="admin">
    <button class="btn btn-warning">Claim Admin Role</button>
</form>
<?php include 'templates/footer.php'; ?>
