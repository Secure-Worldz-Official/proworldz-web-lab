<?php
/**
 * Nexora Integrity - Secure File Verification
 * // secure checksum validation enabled
 */

$files = [
    'kernel.bin' => 'FIPS-140-2 Core Binary',
    'auth_module.php' => 'Identity Validation Logic',
    'system_manifest.xml' => 'Global Infrastructure Manifest'
];

$verify_msg = "";
$verify_class = "alert-light border";

if (isset($_POST['file']) && isset($_POST['hash'])) {
    $target = $_POST['file'];
    $user_hash = $_POST['hash'];
    
    // VULNERABILITY (A08): Data Integrity Failure - Client-provided Checksum
    // The system calculates the hash of the file but compares it to a hash PROVIDED by the user.
    // This makes the validation meaningless as an attacker can provide the hash for their tampered file.
    
    $actual_content = "VIRTUAL_FILE_CONTENT_OF_" . $target;
    // Simulate flag in a specific "corrupted" state
    if ($target === 'kernel.bin' && $user_hash === 'a881337b04928f') {
        $verify_msg = "<h6 class='fw-bold'>INTEGRITY BYPASS DETECTED</h6>File verified with custom developer hash. <br><b>FLAG: FLAG{a08_hash_bypass_02}</b>";
        $verify_class = "alert-success border-success text-success";
    } else {
        $expected_hash = md5($actual_content);
        if ($user_hash === $expected_hash) {
            $verify_msg = "SUCCESS: Integrity checksum for [ $target ] is valid.";
            $verify_class = "alert-info border-info text-info";
        } else {
            $verify_msg = "FAIL: Integrity mismatch. Package rejected.";
            $verify_class = "alert-danger border-danger text-danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | File Checker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #f0f4f8; font-family: 'Segoe UI', serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 700px;">
        <div class="card border-0 shadow p-5 rounded-5 bg-white">
            <h3 class="fw-bold mb-4">Package Integrity Monitor</h3>
            <p class="text-muted small mb-4">Verify software integrity against the Nexora Global Checksum Registry.</p>
            
            <?php if($verify_msg): ?><div class="alert <?= $verify_class ?> p-4 rounded-4 mb-4 small"><?= $verify_msg ?></div><?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="x-small fw-bold opacity-50 mb-1">DOWNLOADED PACKAGE</label>
                    <select name="file" class="form-select">
                        <?php foreach($files as $fname => $desc): ?>
                            <option value="<?= $fname ?>"><?= $fname ?> (<?= $desc ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="x-small fw-bold opacity-50 mb-1">PROVIDED CHECKSUM (MD5-HEX)</label>
                    <input type="text" name="hash" class="form-control" placeholder="d41d8cd98f00b204e9800998ecf8427e" required>
                </div>
                <button class="btn btn-dark w-100 py-2 fw-bold">Verify Binary Integrity</button>
            </form>
        </div>
        <p class="mt-4 text-center x-small text-muted opacity-50">Authorized Checksum Engine v1.0.8. Cross-verified with NIST.</p>
    </div>
</body>
</html>
