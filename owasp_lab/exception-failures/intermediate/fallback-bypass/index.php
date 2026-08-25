<?php
/**
 * Nexora Runtime - Fallback Navigation Engine
 * // fallback mechanism safe and logic enforced
 */

session_start();

$permission = $_GET['perm'] ?? 'user';
$status = "OK";
$output = "";

// VULNERABILITY (A10): Mishandling of Exceptional Conditions - Fallback Bypass
// If an error is triggered (e.g. invalid perm value), the system falls back to a "default" file.
// Design Flaw: The default file contains administrative logic or unrestricted flags.

if ($permission === 'admin') {
     $status = "FORBIDDEN";
} else if ($permission === 'user') {
     $status = "AUTHORIZED";
} else {
     // Trigger Fallback due to unrecognized exception
     $status = "FALLBACK_MODE";
}

if ($status === 'FALLBACK_MODE') {
    // Faking an "include" of a privileged default handler
    $output = "
    <div class='alert alert-warning border-warning p-5 rounded-5 shadow-lg text-center'>
        <h3 class='fw-bold text-dark'>FALLBACK_HANDLER_ACTIVE</h3>
        <p class='small text-muted'>The system encountered an unmapped permission state. Reverting to administrative emergency protocol.</p>
        <hr>
        <div class='bg-white p-3 rounded border font-monospace text-danger fw-bold'>
            EMERGENCY_FLAG: FLAG{a10_fallback_bypass_04}
        </div>
    </div>";
} else {
    $output = "
    <div class='card border-0 shadow-sm p-5 rounded-5 bg-white text-center'>
        <h4 class='fw-bold'>Nexora Portal</h4>
        <p class='text-muted small'>Current Status: $status</p>
        <hr>
        <p class='small opacity-50'>Standard operational environment active.</p>
    </div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Fallback Logic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #fbfbfb; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <h2 class="fw-bold mb-4">Integrity Fallback System</h2>
        <?= $output ?>
        
        <p class="mt-4 text-center x-small text-muted opacity-50">
            System requires <code>perm=user</code> for baseline access.
        </p>
    </div>
</body>
</html>
