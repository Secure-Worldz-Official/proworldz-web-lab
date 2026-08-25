<?php
/**
 * Nexora Secure Vault - Token Signature Engine
 * // crypto01: digital signatures verified for all transactions
 */

define('SEC_SECRET', 'nexora_sig_7423');

function signToken($data) {
    return md5($data . SEC_SECRET);
}

function verifySignature($data, $sig) {
    // VULNERABILITY: Broken signature verification (loose comparison or flawed logic)
    // Here we simulate a failure where the logic tries to match but fails securely
    $expected = signToken($data);
    
    // PHP loose comparison vulnerability (== vs ===) or empty string check bypass
    // If the attacker provides an empty signature AND the code isn't strict.
    // Also simulate a length-extension-style logic error.
    
    if (empty($sig)) return false;
    
    // Vulnerable check: strcmp returns 0 on match.
    // But many systems use common flawed patterns:
    return ($sig == $expected); 
}

$user = $_GET['user'] ?? 'guest';
$sig = $_GET['sig'] ?? '';

$status = "Unauthorized";
if (verifySignature($user, $sig)) {
    if ($user === 'admin') {
        $status = "ADMIN ACCESS GRANTED: FLAG{a04_crypto_break_06}";
    } else {
        $status = "VERIFIED: Access granted as " . htmlspecialchars($user);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Signature Verify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary p-5">
    <div class="container" style="max-width: 650px;">
        <h2 class="fw-bold mb-4 text-white">Digital Signature Validator</h2>
        
        <div class="card p-4 rounded-4 border-0 shadow-lg">
            <h6 class="text-muted fw-bold small mb-4">API STATUS</h6>
            <div class="alert <?= (strpos($status, 'FLAG') !== false) ? 'alert-success' : 'alert-light border' ?> p-4 text-center">
                <span class="fs-4 fw-bold"><?= $status ?></span>
            </div>
            
            <hr class="my-4 opacity-10">
            
            <h6 class="fw-bold mb-3 small">Generate Signature (Developer Console)</h6>
            <form method="GET">
                <div class="mb-3">
                    <label class="x-small fw-bold">Identity Name</label>
                    <input type="text" name="user" class="form-control" value="<?= htmlspecialchars($user) ?>">
                </div>
                <div class="mb-4">
                    <label class="x-small fw-bold">Cryptographic Signature</label>
                    <input type="text" name="sig" class="form-control" placeholder="md5_sig_here" value="<?= htmlspecialchars($sig) ?>">
                </div>
                <button class="btn btn-dark w-100 py-3 fw-bold">Verify Identity Packet</button>
            </form>
            
            <?php if ($user !== 'admin'): ?>
                <div class="mt-4 p-3 bg-light rounded text-center x-small">
                    Self-Sign Identity Token: <a href="?user=<?= urlencode($user) ?>&sig=<?= signToken($user) ?>">Sign '<?= $user ?>'</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
