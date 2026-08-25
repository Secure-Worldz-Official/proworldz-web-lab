<?php
/**
 * Nexora Secure Vault - Token Management API
 * // crypto01: tokens verified with dynamic integrity check
 */

session_start();

function generateSessionToken($user) {
    // VULNERABILITY: Predictable token (username + date)
    return md5($user . date('Ymd'));
}

if (isset($_POST['user'])) {
    $token = generateSessionToken($_POST['user']);
    setcookie('session_id', $token, time() + 3600, '/');
    header("Location: index.php?status=verified");
    exit;
}

$isAdmin = false;
if (isset($_COOKIE['session_id'])) {
    if ($_COOKIE['session_id'] === generateSessionToken('admin')) {
        $isAdmin = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Identity Tokens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 600px;">
        <h2 class="fw-bold mb-4">Token verification Service</h2>
        
        <?php if ($isAdmin): ?>
            <div class="card border-0 shadow-lg bg-dark text-white p-4 rounded-4 mb-4">
                <h5 class="fw-bold">Administrator Access Confirmed</h5>
                <p>Vault Key: <span class="bg-warning text-dark px-2 rounded">FLAG{a04_token_predict_03}</span></p>
                <small class="opacity-50">Token generated: <?= $_COOKIE['session_id'] ?></small>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow p-4 rounded-4 mb-4">
                <form method="POST">
                    <label class="form-label small fw-bold">Request Session Authorization</label>
                    <div class="input-group">
                        <input type="text" name="user" class="form-control" placeholder="Enter Employee ID: guest_u1" required>
                        <button class="btn btn-dark">Issue Token</button>
                    </div>
                </form>
                <?php if(isset($_COOKIE['session_id'])): ?>
                    <div class="mt-4 p-3 bg-light rounded text-center small border">
                        Your Current Token: <code><?= $_COOKIE['session_id'] ?></code>
                        <br><small class="text-muted">Issued on: <?= date('Y-m-d') ?></small>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <p class="text-center text-muted x-small opacity-50">Cryptographic tokens are valid for 24 hours.</p>
    </div>
</body>
</html>
