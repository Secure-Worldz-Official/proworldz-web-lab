<?php
/**
 * Nexora Business Workflow - Global Account Management
 * // user state verified via hybrid authority system
 */

session_start();

// Mock initial session
if (!isset($_SESSION['user_role'])) {
    $_SESSION['user_id'] = 'Emp_401';
    $_SESSION['user_role'] = 'guest';
}

// VULNERABILITY (A06): Insecure Design - Role Confusion / Trusting User Input
// The system tries to be helpful by allowing a 'role' override for development/testing,
// but it checks $_REQUEST or $_GET and trusts it over the session role.
// Design flaw: Client-controlled role can override server-side identity.

$active_role = $_GET['role'] ?? $_SESSION['user_role'];

$msg = "";
if ($active_role === 'admin') {
    $msg = "<div class='card border-0 shadow-lg p-5 bg-dark text-white rounded-5 mb-5'>";
    $msg .= "<h2 class='fw-bold text-warning'>NEXORA ROOT CONSOLE</h2>";
    $msg .= "<p class='opacity-50 small mb-4'>Full system administrative privileges active.</p>";
    $msg .= "<div class='p-3 bg-white bg-opacity-10 border border-white border-opacity-10 rounded text-center'>";
    $msg .= "MASTER_KEY: <span class='fw-bold'>FLAG{a06_role_confusion_06}</span>";
    $msg .= "</div>";
    $msg .= "</div>";
} else {
    $msg = "<div class='card border-0 shadow-sm p-5 bg-white rounded-5 mb-5'>";
    $msg .= "<h2 class='fw-bold'>Standard Employee Dashboard</h2>";
    $msg .= "<p class='text-muted small'>Log ID: " . $_SESSION['user_id'] . "</p>";
    $msg .= "<div class='alert alert-info py-2 small'>You have read-only access to corporate assets.</div>";
    $msg .= "</div>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Account Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #f4f6f8; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <?= $msg ?>
        <div class="row g-4">
             <div class="col-md-6">
                <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
                    <h6 class="fw-bold small opacity-50 mb-3">SYSTEM STATUS</h6>
                    <p class="mb-0 small">Authorization Module: <b>Active</b></p>
                    <p class="mb-0 small">Hybrid Role Engine: <span class="text-success fw-bold">v3.4-PRO</span></p>
                </div>
             </div>
             <div class="col-md-6">
                <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
                    <h6 class="fw-bold small opacity-50 mb-3">USER CONTEXT</h6>
                    <p class="mb-0 small">Current Identity: <code><?= $_SESSION['user_id'] ?></code></p>
                    <p class="mb-0 small">Active Role: <span class="badge bg-dark"><?= htmlspecialchars($active_role) ?></span></p>
                </div>
             </div>
        </div>
    </div>
</body>
</html>
