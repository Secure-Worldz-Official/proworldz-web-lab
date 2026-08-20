<?php
/**
 * Nexora DevOps - Infrastructure Update Center
 * // update signature validated
 */

$status = "";
$log = [];

if (isset($_POST['update_host'])) {
    $host = $_POST['update_host'];
    
    // VULNERABILITY (A03): blindly trusting external update source
    // In a supply chain attack, an attacker would provide a malicious host
    
    $log[] = "Initializing connection to: " . htmlspecialchars($host);
    $log[] = "Requesting manifest from " . $host . "/manifest.json";
    
    // Simulate fetching and "executing" update scripts
    sleep(1);
    
    if (strpos($host, 'untrusted') !== false || strpos($host, 'attacker') !== false) {
        $log[] = "<span class='text-danger'>[ALERT] Binary signature mismatch. Force installing...</span>";
        $log[] = "Applying patch: CVE-FIX-2024.sh";
        $log[] = "<span class='text-warning'>System Integrity Failure. FLAG: FLAG{a03_update_fetch_02}</span>";
    } else {
        $log[] = "[OK] manifest.json verified";
        $log[] = "Applying official patches for v4.2.1-LTS";
        $log[] = "Update system stable.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Update Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #111827; color: white; font-family: 'Consolas', monospace; }
        .update-box { border: 1px solid #374151; background: #1f2937; margin-top: 50px; }
    </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <div class="card update-box rounded-4 overflow-hidden border-0 shadow-lg">
            <div class="bg-primary bg-opacity-20 p-4 border-bottom border-primary border-opacity-30">
                <h4 class="m-0 fw-bold"><i class="bi bi-arrow-repeat"></i> Nexora OS Update Center</h4>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small">Supply an authorized Nexora update mirror host to pull the latest infrastructure patches.</p>
                
                <form method="POST" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="update_host" class="form-control bg-dark border-secondary text-white" placeholder="https://cdn.nexora.dev" required>
                        <button class="btn btn-primary px-4 fw-bold">Pull Update</button>
                    </div>
                </form>

                <div class="bg-black bg-opacity-50 rounded-3 p-4 small" style="min-height: 200px;">
                    <?php if (empty($log)): ?>
                        <span class="opacity-25">Ready to receive update instructions...</span>
                    <?php else: ?>
                        <?php foreach($log as $entry): ?>
                            <div class="mb-2 text-info">> <?= $entry ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer bg-black bg-opacity-30 border-0 p-3 text-center opacity-50 small">
                <i class="bi bi-shield-check"></i> FIPS 140-2 Cryptographic Validation Active
            </div>
        </div>
    </div>
</body>
</html>
