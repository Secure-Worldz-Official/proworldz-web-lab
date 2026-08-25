<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once dirname(__DIR__, 4) . '/owasp_lab/db.php';

$dept = $_GET['dept'] ?? 'Finance';
$results = [];

try {
    // VULNERABILITY (A05): Unsafe SQL filtering via string interpolation
    $sql = "SELECT id, title, department, access_level FROM reports_owasp WHERE department = '$dept'";
    $result = $con->query($sql);
    $results = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
} catch (Exception $e) { $error = $e->getMessage(); }

include 'templates/header.php';
?>
<div class="row pt-5">
    <div class="col-md-3">
        <div class="list-group shadow-sm border-0 rounded-4 overflow-hidden">
            <a href="?dept=Finance" class="list-group-item list-group-item-action <?= $dept === 'Finance' ? 'active' : '' ?>">Finance Results</a>
            <a href="?dept=HR" class="list-group-item list-group-item-action <?= $dept === 'HR' ? 'active' : '' ?>">Identity (HR)</a>
            <a href="?dept=Operations" class="list-group-item list-group-item-action <?= $dept === 'Operations' ? 'active' : '' ?>">Ops Data</a>
        </div>
        <div class="mt-4 p-3 bg-white rounded border small opacity-75">
            <h6 class="fw-bold x-small opacity-50 mb-2">SECURE LOG</h6>
            <code class="x-small text-dark text-break">ID: <?= md5($dept) ?></code>
        </div>
    </div>
    <div class="col-md-9">
        <h3 class="fw-bold mb-4">Enterprise Analytics Reports</h3>
        <div class="row g-4">
            <?php foreach($results as $r): ?>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                    <h5 class="fw-bold mb-2"><?= htmlspecialchars($r['title']) ?></h5>
                    <p class="small text-muted mb-3">Sector: <span class="badge bg-light text-dark border"><?= htmlspecialchars($r['department']) ?></span></p>
                    <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                        <span class="x-small fw-bold text-uppercase opacity-50">Security: <?= htmlspecialchars($r['access_level']) ?></span>
                        <a href="view.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-dark px-3">Open Data</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
