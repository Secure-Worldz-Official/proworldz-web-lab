<?php
/**
 * Nexora Integrity - Deployment Orchestrator
 * // update integrity verified
 */

session_start();

$updates = [
    ['id' => 'SYS-001', 'title' => 'Kernel Security Patch', 'ver' => '4.2.1', 'critical' => true],
    ['id' => 'UI-FIX', 'title' => 'UX Polish & Styles', 'ver' => '1.0.8', 'critical' => false]
];

$msg = "";
if (isset($_GET['source'])) {
    $source = $_GET['source'];
    
    // VULNERABILITY (A08): Software or Data Integrity Failure
    // Blindly "imports" and "runs" a package from a provided source URL/path.
    // In a supply chain / integrity attack, an attacker provides a malicious source.

    if (strpos($source, 'malicious') !== false) {
        $msg = "<div class='alert alert-warning border-warning p-4 rounded-4 shadow-sm'>";
        $msg .= "<h5 class='fw-bold'>SYSTEM COMPROMISE DETECTED</h5>";
        $msg .= "<p class='mb-0 small'>Update from $source installed successfully. Integrity chain broken.</p>";
        $msg .= "<hr><p class='fw-bold mb-0'>FLAG: FLAG{a08_update_inject_03}</p>";
        $msg .= "</div>";
    } else {
        $msg = "<div class='alert alert-secondary p-4 rounded-4 small'>Initializing mirror connection to $source... [INTEGRITY_PENDING]</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Update Importer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #111827; color: #f9fafb; font-family: 'Consolas', monospace; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 900px;">
        <h2 class="fw-bold mb-5"><i class="bi bi-cloud-download"></i> Deployment Orchestrator</h2>
        
        <?= $msg ?>

        <div class="row g-4 mt-2">
            <?php foreach($updates as $u): ?>
            <div class="col-md-6">
                <div class="card bg-gray-800 border-gray-700 p-4 rounded-4 h-100 text-white" style="background: #1f2937; border: 1px solid #374151;">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="badge <?= $u['critical'] ? 'bg-danger' : 'bg-info' ?>"><?= $u['id'] ?></span>
                        <span class="x-small opacity-50">v<?= $u['ver'] ?></span>
                    </div>
                    <h5 class="fw-bold mb-3"><?= $u['title'] ?></h5>
                    <a href="?source=https://mirrors.nexora.dev/updates/<?= $u['id'] ?>.pkg" class="btn btn-outline-primary btn-sm mt-auto py-2 fw-bold">Import from Mirror</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="mt-5 text-center x-small opacity-30">
            Internal Note: Developer custom mirror can be injected via the <code>source</code> parameter.
        </div>
    </div>
</body>
</html>
