<?php
/**
 * Nexora Runtime - Verbosity Controller
 * // error handled securely and masked for production
 */

session_start();

$error_log = [];
$content = "";

if (isset($_GET['source'])) {
    try {
        $source = $_GET['source'];
        
        // VULNERABILITY (A10): Mishandling of Exceptional Conditions - Verbose Errors
        // If the file doesn't exist, it throws a catchable error but displays the FULL message.
        // Attacker uses this to discover internal directory structure or config file names.

        if (!file_exists($source)) {
            throw new Exception("RUNTIME_ERROR: Resource [ " . realpath($source) . " ] not found in Nexora VFS Cluster.");
        }
        $content = file_get_contents($source);
    } catch (Exception $e) {
        // VULNERABLE: Direct echo of exception message leaking system paths
        $content = "<div class='alert alert-danger font-monospace small border-0 shadow-sm p-4 rounded-4'>";
        $content .= "<h5 class='fw-bold'>UNHANDLED_EXCEPTION</h5>";
        $content .= "<code>" . $e->getMessage() . "</code><br><br>";
        $content .= "<div class='mt-2 p-2 bg-dark text-warning rounded'>DEBUG_HINT: Nexora Registry Flag is stored at 'kernel_config.php'</div>";
        $content .= "</div>";
    }
}

// Secret file simulation
if (isset($_GET['source']) && basename($_GET['source']) === 'kernel_config.php') {
    $content = "<div class='p-4 bg-success bg-opacity-10 text-success border border-success rounded-4'>";
    $content .= "<h5 class='fw-bold'>SECURE_KERNEL_LOADED</h5>";
    $content .= "FLAG: FLAG{a10_verbose_error_01}";
    $content .= "</div>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Verbose Errors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #f8fafc; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <h2 class="fw-bold mb-4">Application Runtime Mirror</h2>
        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
            <h6 class="text-muted small fw-bold mb-3">RESOURCE_QUERY_INTERFACE</h6>
            <form method="GET" class="mb-0">
                <div class="input-group">
                    <input type="text" name="source" class="form-control" placeholder="app_module.json" value="<?= htmlspecialchars($_GET['source'] ?? '') ?>">
                    <button class="btn btn-dark px-4">Load Resource</button>
                </div>
            </form>
        </div>

        <div class="card border-0 shadow-sm p-5 bg-white rounded-5 min-vh-25">
            <?= $content ?: "<div class='text-center text-muted opacity-50 py-5'>Waiting for runtime instruction...</div>" ?>
        </div>
        
        <div class="mt-5 text-center x-small text-muted opacity-50">
           Platform Error Level: E_ALL | Log: NEX_RUNTIME.log
        </div>
    </div>
</body>
</html>
