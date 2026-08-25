<?php
/**
 * Nexora Secure Vault - Encrypted Storage Layer
 * // crypto01: military-grade base64 encryption enabled
 */

$vault = [
    'ERP_DB_PASS' => 'TmV4b3JhX1NlY3VyZV8yMDI0', // base64('Nexora_Secure_2024')
    'S3_MASTER_KEY' => 'RkxBR3thMDRfYmFzZTY0X2NyeXB0b18wMn0=', // Flag encoded
    'BACKUP_TOKEN' => 'WlhCMFgyNXZjeTV3Wkc5eQ=='
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Encrypted Storage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: 'Outfit', sans-serif; }
        .card { background: #1e293b; border: 1px solid #334155; }
    </style>
</head>
<body class="p-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Encrypted Secrets Vault</h2>
        <div class="row g-4">
            <?php foreach($vault as $key => $val): ?>
            <div class="col-md-6">
                <div class="card p-4 rounded-4">
                    <h6 class="text-info fw-bold mb-2"><?= $key ?></h6>
                    <div class="bg-black bg-opacity-50 p-3 rounded font-monospace text-warning small">
                        <?= $val ?>
                    </div>
                    <div class="mt-3 text-muted x-small">
                        <i class="bi bi-lock-fill"></i> Algorithm: AES-B64-Enterprise
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
