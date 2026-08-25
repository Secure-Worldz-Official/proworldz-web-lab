<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
include 'templates/header.php';
?>
<h3>User Dashboard</h3>
<p>Welcome back, user.</p>
<!-- VULNERABILITY: Admin link is hidden but the page 'admin.php' is accessible -->
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <div class="alert alert-success">You are admin. <a href="admin.php">Go to Admin Panel</a></div>
<?php else: ?>
    <div class="alert alert-secondary">Standard user account. No administrative features available.</div>
<?php endif; ?>
<?php include 'templates/footer.php'; ?>
