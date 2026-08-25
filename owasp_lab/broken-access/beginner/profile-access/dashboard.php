<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
include 'templates/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <h2>Dashboard</h2>
        <p>Welcome back, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></strong>!</p>
        <div class="card mt-4 p-4">
            <h5>Quick Actions</h5>
            <div class="list-group mt-2">
                <a href="profile.php?id=<?= $_SESSION['user_id'] ?>" class="list-group-item list-group-item-action">View My Profile</a>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
