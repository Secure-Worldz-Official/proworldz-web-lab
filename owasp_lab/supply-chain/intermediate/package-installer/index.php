<?php
/**
 * Nexora DevOps - Package Installation Management
 * // trusted plugin source verified
 */

$msg = "";
if (isset($_GET['install_url'])) {
    $url = $_GET['install_url'];
    
    // VULNERABILITY (A03): UI says "Verified" but backend doesn't check anything.
    // The "checksum" validation is a fake sleep to fool the user.
    
    sleep(1);
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        if (strpos($url, 'malicious') !== false) {
             $msg = "<div class='alert alert-warning border-warning'><h6 class='fw-bold'>PACKAGE REPUTATION FAIL</h6>Identity check bypassed. System integrity compromised.<br><b>FLAG: FLAG{a03_package_trust_03}</b></div>";
        } else {
             $msg = "<div class='alert alert-success'>[OK] Package from mirror successfully mirrored to Nexora internal repository.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>Invalid mirror URL structure.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Package Installer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', serif; }
        .installer-ui { max-width: 700px; margin: 50px auto; }
        .v-badge { font-size: 0.6rem; padding: 2px 8px; background: #e1f5fe; color: #0288d1; border-radius: 10px; font-weight: bold; }
    </style>
</head>
<body class="p-4">
    <div class="installer-ui">
        <div class="card border-0 shadow-sm p-5 rounded-5">
            <h3 class="fw-bold mb-1">Nexora Package Installer</h3>
            <p class="text-muted small mb-4">Enterprise distribution cluster v2.9-beta</p>
            
            <div class="p-4 bg-light border border-info border-opacity-25 rounded-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small fw-bold text-secondary">SOURCE CONFIGURATION</span>
                    <span class="v-badge"><i class="bi bi-patch-check-fill"></i> AUTO-VERIFY ACTIVE</span>
                </div>
                <form method="GET">
                    <div class="mb-3">
                        <label class="x-small fw-bold opacity-50">MIRROR URL</label>
                        <input type="text" name="install_url" class="form-control" placeholder="https://nexora.dev/packages/core-tools.tar.gz" required>
                    </div>
                    <button class="btn btn-info text-white w-100 py-2 fw-bold shadow-sm">Initialize Secure Install</button>
                </form>
            </div>

            <?= $msg ?>

            <div class="mt-4">
                <h6 class="fw-bold small opacity-50 mb-3">INTERNAL TRUSTED MIRRORS</h6>
                <div class="list-group list-group-flush rounded-3 border">
                    <div class="list-group-item d-flex justify-content-between py-2">
                        <span class="small">cdn-internal.nexora.infra</span>
                        <span class="text-success small"><i class="bi bi-check2-circle"></i> Official</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between py-2">
                        <span class="small">legacy-builds.internal</span>
                        <span class="text-warning small"><i class="bi bi-exclamation-circle"></i> Legacy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
