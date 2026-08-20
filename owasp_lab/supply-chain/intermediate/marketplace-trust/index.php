<?php
/**
 * Nexora DevOps - Marketplace Hub
 * // trusted plugin source verified by team
 */

$plugins = [
    ['id' => 'logs', 'name' => 'Log Guardian', 'author' => 'Nexora Internal', 'verified' => true, 'url' => 'modules/logs.php'],
    ['id' => 'git', 'name' => 'GitSync Pro', 'author' => 'Git-Commits Ltd', 'verified' => true, 'url' => 'modules/git_sync.php'],
    ['id' => 'external', 'name' => 'Custom Cloud Hook', 'author' => 'Third-Party', 'verified' => false, 'url' => 'modules/cloud_hook.php']
];

$msg = "";
if (isset($_GET['source'])) {
    // VULNERABILITY (A03): trust based on source URL provided by user, only visual verification on frontend.
    $source = $_GET['source'];
    if (strpos($source, 'malicious') !== false) {
        $msg = "System Compromised: Untrusted Marketplace Provider allowed code injection.<br><b>FLAG: FLAG{a03_marketplace_bypass_04}</b>";
    } else {
        $msg = "Module initialized from $source. (Verification check simulated)";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .v-check { color: #3b82f6; cursor: help; }
        .marketplace-header { background: white; border-bottom: 2px solid #e2e8f0; padding: 3rem 0; }
    </style>
</head>
<body>
    <div class="marketplace-header mb-5">
        <div class="container text-center">
            <h1 class="fw-bold">Nexora App Marketplace</h1>
            <p class="text-muted">Trusted integrations for your DevOps pipeline.</p>
        </div>
    </div>

    <div class="container" style="max-width: 900px;">
        <?php if($msg): ?><div class="alert alert-info border-0 shadow-sm p-4 rounded-4 mb-5 text-center"><?= $msg ?></div><?php endif; ?>
        
        <div class="row g-4">
            <?php foreach($plugins as $p): ?>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4 hvr-grow">
                    <div class="fs-1 text-primary mb-3"><i class="bi bi-box-seam"></i></div>
                    <h5 class="fw-bold mb-1"><?= $p['name'] ?></h5>
                    <p class="text-muted small mb-3">by <?= $p['author'] ?></p>
                    
                    <?php if($p['verified']): ?>
                        <div class="v-check small fw-bold mb-3 d-block"><i class="bi bi-patch-check-fill"></i> VERIFIED BY NEXORA</div>
                    <?php else: ?>
                        <div class="text-muted x-small mb-3 d-block">COMMUNITY MODULE</div>
                    <?php endif; ?>
                    
                    <a href="?source=https://marketplace.nexora.dev/download/<?= $p['id'] ?>" class="btn btn-outline-primary btn-sm w-100 py-2 fw-bold">Install & Verify</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
