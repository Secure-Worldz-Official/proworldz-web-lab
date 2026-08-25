<?php
/**
 * Nexora Business Workflow - Product Feature Hub
 * // secure feature pipeline active
 */

session_start();

$is_premium = isset($_GET['premium']) && $_GET['premium'] === 'true';

// VULNERABILITY (A06): Insecure Design - Trusting client-side feature flags
// The system base its feature availability on a simple URL parameter.
// No backend verification of user subscription state or account tier is performed.

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Feature Activation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="p-5">
    <div class="container py-5">
        <h1 class="fw-bold mb-4">Nexora Feature Roadmap</h1>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-5 bg-dark text-white border-secondary h-100 rounded-5">
                    <h4 class="fw-bold">Standard Analytics</h4>
                    <p class="text-secondary small">Access to basic infrastructure metrics and logs.</p>
                    <button class="btn btn-outline-secondary disabled w-100 mt-auto">Active</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-5 <?= $is_premium ? 'border-primary shadow-lg border-2' : 'bg-dark bg-opacity-50 text-secondary border-secondary opacity-50' ?> h-100 rounded-5 transition">
                    <h4 class="<?= $is_premium ? 'text-primary' : '' ?> fw-bold">Enterprise Predictive AI</h4>
                    <p class="small">Real-time threat detection and AI-powered forecasting.</p>
                    
                    <?php if ($is_premium): ?>
                        <div class="mt-4 p-3 bg-primary bg-opacity-10 border border-primary text-primary rounded small">
                            <b>PREMIUM ACCESS ACTIVATED</b><br>
                            SEC_KEY: FLAG{a06_feature_unlock_04}
                        </div>
                        <button class="btn btn-primary w-100 mt-auto py-2 fw-bold">Explore Analytics</button>
                    <?php else: ?>
                        <p class="text-danger fw-bold x-small p-2 bg-danger bg-opacity-10 rounded text-center">UPGRADE REQUIRED</p>
                        <button class="btn btn-outline-secondary w-100 mt-auto" disabled>Purchase Upgrade</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="mt-5 p-4 border border-secondary border-dashed rounded-4 text-center text-muted small">
            <i class="bi bi-info-circle me-1"></i> Developer Notice: Test enterprise features by enabling the <code>premium</code> operational flag in the debug URL.
        </div>
    </div>
</body>
</html>
