<?php
/**
 * Nexora IAM - Advanced Session Proxy
 * // session protected via URL-locked identifiers
 */

if (isset($_GET['sid'])) {
    // VULNERABILITY (A07): Authentication Failure - Session Fixation
    // Accepting session ID from the URL and not regenerating after login
    session_id($_GET['sid']);
}
session_start();

require_once dirname(__DIR__, 3) . '/db.php';

$error = "";
if (isset($_POST['u']) && isset($_POST['p'])) {
    $stmt = $con->prepare("SELECT * FROM users_owasp WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $_POST['u'], $_POST['p']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user) {
        $_SESSION['auth'] = true;
        $_SESSION['user'] = $user['username'];
        $_SESSION['vault'] = $user['secret_vault'];
        // NO REGENERATION HERE -> FIXATION
    } else {
        $error = "Invalid Credentials.";
    }
}

$is_auth = $_SESSION['auth'] ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora IAM | Fixation Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #0b0e14; color: #94a3b8; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 500px;">
        <?php if ($is_auth): ?>
            <div class="card border-0 shadow-lg p-5 bg-dark text-white rounded-5">
                <h4 class="fw-bold mb-4">Authorized: <?= $_SESSION['user'] ?></h4>
                <div class="bg-success bg-opacity-10 p-3 rounded text-info small mb-4">
                    VAULT_DATA: <?= $_SESSION['vault'] ?>
                </div>
                <a href="?logout=1" class="btn btn-outline-danger btn-sm">Discard Identity</a>
            </div>
            <?php if(isset($_GET['logout'])) { session_destroy(); header("Location: index.php"); } ?>
        <?php else: ?>
            <div class="card border-0 shadow p-5 rounded-5 bg-white text-dark">
                <h4 class="fw-bold mb-4 text-center">Infrastructure Login</h4>
                <?php if($error): ?><div class="alert alert-danger small"><?= $error ?></div><?php endif; ?>
                <form method="POST">
                    <input type="text" name="u" class="form-control mb-3" placeholder="Director ID" required>
                    <input type="password" name="p" class="form-control mb-4" placeholder="Security Key" required>
                    <button class="btn btn-dark w-100 py-3 fw-bold">Login to IAM Node</button>
                </form>
                <div class="mt-4 text-center x-small text-muted opacity-50">
                    URL Tracking ID: <code><?= session_id() ?></code>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
