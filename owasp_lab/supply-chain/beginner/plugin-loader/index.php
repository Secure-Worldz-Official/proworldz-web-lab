<?php
/**
 * Nexora DevOps - Hot-Load Plugin System
 * // package verified by Nexora Security
 */

$plugin_output = "";
if (isset($_GET['plugin_url'])) {
    $url = $_GET['plugin_url'];
    
    // VULNERABILITY (A03): Blindly fetching and "executing" code from an external URL
    // In a real supply chain attack, this would be include($url) if allowed, 
    // or file_get_contents + execution.
    
    $content = @file_get_contents($url);
    if ($content) {
        $plugin_output = "Module Loaded Successfully. Simulation Result: " . htmlspecialchars($content);
        if (strpos($url, 'malicious') !== false) {
            $plugin_output .= "<br><div class='alert alert-danger mt-2'><b>ALERT:</b> Unexpected instruction set detected. Integrity compromised.<br>FLAG: FLAG{a03_plugin_loader_01}</div>";
        }
    } else {
        $plugin_output = "Error: Could not connect to plugin mirror: " . htmlspecialchars($url);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Plugin Loader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .dev-header { background: #0f172a; color: white; padding: 2rem; border-bottom: 4px solid #3b82f6; }
    </style>
</head>
<body>
    <header class="dev-header">
        <div class="container">
            <h2 class="fw-bold m-0"><i class="bi bi-cpu"></i> Nexora Hot-Load Portal</h2>
            <p class="m-0 opacity-50 small">Automated DevOps Integration Layer v4.1</p>
        </div>
    </header>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
                    <h5 class="fw-bold mb-4">Dynamic Module Injection</h5>
                    <p class="text-muted small">Enter the official mirror URL for the plugin package you wish to deploy to the Nexora environment.</p>
                    <form method="GET">
                        <div class="input-group">
                            <input type="text" name="plugin_url" class="form-control" placeholder="https://mirrors.nexora.dev/plugins/legacy_log.php">
                            <button class="btn btn-primary px-4">Deploy Module</button>
                        </div>
                    </form>
                    <div class="mt-4 p-3 bg-light rounded text-center x-small text-muted">
                        <i class="bi bi-shield-check me-1"></i> Nexora Signature Verification Service Active
                    </div>
                </div>

                <?php if ($plugin_output): ?>
                <div class="card border-0 shadow-sm p-4 rounded-4 bg-dark text-light font-monospace small">
                    <div class="d-flex justify-content-between border-bottom border-secondary pb-2 mb-3">
                        <span>TERMINAL OUTPUT</span>
                        <span class="text-success">[ONLINE]</span>
                    </div>
                    <?= $plugin_output ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-5">
                <div class="card border-0 shadow-sm p-4 rounded-4 bg-primary text-white">
                    <h6 class="fw-bold mb-3">Developer Quick-Links</h6>
                    <ul class="list-unstyled mb-0 small opacity-75">
                        <li class="mb-2">Official Mirror: <code>https://mirrors.nexora.dev/v1</code></li>
                        <li class="mb-2">Legacy Assets: <code>https://cdn.legacy.nexora.dev/</code></li>
                        <li>Support: <code>dev-ops@nexora.internal</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
