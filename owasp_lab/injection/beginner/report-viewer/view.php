<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once dirname(__DIR__, 4) . '/owasp_lab/db.php';

$id = $_GET['id'] ?? 1;
$result = $con->query("SELECT * FROM reports_owasp WHERE id = " . (int)$id);
$report = $result ? $result->fetch_assoc() : null;

include 'templates/header.php';
?>
<div class="row justify-content-center py-5">
    <div class="col-md-8">
        <?php if ($report): ?>
        <div class="mb-4">
            <a href="index.php" class="btn btn-link text-dark ps-0 small"><i class="bi bi-arrow-left"></i> Infrastructure Repository</a>
        </div>
        <div class="card p-5 border-0 shadow-lg rounded-5 bg-white">
            <h1 class="fw-bold mb-2"><?= htmlspecialchars($report['title']) ?></h1>
            <p class="text-muted small border-bottom pb-4 mb-4">Sector: <?= htmlspecialchars($report['department']) ?> | Analytics Access: <?= htmlspecialchars($report['access_level']) ?></p>
            <div class="report-content lh-lg text-secondary">
                <?= nl2br(htmlspecialchars($report['content'])) ?>
            </div>
            <div class="mt-5 p-4 bg-light rounded-4 border border-dashed font-monospace text-center">
                <h6 class="fw-bold x-small text-muted mb-2">CRYPTOGRAPHIC INTEGRITY HASH</h6>
                <div class="text-primary"><?= md5($report['secret_key'] ?? '') ?></div>
                <small class="x-small opacity-50 mt-2 d-block">Source code: SEC-KEY-<?= htmlspecialchars($report['secret_key'] ?? 'MISSING') ?></small>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-danger">Report not found.</div>
        <a href="index.php" class="btn btn-dark">Back to Repository</a>
        <?php endif; ?>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
