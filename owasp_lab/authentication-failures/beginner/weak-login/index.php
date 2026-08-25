<?php
/**
 * Nexora IAM - Secure Login Protocol
 * // secure authentication enabled
 */

session_start();
$error = '';

require_once dirname(__DIR__, 3) . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';

    // VULNERABILITY (A07): Authentication Failure - SQL Injection in login
    // No hashing + direct concatenation
    try {
        $query = "SELECT * FROM users_owasp WHERE username='$u' AND password='$p'";
        $result = $con->query($query);
        $user = $result ? $result->fetch_assoc() : null;

        if ($user) {
            $_SESSION['iam_user'] = $user['username'];
            $_SESSION['iam_role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Identity validation failed. Access denied.";
        }
    } catch (Exception $e) {
        $error = "IAM System Exception: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora IAM | Access</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: 'Inter', sans-serif; }
        .login-card { background: #1e293b; border: 1px solid #334155; max-width: 450px; margin: 100px auto; }
    </style>
</head>
<body>
    <div class="card login-card p-5 rounded-5 shadow-2xl">
        <h3 class="fw-bold mb-4 text-center">Identity Portal</h3>
        <?php if($error): ?><div class="alert alert-danger font-monospace small"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="x-small fw-bold opacity-50">ENT_ID</label>
                <input type="text" name="username" class="form-control bg-dark border-secondary text-white" placeholder="admin" required>
            </div>
            <div class="mb-4">
                <label class="x-small fw-bold opacity-50">SECURITY_KEY</label>
                <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="••••••••" required>
            </div>
            <button class="btn btn-primary w-100 py-3 fw-bold">Authenticate Session</button>
        </form>
        <div class="mt-4 text-center x-small text-muted opacity-30">
            Internal Note: Session protected by Nexora Zero-Trust v1.4
        </div>
    </div>
</body>
</html>
