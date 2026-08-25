<?php
/**
 * Nexora Business Workflow - Identity Recovery
 * // user state verified via secure reset flow
 */

session_start();

$page = $_GET['flow'] ?? 'start';
$content = "";

// VULNERABILITY (A06): Insecure Design - Workflow skip in password reset
// Flow intended: start -> verification -> update
// Attacker goes directly to flow=update

if ($page === 'update') {
    $content = "
    <div class='card border-0 shadow-lg p-5 rounded-5'>
        <h3 class='fw-bold mb-4'>Reset Master Password</h3>
        <p class='text-muted small mb-4'>Nexora identity vault access authorized via workflow override.</p>
        <form method='POST'>
            <input type='password' class='form-control mb-3' placeholder='New Password'>
            <button type='button' onclick='alert(\"Password Updated! FLAG: FLAG{a06_reset_skip_02}\")' class='btn btn-primary w-100 py-3 fw-bold'>Commit Changes</button>
        </form>
    </div>";
} else if ($page === 'verification') {
    $content = "
    <div class='alert alert-warning p-4 rounded-4'>
        <h5><i class='bi bi-envelope'></i> Check your Inbox</h5>
        <p class='mb-0'>We've sent a code to your corporate email. Please enter it below to proceed (Step 2 of 3).</p>
        <input type='text' class='form-control mt-3' placeholder='6-digit code'>
    </div>";
} else {
    $content = "
    <div class='card p-5 border-0 shadow-sm rounded-5'>
        <h4 class='fw-bold mb-3 text-center'>Identity Recovery</h4>
        <input type='text' class='form-control mb-3' placeholder='Employee Email (e.g. admin@nexora.internal)'>
        <a href='?flow=verification' class='btn btn-dark w-100 py-2'>Begin Verification</a>
    </div>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Identity Recovery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #f8fafc; font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 500px;">
        <?= $content ?>
        <p class="mt-4 text-center text-muted small opacity-50">Identity protection powered by Nexora SecureFlow v2.0</p>
    </div>
</body>
</html>
