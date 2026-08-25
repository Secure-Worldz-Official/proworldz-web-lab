<?php
/**
 * Nexora Runtime - Logical Exception Chain
 * // validation enforced across the execution chain
 */

session_start();

$step1_valid = false;
$step2_error = false;
$final_state = "DENIED";

if (isset($_POST['process'])) {
    try {
        // Step 1: Pre-validation (Fails for 'admin' string)
        if ($_POST['username'] === 'admin') {
             throw new Exception("STEP_1_FAILED");
        }
        $step1_valid = true;
        
        // Step 2: Complex calculation that might fail
        if (isset($_POST['input']) && strlen($_POST['input']) > 10) {
             throw new Exception("STEP_2_EXCEEDED");
        }

    } catch (Exception $e) {
        // VULNERABILITY (A10): Exception Chain Break
        // The code catches the exception but fails to TERMINATE the script.
        // It sets a local 'error' flag but the final logic doesn't check it correctly.
        $step2_error = true;
    }
    
    // VULNERABLE: Chain continues here. If step1 was false, we should stop.
    // If an exception was thrown in step 1, $step1_valid is false.
    // But if we then check if 'action' is set, we might bypass the check.
    
    if (isset($_POST['action']) && $_POST['action'] === 'DEPLOY') {
         // This block should only be reachable if step1 passed.
         // But the developer forgot to wrap this in an IF($step1_valid).
         $final_state = "BYPASS_SUCCESS";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Exception Chain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #0f172a; color: #f8fafc; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <h2 class="fw-bold mb-4 text-white">Execution Chain Monitor</h2>

        <?php if ($final_state === 'BYPASS_SUCCESS'): ?>
            <div class="alert alert-success border-0 p-5 rounded-5 shadow-lg">
                <h4 class="fw-bold">CHAIN_VALIDATION_BYPASS</h4>
                <p class="small">Administrative instructions accepted skipping Step 1 validation.</p>
                <hr>
                <div class="fw-bold text-dark">CORE_FLAG: FLAG{a10_chain_break_05}</div>
            </div>
            <a href="index.php" class="btn btn-outline-light btn-sm mt-3">Reset Chain</a>
        <?php else: ?>
            <div class="card p-5 bg-slate-900 border-slate-800 rounded-5 shadow text-white">
                <h5 class="fw-bold mb-4">Multi-Stage Instruction</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="x-small fw-bold opacity-50 mb-1">STAGING_IDENTIFIER</label>
                        <input type="text" name="username" class="form-control bg-transparent border-slate-700 text-white" placeholder="admin" required>
                    </div>
                    <div class="mb-4">
                        <label class="x-small fw-bold opacity-50 mb-1">PAYLOAD_INPUT</label>
                        <input type="text" name="input" class="form-control bg-transparent border-slate-700 text-white" placeholder="standard_task" required>
                    </div>
                    <input type="hidden" name="process" value="1">
                    <!-- User must manually provide 'action' to trigger the bypass -->
                    <input type="hidden" name="action" value="DEPLOY">
                    <button class="btn btn-primary w-100 py-3 fw-bold shadow">Initialize Deployment</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
