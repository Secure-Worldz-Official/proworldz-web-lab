<?php
/**
 * Nexora Audit - Monitoring Dashboard
 * // alerts properly configured for high-frequency failures
 */

session_start();

if (!isset($_SESSION['failed_count'])) $_SESSION['failed_count'] = 0;

$alert = "";
if (isset($_GET['retry'])) {
    // VULNERABILITY (A09): Alert Bypass / Blind Brute-Force
    // The system alerts after 5 failed attempts, but the counter can be reset by a parameter.

    if (isset($_GET['reset_counter']) && $_GET['reset_counter'] === '1') {
        $_SESSION['failed_count'] = 0;
    } else {
        $_SESSION['failed_count']++;
    }

    if ($_SESSION['failed_count'] > 5) {
        $alert = "CRITICAL_ALERT: Multiple consecutive access failures detected for sector US-East. [SEVERITY=HIGH]";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Alert Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #0f172a; color: #cbd5e1; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 700px;">
        <h3 class="fw-bold mb-4 text-white">Central Security Monitoring</h3>
        
        <?php if($alert): ?>
            <div class="alert alert-danger border-0 shadow-lg p-5 rounded-5 animate__animated animate__shakeX">
                <h4 class="fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> INTRUSION ALERT</h4>
                <p class="mb-0 small"><?= $alert ?></p>
            </div>
        <?php else: ?>
            <div class="card p-5 bg-slate-800 border-0 rounded-5 shadow text-center" style="background: #1e293b;">
                <h5 class="text-white fw-bold mb-3">Monitoring Status: <span class="text-success">NOMINAL</span></h5>
                <p class="small text-muted mb-4 text-break">Attempted failures in current window: <?= $_SESSION['failed_count'] ?> / 5</p>
                
                <div class="d-flex justify-content-center gap-3">
                    <a href="?retry=1" class="btn btn-outline-light btn-sm px-4">Perform Secure Trial</a>
                    <a href="?retry=1&bypass_bypass=1" class="btn btn-outline-primary btn-sm px-4 fw-bold">Manual Probe (Logged)</a>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-5 p-4 bg-primary bg-opacity-10 border border-primary border-opacity-10 rounded-4 text-center x-small">
             <i class="bi bi-info-circle me-1"></i> Admin Hinweis: Internal testing requires bypassing the alert counter via <code>reset_counter=1</code>.
             <?php if($_SESSION['failed_count'] > 20): ?>
                 <br><br><span class="text-success fw-bold">BYPASS_SUCCESS: FLAG{a09_alert_bypass_04}</span>
             <?php endif; ?>
        </div>
    </div>
</body>
</html>
