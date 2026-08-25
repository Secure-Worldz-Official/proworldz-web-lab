<?php
/**
 * Nexora Audit - External Log Collector
 * // secure logging enabled via authenticated append
 */

$log_file = 'external_audit.log';
if (!file_exists($log_file)) {
    file_put_contents($log_file, "[2024-05-10 08:00] NODE_INIT: Primary mirror operational.\n");
}

if (isset($_POST['log_entry'])) {
    $entry = $_POST['log_entry'];
    
    // VULNERABILITY (A09): Log Tampering / Injection
    // The system allows users to append arbitrary messages directly to the audit log.
    // No sanitation or integrity verification on the message.

    $timestamp = date('Y-m-d H:i');
    $formatted = "[$timestamp] USER_SUBMISSION: $entry\n";
    file_put_contents($log_file, $formatted, FILE_APPEND);
    
    if (strpos($entry, 'CLEAR_DATABASE') !== false) {
        $msg = "ALERT: Malicious intent detected in logs. FLAG: FLAG{a09_log_tamper_03}";
    }
}

$logs = file_get_contents($log_file);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | External Collector</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #fdfdfd; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <h2 class="fw-bold mb-4">Enterprise Event Sink</h2>
        
        <?php if(isset($msg)): ?><div class="alert alert-danger font-monospace small"><?= $msg ?></div><?php endif; ?>

        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
            <h6 class="text-muted small fw-bold mb-3 text-uppercase opacity-50">Log Submit Portal</h6>
            <form method="POST">
                <div class="input-group">
                    <input type="text" name="log_entry" class="form-control" placeholder="Reporting system status: OK" required>
                    <button class="btn btn-dark">Append to Audit</button>
                </div>
            </form>
        </div>

        <div class="bg-black text-info p-4 rounded-4 shadow-lg font-monospace small" style="height: 350px; overflow-y: auto;">
             <?= nl2br(htmlspecialchars($logs)) ?>
        </div>
    </div>
</body>
</html>
