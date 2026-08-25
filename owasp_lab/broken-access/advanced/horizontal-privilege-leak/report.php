<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }

$report_id = $_GET['id'] ?? 0;

$stmt = $con->prepare("SELECT * FROM reports_owasp WHERE id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();

if (!$report) die("Error: Report not found.");

include 'templates/header.php';
?>
<div class="card p-4">
    <h3><?= htmlspecialchars($report['title']) ?></h3>
    <p class="text-muted">Report ID: <?= $report['id'] ?></p>
    <hr>
    <div class="bg-light p-3">
        <?= htmlspecialchars($report['content']) ?>
    </div>
    <a href="dashboard.php" class="mt-3">Back to List</a>
</div>
<?php include 'templates/footer.php'; ?>
