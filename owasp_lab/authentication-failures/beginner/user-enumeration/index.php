<?php
/**
 * Nexora IAM - Identity Lookup Engine
 * // user state verified
 */

$error = '';
$class = 'alert-danger';

require_once dirname(__DIR__, 3) . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['user_id'] ?? '';
    $p = $_POST['pass'] ?? '';

    // VULNERABILITY (A07): Authentication Failure - User Enumeration
    // Different error messages allow an attacker to find valid usernames

    $stmt = $con->prepare("SELECT * FROM users_owasp WHERE username = ?");
    $stmt->bind_param("s", $u);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        $error = "IDENTITY_NOT_FOUND: Enterprise ID '$u' is not registered in Nexora IAM.";
    } else {
        if ($user['password'] === $p) {
             $error = "SUCCESS: Vault data: " . $user['secret_vault'];
             $class = "alert-success";
        } else {
             $error = "AUTH_FAILED: Invalid security key for identity '" . htmlspecialchars($u) . "'.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora IAM | Identity Lookup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 500px;">
        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
            <h5 class="fw-bold mb-4">Identity Verification Gateway</h5>
            <?php if($error): ?><div class="alert <?= $class ?> small font-monospace"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <input type="text" name="user_id" class="form-control mb-3" placeholder="Employee ID (e.g. jdoe_66)" required>
                <input type="password" name="pass" class="form-control mb-4" placeholder="Corporate Password" required>
                <button class="btn btn-dark w-100 py-2">Query Vault</button>
            </form>
        </div>
        <p class="text-center x-small text-muted opacity-50">Information Disclosure Policy: All login attempts are audited for metadata intelligence.</p>
    </div>
</body>
</html>
