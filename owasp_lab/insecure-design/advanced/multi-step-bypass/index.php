<?php
/**
 * Nexora Business Workflow - Multi-Step Asset Allocation
 * // secure multi-step pipeline active
 */

session_start();

$step = $_POST['step'] ?? 1;
$msg = "";

// VULNERABILITY (A06): Insecure Design - Multi-step form bypass
// Step 1: Input details
// Step 2: Verification (backend supposed to check here)
// Step 3: Deployment
// Attacker can issue a direct POST to Step 3 with 'step=3', bypassing any Step 2 logic.
// The code blindly processes Step 3 if the parameter is present.

if ($step == 3) {
    if (isset($_POST['asset_id']) && isset($_POST['department'])) {
        $msg = "<div class='alert alert-success border-0 p-4 shadow rounded-4'>";
        $msg .= "<h4 class='fw-bold'>Asset Allocation Confirmed</h4>";
        $msg .= "<p class='mb-0 small'>Resource " . htmlspecialchars($_POST['asset_id']) . " deployed to " . htmlspecialchars($_POST['department']) . " sector.</p>";
        $msg .= "<hr><p class='fw-bold mb-0'>BYPASS_FLAG: FLAG{a06_multi_step_skip_05}</p>";
        $msg .= "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Asset Allocation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f1f5f9; font-family: 'Inter', sans-serif; }
        .step-indicator { width: 40px; height: 40px; line-height: 40px; text-align: center; border-radius: 50%; background: #cbd5e1; color: white; display: inline-block; }
        .active { background: #0f172a; }
    </style>
</head>
<body class="py-5">
    <div class="container" style="max-width: 600px;">
        <h2 class="fw-bold mb-4 text-center">New Asset Allocation</h2>
        
        <div class="text-center mb-5 d-flex justify-content-center align-items-center gap-3">
             <div class="step-indicator <?= $step == 1 ? 'active' : '' ?>">1</div>
             <div class="progress w-25" style="height: 2px;"><div class="progress-bar bg-dark" style="width: 100%"></div></div>
             <div class="step-indicator <?= $step == 2 ? 'active' : '' ?>">2</div>
             <div class="progress w-25" style="height: 2px;"><div class="progress-bar bg-dark" style="width: 0%"></div></div>
             <div class="step-indicator <?= $step == 3 ? 'active' : '' ?>">3</div>
        </div>

        <?php if ($msg): ?>
            <?= $msg ?>
        <?php else: ?>
            <div class="card p-5 border-0 shadow-sm rounded-5 bg-white">
                <?php if ($step == 1): ?>
                    <form method="POST">
                        <h4 class="fw-bold mb-4">Resource Identification</h4>
                        <div class="mb-3">
                            <label class="small fw-bold opacity-50">ASSET_ID</label>
                            <input type="text" name="asset_id" class="form-control" placeholder="NEX-771" required>
                        </div>
                        <div class="mb-4">
                            <label class="small fw-bold opacity-50">TARGET_DEPARTMENT</label>
                            <input type="text" name="department" class="form-control" placeholder="IT-Security" required>
                        </div>
                        <input type="hidden" name="step" value="2">
                        <button class="btn btn-dark w-100 py-3 fw-bold">Next: Security Clearance</button>
                    </form>
                <?php elseif ($step == 2): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-clock-history fs-1 text-warning"></i>
                        <h4 class="fw-bold mt-3">Verifying Credentials</h4>
                        <p class="text-muted small">Our automated system is performing a background check on the allocation request. Please wait...</p>
                        <div class="spinner-border text-dark mt-3" role="status"></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
