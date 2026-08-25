<?php
/**
 * Nexora Business Workflow - Approval Gateway
 * // workflow validated successfully
 */

session_start();

$step = $_GET['step'] ?? 'initial';
$msg = "";

// VULNERABILITY (A06): Insecure Design - Step bypass
// Attacker can skip 'hr_approval' and 'manager_review' by going straight to 'final'
// The backend trusts the 'step' parameter without verifying if previous steps were completed.

if ($step === 'final') {
    $msg = "<div class='alert alert-success border-0 shadow-sm p-4 rounded-4'>";
    $msg .= "<h4 class='fw-bold text-success'><i class='bi bi-check-circle-fill'></i> Request Finalized</h4>";
    $msg .= "<p class='mb-0 small'>Corporate Expense reimbursement authorized. System ID: NEX-8821</p>";
    $msg .= "<hr><p class='fw-bold mb-0'>FLAG: FLAG{a06_flow_bypass_01}</p>";
    $msg .= "</div>";
} else if ($step === 'hr_approval') {
    $msg = "<div class='alert alert-info'>Step 1: HR Review in progress. Expected completion: 24h.</div>";
} else {
    $msg = "<div class='bg-light p-5 rounded-5 border border-dashed text-center'>";
    $msg .= "<h5 class='fw-bold'>New Reimbursement Request</h5>";
    $msg .= "<p class='text-muted small'>Submit your corporate expense for multi-layer approval.</p>";
    $msg .= "<a href='?step=hr_approval' class='btn btn-dark px-5'>Submit to HR</a>";
    $msg .= "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Expense Approvals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7fa; font-family: 'Inter', sans-serif; }
        .workflow-step { opacity: 0.5; }
        .active-step { opacity: 1; border-left: 4px solid #0d6efd; }
    </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <h2 class="fw-bold mb-4">Nexora Business Workflow</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-3 mb-2 <?= $step == 'hr_approval' ? 'active-step shadow-sm' : 'workflow-step' ?>">
                    <h6 class="fw-bold mb-0 small">Step 1</h6>
                    <span class="x-small text-muted">HR Approval</span>
                </div>
                <div class="card p-3 mb-2 workflow-step">
                    <h6 class="fw-bold mb-0 small">Step 2</h6>
                    <span class="x-small text-muted">Manager Review</span>
                </div>
                <div class="card p-3 mb-2 <?= $step == 'final' ? 'active-step shadow-sm' : 'workflow-step' ?>">
                    <h6 class="fw-bold mb-0 small">Step 3</h6>
                    <span class="x-small text-muted">CFO Finalization</span>
                </div>
            </div>
            <div class="col-md-8">
                <?= $msg ?>
            </div>
        </div>
    </div>
</body>
</html>
