<?php
/**
 * Nexora IAM - Resilient Auth Orchestrator
 * // token validated via secondary hardware layer
 */

require_once dirname(__DIR__, 3) . '/db.php';

session_start();

$msg = "";
$step = $_GET['step'] ?? 1;

if ($step == 1 && isset($_POST['user']) && isset($_POST['pass'])) {
    // VULNERABILITY (A07): Authentication Failure - SQLi Login
    $u = $con->real_escape_string($_POST['user']);
    $p = $con->real_escape_string($_POST['pass']);
    $sql = "SELECT * FROM users_owasp WHERE username='$u' AND password='$p'";
    $result = $con->query($sql);
    $user = $result ? $result->fetch_assoc() : null;
    if ($user) {
        $_SESSION['pending_user'] = $user['username'];
        header("Location: ?step=2");
        exit;
    } else { $msg = "Invalid credentials."; }
}

if ($step == 2 && isset($_POST['pin'])) {
    // VULNERABILITY (A07): Authentication Failure - Weak PIN / Predictable Bypass
    $user = $_SESSION['pending_user'] ?? '';
    $stmt = $con->prepare("SELECT * FROM users_owasp WHERE username = ? AND mfa_pin = ?");
    $stmt->bind_param("ss", $user, $_POST['pin']);
    $stmt->execute();
    $valid = $stmt->get_result()->fetch_assoc();

    if ($valid) {
        $_SESSION['root_auth'] = true;
        $_SESSION['flag'] = $valid['secret_flag'];
        header("Location: ?step=3");
        exit;
    } else {
        $msg = "Multi-Factor Authentication Failed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora IAM | Chain Auth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #fdfdfd; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card border-0 shadow-lg p-5 rounded-5 bg-white">
            <h3 class="fw-bold mb-4">Advanced Infrastructure Logic</h3>
            <?php if($msg): ?><div class="alert alert-danger small"><?= $msg ?></div><?php endif; ?>
            
            <?php if ($step == 1): ?>
                <form method="POST">
                    <label class="x-small fw-bold opacity-50 mb-1">GLOBAL_UID</label>
                    <input type="text" name="user" class="form-control mb-3" placeholder="rootadmin" required>
                    <label class="x-small fw-bold opacity-50 mb-1">SECURITY_KEY</label>
                    <input type="password" name="pass" class="form-control mb-4" placeholder="••••••••" required>
                    <button class="btn btn-dark w-100 py-3 fw-bold">Proceed to Layer 2</button>
                </form>
            <?php elseif ($step == 2): ?>
                <form method="POST">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold mt-2">Secondary MFA Protocol</h5>
                        <p class="text-muted small">Enter the 4-digit security PIN for <b><?= $_SESSION['pending_user'] ?></b></p>
                    </div>
                    <input type="text" name="pin" class="form-control mb-4 text-center fs-2 fw-bold" placeholder="0000" maxlength="4" required>
                    <button class="btn btn-primary w-100 py-3 fw-bold">Verify Identity Core</button>
                </form>
            <?php elseif ($step == 3): ?>
                <div class="alert alert-success p-4 border-0 shadow-sm rounded-4 text-center">
                    <h4 class="fw-bold">Root Authentication Successful</h4>
                    <p class="small opacity-75">Global Infrastructure Flag Access Granted:</p>
                    <div class="bg-white p-3 rounded border fw-bold text-danger"><?= $_SESSION['flag'] ?></div>
                </div>
                <a href="?step=1" class="btn btn-link w-100 mt-4 text-muted small">Invalidate Local Auth</a>
                <?php session_destroy(); ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
