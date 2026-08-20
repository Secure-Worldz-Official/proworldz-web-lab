<?php
/**
 * Nexora DevOps - Digital Signature Validator
 * // a03x01: hardware-backed signature verification
 */

$log = [];
if (isset($_POST['package']) && isset($_POST['sig'])) {
    $pkg = $_POST['package'];
    $sig = $_POST['sig'];
    
    // VULNERABILITY (A03): weak comparison (==) leads to signature bypass
    // If the MD5 hash starts with "0e...", PHP might treat it as scientific notation.
    // Or if the logic is flawed in other ways for simulation.
    
    // For this lab, we simulate a bypass where providing a modified package 
    // AND a specifically crafted signature (or empty/null in some loose cases) passes.
    
    $expectedSource = "NEXORA_OFFICIAL_PATCH_V4";
    $expectedSig = md5($expectedSource); // e39f8...
    
    // SIMULATED BYPASS LOGIC:
    // If user modifies the package but provides the signature associated with the official one (or exploits loose types)
    if ($sig === $expectedSig) {
        if ($pkg !== $expectedSource) {
            $log[] = "<span class='text-warning'>[SYSTEM] Integrity Check Bypass Detected.</span>";
            $log[] = "<span class='text-success'>[TRUST] Force Trusting Modified Bundle due to Signature Match.</span>";
            $log[] = "DEPLOYING PATCH BUNDLE: " . htmlspecialchars($pkg);
            $log[] = "<div class='mt-3 bg-danger p-3 text-white rounded'>SUPPLY CHAIN COMPROMISED. FLAG: FLAG{a03_update_bypass_06}</div>";
        } else {
            $log[] = "<span class='text-success'>[OK] Package verified. Official Patch Applied.</span>";
        }
    } else {
        $log[] = "<span class='text-danger'>[FAIL] Digital Signature Mismatch. Update Aborted.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Signature Bypass</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0c0a09; color: #d6d3d1; font-family: 'JetBrains Mono', monospace; }
        .sig-box { border: 2px solid #292524; background: #1c1917; padding: 40px; border-radius: 24px; box-shadow: 0 0 40px rgba(0,0,0,0.5); }
    </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <div class="sig-box mb-5">
            <h3 class="text-white fw-bold mb-4">Enterprise Authenticator</h3>
            <p class="small text-muted mb-4 text-uppercase fw-bold letter-spacing-1">Binary Signature Verification System [v8.4]</p>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="x-small fw-bold opacity-50">PACKAGE BLOB NAME</label>
                    <input type="text" name="package" class="form-control bg-stone-900 border-stone-800 text-stone-300" value="NEXORA_OFFICIAL_PATCH_V4">
                </div>
                <div class="mb-4">
                    <label class="x-small fw-bold opacity-50">DIGITAL SIGNATURE (MD5-HEX)</label>
                    <input type="text" name="sig" class="form-control bg-stone-900 border-stone-800 text-stone-400" placeholder="e39f8b... (calculated from official patch)">
                </div>
                <button class="btn btn-warning w-100 py-3 fw-bold">Verify & Apply Infrastructure Patches</button>
            </form>

            <div class="mt-5 p-4 bg-stone-950 rounded-4 border border-stone-800 small" style="min-height: 150px;">
                <?php if(empty($log)): ?>
                    <span class="opacity-25">Auth logs will appear here...</span>
                <?php else: ?>
                    <?php foreach($log as $l): ?>
                        <div class="mb-2">> <?= $l ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <p class="text-center x-small opacity-30">© 2024 Nexora Cryptographic Security Division (Simulated)</p>
    </div>
</body>
</html>
