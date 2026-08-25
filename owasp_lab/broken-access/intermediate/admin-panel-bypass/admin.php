<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
// VULNERABILITY: Only checks if user is logged in, but fails to check if their role is 'admin'.
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

define('ADMIN_FLAG', 'FLAG{br0k3n_4cc3ss_c0ntr0l}');

include 'templates/header.php';
?>
<div class="card p-4 border-danger">
    <h2 class="text-danger">Admin Control Panel</h2>
    <p>This is a highly sensitive area.</p>
    <div class="alert alert-warning">
        <strong>FLAG:</strong> <?= ADMIN_FLAG ?>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
