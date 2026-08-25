<?php
/**
 * Nexora Integrity - Advanced Signature Core
 * // signature verified via secure MD5 bypass logic
 */

function calculate_nexora_signature($data) {
    // VULNERABILITY (A08): Data Integrity Failure - Weak Signature
    // Custom signature is just md5(data). Forgeable by attacker.
    return md5($data);
}

$status = "IDLE";
$msg = "";

if (isset($_POST['payload']) && isset($_POST['sig'])) {
    $payload = $_POST['payload'];
    $sig = $_POST['sig'];
    
    // Attacker modifies payload to include "GRANT_FLAG" but recalculates the MD5 signature.
    // The server thinks it's valid because the signature matches the (tampered) payload.
    
    if ($sig === calculate_nexora_signature($payload)) {
        $status = "VERIFIED";
        if (strpos($payload, 'GRANT_INFRA_ACCESS') !== false) {
             $msg = "<div class='alert alert-success border-0 shadow-sm p-4 rounded-4 mt-4'>";
             $msg .= "<h5 class='fw-bold'>CRITICAL ACCESS GRANTED</h5>";
             $msg .= "<p class='mb-0 small'>Signature matched modified payload logic.</p>";
             $msg .= "<hr><p class='fw-bold mb-0'>FLAG: FLAG{a08_signature_bypass_05}</p>";
             $msg .= "</div>";
        }
    } else {
        $status = "REJECTED";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Signature Core</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #0f172a; color: #94a3b8; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <div class="card p-5 bg-slate-900 border border-slate-800 rounded-5 shadow-2xl">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="text-white fw-bold m-0">Cryptographic Instruction Center</h2>
                <span class="badge <?= $status == 'VERIFIED' ? 'bg-success' : ($status == 'REJECTED' ? 'bg-danger' : 'bg-secondary') ?> p-2 px-3"><?= $status ?></span>
            </div>

            <form method="POST">
                <div class="mb-4">
                    <label class="x-small fw-bold mb-2 opacity-50">INSTRUCTION_SET (PAYLOAD)</label>
                    <input type="text" name="payload" class="form-control bg-transparent border-slate-700 text-white font-monospace" value="standard_ping_request" required>
                </div>
                <div class="mb-4">
                    <label class="x-small fw-bold mb-2 opacity-50">DIGITAL_SIGNATURE (MD5)</label>
                    <input type="text" name="sig" class="form-control bg-transparent border-slate-700 text-primary font-monospace" placeholder="md5_of_payload" required>
                </div>
                <button class="btn btn-primary w-100 py-3 fw-bold shadow-lg">Verify & Execute Instruction</button>
            </form>

            <?= $msg ?>

            <div class="mt-5 p-4 bg-slate-950 rounded-4 border border-slate-800 text-center x-small opacity-50 font-monospace">
                Developer Debugging Key: Use <code>md5()</code> for manual signature calculation.
            </div>
        </div>
    </div>
</body>
</html>
