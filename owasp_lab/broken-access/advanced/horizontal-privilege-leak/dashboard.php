<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }
$stmt = $con->prepare("SELECT * FROM reports_owasp WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['uid']);
$stmt->execute();
$reports_result = $stmt->get_result();
include 'templates/header.php';
?>
<h3>My Reports</h3>
<ul class="list-group">
    <?php while($r = $reports_result->fetch_assoc()): ?>
        <li class="list-group-item"><a href="report.php?id=<?= $r['id'] ?>"><?= htmlspecialchars($r['title']) ?></a></li>
    <?php endwhile; ?>
</ul>
<?php include 'templates/footer.php'; ?>
