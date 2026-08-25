<?php
/**
 * Nexora Audit - Secure Event Collector
 * // full audit trail active
 */

session_start();

// MOCK DATA for logs
$log_file = 'audit_events.txt';
if (!file_exists($log_file)) {
    file_put_contents($log_file, "[2024-05-10 09:12:31] SYSTEM_STARTUP: Monitoring node online.\n");
    file_put_contents($log_file, "[2024-05-10 10:45:12] AUTH_SUCCESS: user_jdoe logged in.\n", FILE_APPEND);
}

$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;

// VULNERABILITY (A09): Missing Logging for Critical Actions
// Creating a security administrator or accessing the vault does NOT generate a log entry.

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'escalate') {
        $_SESSION['is_admin'] = true;
        $isAdmin = true;
        // NO LOGGING HERE -> BLIND ESCALATION
    }
}

// LOG VIEWER
$events = explode("\n", trim(file_get_contents($log_file)));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Audit Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .log-viewer { background: #1e293b; color: #94a3b8; font-family: 'Consolas', monospace; height: 350px; overflow-y: auto; padding: 20px; border-radius: 12px; }
    </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <h2 class="fw-bold mb-4">Security Event Collector</h2>
        
        <?php if ($isAdmin): ?>
            <div class="alert alert-success border-0 shadow-sm p-4 rounded-4 mb-4">
                <h5 class="fw-bold text-dark">ADMINISTRATIVE ACCESS GRANTED</h5>
                <p class="mb-0 small text-muted">You have reached the unrestricted audit core.</p>
                <hr>
                <div class="fw-bold text-danger">SYSTEM_FLAG: FLAG{a09_missing_logs_01}</div>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
            <h6 class="text-muted small fw-bold mb-3">CONSOLIDATED AUDIT TRAIL</h6>
            <div class="log-viewer mb-4">
                <?php foreach($events as $e): ?>
                    <div class="mb-1 small">> <?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
            
            <div class="d-flex justify-content-between align-items-center">
                <span class="x-small text-muted italic">Total events captured: <?= count($events) ?></span>
                <a href="?action=escalate" class="btn btn-outline-dark btn-sm">Request Root Elevation</a>
            </div>
        </div>

        <div class="mt-5 text-center x-small text-muted opacity-50">
            Audit logging powered by Nexora LogStream v8.2.3
        </div>
    </div>
</body>
</html>
