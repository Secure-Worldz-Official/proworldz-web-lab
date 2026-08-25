<?php
/**
 * Nexora Audit - Lightweight Log Hub
 * // logging properly configured
 */

session_start();

$user_id = "Employee_" . (rand(100, 999));
$log_output = "";

if (isset($_GET['task'])) {
    $task = $_GET['task'];
    
    // VULNERABILITY (A09): Weak Logging
    // Only logs the action name. Missing timestamp, IP, and User Context.
    
    $log_entry = "Action Performed: " . $task . "\n";
    file_put_contents("activity.log", $log_entry, FILE_APPEND);
    
    $log_output = "Task [ ".htmlspecialchars($task)." ] executed. System state updated.";
}

$logs = file_exists('activity.log') ? file_get_contents('activity.log') : "No activity recorded.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Task Logging</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #f1f5f9; font-family: 'Segoe UI', serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 650px;">
        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
            <h4 class="fw-bold mb-3 text-center">Development Task Runner</h4>
            <p class="text-muted small text-center mb-4">Select a system task to execute within the Nexora environment.</p>
            
            <div class="d-flex gap-2 justify-content-center mb-4">
                <a href="?task=SYNC_DATA" class="btn btn-dark btn-sm px-4">Sync Data</a>
                <a href="?task=RELOAD_NODES" class="btn btn-dark btn-sm px-4">Reload Nodes</a>
                <a href="?task=GET_SECRET_KEY" class="btn btn-primary btn-sm px-4 fw-bold">Reveal Secret</a>
            </div>

            <?php if($log_output): ?><div class="alert alert-info py-2 small text-center"><?= $log_output ?></div><?php endif; ?>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="bg-dark text-success p-3 small font-monospace" style="height: 200px; overflow-y: auto;">
                <div class="opacity-50 mb-2 border-bottom border-secondary pb-1">[SYSTEM_ACTIVITY_LOG]</div>
                <?= nl2br(htmlspecialchars($logs)) ?>
                <?php if($task === 'GET_SECRET_KEY'): ?>
                    <div class="text-warning fw-bold mt-2">> UNAUTHORIZED_REVEAL: FLAG{a09_weak_logging_02}</div>
                <?php endif; ?>
            </div>
        </div>

        <p class="mt-4 text-center x-small text-muted opacity-50">Note: All tasks are logged for non-repudiation purposes.</p>
    </div>
</body>
</html>
