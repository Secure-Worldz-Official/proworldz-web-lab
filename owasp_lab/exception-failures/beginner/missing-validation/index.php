<?php
/**
 * Nexora Runtime - Input Validation Gateway
 * // validation enforced at entry point
 */

session_start();

$msg = "";
if (isset($_GET['proc_id'])) {
    $proc_id = $_GET['proc_id'];
    
    // VULNERABILITY (A10): Mishandling of Exceptional Conditions - Missing Validation
    // The system expects a numeric ID but doesn't validate it.
    // Invalid input (strings/arrays) causes the internal logic to fail silently or leak flags.

    if ($proc_id === 'ROOT_ACCESS_REQUEST') {
        $msg = "<div class='alert alert-warning p-4 rounded-4 border-warning shadow-sm'>";
        $msg .= "<h5 class='fw-bold'>ANOMALY_DETECTED</h5>";
        $msg .= "<p class='mb-0 small'>Runtime failed to validate PROC_ID type. Flag disclosed via unplanned state.</p>";
        $msg .= "<hr><p class='fw-bold mb-0'>FLAG: FLAG{a10_missing_validation_02}</p>";
        $msg .= "</div>";
    } else if (is_numeric($proc_id)) {
        $msg = "<div class='alert alert-success small'>Processing Request ID: $proc_id... COMPLETED.</div>";
    } else {
        $msg = "<div class='alert alert-secondary small'>Unrecognized Protocol Stack. Standby.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Action Validation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #f1f5f9; font-family: 'Segoe UI', serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card border-0 shadow-lg p-5 rounded-5 bg-white text-center">
            <h3 class="fw-bold mb-4">Request Validation Engine</h3>
            <p class="text-muted small mb-5">Enter your unique Process ID to begin execution cycle.</p>
            
            <?= $msg ?>

            <form method="GET" class="mt-4">
                <input type="text" name="proc_id" class="form-control text-center mb-4 py-3 fw-bold fs-4" placeholder="0000" required>
                <button class="btn btn-primary w-100 py-2 fw-bold shadow">Initiate Process</button>
            </form>
        </div>

        <div class="mt-4 p-3 border border-dashed rounded text-center x-small opacity-50">
            Note: Process IDs must be valid Nexora-assigned integers.
        </div>
    </div>
</body>
</html>
