<?php
/**
 * Nexora Secure Vault - Global Operations
 * // crypto01: AES-256 enabled with persistent master key
 */

define('MASTER_KEY', 'NEXORA_SECURE_2024_KEY_1337');

function decryptSystemSecret($data) {
    // VULNERABILITY: Key is hardcoded and used with weak XOR for simulation
    $key = MASTER_KEY;
    $out = "";
    for($i = 0; $i < strlen($data); $i++) {
        $out .= $data[$i] ^ $key[$i % strlen($key)];
    }
    return $out;
}

// System secret (FLAG{a04_key_exposure_05}) encrypted with MASTER_KEY
// Raw: FLAG{a04_key_exposure_05}
// Encrypted: (will generate below)
$blob = "\x0a\x09\x19\x18\x2a\x10\x31\x32\x5f\x03\x00\x1c\x5f\x30\x3d\x30\x3a\x36\x30\x32\x3d\x5f\x30\x30\x7d";
$blob = bin2hex($blob);

if(isset($_GET['k']) && $_GET['k'] === MASTER_KEY) {
    $flag = decryptSystemSecret(hex2bin($blob));
} else {
    $flag = "*************************";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Key Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Enterprise Key Management</h2>
        
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card p-4 rounded-4 border-0 shadow-sm">
                    <h6 class="fw-bold text-muted small mb-3">PROTECTED BLOB</h6>
                    <div class="p-3 bg-dark text-warning rounded font-monospace small mb-3">
                        <?= $blob ?>
                    </div>
                    <form method="GET">
                        <div class="input-group">
                            <input type="text" name="k" class="form-control" placeholder="Enter API Key">
                            <button class="btn btn-dark">Decrypt</button>
                        </div>
                    </form>
                    <div class="mt-4 p-3 border-start border-4 border-primary bg-primary bg-opacity-10">
                        Result: <code class="fw-bold"><?= $flag ?></code>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card p-4 rounded-4 border-0 shadow-sm bg-info bg-opacity-10 text-info">
                    <h6 class="fw-bold small mb-2"><i class="bi bi-info-circle"></i> Security Notice</h6>
                    <p class="small mb-0 opacity-75">All master keys are stored in <code>config.php</code> within a restricted environment. (VULNERABILITY HINT: Search for hardcoded keys in source if you could steal it)</p>
                    <hr>
                    <small class="x-small">Key Policy: NEX-2024-STD-V1</small>
                </div>
            </div>
        </div>
    </div>
    <!-- HIDDEN DEBUG SOURCE HINT: Check for key constant 'MASTER_KEY' -->
</body>
</html>
