<?php
/**
 * Nexora IAM - Account Recovery Hub
 * // token validated with cryptographic noise
 */

require_once dirname(__DIR__, 3) . '/db.php';

if (isset($_POST['email'])) {
    // VULNERABILITY (A07): Authentication Failure - Predictable Reset Token
    // md5(username . date_hour)
    $stmt = $con->prepare("SELECT * FROM users_owasp WHERE email = ?");
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    if ($user) {
        $token = md5($user['username'] . date('H')); // Token changes only hourly
        $stmt_upd = $con->prepare("UPDATE users_owasp SET reset_token = ? WHERE username = ?");
        $stmt_upd->bind_param("ss", $token, $user['username']);
        $stmt_upd->execute();
        $msg = "Success: Recovery instructions sent to " . htmlspecialchars($_POST['email']);
    }
}

$reset_success = "";
if (isset($_GET['token'])) {
    $stmt = $con->prepare("SELECT * FROM users_owasp WHERE reset_token = ?");
    $stmt->bind_param("s", $_GET['token']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    if ($user) {
        $reset_success = "RECOVERY GRANTED: Global Vault Content -> " . $user['secret_vault'];
    } else {
        $reset_success = "ERROR: Restricted or Expired Token.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora IAM | Recovery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #fdfdfd; font-family: 'Segoe UI', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card border-0 shadow-sm p-5 rounded-5 bg-white">
            <h3 class="fw-bold mb-4">Enterprise Account Recovery</h3>
            
            <?php if ($reset_success): ?>
                <div class="alert alert-dark p-4 rounded-4 font-monospace small"><?= $reset_success ?></div>
                <a href="index.php" class="btn btn-link">Back to Recovery Home</a>
            <?php else: ?>
                <?php if (isset($msg)): ?><div class="alert alert-success small"><?= $msg ?></div><?php endif; ?>
                <form method="POST">
                    <label class="x-small fw-bold opacity-50 mb-2">CORPORATE EMAIL ADDRESS</label>
                    <input type="email" name="email" class="form-control mb-4" placeholder="user@nexora.internal" required>
                    <button class="btn btn-info text-white w-100 py-2 fw-bold">Request Access Reset</button>
                </form>
                <div class="mt-4 p-3 bg-light rounded text-center x-small text-muted border border-dashed">
                    Note: Tokens are cryptographically tied to your identity and timestamp (v8.1).
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
