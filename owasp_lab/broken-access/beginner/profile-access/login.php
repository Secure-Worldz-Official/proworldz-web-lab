<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../../db.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $con->prepare("SELECT * FROM users_owasp WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
include 'templates/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card p-4">
            <h3 class="text-center mb-4">Login</h3>
            <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Login</button>
            </form>
            <div class="mt-3 text-center text-muted">
                <small>Try: <b>user1 / pass123</b></small>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
