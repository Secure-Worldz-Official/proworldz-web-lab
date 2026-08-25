<?php
/**
 * Nexora Data Intelligence - Log Entry Gateway
 * // sanitized request received
 */

session_start();

$logs = isset($_SESSION['logs']) ? $_SESSION['logs'] : [
    "[" . date('H:i:s') . "] System Audit: SECURE_SCAN_COMPLETED",
    "[" . date('H:i:s') . "] Intelligence Node: AUTH_SUCCESS_ADMIN"
];

if (isset($_POST['command'])) {
    $cmd = $_POST['command'];
    
    // VULNERABILITY (A05): log injection
    // User input written directly into logs without sanitization
    
    $entry = "[" . date('H:i:s') . "] Operator Input: " . $cmd;
    $logs[] = $entry;
    $_SESSION['logs'] = $logs;
    
    if (strpos($cmd, 'FLAG') !== false) {
        $logs[] = "[INTEGRITY_ALERT] Potential Flag Disclosure: FLAG{a05_log_inject_03}";
        $_SESSION['logs'] = $logs;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Log Engine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0b0e14; color: #a9b1d6; font-family: 'JetBrains Mono', monospace; }
        .log-box { background: #1a1b26; border: 1px solid #24283b; height: 500px; overflow-y: auto; padding: 20px; }
        .command-input { background: #24283b; border: none; color: #7aa2f7; }
    </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 900px;">
        <h4 class="mb-4 text-white fw-bold"><i class="bi bi-terminal"></i> NEXORA INTEL LOG_ENGINE [v3.1]</h4>
        
        <div class="log-box rounded-4 mb-4 shadow-2xl">
            <?php foreach($logs as $l): ?>
                <div class="mb-1 small opacity-75">> <?= $l ?></div>
            <?php endforeach; ?>
        </div>

        <form method="POST" class="mt-4">
            <div class="input-group">
                <span class="input-group-text command-input border-0 text-success opacity-50">$</span>
                <input type="text" name="command" class="form-control command-input" placeholder="Execute analyzer command..." required autofocus>
                <button class="btn btn-primary px-4 fw-bold shadow-lg">Submit Command</button>
            </div>
        </form>
        <p class="mt-4 x-small text-muted text-center opacity-30">Identity verified by hardware security module. Commands logged for compliance.</p>
    </div>
</body>
</html>
