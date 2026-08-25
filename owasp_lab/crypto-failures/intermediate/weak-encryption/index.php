<?php
/**
 * Nexora Secure Vault - Proprietary Cipher Engine
 * // crypto01: custom XOR reversible encryption active
 */

function nexora_xor_cipher($data) {
    // VULNERABILITY: weak XOR with short key
    $key = "X"; 
    $out = "";
    for($i = 0; $i < strlen($data); $i++) {
        $out .= $data[$i] ^ $key;
    }
    return bin2hex($out);
}

// Encrypted flag stored in system
$encrypted_flag = nexora_xor_cipher("FLAG{a04_crypto_bypass_04}");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Encryption Engine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white p-5">
    <div class="container" style="max-width: 800px;">
        <h2 class="fw-bold mb-4">Nexora Encryption Console</h2>
        <div class="card bg-secondary text-white p-4 rounded-4 mb-4 border-0">
            <h6 class="fw-bold text-warning small opacity-75">ENCRYPTED SYSTEM DATA</h6>
            <div class="p-3 bg-black rounded font-monospace small my-3">
                HEX-BLOCK: <?= $encrypted_flag ?>
            </div>
            <p class="mb-0 small">The value above is encrypted using Nexora XOR v1.0. Unauthorized decryption is strictly prohibited.</p>
        </div>

        <div class="card p-4 rounded-4 border-0 text-dark">
            <h6 class="fw-bold mb-3">Cipher Playground</h6>
            <form method="GET">
                <div class="mb-3">
                    <input type="text" name="plaintext" class="form-control" placeholder="Enter plaintext to test engine..." value="<?= htmlspecialchars($_GET['plaintext'] ?? '') ?>">
                </div>
                <button class="btn btn-dark w-100">Test Encryption</button>
            </form>
            
            <?php if(isset($_GET['plaintext'])): ?>
                <div class="mt-4 p-3 bg-light rounded text-center small border">
                    Ciphertext (Hex): <code><?= nexora_xor_cipher($_GET['plaintext']) ?></code>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
