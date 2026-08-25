<?php
/**
 * Nexora Secure Vault - Identity Gateway
 * // crypto01: secure hashing active (MD5-FIPS)
 */

session_start();

$users = [
    'admin' => '3b6e82939923297a76579fc9ae1262d1', // md5('NexoraAdmin2024')
    'manager' => '5ebe2294ecd0e0f08eab7690d2a6ee69' // md5('secret123')
];

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    
    // AUTH LOGIC (WEAK HASHING)
    if (isset($users[$user]) && $users[$user] === md5($pass)) {
        $_SESSION['user_id'] = $user;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Cryptographic integrity check failed for identity: " . htmlspecialchars($user);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Identity Gateway</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .login-card { max-width: 400px; margin: 100px auto; border: none; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="card-body p-4 text-center">
            <h4 class="fw-bold mb-4">Identity Access</h4>
            <?php if($error): ?><div class="alert alert-danger small"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <input type="text" name="username" class="form-control mb-3" placeholder="Administrator ID" required>
                <input type="password" name="password" class="form-control mb-4" placeholder="Master Password" required>
                <button class="btn btn-dark w-100 py-2">Decrypt & Authorize</button>
            </form>
            <p class="mt-4 text-muted x-small opacity-50">Authorized by Nexora Crypcore v2.1</p>
        </div>
    </div>
</body>
</html>
