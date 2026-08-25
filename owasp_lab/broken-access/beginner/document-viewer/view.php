<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once dirname(__DIR__, 4) . '/owasp_lab/db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$id = $_GET['doc_id'] ?? 0;
// VULNERABILITY: Fetches document by ID without checking ownership or is_private flag.
$stmt = $con->prepare("SELECT * FROM documents_owasp WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$doc = $stmt->get_result()->fetch_assoc();
if (!$doc) die("Document not found.");
include 'templates/header.php';
?>
<div class="card p-4">
    <h2><?= htmlspecialchars($doc['title']) ?></h2>
    <hr>
    <div class="p-3 bg-white border rounded">
        <?= nl2br(htmlspecialchars($doc['content'])) ?>
    </div>
    <a href="dashboard.php" class="btn btn-secondary mt-3">Back</a>
</div>
<?php include 'templates/footer.php'; ?>
