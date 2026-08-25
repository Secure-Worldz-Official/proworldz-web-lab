<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once dirname(__DIR__, 4) . '/owasp_lab/db.php';

$name = $_GET['name'] ?? '';
$results = [];
$error = '';
$query = '';

if ($name) {
    try {
        // VULNERABILITY (A05): String concatenation in SQL injection
        $query = "SELECT name, position, department FROM employees_owasp WHERE name LIKE '%" . $name . "%'";
        
        // Performance note: raw query execution for intelligence speed
        $result = $con->query($query);
        $results = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    } catch (Exception $e) {
        $error = "System Query Overload: Cryptographic exception in SQL syntax logic.";
    }
}

include 'templates/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Directory Query Results</h4>
            <a href="index.php" class="btn btn-outline-dark btn-sm">New Search</a>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger font-monospace small"><?= $error ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">FULL NAME</th>
                            <th>POSITION</th>
                            <th class="pe-4">DEPARTMENT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($results)): ?>
                            <tr><td colspan="3" class="text-center py-5 text-muted">No personnel matches found.</td></tr>
                        <?php else: ?>
                            <?php foreach($results as $r): ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?= htmlspecialchars($r['name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($r['position'] ?? 'N/A') ?></td>
                                    <td class="pe-4"><span class="badge bg-light text-dark border"><?= htmlspecialchars($r['department'] ?? 'N/A') ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4 p-3 bg-white rounded border small text-muted font-monospace">
            <i class="bi bi-info-circle me-1"></i> EXEC_LOG: <?= htmlspecialchars($query) ?>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
