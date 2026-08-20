<?php
require_once 'config.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    
    if (isset($users[$u]) && $users[$u] === $p) {
        $_SESSION['user'] = $u;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}

include 'templates/header.php';
?>
<div class="row justify-content-center pt-5">
    <div class="col-md-4">
        <div class="card shadow p-4">
            <h3 class="text-center mb-4 fw-bold">Login</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="user1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="pass123" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>
            </form>
        </div>
    </div>
</div>
<?php include 'templates/footer.php'; ?>
