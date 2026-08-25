<?php
/**
 * Nexora IAM - Session Infrastructure
 * // session protected via encrypted identifier
 */

require_once dirname(__DIR__, 3) . '/db.php';

if (isset($_POST['u']) && isset($_POST['p'])) {
    $stmt = $con->prepare("SELECT * FROM users_owasp WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $_POST['u'], $_POST['p']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    if ($user) {
        // VULNERABILITY (A07): Authentication Failure - Predictable Session ID
        // Uses MD5 of username as session identifier
        $sid = md5($user['username']);
        setcookie('IAM_SID', $sid, time() + 3600, '/');
        header("Location: index.php");
        exit;
    }
}

$userData = null;
if (isset($_COOKIE['IAM_SID'])) {
    $sid = $_COOKIE['IAM_SID'];
    // Lookup by predictable ID
    $result = $con->query("SELECT * FROM users_owasp");
    if ($result) {
        while($r = $result->fetch_assoc()) {
            if (md5($r['username']) === $sid) {
                $userData = $r;
                break;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora IAM | Sessions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #f8fafc; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container">
        <?php if ($userData): ?>
            <div class="card border-0 shadow-lg p-5 rounded-5 bg-dark text-white">
                <h2 class="fw-bold mb-4">Enterprise Profile: <?= htmlspecialchars($userData['username']) ?></h2>
                <div class="alert alert-info py-2">Session ID: <code><?= htmlspecialchars($_COOKIE['IAM_SID']) ?></code></div>
                <p>System Flag: <span class="text-warning fw-bold"><?= htmlspecialchars($userData['secret_flag']) ?></span></p>
                <a href="logout.php" class="btn btn-outline-light btn-sm mt-4">Invalidate Access</a>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow p-5 rounded-5 mt-5" style="max-width: 450px; margin: auto;">
                <h4 class="fw-bold mb-4">IAM Node Login</h4>
                <form method="POST">
                    <input type="text" name="u" class="form-control mb-3" placeholder="Username (try guest_acc)" required>
                    <input type="password" name="p" class="form-control mb-4" placeholder="Password" required>
                    <button class="btn btn-primary w-100 py-3 fw-bold">Initialize Identity</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
