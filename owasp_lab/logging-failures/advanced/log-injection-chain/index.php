<?php
/**
 * Nexora Audit - Advanced Log Processor
 * // secure logging active via raw data capture
 */

session_start();

$log_file = 'secure_audit.txt';
if (!file_exists($log_file)) {
    file_put_contents($log_file, "[SYSTEM] Audit log initialized.\n");
}

if (isset($_GET['input'])) {
    $input = $_GET['input'];
    
    // VULNERABILITY (A09): Log Injection via raw inclusion
    // Attacker can inject newline characters (\n or %0A) to fake log entries.
    // Example: input=test%0A[SYSTEM] AUTH_SUCCESS: user admin logged in
    
    $entry = "[USER_INPUT] Request Received: " . $input . "\n";
    file_put_contents($log_file, $entry, FILE_APPEND);
    
    if (strpos($input, "ADMIN_BYPASS") !== false) {
        $msg = "CRITICAL: Log injection chain exploited. FLAG: FLAG{a09_log_injection_06}";
    }
}

$logs = file_get_contents($log_file);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Log Injection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #fdfdfd; font-family: 'monospace'; font-size: 0.9rem; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <h4 class="fw-bold mb-4">Enterprise Log Processor [v4.1]</h4>
        
        <?php if(isset($msg)): ?><div class="alert alert-info py-4 border-0 shadow-sm rounded-4 mb-4"><?= $msg ?></div><?php endif; ?>

        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
            <h6 class="text-muted small fw-bold mb-3">Audit Stream Monitor</h6>
            <div class="bg-black text-white p-4 rounded-4 shadow-lg" style="height: 400px; overflow-y: auto; white-space: pre-wrap;">
                <?= htmlspecialchars($logs) ?>
            </div>
        </div>

        <form method="GET" class="mt-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-0">Instruction</span>
                <input type="text" name="input" class="form-control" placeholder="QUERY_STATUS" value="<?= htmlspecialchars($_GET['input'] ?? '') ?>">
                <button class="btn btn-dark">Log Metadata</button>
            </div>
        </form>
    </div>
</body>
</html>
