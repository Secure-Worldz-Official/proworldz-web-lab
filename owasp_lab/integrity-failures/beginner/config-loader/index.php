<?php
/**
 * Nexora Integrity - Enterprise Configuration Loader
 * // trusted configuration loaded with integrity verification
 */

$config_file = 'config_v1.json';
if (!file_exists($config_file)) {
    file_put_contents($config_file, json_encode([
        'system_name' => 'Nexora Core',
        'auth_mode' => 'standard',
        'debug' => false,
        'flag' => 'FLAG{a08_config_tamper_01}'
    ]));
}

$file = $_GET['file'] ?? $config_file;
$msg = "";

// VULNERABILITY (A08): Software and Data Integrity Failure
// Loads configuration from a file path provided via GET.
// No integrity check or signature validation on the file content.
// Attacker can manipulate the file or point it to a malicious local file.

try {
    $content = @file_get_contents($file);
    if ($content === false) throw new Exception("Configuration Mirror Unreachable.");
    
    $config = json_decode($content, true);
    if (!$config) throw new Exception("Integrity Mapping Error: Invalid JSON Payload.");

    $msg = "Configuration [ ".htmlspecialchars($file)." ] Loaded Successfully.";
} catch (Exception $e) {
    $msg = "<span class='text-danger'>ERROR: " . $e->getMessage() . "</span>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Config Loader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .config-card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <h2 class="fw-bold mb-4">Infrastructure Config Manager</h2>
        <div class="card config-card p-4 mb-4">
            <h6 class="text-muted small fw-bold mb-3">LOADER STATUS</h6>
            <div class="alert alert-info py-2 px-3 small border-0"><?= $msg ?></div>
            
            <form method="GET" class="mt-3">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0 small">Mirror Path</span>
                    <input type="text" name="file" class="form-control" value="<?= htmlspecialchars($file) ?>">
                    <button class="btn btn-dark px-4">Fetch Config</button>
                </div>
            </form>
        </div>

        <?php if(isset($config)): ?>
        <div class="row g-4">
            <?php foreach($config as $k => $v): ?>
            <div class="col-md-6">
                <div class="card p-3 bg-white border-0 shadow-sm rounded-4">
                    <label class="x-small fw-bold opacity-50 text-uppercase"><?= $k ?></label>
                    <div class="fw-bold fs-5 <?= ($k == 'flag') ? 'text-primary' : '' ?>"><?= is_bool($v) ? ($v ? 'TRUE' : 'FALSE') : htmlspecialchars($v) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="mt-5 text-center x-small text-muted opacity-50">
            System validated by Nexora Integrity Engine v4.2.1-lts
        </div>
    </div>
</body>
</html>
