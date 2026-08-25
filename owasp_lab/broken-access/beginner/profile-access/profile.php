<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = $_GET['id'] ?? $_SESSION['user_id'];

$stmt = $con->prepare("SELECT * FROM users_owasp WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();

if (!$profile) {
    die("User not found.");
}

include 'templates/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h3 class="mb-4">User Profile: <?= htmlspecialchars($profile['username']) ?></h3>
            <p><strong>Role:</strong> <?= htmlspecialchars($profile['role']) ?></p>
            <p><strong>Bio:</strong></p>
            <div class="alert alert-info">
                <?= htmlspecialchars($profile['bio']) ?>
            </div>
            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
