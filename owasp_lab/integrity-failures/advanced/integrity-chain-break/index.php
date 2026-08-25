<?php
/**
 * Nexora Integrity - Chain Verification Orchestrator
 * // integrity verified via 3-stage validation pipeline
 */

session_start();

$step = $_GET['step'] ?? 1;
$msg = "";

// VULNERABILITY (A08): Software or Data Integrity Failure - Skip Verification Step
// Step 1: Upload (Simulation)
// Step 2: Verify (Checksum comparison)
// Step 3: Apply Update
// Attacker can skip Step 2 and go straight to Step 3 with a 'verified' flag or similar logic flaw.
// Design flaw: The final 'apply' logic only checks if the 'apply' parameter is set, not if 'verify' was successful.

if ($step == 3) {
    if (isset($_GET['source']) && isset($_GET['verified'])) {
        $msg = "<div class='alert alert-warning border-warning p-4 rounded-4 shadow-sm text-center'>";
        $msg .= "<h4 class='fw-bold'>CHAIN_INTEGRITY_SUCCESS</h4>";
        $msg .= "<p class='mb-0 small'>Integrity chain completed with state bypass.</p>";
        $msg .= "<hr><p class='fw-bold mb-0'>FLAG: FLAG{a08_integrity_chain_06}</p>";
        $msg .= "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Integrity Chain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fdfdfd; font-family: 'Inter', sans-serif; }
        .step-circle { width: 35px; height: 35px; line-height: 35px; text-align: center; border-radius: 50%; background: #e2e8f0; color: #64748b; font-weight: bold; }
        .active-step { background: #0f172a; color: white; }
    </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 700px;">
        <h2 class="fw-bold mb-5 text-center">Multi-Stage Integrity Pipeline</h2>
        
        <div class="d-flex justify-content-center align-items-center gap-3 mb-5">
            <div class="step-circle <?= $step == 1 ? 'active-step' : '' ?>">1</div>
            <div class="border-top w-25"></div>
            <div class="step-circle <?= $step == 2 ? 'active-step' : '' ?>">2</div>
            <div class="border-top w-25"></div>
            <div class="step-circle <?= $step == 3 ? 'active-step' : '' ?>">3</div>
        </div>

        <?php if ($msg): ?>
            <?= $msg ?>
        <?php else: ?>
            <div class="card p-5 border-0 shadow-sm rounded-5 bg-white text-center">
                <?php if ($step == 1): ?>
                    <h4 class="fw-bold mb-3">Payload Staging</h4>
                    <p class="text-muted small">Select the infrastructure bundle to upload to the staging area.</p>
                    <a href="?step=2&source=patch_v8.bundle" class="btn btn-dark px-5 py-2">Stage Bundle</a>
                <?php elseif ($step == 2): ?>
                    <h4 class="fw-bold mb-3 text-warning">Integrity Verification</h4>
                    <p class="text-muted small">The system is performing a deep-packet inspection and checksum comparison. [IN_PROGRESS]</p>
                    <div class="spinner-border text-warning mt-3 mb-4" role="status"></div>
                    <p class="x-small text-muted mb-0">System Log: Mirroring patch_v8.bundle to Secure_Vault...</p>
                    <hr class="my-4">
                    <a href="?step=3&source=patch_v8.bundle&verified=true" class="btn btn-outline-dark btn-sm">Force Release [DEBUG ONLY]</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="mt-4 text-center x-small text-muted opacity-30">
            Secure Pipeline v9.3. Integrity of intermediate stage must be maintained.
        </div>
    </div>
</body>
</html>
